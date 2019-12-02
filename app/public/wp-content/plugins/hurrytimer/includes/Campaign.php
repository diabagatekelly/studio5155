<?php

namespace Hurrytimer;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Hurrytimer\Utils\Helpers;

class Campaign
{

    /**
     * Campaign custom post ID
     *
     * @var int
     */
    private $id;

    /**
     * Campaign mode.
     *
     * @see C.php
     *
     * @var int
     */
    public $mode = C::MODE_REGULAR;

    /**
     * Evergreen duration array.
     *
     * @see getDuration()
     *
     * @var array
     */
    public $duration;

    /**
     * Recurrence duration
     *
     * @var int
     */
    public $recurringDuration;

    /**
     * Recurrence start date/time
     *
     * @var string
     */
    public $recurringStartTime;

    /**
     * Recurrence end option
     *
     * @var int
     */
    public $recurringEnd = C::RECURRING_END_NEVER;

    /**
     * Recurrence frequency
     *
     * @see C
     * @var string
     */
    public $recurringFrequency = C::RECURRING_DAILY;

    /**
     * Recurrence interval
     *
     * @var int
     */
    public $recurringInterval = 1;

    /**
     * Recurrence count
     *
     * @var int
     */
    public $recurringCount = 2;

    /**
     * Recurrence end date/time
     *
     * @var
     */
    public $recurringUntil;

    /**
     * Recurrence allowed days
     *
     * @var array
     */
    public $recurringDays = [ 0, 1, 2, 3, 4, 5, 6 ];

    /**
     * Recurrence timezone
     *
     * @todo Not used
     * @var mixed|string|void
     */
    public $recurringTimezone;

    /**
     * Restart option after expiration.
     *
     * @see \Hurrytimer\CampaignRestart
     *
     * @var int
     */
    public $restart;

    /**
     * Headline text.
     *
     * @var string
     */
    public $headline = "Hurry Up!";

    /**
     * Headline color.
     *
     * @var string
     */
    public $headlineColor = "#000";

    /**
     * Headline size.
     *
     * @var int
     */
    public $headlineSize = 30;

    /**
     * Headline position.
     *
     * @var int
     */
    public $headlinePosition = C::HEADLINE_POSITION_ABOVE_TIMER;

    /**
     * Headline visibility.
     *
     * @var boolean
     */
    public $headlineVisibility = C::YES;

    public $headlineSpacing = 5;

    /**
     * Control labels visibility.
     *
     * @var string
     */
    public $labelVisibility = C::YES;

    /**
     * Show/hide days block.
     *
     * @var string
     */
    public $daysVisibility = C::YES;

    /**
     * Show/hide hours block.
     *
     * @var string
     */
    public $hoursVisibility = C::YES;

    /**
     * Show/hide minutes block.
     *
     * @var string
     */
    public $minutesVisibility = C::YES;

    /**
     * Show/hide seconds block.
     *
     * @var string
     */
    public $secondsVisibility = C::YES;

    /**
     * Regular compaign end datetime.
     *
     * @var string
     */
    public $endDatetime;

    /**
     * Labels texts.
     *
     * @var array
     */
    public $labels
        = [
            'days'    => 'days',
            'hours'   => 'hrs',
            'minutes' => 'mins',
            'seconds' => 'secs',
        ];

    /**
     * Digit color.
     *
     * @var string
     */
    public $digitColor = "#000";

    /**
     * Digit size.
     *
     * @var int
     */
    public $digitSize = 35;

    /**
     * Redirect action url.
     *
     * @var string
     */
    public $redirectUrl;

    /**
     * Label size.
     *
     * @var int
     */
    public $labelSize = 12;

    /**
     * Label color.
     *
     * @var string
     */
    public $labelColor = "#000";

    /**
     * End action.
     *
     * @var array
     */
    public $actions = [];

    /**
     * WooCommerce compaign position in product page.
     *
     * @var int
     */
    public $wcPosition = C::WC_POSITION_ABOVE_TITLE;

    /**
     * Enable/disable woocommerce integration.
     *
     * @var string
     */
    public $wcEnable = C::NO;

    /**
     * WooCommerce products selection.
     *
     * @var array
     */
    public $wcProductsSelection;

