<?php
defined('ABSPATH') or die;

class SiteDataProduct
{

    public static $product;
    public static $siteProductId = 0;

    /**
     * Get site product object
     *
     * @param int $product_id
     *
     * @return object $product
     */
    public static function getProduct($product_id)
    {
        return $post = get_post($product_id);
    }

    /**
     * Get full data product for np
     *
     * @param bool $editor
     *
     * @return array $product_data
     */
    public static function getProductData($editor = false)
    {
        $product_data = array(
            'product' => self::$product,
            'type' => 'simple',
            'title' => self::getProductTitle(),
            'desc' => self::getProductDesc(),
            'image_url' => self::getProductImageUrl(),
            'price' => self::getProductPrice($editor),
            'price_old' => self::getProductPriceOld($editor),
            'add_to_cart_text' => 'Add to cart',
            'attributes' => array(),
            'variations_attributes' => array(),
            'gallery_images' => self::getProductImagesUrls(),
            'tabs' => array(),
            'meta' => '',
        );
        return $product_data;
    }

    /**
     * Get product title
     *
     * @return string $title
     */
    public static function getProductTitle()
    {
        return $title = isset(self::$product->post_title) ? self::$product->post_title : 'Product title';
    }

    /**
     * Get product description
     *
     * @return string $desc
     */
    public static function getProductDesc()
    {
        $desc = isset(self::$product->post_content) ? self::$product->post_content : 'Product description';
        return plugin_trim_long_str($desc, 150);
    }

    /**
     * Get product image url
     *
     * @return string $image_url
     */
    public static function getProductImageUrl()
    {
        $image = isset(self::$product->image) ? self::$product->image : '';
        $image_url = isset($image['url']) ? np_data_provider()->fixImagePaths($image['url']) : '';
        return $image_url;
    }

    /**
     * Get product price
     *
     * @param bool $editor
     *
     * @return int $price
     */
    public static function getProductPrice($editor = false)
    {
        $price = isset(self::$product->price) ? self::$product->price : '';
        $price = str_replace('$', '_dollar_symbol_', $price);
        return $price;
    }

    /**
     * Get product price old
     *
     * @param bool $editor
     *
     * @return int $price_old
     */
    public static function getProductPriceOld($editor = false)
    {
        $price_old = '';
        $price_old = str_replace('$', '_dollar_symbol_', $price_old);
        return $price_old;
    }

    /**
     * Get product gallery images
     *
     * @return array $images urls
     */
    public static function getProductImagesUrls()
    {
        $images = isset(self::$product->gallery_images) && self::$product->gallery_images ? self::$product->gallery_images : array();
        $imagesUrls = array();
        foreach ($images as $image) {
            if ($image['url']) {
                $imagesUrls[] = np_data_provider()->fixImagePaths($image['url']);
            }
        }
        return $imagesUrls;
    }

    /**
     * Get button add to cart html
     *
     * @param string $button_html
     * @param bool   $goToProduct
     * @param array  $allProducts
     *
     * @return string $button_html
     */
    public static function getProductButtonHtml($button_html, $goToProduct, $allProducts)
    {
        if (self::$product && self::$siteProductId) {
            $button_html = str_replace('data-product-id=""', 'data-product-id="' . self::$siteProductId  . '"', $button_html);
            $button_html = str_replace('<a', '<a data-product="' . htmlspecialchars(json_encode($allProducts[self::$siteProductId]))  . '"', $button_html);
        }
        if (!$allProducts) {
            if ($goToProduct) {
                return preg_replace_callback(
                    '/href=[\"\']{1}product-?(\d+)[\"\']{1}/',
                    function ($hrefMatch) {
                        return 'href="' . home_url('?productId=' . $hrefMatch[1]) . '"';
                    },
                    $button_html
                );
            }
        }
        return $button_html;
    }
}

/**
 * Construct SiteDataProduct object
 *
 * @param int  $product_id      Product Id from cms
 * @param int  $site_product_id Site Product Id
 * @param bool $editor          Need to check editor or live site
 *
 * @return array SiteDataProduct
 */
function site_data_product($product_id = 0, $site_product_id = 0, $editor = false)
{
    SiteDataProduct::$siteProductId = $site_product_id;
    SiteDataProduct::$product = SiteDataProduct::getProduct($product_id);
    return SiteDataProduct::$product ? SiteDataProduct::getProductData($editor) : array();
}
