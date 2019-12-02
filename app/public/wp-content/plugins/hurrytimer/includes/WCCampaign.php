<?php

namespace Hurrytimer;

class WCCampaign
{
    /**
     * Returns campaigns with WooCommerce enabled.
     *
     * @return array
     */
    public function findWCCampaigns()
    {
        $posts = get_posts([
            'post_type' => HURRYT_POST_TYPE,
            'numberposts' => -1,
            'post_status' => 'publish',
            'meta_key' => 'wc_enable',
            'meta_value' => C::YES,
            'suppress_filters' => false,
        ]);

        return array_map(function ($post) {
            return new Campaign($post->ID);
        }, $posts);
    }

    /**
     * Run campaign for then given product page.
     *
     * @param $productId
     */
    public function run($productId)
    {

        $compaigns = $this->findWCCampaigns();
        foreach ($compaigns as $compaign) {
            $compaign->loadSettings();
            if($compaign->enableSticky === C::YES) {
                continue;
            };

            if ($this->hasActiveCampaign($compaign, $productId) && $this->canApply($compaign,$productId)) {
                add_action(
                    'woocommerce_single_product_summary',
                    function () use ($compaign) {$this->renderShortcode($compaign);},
                    $compaign->getWcPosition(),
                    [$compaign->getId()]
                );
            }
        }
    }

    /**
     * Check if given product has an active campaign.
     *
     * @param Campaign $campaign
     * @param          $productId
     *
     * @return bool
     */
    public function hasActiveCampaign(
        $campaign,
        $productId
    ) {
        if (!$campaign->isWcEnabled()) {
            return false;
        }
        $categories = $this->getProductsCategories($productId);

        $type = $campaign->getWcProductsSelectionType();

        switch ($type) {
            case C::WC_PS_TYPE_ALL:
                return true;

            case C::WC_PS_TYPE_INCLUDE_PRODUCTS:
                return in_array($productId, $campaign->getWcProductsSelection());

            case C::WC_PS_TYPE_EXCLUDE_PRODUCTS:
                return !in_array($productId, $campaign->getWcProductsSelection());

            case C::WC_PS_TYPE_INCLUDE_CATEGORIES:
                $included_categories = $campaign->getWcProductsSelection();
                $callback = function ($id)
                 use ($categories) {
                    return in_array($id, $categories);
                };
                $results = array_filter($included_categories, $callback);

                return count($results) > 0;

            case C::WC_PS_TYPE_EXCLUDE_CATEGORIES:
                $excluded_categories = $campaign->getWcProductsSelection();
                $results = array_filter($excluded_categories, function (
                    $id
                ) use ($categories) {
                    return !in_array($id, $categories);
                });

                return count($results) > 0;

            default:
                return false;
        }
    }

    /**
     * Returns categories ids for the given product.
     *
     * @param $product_id
     *
     * @return array|\WP_Error
     */
    public function getProductsCategories($product_id)
    {
        return wp_get_object_terms($product_id, 'product_cat', [
            'fields' => 'ids',
        ]);
    }

    /**
     * Render shortcode for the given campaign ID.
     *
     * @param Campaign $campaign
     */
    public function renderShortcode($campaign)
    {
        $shortcode = '[hurrytimer id=' . $campaign->getId() . ']';
        echo do_shortcode($shortcode);
    }

    /**
     * Change stock status.
     *
     * @param Campaign $campaign
     * @param int $stockStatus
     */
    public function changeStockStatus($campaign, $stockStatus)
    {

        if (!$campaign->isWcEnabled()) {
            return;
        }

        $products = $this->getProductsIdsByCampaign($campaign);
        foreach ($products as $product) {
            @$product->set_stock_status($stockStatus);
            @$product->save();
        }
    }

    /**
     * Get campaign's products ID.
     *
     * @param Campaign $campaign
     *
     * @return array
     */
    private function getProductsIdsByCampaign($campaign)
    {
        $products = [];
        $selection = $campaign->getWcProductsSelection();
        $type = $campaign->getWcProductsSelectionType();
        switch ($type) {
            case C::WC_PS_TYPE_ALL:
                $products = wc_get_products(['status' => 'publish']);
                break;
            case C::WC_PS_TYPE_EXCLUDE_PRODUCTS:
                $products = wc_get_products([
                    'status' => 'publish',
                    'exclude' => $selection,
                ]);
                break;
            case C::WC_PS_TYPE_INCLUDE_PRODUCTS:
                $products = wc_get_products(['status' => 'publish', 'include' => $selection]);
                break;
            case C::WC_PS_TYPE_INCLUDE_CATEGORIES:
                $categories = get_terms([
                    'taxonomy' => 'product_cat',
                    'include' => implode(',', $selection),
                    'fields' => 'slugs',
                ]);
                $products = wc_get_products(['status' => 'publish', 'category' => $categories]);
                break;
            case C::WC_PS_TYPE_EXCLUDE_CATEGORIES:
                $categories = get_terms([
                    'taxonomy' => 'product_cat',
                    'exclude' => implode(',', $selection),
                    'fields' => 'slugs',
                ]);
                $products = wc_get_products(['status' => 'publish', 'category' => $categories]);
                break;
        }

        return $products;
    }

    public function canApply($campaign, $objectId)
    {
        $groups = $campaign->getWcConditions();
        if(empty($groups)) return true;

        $product = wc_get_product($objectId);
        $trueConditions = [];
        $trueGroups = [];
        foreach ($groups as $conditions) {
            foreach ($conditions as $condition) {
                switch ($condition['key']) {
                    case 'stock_status':
                        if ($condition['operator'] === '==') {
                            $trueConditions[] = $product->get_stock_status() == $condition['value'];
                        }
                        if ($condition['operator'] === '!=') {
                            $trueConditions[] = $product->get_stock_status() != $condition['value'];
                        }
                        break;
                    case 'stock_quantity':
                        if ($condition['operator'] === '==') {
                            $trueConditions[] = $product->get_stock_quantity() == $condition['value'];
                        }
                        if ($condition['operator'] === '!=') {
                            $trueConditions[] = $product->get_stock_quantity() != $condition['value'];
                        }
                        if ($condition['operator'] === '>') {
                            $trueConditions[] = $product->get_stock_quantity() > $condition['value'];
                        }
                        if ($condition['operator'] === '<') {
                            $trueConditions[] = $product->get_stock_quantity() < $condition['value'];
                        }
                        break;
                    case 'shipping_class':
                        if ($condition['operator'] === '==') {
                            $trueConditions[] = $product->get_shipping_class_id() == $condition['value'];
                        }
                        if ($condition['operator'] === '!=') {
                            $trueConditions[] = $product->get_shipping_class_id() != $condition['value'];
                        }
                        break;
                }
            }
            $_true = array_filter($trueConditions, function ($bool) {
                return $bool === true;
            });

            $trueGroups[] = count($_true) === count($conditions);
        }
        return !empty(array_filter($trueGroups, function ($bool) {
            return $bool === true;
        }));
    }
}
