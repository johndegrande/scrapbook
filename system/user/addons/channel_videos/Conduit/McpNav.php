<?php

namespace EEHarbor\ChannelVideos\Conduit;

use EEHarbor\ChannelVideos\FluxCapacitor\Conduit\McpNav as FluxNav;

class McpNav extends FluxNav
{
    protected function defaultItems()
    {
        $this->setToolbarIcon(null, 'vimeo');

        $default_items = array(
            'vimeo' => lang('cv:player:vimeo'),
            'youtube' => lang('cv:player:youtube'),
            'https://eeharbor.com/channel-videos/documentation' => lang('cv:docs'),
        );

        return $default_items;
    }
}
