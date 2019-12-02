<?php

namespace Hurrytimer;

use Hurrytimer\Utils\Helpers;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://nabillemsieh.com
 * @since      1.0.0
 *
 * @package    Hurrytimer
 * @subpackage Hurrytimer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Hurrytimer
 * @subpackage Hurrytimer/public
 * @author     Nabil Lemsieh <contact@nabillemsieh.com>
 */
class Frontend
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     *
     * @since    1.0.0
     *
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
        $shortcode         = new CampaignShortcode();
        add_shortcode( 'hurrytimer', [ $shortcode, 'content' ] );
        add_action( 'wp_footer', [ $this, 'maybeDisplayStickyBar' ] );
        add_action( 'wp', [ $this, 'handleWCSingleProduct' ] );
        add_action( 'wp', [ $this, 'handle_actions' ] );
        add_action( 'wp_ajax_action_change_stock_status', [ $this, 'ajaxChangeStockStatus' ] );
        add_action( 'wp_ajax_nopriv_action_change_stock_status',
            [ $this, 'ajaxChangeStockStatus' ] );
        add_action( 'wp_ajax_set_timestamp', [ $this, 'bypassEvergreenDetectCookieCache' ] );
        add_action( 'wp_ajax_nopriv_set_timestamp', [ $this, 'bypassEvergreenDetectCookieCache' ] );

        add_action( 'wp_ajax_next_recurrence', [ $this, 'getNextRecurrence' ] );
        add_action( 'wp_ajax_nopriv_next_recurrence', [ $this, 'getNextRecurrence' ] );
    }

    public function handle_actions()
    {
        ob_start();
        global $post;
        $pattern = get_shortcode_regex();
        $tag     = 'hurrytimer';
        $ids     = [];
        if ( ! is_admin() && $post
             && preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches )
             && array_key_exists( 2, $matches )
             && in_array( $tag, $matches[ 2 ] )
        ) {
            foreach ( (array)$matches[ 2 ] as $key => $value ) {
                if ( $tag === $value ) {
                    $ids[] = shortcode_parse_atts( $matches[ 3 ][ $key ] );
                }
            }
            $ids      = array_map( function ( $id ) {
                return isset( $id[ 'id' ] ) ? $id[ 'id' ] : null;
            }, $ids );
            $ids      = array_filter( $ids );
            $redirect = false;
            foreach ( $ids as $id ) {
                $campaign                   = new Campaign( $id );
                $isOneTimeCampaignExpired   = $campaign->isRegular() && $campaign->isExpired();
                $isRecurringCampaignExpired = $campaign->isRecurring()
                                              && $campaign->isRecurringExpired();

                if ( $isRecurringCampaignExpired || $isOneTimeCampaignExpired ) {
                    foreach ( $campaign->actions as $action ) {
                        if ( $action[ 'id' ] === C::ACTION_REDIRECT ) {
                            $redirect = true;
                            break;
                        }
                    }
                }
                if ( $redirect ) {
                    break;
                }
            }

            if ( $redirect ) {
                wp_redirect( $action[ 'redirectUrl' ] );
                exit;
            }
        }

    }

    public function getNextRecurrence()
    {
        check_ajax_referer( 'hurryt', 'nonce' );
        $campaign_id = absint( $_GET[ 'id' ] );
        if ( ! get_post( $campaign_id ) || get_post_type( $campaign_id ) !== HURRYT_POST_TYPE ) {
            die( -1 );
        }

        $campaign = new Campaign( $campaign_id );
        $endDate  = $campaign->getRecurringDeadline();
        wp_send_json_success( [
            'endTimestamp' => $endDate ? $endDate->getBrowserTimestamp() : null,
        ] );
    }

    public function bypassEvergreenDetectCookieCache()
    {
        check_ajax_referer( 'hurryt', 'nonce' );
        if ( ! isset( $_POST[ 'timestamp' ] ) || ! isset( $_POST[ 'cid' ] ) ) {
            wp_die();
        }
        $ipDetection       = new IPDetection();
        $cookieDetection   = new CookieDetection();
        $evergreenCampaign = new EvergreenCampaign( intval( $_POST[ 'cid' ] ), $cookieDetection,
            $ipDetection );
        $evergreenCampaign->setEndDate( filter_input( INPUT_POST, 'timestamp' ) );
        wp_die();
    }

    public function maybeDisplayStickyBar()
    {
        $stickyCampaigns = $posts = get_posts( [
            'post_type'        => HURRYT_POST_TYPE,
            'numberposts'      => -1,
            'post_status'      => 'publish',
            'meta_key'         => 'enable_sticky',
            'meta_value'       => C::YES,
            'suppress_filters' => false,
        ] );
        foreach ( $stickyCampaigns as $post ) {
            echo do_shortcode( '[hurrytimer id="' . $post->ID . '"]' );
        }

    }

    /**
     * Apply change stock status
     */
    public function changeStockStatus()
    {
        check_ajax_referer( 'hurryt', 'ajax_nonce' );
        if ( isset( $_POST[ 'id' ] ) || ! isset( $_POST[ 'stockStatus' ] ) ) {
            die( -1 );
        }
        $id          = intval( $_POST[ 'id' ] );
        $stockStatus = sanitize_key( $_POST[ 'stockStatus' ] );
        $wcCampaign  = new WCCampaign();
        $campaign    = new Campaign( $id );

        $wcCampaign->changeStockStatus( $campaign, $stockStatus );
        die();
    }

    public function handleWCSingleProduct()
    {
        global $post;

        if ( Utils\Helpers::isWcActive() && is_product() ) {
            $wcCampaign = new WCCampaign();
            $wcCampaign->run( $post->ID );
        }

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        $buildVersion = false;

        $filePath = HURRYT_DIR . 'assets/css/hurrytimer.css';
        if ( file_exists( $filePath ) ) {
            $buildVersion = filemtime( $filePath );
        }

        if ( ! $buildVersion ) {
            $buildVersion = time();
        }

        wp_enqueue_style(
            $this->plugin_name,
            HURRYT_URL . 'assets/css/hurrytimer.css', [], $buildVersion
        );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        wp_enqueue_script(
            'hurryt-cookie',
            HURRYT_URL . 'assets/js/cookie.min.js',
            [],
            '2.2.0',
            true
        );
        wp_enqueue_script(
            'hurryt-countdown',
            HURRYT_URL . 'assets/js/jquery.countdown.min.js',
            [ 'jquery' ],
            '2.2.0',
            true
        );
        wp_enqueue_script(
            $this->plugin_name,
            HURRYT_URL . 'assets/js/hurrytimer.js',
            [ 'hurryt-countdown', 'hurryt-cookie' ],
            defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : $this->version,
            true
        );
        $pluginSettings = new PluginSettings();
        $settings       = $pluginSettings->getSettings();
        wp_localize_script( $this->plugin_name, 'hurrytimer_ajax_object', [
            'tz'                      => \hurryt_tz(),
            'ajax_url'                => admin_url( 'admin-ajax.php' ),
            'ajax_nonce'              => wp_create_nonce( 'hurryt' ),
            'disable_actions'         => Helpers::isUsingDashboard()
                                         && $settings[ 'disable_actions' ],
            'sticky_bar_hide_timeout' => apply_filters( 'hurryt_sticky_bar_hide_timeout',
                7 ),
            'actionsOptions'          => [
                'none'                => C::ACTION_NONE,
                'hide'                => C::ACTION_HIDE,
                'redirect'            => C::ACTION_REDIRECT,
                'stockStatus'         => C::ACTION_CHANGE_STOCK_STATUS,
                'hideAddToCartButton' => C::ACTION_HIDE_ADD_TO_CART_BUTTON,
                'displayMessage'      => C::ACTION_DISPLAY_MESSAGE,
            ],
            'restartOptions'          => [
                'none'        => C::RESTART_NONE,
                'immediately' => C::RESTART_IMMEDIATELY,
                'afterReload' => C::RESTART_AFTER_RELOAD,
            ],
            'redirect_no_back'        => apply_filters( 'hurryt_redirect_no_back', true ),
        ] );
    }
}