    /**
     * WooCommerce products selection type.
     *
     * @var int
     */
    public $wcProductsSelectionType;

    public $wcConditions;

    /**
     * Timer block border color.
     *
     * @var string
     */
    public $blockBorderColor = "";

    /**
     * Timer Block border width.
     *
     * @var int
     */

    public $blockBorderWidth = 0;

    /**
     * Timer block radius.
     *
     * @var int
     */
    public $blockBorderRadius = 0;

    /**
     * Block size.
     *
     * @var int
     */

    public $blockSize = 50;

    /**
     * Block background color.
     *
     * @var string
     */
    public $blockBgColor = '';

    /**
     * Block spacing.
     *
     * @var int
     */
    public $blockSpacing = 5;

    /**
     * Block padding.
     *
     * @var int
     */
    public $blockPadding = 0;

    /**
     * Block spearator visibility.
     *
     * @var boolean
     */
    public $blockSeparatorVisibility = C::YES;

    /**
     * Label case
     *
     * @var string
     */
    public $labelCase = C::TRANSFORM_UPPERCASE;

    /**
     * Custom CSS
     *
     * @var string
     */
    public $customCss = '';

    /**
     * Block elements display
     * Values: block, inline
     *
     * @var string
     */
    public $blockDisplay = 'block';

    /**
     * Enable sticky bar.
     */
    public $enableSticky = C::NO;

    /**
     * Sticky bar background color.
     *
     * @var string
     */
    public $stickyBarBgColor = '#eee';

    /**
     * Sticky bar close button color.
     *
     * @var string
     */
    public $stickyBarCloseBtnColor = '#fff';

    /**
     * Sticky bar position.
     * Values: top, bottom
     *
     * @var string
     */
    public $stickyBarPosition = 'top';

    /**
     * Sticky bar padding.
     *
     * @var integer
     */
    public $stickyBarPadding = 5;

    /**
     * Sticky bar display pages.
     *
     * @var array
     */
    public $stickyBarPages = [];

    /**
     * Where to display the sticky bar option
     *
     * @var string
     */
    public $stickyBarDisplayOn = 'all_pages';

    /**
     * Show sticky bar close button?
     *
     * @var string
     */
    public $stickyBarDismissible = C::YES;

    /**
     * CTA settings.
     *
     * @var array
     */
    public $callToAction
        = [
            'enabled'       => C::NO,
            'new_tab'       => C::NO,
            'url'           => '',
            'text'          => 'Learn More',
            'text_size'     => 15,
            'text_color'    => '#fff',
            'bg_color'      => '#000',
            'y_padding'     => 10,
            'x_padding'     => 15,
            'border_radius' => 3,
            'spacing'       => 5,
        ];

    /**
     * Campaign elements display
     * values: block,inline
     *
     * @var string
     */
    public $campaignDisplay = 'block';

    /**
     * Campaign aligments
     * values: center,left,right
     *
     * @var string
     */
    public $campaignAlign = 'center';

    /**
     * Campaign spacing.
     *
     * @var integer
     */
    public $campaignSpacing = 10;

    /**
     * Campaign horizontal padding.
     *
     * @var integer
     */
    public $campaignXPadding = 10;

    /**
     * Campaign vertical padding.
     *
     * @var integer
     */
    public $campaignYPadding = 10;

    public function __construct( $id )
    {
        // Default recurring timezone.
        $this->recurringTimezone  = Utils\Helpers::getSiteTimezone();
        $this->recurringStartTime = Carbon::now( hurryt_tz() )->format( 'Y-m-d h:i A' );
        $this->id                 = $id;
    }

