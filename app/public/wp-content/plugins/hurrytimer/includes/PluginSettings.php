<?php

namespace Hurrytimer;

class PluginSettings
{

    public $defaults
        = [
            'disable_actions' => 0,
        ];

    function getSettings()
    {
        return array_merge($this->defaults, get_option('hurryt_settings') ?: []);
    }
}
