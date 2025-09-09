<?php

defined('ABSPATH') || exit;

add_action('rest_api_init', function () {
    register_rest_route('lmfwc/v2', '/product-licenses/(?P<product_id>\d+)', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'getLicensesForProduct',
        'args' => [
            'product_id' => [
                'validate_callback' => fn ($param) => is_numeric($param)
            ]
        ]
    ]);
});

function getLicensesForProduct(WP_REST_Request $request)
{
    $productId = $request->get_param('product_id');

    try {
        $licenses = lmfwc_get_licenses(compact('productId'));
    } catch (Exception $e) {
        return new WP_Error(
            'lmfwc_rest_data_error',
            $e->getMessage(),
            array('status' => 404)
        );
    }

    $data = array_map(function ($license) {
        $licenseData = $license->toArray();

        // Remove the hash, decrypt the license key, and add it to the response
        unset($licenseData['hash']);
        $licenseData['licenseKey'] = $license->getDecryptedLicenseKey();

        return $licenseData;
    }, $licenses);

    return rest_ensure_response(
        apply_filters('lmfwc_rest_api_pre_response', 'GET', $request->get_route(), $data)
    );
}