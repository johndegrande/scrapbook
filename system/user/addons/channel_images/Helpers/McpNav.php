<?php

namespace EEHarbor\ChannelImages\Helpers;

use EEHarbor\ChannelImages\FluxCapacitor\Helpers\McpNav as FluxNav;

class McpNav extends FluxNav
{
    protected function defaultItems($items = array())
    {
        $default_items = array(
            '' => lang('ci:batch_actions'),
            'import' => lang('ci:import').' (Matrix / File)',
        );

        return $default_items;
    }

    protected function defaultButtons()
    {
        return array(
        );
    }

    protected function defaultActiveMap()
    {
        return array(
        );
    }
}
