<?php

use LicenseManagerForWooCommerce\Repositories\Resources\License as LicenseResourceRepository;

defined('ABSPATH') || exit;

add_action('rest_api_init', function () {
    register_rest_route('lmfwc/v2', '/product-licenses/(?P<product_id>\d+)', [
        [
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'getLicensesForProduct',
            'args' => [
                'product_id' => [
                    'validate_callback' => fn($param) => is_numeric($param)
                ]
            ]
        ]
    ]);
});

function getLicensesForProduct(WP_REST_Request $request): WP_Error|WP_REST_Response
{
    $productId = $request->get_param('product_id');

    $query = $request->get_query_params();

    // Grab offset & limit from query params
    $page = absint((int) $query['page'] ?? 1);
    $perPage = absint((int) $query['per_page'] ?? 100);
    $oderBy = $query['order_by'] ?? 'id';
    $offset = max(($page - 1), 0) * max($perPage, 0);

    if (array_key_exists('license_key', $query)) {
        $query['hash'] = apply_filters('lmfwc_hash', $query['license_key']);

        unset($query['license_key']);
    }

    // Only grab supported columns
    $query = array_filter($query, fn($v, $key) => in_array($key, [
        'id',
        'order_id',
        'user_id',
        'hash',
        'expires_at',
        'valid_for',
        'source',
        'status',
        'times_activated',
        'times_activated_max',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ]), ARRAY_FILTER_USE_BOTH);

    $query['product_id'] = $productId;

    // Normalize values
    $query = array_map(fn($value) => match (true) {
        // Cast 'null'
        $value === 'null',
        $value === 'NULL' => null,
        // Cast booleans
        $value === 'true' => true,
        $value === 'false' => false,

        // cast to int/float
        is_numeric($value) => $value + 0,

        default => $value,
    }, $query);


    try {
        $licenses = LicenseResourceRepository::instance()->findAllBy(
            $query,
            $oderBy,
            "LIMIT {$perPage} OFFSET {$offset}"
        ) ?: [];
    } catch (Exception $e) {
        return new WP_Error(
            'lmfwc_rest_data_error',
            $e->getMessage(),
            ['status' => 404]
        );
    }

    $data = array_map(function ($license) {
        $licenseData = $license->toArray();

        // Remove the hash, decrypt the license key, and add it to the response
        unset($licenseData['hash']);
        $licenseData['licenseKey'] = $license->getDecryptedLicenseKey();

        return $licenseData;
    }, $licenses);

    return new WP_REST_Response([
        'success' => true,
        'data' => $data,
        'page' => $page
    ]);
}