    /**
     * Returns compaign post iD.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function showStickyBarOn( $pageId )
    {
        if ( $this->enableSticky === C::NO ) {
            return true;
        }

        $can_show = false;
        switch ( $this->getStickyBarDisplayOn() ) {
            case 'all_pages':
                $can_show = true;
                break;
            case 'wc_products_pages':
                if ( function_exists( 'is_product' ) && is_product() ) {
                    $wc_campaign = new WCCampaign();
                    $can_show    = ( $wc_campaign->hasActiveCampaign( $this, $pageId ) );
                } else {
                    $can_show = false;
                }
                break;
            case 'specific_pages':
                $pages    = $this->getStickyBarPages();
                $can_show = in_array( $pageId, $pages );
                break;
        }

        return apply_filters( 'hurryt_show_sticky_bar', $can_show, $this->getId() );

    }

    public function getStickyBarPages()
    {
        $pagesIds = $this->getRawSetting( 'sticky_bar_pages' );

        return array_filter( array_map( 'intval', $pagesIds ) );
    }

    public function setStickyBarPages( $value )
    {
        $this->storeRawSetting( 'sticky_bar_pages', $value );
    }

    public function getStickyBarDisplayOn()
    {

        // backward compat.
        $all_pages = $this->getRawSetting( 'sticky_bar_show_on_all_pages', false );

        if ( $all_pages === C::YES ) {
            $this->setStickyBarDisplayOn( 'all_pages' );
        }

        $this->deleteRawSetting( 'sticky_bar_show_on_all_pages' );

        $value = $this->getRawSetting( '_hurryt_sticky_bar_display_on', false );

        return ! empty( $value ) ? $value : $this->stickyBarDisplayOn;
    }

    public function setStickyBarDisplayOn( $value )
    {
        $this->storeRawSetting( '_hurryt_sticky_bar_display_on', $value );
    }

    /**
     * Get raw setting.
     *
     * @param string $name
     * @param boolean
     *
     * @return mixed
     */
    public function getRawSetting( $name, $useDefault = true )
    {
        $value = get_post_meta( $this->id, $name, true );
        if ( ! empty( $value ) ) {
            return $value;
        }
        if ( $useDefault ) {
            return $this->{Helpers::snakeToCamelCase( $name )};
        }

        return $value;
    }

    public function storeRawSetting( $name, $value )
    {
        $value        = ! empty( $value ) ? $value : '';
        $getterMethod = Helpers::snakeToCamelCase( $name );
        if ( empty( $value ) && method_exists( $this, $getterMethod ) ) {
            $value = $this->{$getterMethod}();
        }

        update_post_meta( $this->id, $name, $value );
    }

    /**
     * Bulk save for compaign settings.
     *
     * @param $data
     */
    public function storeSettings( $data )
    {
        foreach ( $data as $prop => $value ) {
            $method = Helpers::snakeToCamelCase( "set_{$prop}" );
            if ( method_exists( $this, $method ) ) {
                $this->$method( $value ?: $this->$prop );
            } elseif ( property_exists( __CLASS__, Helpers::snakeToCamelCase( $prop ) ) ) {
                $this->storeRawSetting( $prop, $value );
            }
        }
        if ( ! isset( $data[ 'wc_conditions' ] ) ) {
            $this->setWcConditions( [] );
        }
        if ( ! isset( $data[ 'sticky_bar_pages' ] ) ) {
            $this->setStickyBarPages( [] );
        }
        self::buildCss();
    }

    //removeIf(pro)
    /**
     * @param int $mode
     */
     public function setMode($mode)
     {
         if($mode == C::MODE_RECURRING){
             return;
         }
         $this->storeRawSetting('mode',$mode);
     }
    //endRemoveIf(pro)
    /**
     * Build User CSS,
     * Then merge it with base one.
     *
     * @return void
     */
    public static function buildCss()
    {

        // Build and return CSS
        ob_start();
        include HURRYT_DIR . 'includes/css.php';
        $css  = ob_get_clean();
        $base = file_get_contents( HURRYT_DIR . '/assets/css/base.css' );

        // Merge built and base css.
        $base .= $css;

        // Save to public file.
        file_put_contents( HURRYT_DIR . '/assets/css/hurrytimer.css', $base );
    }

    private function setRecurringDates( $dates )
    {
        foreach ( $dates as $d ) {
            add_post_meta( $this->getId(), '_hurryt_recurring_dates', $d );
        }
    }

    public function loadSettings()
    {

        $reflection = new \ReflectionObject( $this );
        foreach ( $reflection->getProperties( \ReflectionProperty::IS_PUBLIC ) as $prop ) {
            $name   = $prop->getName();
            $method = 'get' . ucfirst( $name );
            if ( method_exists( $this, $method ) ) {
                $this->{$name} = $this->$method();
            } else {
                $this->{$name} = $this->getRawSetting( Helpers::camelToSnakeCase( $name ) );
            }
        }
    }

