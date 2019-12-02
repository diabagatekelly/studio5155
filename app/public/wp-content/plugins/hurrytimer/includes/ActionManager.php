<?php
namespace Hurrytimer;

use Hurrytimer\Utils\Helpers;

/**
 *
 * This class handle actions executions
 *
 * Class ActionManager
 *
 * @package Hurrytimer
 */
class ActionManager
{
    /**
     * @var Campaign
     */
    protected $campaign;

    public function __construct($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute registered campaign actions.
     */
    public function run()
    {
        $pluginSettings =  new PluginSettings();
        $settings = $pluginSettings->getSettings();
        if(Helpers::isUsingDashboard() && $settings['disable_actions']){
            return;
        }

        foreach ($this->campaign->actions as $action) {
            switch ($action['id']) {

                // Hide.
                case C::ACTION_HIDE:
                    add_filter('hurryt_campaign_template', '__return_empty_string', 1);
                    break;

                // URL redirect.
                case C::ACTION_REDIRECT;
                    @wp_redirect($action['redirectUrl']);
                    break;

                // Change stock status (regular mode)
                case C::ACTION_CHANGE_STOCK_STATUS:
                    $wcCampaign = new WCCampaign();
                    $wcCampaign->changeStockStatus($this->campaign, $action['wcStockStatus']);
                    break;

                // Display message.
                case C::ACTION_DISPLAY_MESSAGE:
                    add_filter('hurryt_campaign_template',
                        function () use ($action) {
                            return
                                "<div class='hurrytimer-campaign-message'>{$action['message']}</div>";
                        }, 2);
                    break;
            }
        }
    }

    }
