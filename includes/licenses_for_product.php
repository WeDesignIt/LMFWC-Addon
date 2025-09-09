<?php

defined( 'ABSPATH' ) || exit;

add_action('rest_api_init', function () {
    register_rest_route('lmfwc/v2', '/product-licenses/(?P<product_id>\d+)', [
        'methods' => 'GET',
        'callback' => 'lmfwc_get_licenses_for_product',
        'permission_callback' => function () {
            return current_user_can('manage_woocommerce');
        },
        'args' => [
            'product_id' => [
                'validate_callback' => function ($param) {
                    return is_numeric($param);
                }
            ]
        ]
    ]);
});

if (! function_exists('lmfwc_get_licenses_for_product')) {
    function lmfwc_get_licenses_for_product($request)
    {
        $productId = (int) $request['product_id'];

        $licenses = lmfwc_get_licenses(compact('productId'));

        return rest_ensure_response($licenses);
    }
}