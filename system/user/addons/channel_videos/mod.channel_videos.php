<?php

use EEHarbor\ChannelVideos\FluxCapacitor\Base\Mod;

class Channel_videos extends Mod
{
    public function __construct()
    {
        parent::__construct();

        $this->site_id = ee()->config->item('site_id');
        $this->SSL = false;

        ee()->load->library('channel_videos_helper');

        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') {
            $this->SSL = true;
        }

        return;
    }

    // ********************************************************************************* //

    public function videos()
    {
        // Variable prefix
        $prefix = ee()->TMPL->fetch_param('prefix', 'video') . ':';
        $attr_id = ee()->TMPL->fetch_param('attr:id');
        $attr_class = ee()->TMPL->fetch_param('attr:class');
        // -----------------------------------------
        // Limit?
        // -----------------------------------------
        $limit = ctype_digit((string)ee()->TMPL->fetch_param('limit')) ? ee()->TMPL->fetch_param('limit') : 15;
        // -----------------------------------------
        // Offset?
        // -----------------------------------------
        $offset = ctype_digit((string)ee()->TMPL->fetch_param('offset')) ? ee()->TMPL->fetch_param('offset') : 0;
        // Video ID?
        if (ee()->TMPL->fetch_param('video_id') != false) {
            ee()->db->limit(1);
            ee()->db->where('video_id', ee()->TMPL->fetch_param('video_id'));
        } else {
            // Entry ID
            $this->entry_id = ee()->channel_videos_helper->get_entry_id_from_param();
            // We need an entry_id
            if ($this->entry_id == false) {
                ee()->db->_reset_select();
                ee()->TMPL->log_item('CHANNEL VIDEOS: Entry ID could not be resolved');
                return ee()->channel_videos_helper->custom_no_results_conditional($prefix . 'no_videos', ee()->TMPL->tagdata);
            }

            ee()->db->where('entry_id', $this->entry_id);
        }

        ee()->db->select('*');
        ee()->db->from('exp_channel_videos');
        // -----------------------------------------
        // Field ID?
        // -----------------------------------------
        if (ee()->TMPL->fetch_param('field_id') != false) {
            ee()->db->where('field_id', ee()->TMPL->fetch_param('field_id'));
        }

        // -----------------------------------------
        // Which Field
        // -----------------------------------------
        if (ee()->TMPL->fetch_param('field') != false) {
            $group = ee()->TMPL->fetch_param('field');
            // Multiple Fields
            if (strpos($group, '|') !== false) {
                $group = explode('|', $group);
                $groups = array();
                foreach ($group as $name) {
                    $groups[] = $name;
                }
            } else {
                $groups = $group;
            }

            ee()->db->join('exp_channel_fields cf', 'cf.field_id = exp_channel_videos.field_id', 'left');
            ee()->db->where_in('cf.field_name', $groups);
        }

        // -----------------------------------------
        // Sort?
        // -----------------------------------------
        $sort = 'asc';
        if (ee()->TMPL->fetch_param('sort') == 'desc') {
            $sort = 'desc';
        }

        // -----------------------------------------
        // Order By
        // -----------------------------------------
        $orderby_list = array('video_order' => 'video_order', 'duration' => 'video_duration', 'views' => 'video_views', 'date' => 'video_date');
        $order = ee()->TMPL->fetch_param('orderby', 'order');
        if (! $temp = array_search($order, $orderby_list)) {
            ee()->db->order_by('video_order', $sort);
        } else {
            ee()->db->order_by($orderby_list[$order], $sort);
        }

        // -----------------------------------------
        // Shoot!
        // -----------------------------------------
        ee()->db->limit($limit, $offset);
        $query = ee()->db->get();
        // No Results?
        if ($query->num_rows() == 0) {
            ee()->TMPL->log_item("CHANNEL VIDEOS: No videos found. (Entry_ID:{$this->entry_id})");
            return ee()->channel_videos_helper->custom_no_results_conditional($prefix . 'no_videos', ee()->TMPL->tagdata);
        }

        // Grab Module settings
        $module_settings = ee()->channel_videos_helper->grab_settings($this->site_id);
        if (isset($module_settings['players']['youtube']) == false) {
            $module_settings['players']['youtube'] = array();
        }
        if (isset($module_settings['players']['vimeo']) == false) {
            $module_settings['players']['vimeo'] = array();
        }

        // -----------------------------------------
        // Load our video classes
        // -----------------------------------------

        // Load Main Class
        if (class_exists('Video_Service') == false) {
            require PATH_THIRD . 'channel_videos/services/video_service.php';
        }

        // Try to load Video Class
        if (class_exists('Video_Service_youtube') == false) {
            $location_file = PATH_THIRD . 'channel_videos/services/youtube/youtube.php';
            require $location_file;
        }
        $YOUTUBE = new Video_Service_youtube();
        // Try to load Video Class
        if (class_exists('Video_Service_vimeo') == false) {
            $location_file = PATH_THIRD . 'channel_videos/services/vimeo/vimeo.php';
            require $location_file;
        }
        $VIMEO = new Video_Service_vimeo();
        //----------------------------------------
        // Switch=""
        //----------------------------------------
        $parse_switch = false;
        $switch_matches = array();
        if (preg_match_all("/" . LD . "({$prefix}switch\s*=.+?)" . RD . "/is", ee()->TMPL->tagdata, $switch_matches) > 0) {
            $parse_switch = true;
            // Loop over all matches
            foreach ($switch_matches[0] as $key => $match) {
                $switch_vars[$key] = ee()->functions->assign_parameters($switch_matches[1][$key]);
                $switch_vars[$key]['original'] = $switch_matches[0][$key];
            }
        }

        // -----------------------------------------
        // Loop through all results!
        // -----------------------------------------
        $final = '';
        $count = 0;
        $total = $query->num_rows();
        foreach ($query->result() as $vid) {
            $temp = '';
            $count++;
            $vars = array();
            // HTTP to HTTPS
            $vid->video_img_url = str_replace('http://', 'https://', $vid->video_img_url);
            $vars[$prefix . 'count']      = $count;
            $vars[$prefix . 'total']      = $total;
            $vars[$prefix . 'id']         = $vid->video_id;
            $vars[$prefix . 'service']    = $vid->service;
            $vars[$prefix . 'service_id'] = $vid->service_video_id;
            $vars[$prefix . 'title']      = $vid->video_title;
            $vars[$prefix . 'description'] = $vid->video_desc;
            $vars[$prefix . 'username']   = $vid->video_username;
            $vars[$prefix . 'author']     = $vid->video_author;
            $vars[$prefix . 'date']       = $vid->video_date;
            $vars[$prefix . 'views']      = $vid->video_views;
            $vars[$prefix . 'duration']   = sprintf("%0.2f", $vid->video_duration / 60) . ' min';
            $vars[$prefix . 'duration_secs']  = $vid->video_duration;
            $vars[$prefix . 'img_url']    = $vid->video_img_url;
            // Service specific vars
            if ($vid->service == 'youtube') {
                $vars[$prefix . 'embed_code'] = $YOUTUBE->render_player($vid->service_video_id, $module_settings['players']['youtube']);
                $vars[$prefix . 'embed_code_hd'] = $YOUTUBE->render_player($vid->service_video_id, $module_settings['players']['youtube'], true);
                $vars[$prefix . 'web_url'] = 'https://www.youtube.com/watch?v=' . $vid->service_video_id;
                $vars[$prefix . 'url'] = 'https://www.youtube.com/v/' . $vid->service_video_id . '?version=3';
                $vars[$prefix . 'url_hd']     = 'https://www.youtube.com/v/' . $vid->service_video_id . '?hd=1';
                if (strpos($vid->video_img_url, 'sddefault.jpg') !== false) {
                    $vars[$prefix . 'img_url_hd'] = str_replace('sddefault.jpg', 'hqdefault.jpg', $vid->video_img_url);
                } else {
                    $vars[$prefix . 'img_url_hd'] = str_replace('default.jpg', 'hqdefault.jpg', $vid->video_img_url);
                }
            } elseif ($vid->service == 'vimeo') {
                $extra_params = ee()->TMPL->fetch_param('vimeo:url_params');
                $vars[$prefix . 'web_url'] = 'https://vimeo.com/' . $vid->service_video_id;
                $vars[$prefix . 'embed_code'] = $VIMEO->render_player($vid->service_video_id, $module_settings['players']['vimeo']);
                $vars[$prefix . 'embed_code_hd'] = $VIMEO->render_player($vid->service_video_id, $module_settings['players']['vimeo'], true);
                $vars[$prefix . 'url'] = 'https://vimeo.com/moogaloop.swf?clip_id=' . $vid->service_video_id . '&server=vimeo.com&show_title=1&show_byline=1&show_portrait=0&color=00ADEF&fullscreen=1&' . $extra_params;
                $vars[$prefix . 'url_hd']     = 'https://vimeo.com/moogaloop.swf?clip_id=' . $vid->service_video_id . '&server=vimeo.com&show_title=1&show_byline=1&show_portrait=0&color=00ADEF&fullscreen=1&' . $extra_params;
                $vars[$prefix . 'img_url_hd'] = preg_replace('#_100(.*)?\.jpg$#', '_640.jpg', $vid->video_img_url);
                /*
                     // It's a shame but this is the only way to really get correct URL's
                       $temp = ee()->channel_videos_helper->fetch_url_file('https://vimeo.com/api/v2/video/'.$vid->service_video_id.'.json');
                     if ($temp) {
                   $temp = ee()->channel_videos_helper->decode_json($temp);
                  if (isset($temp->thumbnail_large)) {
                       $vars[$prefix.'img_url_hd'] = $temp->thumbnail_large;
                  }
                      }
                    */

                // Vimeo does not have https certs on all their domains, so lets turn https off if not needed.
                if (!$this->SSL) {
                    $vars[$prefix . 'img_url'] = str_replace('https://', 'http://', $vars[$prefix . 'img_url']);
                    $vars[$prefix . 'img_url_hd'] = str_replace('https://', 'http://', $vars[$prefix . 'img_url_hd']);
                }
            }

            $temp = ee()->TMPL->parse_variables_row(ee()->TMPL->tagdata, $vars);
            // -----------------------------------------
            // Parse Switch {switch="one|twoo"}
            // -----------------------------------------
            if ($parse_switch) {
                // Loop over all switch variables
                foreach ($switch_vars as $switch) {
                    $sw = '';
                    // Does it exist? Just to be sure
                    if (isset($switch[$prefix . 'switch']) !== false) {
                        $sopt = explode("|", $switch[$prefix . 'switch']);
                        $sw = $sopt[(($count - 1) + count($sopt)) % count($sopt)];
                    }

                    $temp = str_replace($switch['original'], $sw, $temp);
                }
            }


            $final .= $temp;
        }

        // Resources are not free..
        $query->free_result();
        return $final;
    }

    // ********************************************************************************* //

    public function channel_videos_router()
    {
        @header('Access-Control-Allow-Origin: *');
        @header('Access-Control-Allow-Credentials: true');
        @header('Access-Control-Max-Age: 86400');
        @header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        @header('Access-Control-Allow-Headers: Keep-Alive, Content-Type, User-Agent, Cache-Control, X-Requested-With, X-File-Name, X-File-Size');
        // -----------------------------------------
        // Ajax Request?
        // -----------------------------------------
        //if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        //{
        // Load Library
        if (class_exists('Channel_Videos_AJAX') != true) {
            include 'ajax.channel_videos.php';
        }

        $AJAX = new Channel_Videos_AJAX();
        // Shoot the requested method
        $method = ee()->input->get_post('ajax_method');
        if ($method) {
            echo $AJAX->$method();
            exit();
        }

        //}

        exit('Channel Videos ACT URL');
    }
}
// END CLASS

/* End of file mod.channel_videos.php */
/* Location: ./system/user/addons/channel_videos/mod.channel_videos.php */