    /**
     * Returns compaign headline.
     *
     * @return mixed
     */
    public function getHeadline()
    {
        return $this->id ? get_the_title( $this->id ) : $this->headline;
    }

    public function isPublished()
    {
        return get_post_status( $this->id ) === "publish";
    }

    public function isWcEnabled()
    {
        return $this->getWcEnable() === C::YES;
    }

    /**
     * Check current countdown timer mode
     *
     * @return bool
     */
    public function isEvergreen()
    {
        return $this->getRawSetting( 'mode' ) == C::MODE_EVERGREEN;
    }

    public function isRegular()
    {
        return $this->getRawSetting( 'mode' ) == C::MODE_REGULAR;
    }

    public function isRecurring()
    {
        return $this->getRawSetting( 'mode' ) == C::MODE_RECURRING;

    }

    /**
     * Save compaign endtime for regular mode.
     *
     * @param $date
     */
    public function setEndDatetime( $date )
    {
        $this->storeRawSetting( 'end_datetime', $date ?: $this->defaultEndDatetime() );
    }

    /**
     * Returns default end datetime for regular mode.
     *
     * @return false|string
     */
    private function defaultEndDatetime()
    {
        return date( 'Y-m-d h:i A', strtotime( '+1 week' ) );
    }

    /**
     * Save digit color.
     *
     * @param $color
     */
    public function setDigitColor( $color )
    {
        $this->storeRawSetting( 'digit_color', $color );
    }

    /**
     * Returns headline visibility.
     *
     * @return mixed
     */

    public function getHeadlineVisibility()
    {
        $meta = 'headline_visibility';

        $value = $this->getRawSetting( $meta, false );
        if ( $value === C::NO || $value === C::YES ) {
            return $value;
        }

        $legacyMeta  = 'display_headline';
        $legacyValue = filter_var( $this->getLegacySetting( $legacyMeta ),
            FILTER_VALIDATE_BOOLEAN );
        $legacyValue = $legacyValue ? C::YES : C::NO;

        $this->storeRawSetting( $meta, $legacyValue );

        $this->deleteRawSetting( $legacyMeta );

        return $this->getRawSetting( $meta );
    }

    /**
     * Returns digit color.
     * Backward compat with older versions.
     *
     * @return mixed
     */
    public function getDigitColor()
    {
        $value = $this->getRawSetting( 'digit_color', false );
        if ( $value ) {
            return $value;
        }

        $legacy = $this->getLegacySetting( 'text_color' );
        if ( ! $legacy ) {
            return $this->digitColor;
        }

        $this->setDigitColor( $legacy );
        if ( ! $this->getRawSetting( 'label_color', false ) ) {
            $this->setLabelColor( $legacy );
        }

        $this->deleteRawSetting( 'text_color' );

        return $this->getRawSetting( 'digit_color' );
    }

    public function setLabelColor( $color )
    {
        $this->storeRawSetting( 'label_color', $color );
    }

    /**
     * Get label color
     *
     * @return mixed
     */
    public function getLabelColor()
    {
        $value = $this->getRawSetting( 'label_color', false );
        if ( $value ) {
            return $value;
        }

        $legacy = $this->getLegacySetting( 'text_color' );
        if ( ! $legacy ) {
            return $this->labelColor;
        }

        $this->setLabelColor( $legacy );
        if ( ! $this->getRawSetting( 'digit_color', false ) ) {
            $this->setDigitColor( $legacy );
        }

        $this->deleteRawSetting( 'text_color' );

        return $this->getRawSetting( 'label_color' );

    }

    /**
     *
     * Return timer digit size.
     * Backward comapt. with older versions.
     *
     * @return int
     * @since 1.2.4
     */
    public function getDigitSize()
    {
        $meta  = 'digit_size';
        $value = $this->getRawSetting( $meta, false );
        if ( ! empty( $value ) ) {
            return $value;
        }
        $legacy = $this->getLegacySetting( 'text_size' );

        if ( empty( $legacy ) ) {
            return $this->digitSize;
        }

        $this->setDigitSize( $legacy );

        $this->deleteRawSetting( 'text_size' );

        return $this->getRawSetting( 'digit_size' );
    }

    /**
     * Save timer digit size.
     *
     * @param $size
     */
    public function setDigitSize( $size )
    {
        $this->storeRawSetting( 'digit_size', $size );
    }

