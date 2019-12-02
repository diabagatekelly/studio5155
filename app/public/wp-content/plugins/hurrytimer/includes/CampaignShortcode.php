<?php

namespace Hurrytimer;

use \WP_Post;
use \Hurrytimer\Utils\Helpers;
class CampaignShortcode
{

    /**
     * Return shortcode content.
     *
     * @param $attrs
     *
     * @return string
     */
    public function content( $attrs )
    {
        if(is_admin()){
            return '';
        }

        $id = $this->getId( $attrs );

        if ( $id === null ) {
            return '';
        }
        $campaign = new Campaign( $id );
        $campaign->loadSettings();
        if ( ! $campaign->isActive()
             || $campaign->isScheduled()
             || ! $campaign->showStickyBarOn( $this->getCurrentPageId() )
             || $campaign->isStickyDismissed()
             || ( $campaign->isRecurring() && ! $campaign->canRecurToday() )
             || ( $campaign->isRecurring() && $campaign->getRecurringDeadline() === null )
        ) {

            return apply_filters( 'hurryt_no_campaign', '', $campaign->getId() );
        }
        $isOneTimeCampaignExpired   = $campaign->isRegular() && $campaign->isExpired();
        $isRecurringCampaignExpired = $campaign->isRecurring() && $campaign->isRecurringExpired();

        if ( $isRecurringCampaignExpired || $isOneTimeCampaignExpired ) {
            $actionManager = new ActionManager( $campaign );
            $actionManager->run();
        }

        $template = apply_filters( 'hurryt_campaign_template', $campaign->buildTemplate() );

        return $template === '' ? '' : $campaign->wrapTemplate( $template );

    }

    /**
     * Returns campaign ID if set.
     *
     * @param $attrs
     *
     * @return int|null
     */
    public function getId( $attrs )
    {
        if ( ! $this->hasId( $attrs ) ) {
            return null;
        }

        $id = intval( $attrs[ 'id' ] );

        if ( ! $this->campaignExists( $id ) ) {
            return null;
        }

        return $id;
    }

    public function getCurrentPageId()
    {

        $objectId = get_queried_object_id();
        if ( ! Utils\Helpers::isWcActive() ) {
            return $objectId;
        }
        $wcIds = [
            'shop'         => get_option( 'woocommerce_shop_page_id' ),
            'cart'         => get_option( 'woocommerce_cart_page_id' ),
            'checkout'     => get_option( 'woocommerce_checkout_page_id' ),
            'checkout_pay' => get_option( 'woocommerce_pay_page_id' ),
            'thanks'       => get_option( 'woocommerce_thanks_page_id' ),
            'myaccount'    => get_option( 'woocommerce_myaccount_page_id' ),
            'edit_address' => get_option( 'woocommerce_edit_address_page_id' ),
            'view_order'   => get_option( 'woocommerce_view_order_page_id' ),
            'terms'        => get_option( 'woocommerce_terms_page_id' ),
        ];
        if ( is_shop() ) {
            $objectId = $wcIds[ 'shop' ];
        } elseif ( is_account_page() ) {
            $objectId = $wcIds[ 'myaccount' ];
        } elseif ( is_checkout_pay_page() ) {
            $objectId = $wcIds[ 'checkout_pay' ];
        } elseif ( is_checkout() ) {
            $objectId = $wcIds[ 'checkout' ];
        } elseif ( is_cart() ) {
            $objectId = $wcIds[ 'cart' ];
        } elseif ( is_view_order_page() ) {
            $objectId = $wcIds[ 'view_order' ];
        } elseif ( is_view_order_page() ) {
            $objectId = $wcIds[ 'view_order' ];
        } elseif ( is_view_order_page() ) {
            $objectId = $wcIds[ 'view_order' ];
        } elseif ( is_view_order_page() ) {
            $objectId = $wcIds[ 'view_order' ];
        }

        return $objectId;
    }

    /**
     * Verify if the given attributes contain the campaign ID.
     *
     * @param $attrs
     *
     * @return bool
     */
    public function hasId( $attrs )
    {
        return isset( $attrs[ 'id' ] );
    }

    /**
     * Verify if the given campaign exists in database.
     *
     * @param $id
     *
     * @return boolean
     */
    public function campaignExists( $id )
    {
        return get_post( $id ) instanceof WP_Post;
    }
}
