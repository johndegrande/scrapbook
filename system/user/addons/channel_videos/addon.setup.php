<?php

require_once 'autoload.php';
$addonJson = json_decode(file_get_contents(__DIR__ . '/addon.json'));

return array(
    'name'              => $addonJson->name,
    'description'       => $addonJson->description,
    'version'           => $addonJson->version,
    'namespace'         => $addonJson->namespace,
    'author'            => 'EEHarbor',
    'author_url'        => 'http://eeharbor.com/channel-videos',
    'docs_url'          => 'http://eeharbor.com/channel-videos/documentation',
    'settings_exist' => true,
    'settings_module' => array(
        'youtube' => array(
            'width' => 560,
            'height' => 315,
            'autohide' => 1,
            'autoplay' => 0,
            'cc_load_policy' => 0,
            'color' => 'red',
            'controls' => 1,
            'disablekb' => 0,
            'enablejsapi' => 0,
            'end' => '',
            'fs' => 1,
            'iv_load_policy' => 1,
            'list' => '',
            'listType' => '',
            'loop' => 0,
            'modestbranding' => 0,
            'origin' => '',
            'playerapiid' => '',
            'playlist' => '',
            'rel' => 1,
            'showinfo' => 1,
            'start' => 0,
            'theme' => 'dark',
        ),
        'vimeo' => array(
            'width' => 500,
            'height' => 281,
            'title' => 1,
            'byline' => 1,
            'portrait' => 1,
            'color' => '00adef',
            'autoplay' => 0,
            'loop' => 0,
            'api' => 0,
            'player_id' => '',
        )
    )
);
