<?php

use EEHarbor\ChannelVideos\FluxCapacitor\FluxCapacitor;
use EEHarbor\ChannelVideos\FluxCapacitor\Base\Mcp;

class Channel_videos_mcp extends Mcp
{
    public function __construct()
    {
        parent::__construct();
        ee()->load->library('channel_videos_helper');

        // Some Globals
        $this->initGlobals();
    }

    // ********************************************************************************* //

    public function index($players = '')
    {
        if ($players != 'youtube') {
            $players = 'vimeo';
        }

        return ee()->functions->redirect($this->flux->moduleUrl($players));
    }

    public function youtube()
    {
        $this->vData['cp_page_title'] = lang('cv:player:youtube');
        $this->vData['players'] = 'youtube';
        $this->vData['sections'] = array(
            array(
                array(
                    'title' => lang('cv:yt:width'),
                    'desc' => '<small>(' . lang('cv:flash') . ',' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][width]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['width']) == true) ? $this->vData['youtube']['width'] : 560),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:height'),
                    'desc' => '<small>(' . lang('cv:flash') . ' &amp; ' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][height]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['height']) == true) ? $this->vData['youtube']['height'] : 315),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:autohide'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][autohide]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['autohide']) == true) ? $this->vData['youtube']['autohide'] : 1),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:autoplay'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][autoplay]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['autoplay']) == true) ? $this->vData['youtube']['autoplay'] : 0),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:cc_load_policy'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][cc_load_policy]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['cc_load_policy']) == true) ? $this->vData['youtube']['cc_load_policy'] : 0),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:color'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][color]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['color']) == true) ? $this->vData['youtube']['color'] : 'red'),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:controls'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][controls]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['controls']) == true) ? $this->vData['youtube']['controls'] : 1),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:disablekb'),
                    'desc' => '<small>(' . lang('cv:flash') . ')</small>',
                    'fields' => array(
                        'players[youtube][disablekb]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['disablekb']) == true) ? $this->vData['youtube']['disablekb'] : 0),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:enablejsapi'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][enablejsapi]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['enablejsapi']) == true) ? $this->vData['youtube']['enablejsapi'] : 0),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:end'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][end]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['end']) == true) ? $this->vData['youtube']['end'] : ''),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:iv_load_policy') ,
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][iv_load_policy]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['iv_load_policy']) == true) ? $this->vData['youtube']['iv_load_policy'] : 1),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:list'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][list]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['list']) == true) ? $this->vData['youtube']['list'] : ''),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:listType'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][listType]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['listType']) == true) ? $this->vData['youtube']['listType'] : 500),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:loop'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][loop]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['loop']) == true) ? $this->vData['youtube']['loop'] : 0),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:modestbranding'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][modestbranding]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['modestbranding']) == true) ? $this->vData['youtube']['modestbranding'] : 0),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:origin'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][origin]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['origin']) == true) ? $this->vData['youtube']['origin'] : ''),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:playerapiid'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][playerapiid]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['playerapiid']) == true) ? $this->vData['youtube']['playerapiid'] : ''),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:playlist'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][playlist]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['playlist']) == true) ? $this->vData['youtube']['playlist'] : ''),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:rel'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][rel]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['rel']) == true) ? $this->vData['youtube']['rel'] : 1),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:showinfo'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][showinfo]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['showinfo']) == true) ? $this->vData['youtube']['showinfo'] : 1),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:start'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][start]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['start']) == true) ? $this->vData['youtube']['start'] : 0),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:yt:theme'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][theme]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['theme']) == true) ? $this->vData['youtube']['theme'] : 'dark'),
                        ),
                    ),
                ),
            array(
                    'title' => lang('cv:yt:apikey'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[youtube][apikey]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['youtube']['apikey']) == true) ? $this->vData['youtube']['apikey'] : 0),
                        ),
                    ),
                ),
            ),
        );

        $this->vData['base_url'] = ee('CP/URL', 'addons/settings/channel_videos/update_players&players=youtube');
        $this->vData['save_btn_text'] = 'Save Player';
        $this->vData['save_btn_text_working'] = 'btn_saving';
        $this->vData['flux'] = $this->flux;

        return array(
            'heading' => lang('cv:players'),
            'body' => ee('View')->make('channel_videos:mcp')->render($this->vData),
        );
    }

    public function vimeo()
    {
        $this->vData['players'] = 'vimeo';
        $this->vData['cp_page_title'] = lang('cv:player:vimeo');
        $this->vData['sections'] = array(
            array(
                array(
                    'title' => lang('cv:vi:width'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[vimeo][width]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['vimeo']['width']) == true) ? $this->vData['vimeo']['width'] : 500),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:vi:height'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[vimeo][height]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['vimeo']['height']) == true) ? $this->vData['vimeo']['height'] : 281),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:vi:title'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[vimeo][title]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['vimeo']['title']) == true) ? $this->vData['vimeo']['title'] : 1),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:vi:byline'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[vimeo][byline]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['vimeo']['byline']) == true) ? $this->vData['vimeo']['byline'] : 1),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:vi:portrait'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[vimeo][portrait]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['vimeo']['portrait']) == true) ? $this->vData['vimeo']['portrait'] : 1),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:vi:color'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[vimeo][color]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['vimeo']['color']) == true) ? $this->vData['vimeo']['color'] : '00adef'),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:vi:autoplay'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[vimeo][autoplay]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['vimeo']['autoplay']) == true) ? $this->vData['vimeo']['autoplay'] : 0),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:vi:loop'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[vimeo][loop]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['vimeo']['loop']) == true) ? $this->vData['vimeo']['loop'] : 0),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:vi:api'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[vimeo][api]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['vimeo']['api']) == true) ? $this->vData['vimeo']['api'] : 0),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:vi:player_id'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[vimeo][player_id]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['vimeo']['player_id']) == true) ? $this->vData['vimeo']['player_id'] : ''),
                        ),
                    ),
                ),
                array(
                    'title' => lang('cv:vi:api_key'),
                    'desc' => '<small>(' . lang('cv:html5') . ')</small>',
                    'fields' => array(
                        'players[vimeo][api_key]' => array(
                            'type' => 'text',
                            'value' => ((isset($this->vData['vimeo']['api_key']) == true) ? $this->vData['vimeo']['api_key'] : 0),
                        ),
                    ),
                )
            )
        );

        $this->vData['base_url'] = ee('CP/URL', 'addons/settings/channel_videos/update_players&players=vimeo') ;
        $this->vData['save_btn_text'] = 'Save Player';
        $this->vData['save_btn_text_working'] = 'btn_saving';
        $this->vData['flux'] = $this->flux;

        return array(
            'heading' => lang('cv:players'),
            'body' => ee('View')->make('channel_videos:mcp')->render($this->vData),
        );
    }

    // ********************************************************************************* //

    public function update_players()
    {
        $defaultSettings = ee('App')->get('channel_videos')->get('settings_module');

        // Grab Settings
        $query = ee()->db->query("SELECT settings FROM exp_modules WHERE module_name = 'Channel_videos'");

        if ($query->row('settings') != false) {
            $settings = @unserialize($query->row('settings'));

            if (isset($settings['site:' . $this->site_id]) == false) {
                $settings['site:' . $this->site_id] = array(
                    'players' => array(
                        'vimeo' => $defaultSettings['vimeo'],
                        'youtube' => $defaultSettings['youtube'],
                    )
                );
            }
        }

        if (isset($_POST['players']['vimeo']) == true) {
            $settings['site:' . $this->site_id]['players']['vimeo'] = array_merge($defaultSettings['vimeo'], $_POST['players']['vimeo']);
        }

        if (isset($_POST['players']['youtube']) == true) {
            $settings['site:' . $this->site_id]['players']['youtube'] = array_merge($defaultSettings['youtube'], $_POST['players']['youtube']);
        }

        // Put it Back
        ee()->db->set('settings', serialize($settings));
        ee()->db->where('module_name', 'Channel_videos');
        $upd = ee()->db->update('exp_modules');

        ee('CP/Alert')->makeInline('shared-form')
            ->asSuccess()
            ->withTitle(lang('channel_videos_updated'))
            ->addToBody(sprintf(lang('channel_videos_updated_desc'), 'Player ' . $upd))
            ->defer();

        ee()->functions->redirect(ee('CP/URL', 'addons/settings/channel_videos/index/' . ee()->input->get('players')));
    }

    // ********************************************************************************* //

    private function initGlobals()
    {
        // Some Globals
        $this->baseUrl = ee('CP/URL', 'addons/settings/channel_videos');
        $this->site_id = ee()->config->item('site_id');
        $this->vData['baseUrl'] = $this->baseUrl->compile();
        $this->base = $this->baseUrl;
        $this->base_short = $this->baseUrl;


        ee()->view->cp_page_title = ee()->lang->line('channel_videos_module_name');

        ee()->channel_videos_helper->addMcpAssets('gjs');
        ee()->channel_videos_helper->addMcpAssets('css', 'css/mcp.css?v=' . $this->version, 'channel_videos', 'mcp');

        if (ee()->config->item('channel_videos_debug') == 'yes') {
            ee()->channel_videos_helper->addMcpAssets('js', 'js/mcp.js?v=' . $this->version, 'channel_videos', 'mcp');
        } else {
            ee()->channel_videos_helper->addMcpAssets('js', 'js/mcp.min.js?v=' . $this->version, 'channel_videos', 'mcp');
        }
        /* Cargar Configuracion */
        $query = ee()->db->query("SELECT settings FROM exp_modules WHERE module_name = 'Channel_videos'");
        if ($query->row('settings') != false) {
            $settings = @unserialize($query->row('settings'));

            if (isset($settings['site:' . $this->site_id]) == false) {
                $settings['site:' . $this->site_id] = array();
            }
        }

        if (isset($settings['site:' . $this->site_id]['players']) == false) {
            $settings['site:' . $this->site_id]['players'] = array();
        }


        $this->vData = array_merge($this->vData, $settings['site:' . $this->site_id]['players']);
    }

    // ********************************************************************************* //

    public function ajax_router()
    {
        // -----------------------------------------
        // Ajax Request?
        // -----------------------------------------
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // Load Library
            if (class_exists('Channel_Videos_AJAX') != true) {
                include 'ajax.channel_videos.php';
            }

            $AJAX = new Channel_Videos_AJAX();

            // Shoot the requested method
            $method = ee()->input->get_post('ajax_method');
            echo $AJAX->$method();
            exit();
        }
    }

    // ********************************************************************************* //
}
// END CLASS

/* End of file mcp.shop.php */
/* Location: ./system/user/addons/points/mcp.shop.php */
