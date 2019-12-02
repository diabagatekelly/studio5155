<?php

namespace Hurrytimer;

use Carbon\Carbon;
use Exception;

class CampaignBuilder
{
    use CampaignBuilderLegacy;

    /**
     * Build campaign Template.
     *
     * @return string
     */

    /**
     * @var \Hurrytimer\Campaign
     */
    protected $campaign;

    public function __construct( $campaign )
    {
        $this->campaign = $campaign;
        $this->campaign->loadSettings();
    }

    /**
     * Returns built template.
     *
     * @param string
     * @param boolean
     *
     * @return string
     */
    public function build( $content = '', $withConfig = true )
    {
        $config      = $this->getClientConfig();
        $json        = json_encode( $config );
        $legacyClass = $this->legacyCampaignClass( $this->campaign->getId() );
        if ( $this->campaign->enableSticky === C::YES ) {

            return "<div class='hurrytimer-sticky hurryt-loading hurrytimer-sticky-{$this->campaign->getId()}'><div class='hurrytimer-sticky-inner'>"
                   . "<div class='{$legacyClass} hurrytimer-campaign hurrytimer-campaign-{$this->campaign->getId()}'"
                   . "data-config='{$json}' >{$content}</div></div>" . $this->stickyBarCloseButton()
                   . "</div>";
        }

        return "<div class='{$legacyClass} hurrytimer-campaign hurryt-loading hurrytimer-campaign-{$this->campaign->getId()}'"
               . "data-config='{$json}' >{$content}</div>";
    }

    public function template()
    {

        $template = ( $this->campaign->headlinePosition == C::HEADLINE_POSITION_ABOVE_TIMER
                ? $this->headline()
                : '' )
                    . '<div class="' . $this->legacyTimerClass() . ' hurrytimer-timer"></div>'
                    . ( $this->campaign->headlinePosition == C::HEADLINE_POSITION_BELOW_TIMER
                ? $this->headline() : '' )
                    . $this->callToActionButton();


        return $template;

    }

    /**
     * Returns common client config.
     *
     * @return array
     */
    private function commonClientConfig()
    {
        return [
            'id'       => $this->campaign->getId(),
            'actions'  => $this->campaign->actions,
            'template' => $this->timer(),
        ];
    }

    private function evergreenClientConfig()
    {
        $evergreenCompaign = new EvergreenCampaign(
            $this->campaign->getId(),
            new CookieDetection(),
            new IPDetection()
        );

        return [
            'isRegular'  => false,
            'duration'   => $this->campaign->durationArrayToSeconds(),
            'reset'      => $evergreenCompaign->reseting(),
            'restart'    => apply_filters( 'hurryt_evergreen_restart',
                $this->campaign->getRestart() ),
            'endDate'    => intval( $evergreenCompaign->getEndDate() ),
            'cookieName' => $evergreenCompaign->cookieName(),
        ];

    }

    /**
     * Returns regular config.
     *
     * @return array
     */
    private function regularClientConfig()
    {
        try {
            $endDate = Carbon::parse( $this->campaign->getEndDatetime(), hurryt_tz() )
                             ->getBrowserTimestamp();
            if ( $this->campaign->isRecurring() ) {
                $endDate = $this->campaign->getRecurringDeadline();
                if ( $endDate ) {
                    $endDate = $endDate->getBrowserTimestamp();
                } else {
                    $endDate = null;
                }
            }

            $timeToNextRecurrence = $this->campaign->isRecurring()
                ? $this->campaign->getTimeToNextRecurrence()
                : 0;

            return [
                'recurr'               => $this->campaign->isRecurring(),
                'timeToNextRecurrence' => $timeToNextRecurrence,
                'isRegular'            => true,
                'endDate'              => $endDate,
            ];

        } catch ( Exception $e ) {
            echo __( sprintf( 'HurryTimer Error: Invalid campaign (ID: %d). Please double check your settings.',
                $this->campaign->getId() ), 'hurrytimer' );
        }
    }

    /**
     * Returns client config for the compaign.
     *
     * @return array|null
     */
    public function getClientConfig()
    {
        if ( $this->campaign->isEvergreen() ) {
            return array_merge( $this->commonClientConfig(), $this->evergreenClientConfig() );

        } else {
            return array_merge( $this->commonClientConfig(), $this->regularClientConfig() );

        }
    }