    /**
     * Save timer label size.
     *
     * @param $size
     */
    public function setLabelSize( $size )
    {
        $this->storeRawSetting( 'label_size', $size );
    }

    public function setHeadlineSize( $size )
    {
        $this->storeRawSetting( 'headline_size', $size );
    }

    /**
     * Returns evergreen duration.
     *
     * @return array
     */
    public function getDuration()
    {
        $default  = [ 0, 0, 0, 0 ];
        $duration = $this->getRawSetting( 'duration' );
        if ( is_array( $duration ) ) {
            $duration = array_merge( $duration, $default );

            return array_map( 'intval', $duration );
        }

        return $default;
    }

    public function setRecurringDuration( $value )
    {
        $this->storeRawSetting( '_hurryt_recurring_duration', $value );
    }

    public function setRecurringPauseDuration( $value )
    {
        $this->storeRawSetting( '_hurryt_recurring_pause_duration', $value );
    }

    /**
     * Returns evergreen duration.
     *
     * @return array
     */
    public function getRecurringDuration()
    {
        $default  = [ 0, 0, 0, 0 ];
        $duration = $this->getRawSetting( '_hurryt_recurring_duration', false );
        if ( is_array( $duration ) ) {
            return array_map( 'intval', array_merge( $duration, $default ) );
        }

        return $default;
    }

    /**
     * Returns actions array.
     *
     * @return array
     */
    public function getActions()
    {
        $legacy = $this->getLegacySetting( 'end_action' );
        if ( $legacy ) {
            $redirectUrl = $this->getLegacySetting( 'redirect_url' );
            $actions     = [
                [
                    'id'          => intval( $legacy ),
                    'redirectUrl' => $redirectUrl,
                ],
            ];
            $this->storeRawSetting( 'actions', $actions );
            $this->deleteRawSetting( 'end_action' );
            $this->deleteRawSetting( 'redirect_url' );

            return $this->mergeActions( $actions );
        }

        return $this->mergeActions( $this->getRawSetting( 'actions' ) );
    }

    private function mergeActions( $actions )
    {
        $defaults = [
            [
                'id'            => C::ACTION_NONE,
                'redirectUrl'   => '',
                'message'       => '',
                'wcStockStatus' => '',
            ],
        ];

        if ( count( $actions ) === 0 ) {
            return $defaults;
        }

        return array_map( function ( $action ) use ( $defaults ) {
            $action[ 'id' ] = (int)$action[ 'id' ];

            return array_merge( $defaults[ 0 ], $action );
        }, $actions );
    }

    /**
     * Returns evergreen duration in seconds.
     *
     * @param array $duration
     *
     * @return int
     */
    public function durationArrayToSeconds( $duration = [] )
    {
        list( $d, $h, $m, $s ) = empty( $duration ) ? $this->getDuration() : $duration;

        return $d * DAY_IN_SECONDS +
               $h * HOUR_IN_SECONDS +
               $m * MINUTE_IN_SECONDS +
               $s;
    }

    /**
     * Returns end datetime.
     *
     * @return string
     */
    public function getEndDatetime()
    {
        return $this->getRawSetting( 'end_datetime' ) ?: $this->defaultEndDatetime();
    }

    /**
     * Return true if the recurrence is expired.
     *
     * @return bool
     */
    public function isRecurringExpired()
    {
        $deadline = $this->getRecurringDeadline();
        if ( ! ( $deadline instanceof Carbon ) ) {
            return false;
        }

        $now = Carbon::now( hurryt_tz() );

        return $now->isAfter( $deadline );
    }

    function getTimeToNextRecurrence()
    {
        $this->loadSettings();
        $interval = absint( $this->recurringInterval );

        if ( $this->recurringFrequency === C::RECURRING_DAILY ) {
            $frequencyInSeconds = DAY_IN_SECONDS;
        } elseif ( $this->recurringFrequency === C::RECURRING_WEEKLY ) {
            $frequencyInSeconds = WEEK_IN_SECONDS;

        } elseif ( $this->recurringFrequency === C::RECURRING_HOURLY ) {
            $frequencyInSeconds = HOUR_IN_SECONDS;

        } else {
            $frequencyInSeconds = MINUTE_IN_SECONDS;
        }

        $duration = $this->durationArrayToSeconds( $this->getRecurringDuration() );

        return max( 0, ( $frequencyInSeconds * $interval ) - $duration );
    }

