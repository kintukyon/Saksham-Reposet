<?php
defined('ABSPATH') or die;

if (isset($_GET['action']) && $_GET['action'] === 'np_route_products_json') {
    if (isset($_GET['np_from']) && $_GET['np_from'] == 'theme') {
        if (file_exists(get_template_directory() . '/shop/products.json')) {
            $data = file_get_contents(get_template_directory() . '/shop/products.json');
            $data = json_decode($data, true);
        }
    } else {
        if (function_exists('np_data_provider')) {
            $data = np_data_provider()->getProductsJson();
        }
    }
    $products = isset($data['products']) ? $data['products'] : array();
    if (isset($_GET['np_from']) && $_GET['np_from'] == 'theme') {
        foreach ($products as $index => $product) {
            if (isset($products[$index]['images'])) {
                foreach ($products[$index]['images'] as $i => $image) {
                    if (isset($products[$index]['images'][$i]['url']) && $products[$index]['images'][$i]['url']) {
                        $products[$index]['images'][$i]['url'] = get_template_directory_uri() . '/' . $image['url'];
                    }
                }
            }
        }
    }
    header('Content-Type: application/json');
    echo json_encode($products);
    exit();
}