    /**
     * Returns timer.
     *
     * @return string
     */
    public function timer()
    {
        $blocks = array_filter( [
            $this->daysBlock(),
            $this->hoursBlock(),
            $this->minutesBlock(),
            $this->secondsBlock(),
        ] );

        $template = implode( $this->separator(), $blocks );


        return $template;

    }

    /**
     * Returns separator.
     *
     * @return string
     */
    public function separator()
    {
        return $this->campaign->blockSeparatorVisibility === C::YES
            ? '<div class="' . $this->legacySeparatorClass() . ' hurrytimer-timer-sep">:</div>'
            : '';
    }

    /**
     * Returns days block.
     *
     * @return string
     */
    public function daysBlock()
    {
        $label = $this->campaign->labels[ 'days' ];

        return $this->campaign->daysVisibility === C::YES
            ? $this->block( "%D", $label )
            : '';
    }

    /**
     * Returns hours block.
     *
     * @return string
     */
    public function hoursBlock()
    {
        $label = $this->campaign->labels[ 'hours' ];

        return $this->campaign->hoursVisibility === C::YES
            ? $this->block( "%H", $label )
            : '';
    }

    /**
     * Returns minutes block.
     *
     * @return string
     */
    public function minutesBlock()
    {
        $label = $this->campaign->labels[ 'minutes' ];

        return $this->campaign->minutesVisibility === C::YES
            ? $this->block( "%M", $label )
            : '';

    }

    /**
     * Returns seconds block.
     *
     * @return string
     */
    public function secondsBlock()
    {

        $label = $this->campaign->labels[ 'seconds' ];

        return $this->campaign->secondsVisibility === C::YES
            ? $this->block( "%S", $label )
            : '';

    }

    /**
     * Returns block.
     *
     * @param $digitFormat
     * @param $label
     *
     * @return string
     */
    public function block( $digitFormat, $label )
    {
        return '<div class="hurrytimer-timer-block ' . $this->legacyBlockClass() . '">'
               . $this->digit( $digitFormat )
               . $this->label( $label )
               . '</div>';
    }

    public function digit( $format )
    {
        return '<div class="hurrytimer-timer-digit ' . $this->legacyDigitClass() . '">' . $format
               . '</div>';
    }

    public function label( $text )
    {
        if ( $this->campaign->labelVisibility === "no" ) {
            return '';
        }

        return '<div class="hurrytimer-timer-label ' . $this->legacyLabelClass() . '" >'
               . __( $text, 'hurrytimer' ) . '</div>';

    }

    public function headline()
    {

        $headline = __( $this->campaign->headline, 'hurrytimer' );

        return $this->campaign->headlineVisibility === C::YES
            ? '<div class="' . $this->legacyHeadlineClass() . ' hurrytimer-headline">'
              . $headline . '</div>'
            : '';

    }

    public function callToActionButton()
    {
        if ( $this->campaign->callToAction[ 'enabled' ] === C::NO ) {
            return '';
        }

        $cta_text = $this->campaign->callToAction[ 'text' ];

        $cta_url = $this->campaign->callToAction[ 'url' ];

        $target   = $this->campaign->callToAction[ 'new_tab' ] === C::YES ? '_blank' : '_self';
        $template = "<a class='hurrytimer-button' target='" . $target . "' href='" . $cta_url
                    . "' >" . $cta_text . "</a>";

        return "<div class='hurrytimer-button-wrap'>" . $template . "</div>";

    }

    public function stickyBarCloseButton()
    {
        if ( $this->campaign->stickyBarDismissible === C::NO
             || isset( $_COOKIE[ '_dismissed_sticky_' . $this->campaign->getId() ] )
        ) {
            return '';
        }

        if ( $this->campaign->stickyBarDismissible === C::YES ) {
            return '<button type="button" class="hurrytimer-sticky-close"><svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 357 357">
<polygon points="357,35.7 321.3,0 178.5,142.8 35.7,0 0,35.7 142.8,178.5 0,321.3 35.7,357 178.5,214.2 321.3,357 357,321.3
        214.2,178.5"/></svg></button>';

        }

    }

}