    /**
     * Return true if the current campaign can recur today.
     *
     * @return bool
     */
    public function canRecurToday()
    {
        $this->loadSettings();
        $day   = absint( Carbon::now( hurryt_tz() )->format( 'w' ) );
        $recur = in_array( $day, array_map( 'absint', $this->recurringDays ) );

        return apply_filters( 'hurryt_recur_today', $recur, $this->getId() );
    }

    /**
     * Return current recurrence date
     *
     * @return \Carbon\CarbonInterface|null
     */
    public function getRecurringCurrentDate()
    {
        $this->loadSettings();
        $dtStart = Carbon::parse( $this->recurringStartTime, hurryt_tz() );
        $now     = Carbon::now( hurryt_tz() );

        $range    = CarbonPeriod::since( $dtStart );
        $interval = absint( $this->recurringInterval );

        if ( $this->recurringFrequency === C::RECURRING_DAILY ) {
            $range->days( $interval );
        } elseif ( $this->recurringFrequency === C::RECURRING_WEEKLY ) {
            $range->weeks( $interval );
        } elseif ( $this->recurringFrequency === C::RECURRING_HOURLY ) {
            $range->hours( $interval );
        } else {
            $range->minutes( $interval );
        }

        // Recurs forever
        if ( absint( $this->recurringEnd ) === C::RECURRING_END_NEVER ) {
            $range->until( $now );
            // Recurs until
        } elseif ( absint( $this->recurringEnd ) === C::RECURRING_END_TIME ) {
            $range->until( Carbon::parse( $this->recurringUntil, hurryt_tz() ) );

            $range->addFilter( function ( $date ) use ( $now ) {
                /**
                 * @var Carbon $date
                 */
                return $date->isBefore( $now );
            } );

            // Recurs N times.
        } elseif ( absint( $this->recurringEnd ) === C::RECURRING_END_OCCURRENCES ) {

            // Virtual endDate
            $range->until( Carbon::now( hurryt_tz() )->addCenturies( 1 ) );

            // Limit to the number of occurences in `$this->recurringCount`
            $range->setRecurrences( absint( $this->recurringCount ) );

            // Set the endDate to the last occurence date
            $range->setEndDate( $range->last() );
            $range->addFilter( function ( $date ) use ( $now ) {

                /**
                 * @var Carbon $date
                 */
                return $date->isBefore( $now );
            } );

        }

        return $range->last();
    }

    /**
     * Get the next/current deadline based on the current date.
     *
     * @return null|Carbon
     */
    public function getRecurringDeadline()
    {
        $date = $this->getRecurringCurrentDate();
        if ( ! $date ) {
            return null;
        }
        $date = clone Carbon::instance( $date );

        $duration = $this->durationArrayToSeconds( $this->getRecurringDuration() );

        $date->addSeconds( $duration );

        return $date;
    }

    /**
     * Returns compaign publish datetime.
     *
     * @return mixed
     */
    public function getStartTimestamp()
    {
        return get_the_date( $this->id );
    }

    /**
     * Returns position in WooCommerce product page.
     *
     * @return mixed
     */
    public function getWcPosition()
    {
        $legacy = $this->getLegacySetting( 'position' );

        if ( ! $legacy ) {
            return $this->getRawSetting( 'wc_position' );
        }
        $this->storeRawSetting( 'wc_position', $legacy );
        $this->deleteRawSetting( 'position' );

        return $legacy;
    }

    private function getLegacySetting( $name )
    {
        return get_post_meta( $this->id, $name, true );
    }

    /**
     * Returns true if WooCommerce integration is enabled.
     *
     * @return mixed
     */
    public function getWcEnable()
    {
        $value = $this->getRawSetting( 'wc_enable', false );

        if ( $value === C::YES || $value === C::NO ) {
            return $value;
        }

        $legacy = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
        if ( $legacy ) {
            $this->storeRawSetting( 'wc_enable', C::YES );
        } else {
            $this->storeRawSetting( 'wc_enable', C::NO );
        }

        return $this->getRawSetting( 'wc_enable' );
    }

