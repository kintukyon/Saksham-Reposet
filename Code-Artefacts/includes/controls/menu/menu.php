<?php

defined('ABSPATH') or die;

require_once dirname(__FILE__) . '/navigation.php';

if (isset($controlProps) && isset($controlTemplate)) {
    register_nav_menus(
        array(
            $controlProps['menuInfo']['id'] => $controlProps['menuInfo']['name'],
        )
    );

    preg_match('/<\!--np_mega_menu_json-->([\s\S]+?)<\!--\/np_mega_menu_json-->/', $controlTemplate, $matches);
    $megaMenu = isset($matches[1]) ? json_decode($matches[1], true) : array();
    if ($megaMenu) {
        $controlTemplate = str_replace($matches[0], '', $controlTemplate);
        $controlProps['mega_menu'] = $megaMenu;
    }

    echo Plugin_NavMenu::getMenuHtml(
        array(
            'container_class' => $controlProps['container_class'],
            'menu' => array(
                'is_mega_menu' => isset($controlProps['is_mega_menu']) ? $controlProps['is_mega_menu'] : false,
                'menu_class' => $controlProps['menu_class'],
                'item_class' => $controlProps['item_class'],
                'link_class' => $controlProps['link_class'],
                'link_style' => $controlProps['link_style'],
                'submenu_class' => $controlProps['submenu_class'],
                'submenu_item_class' => $controlProps['submenu_item_class'],
                'submenu_link_class' => $controlProps['submenu_link_class'],
                'submenu_link_style' => $controlProps['submenu_link_style'],
            ),
            'responsive_menu' => array(
                'is_mega_menu' => false,
                'menu_class' => $controlProps['r_menu_class'],
                'item_class' => $controlProps['r_item_class'],
                'link_class' => $controlProps['r_link_class'],
                'link_style' => $controlProps['r_link_style'],
                'submenu_class' => $controlProps['r_submenu_class'],
                'submenu_item_class' => $controlProps['r_submenu_item_class'],
                'submenu_link_class' => $controlProps['r_submenu_link_class'],
                'submenu_link_style' => $controlProps['r_submenu_link_style'],
            ),
            'theme_location' => $controlProps['theme_location'],
            'template' => $controlTemplate,
            'mega_menu' => isset($controlProps['mega_menu']) ? $controlProps['mega_menu'] : array(),
        )
    );
}