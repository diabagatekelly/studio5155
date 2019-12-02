<?php

if (!function_exists('hurryt_wc_stock_statuses')) {
    function hurryt_wc_stock_statuses()
    {
        return [
            [
                'name' => __('In stock', 'hurrytimer'),
                'id'   => \Hurrytimer\C::WC_IN_STOCK,
            ],
            [
                'name' => __('Out of stock', 'hurrytimer'),
                'id'   => \Hurrytimer\C::WC_OUT_OF_STOCK,
            ],
            [
                'name' => __('On backorder', 'hurrytimer'),
                'id'   => \Hurrytimer\C::WC_ON_BACKORDER,
            ],
        ];
    }
}

if (!function_exists('hurryt_wc_conditions')) {
    function hurryt_wc_conditions()
    {
        return (new \Hurrytimer\ConditionalLogic())->addRule([
            'key'       => 'stock_status',
            'name'      => __('Stock status', 'hurrytimer'),
            'operators' => ['==', '!='],
            'values'    => hurryt_wc_stock_statuses(),
            'type'      => 'string',
        ])->addRule([
            'key'    => 'stock_quantity',
            'name'   => __('Stock quantity', 'hurrytimer'),
            'operators' => ['==', '!=', '<', '>'],
            'values' => [],
            'type'   => 'number',
        ])->addRule([
            'key'    => 'shipping_class',
            'name'   => __('Shipping class', 'hurrytimer'),
            'operators' => ['==', '!='],
            'values' => array_map(function ($class) {
                return [
                    'name' => $class->name,
                    'id'   => $class->term_id,
                ];
            }, \WC_Shipping::instance()->get_shipping_classes()),
        ])->get();
    }
}

if(!function_exists('hurryt_tz')){

    function hurryt_tz(){
        $timezone_string = get_option('timezone_string');
        if (!empty($timezone_string)) {
            return $timezone_string;
        }
        $offset = get_option('gmt_offset');
        $hours = (int) $offset;
        $minutes = abs(($offset - (int) $offset) * 60);
        $offset = sprintf('%+03d:%02d', $hours, $minutes);
        return $offset;
    }
}