    /**
     * Returns true if label should be displayed.
     *
     * @return mixed
     */
    public function getLabelVisibility()
    {
        $meta  = 'label_visibility';
        $value = $this->getRawSetting( $meta, false );
        if ( $value === C::NO || $value === C::YES ) {
            return $value;
        }

        $legacy = filter_var( $this->getLegacySetting( 'display_labels' ),
            FILTER_VALIDATE_BOOLEAN );
        $legacy = $legacy ? C::YES : C::NO;
        $this->storeRawSetting( $meta, $legacy );
        $this->deleteRawSetting( 'display_labels' );

        return $this->getRawSetting( $meta );

    }

    /**
     *
     * Returns restart type.
     *
     * @return int
     */
    public function getRestart()
    {
        return $this->getRawSetting( 'restart' ) ?: C::RESTART_NONE;
    }

    /**
     * Returns WooCommerce products selection type.
     *
     * @return string
     */
    public function getWcProductsSelectionType()
    {
        $legacy = $this->getLegacySetting( 'products_type' );
        if ( ! $legacy ) {
            return $this->getRawSetting( 'wc_products_selection_type' );
        }
        $this->storeRawSetting( 'wc_products_selection_type', $legacy );
        $this->deleteRawSetting( 'products_type' );

        return $legacy;
    }

    /**
     * Delete setting item.
     *
     * @param $name
     */
    public function deleteRawSetting( $name )
    {
        delete_post_meta( $this->id, $name );
    }

    /**
     * Returns products selection IDs.
     *
     * @return array
     */
    public function getWcProductsSelection()
    {
        $legacy = $this->getLegacySetting( 'products' );
        if ( ! $legacy ) {
            return $this->getRawSetting( 'wc_products_selection' );
        }
        $this->storeRawSetting( 'wc_products_selection', $legacy );
        $this->deleteRawSetting( 'products' );

        return $legacy;
    }

    /**
     * Store custom labels.
     *
     * @param $labels
     */
    public function setLabels( $labels )
    {
        $labels = array_merge( $this->labels, array_filter( $labels ) );
        update_post_meta( $this->id, 'labels', $labels );
    }

    /**
     * Returns true if compaign can be published.
     *
     * @return bool
     */
    public function isActive()
    {
        return get_post_status( $this->id ) === "publish"
               || get_post_status( $this->id ) === "future";

    }

    /**
     * Returns trus if the compaign is scheduled.
     *
     * @return bool
     */
    public function isScheduled()
    {
        $scheduled = get_post_status( $this->getId() ) === "future";
        if ( $scheduled ) {
            return true;
        }

        if ( $this->isRecurring() ) {
            $start_date = Carbon::parse( $this->recurringStartTime, hurryt_tz() );
            $now        = Carbon::now( hurryt_tz() );
            if ( $now->isBefore( $start_date ) ) {
                return true;
            }
        }

        return $scheduled;
    }

    /**
     * Returns true if fixed campaign datetime is expired.
     *
     * @param string|null $date
     *
     * @return bool
     */
    public function isExpired( $date = null )
    {
        $endDate = $date === null ? date_create( $this->endDatetime ) : $date;
        $today   = date_create( current_time( "mysql" ) );

        return $endDate < $today;
    }

    public function isStickyDismissed()
    {
        return isset( $_COOKIE[ CookieDetection::COOKIE_PREFIX . $this->getId() . '_dismissed' ] )
               && $this->stickyBarDismissible === C::YES
               && $this->enableSticky === C::YES;
    }

    /**
     * Returns compaign template wrapper.
     *
     * @param string
     *
     * @return string
     */
    public function wrapTemplate( $content )
    {
        $campaignBuilder = new CampaignBuilder( $this );

        return $campaignBuilder->build( $content );
    }

    /*
     * Returns campaign template content.
     */
    public function buildTemplate()
    {
        $campaignBuilder = new CampaignBuilder( $this );

        return $campaignBuilder->template();
    }

    public function setWcConditions( $value )
    {
        $this->storeRawSetting( '_hurryt_wc_conditions', ! empty( $value ) ? $value : [] );
    }

    public function getWcConditions()
    {
        return $this->getRawSetting( '_hurryt_wc_conditions', false );
    }

}
