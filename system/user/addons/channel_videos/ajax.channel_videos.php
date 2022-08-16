<?php

class Channel_Videos_AJAX
{

    /**
     * Constructor
     *
     * @access public
     *
     * Calls the parent constructor
     */
    public function __construct()
    {
        ee()->load->add_package_path(PATH_THIRD . 'channel_videos/');
        ee()->lang->loadfile('channel_videos');
        ee()->load->library('channel_videos_helper');
        ee()->config->load('cv_config');

        if (ee()->input->get_post('site_id')) {
            $this->site_id = ee()->input->get_post('site_id');
        } elseif (ee()->input->cookie('cp_last_site_id')) {
            $this->site_id = ee()->input->cookie('cp_last_site_id');
        } else {
            $this->site_id = ee()->config->item('site_id');
        }
    }

    // ********************************************************************************* //

    /**
     * Search For Videos
     *
     * @access public
     * @return string
     */
    public function search_videos()
    {
        // View Data
        $this->vData = array();
        $this->vData['field_id'] = ee()->input->get_post('field_id');

        // -----------------------------------------
        // Grab Field Settings
        // -----------------------------------------
        $query = ee()->db->select('field_settings')->from('exp_channel_fields')->where('field_id', $this->vData['field_id'])->get();
        $settings = unserialize(base64_decode($query->row('field_settings')));
        $defaults = ee()->config->item('cv_defaults');
        $settings = array_merge($defaults, $settings);

        // Grab Module settings
        $module_settings = ee()->channel_videos_helper->grab_settings($this->site_id);

        // -----------------------------------------
        // Search Options
        // -----------------------------------------
        $search = array();

        // Keywords
        $search['keywords'] = ee()->channel_videos_helper->parse_keywords(ee()->input->post('keywords'));
        if ($search['keywords'] != false) {
            $search['keywords'] = urlencode($search['keywords']);
        }

        // Author
        $search['author'] = (trim(ee()->input->post('author')) != false) ? trim(ee()->input->post('author')) : false;

        // Limit
        $search['limit'] = (ee()->input->post('limit') > 1) ? ee()->input->post('limit') : 10;

        // Any Services?
        if (isset($settings['cv_services']) == false or empty($settings['cv_services']) == true) {
            exit('MISSING SERVICES');
        }

        // Search Results
        $results = array();

        // -----------------------------------------
        // Loop over all services
        // -----------------------------------------
        foreach ($settings['cv_services'] as $service) {
            // -----------------------------------------
            // Load Service!
            // -----------------------------------------
            $video_class = 'Video_Service_' . $service;

            // Load Main Class
            if (class_exists('Video_Service') == false) {
                require PATH_THIRD . 'channel_videos/services/video_service.php';
            }

            // Try to load Video Class
            if (class_exists($video_class) == false) {
                $location_file = PATH_THIRD . 'channel_videos/services/' . $service . '/' . $service . '.php';

                require $location_file;
            }

            // Init!
            $VID = new $video_class();

            // Search!
            $results[$service] = $VID->search($search);
        }

        $out = array('services' => $results);

        exit(ee()->channel_videos_helper->generate_json($out));
    }

    // ********************************************************************************* //

    public function get_video()
    {
        $out = array('success' => 'no', 'body');

        // Params
        $url = ee()->input->get_post('url');
        $service = ee()->input->get_post('service');
        $video_id = ee()->input->get_post('video_id');
        $field_id = ee()->input->get_post('field_id');

        // -----------------------------------------
        // Grab Field Settings
        // -----------------------------------------
        $query = ee()->db->select('field_settings')->from('exp_channel_fields')->where('field_id', $field_id)->get();
        $settings = unserialize(base64_decode($query->row('field_settings')));
        $defaults = ee()->config->item('cv_defaults');
        $settings = array_merge($defaults, $settings);

        // -----------------------------------------
        // Load Services
        // -----------------------------------------
        ee()->load->helper('directory');
        if (class_exists('Video_Service') == false) {
            include(PATH_THIRD . 'channel_videos/services/video_service.php');
        }
        $services = array();

        // Make the map
        if (($temp = directory_map(PATH_THIRD . 'channel_videos/services/', 2)) !== false) {
            // Loop over all fields
            foreach ($temp as $classname => $files) {
                // Kill YOUTUBE
                if ($classname == 'youtube') {
                    continue;
                }

                // If allows
                if (in_array($classname, $settings['cv_services']) === false) {
                    continue;
                }

                // Check for empty array and such
                if (is_array($files) == false or empty($files) == true) {
                    continue;
                }

                // Search for the file we need, not there? continue
                if (array_search($classname . '.php', $files) === false) {
                    continue;
                }

                $final_class = 'Video_Service_' . $classname;

                // Do a simple check, we don't want fatal errors
                if (class_exists($final_class) == false) {
                    // Include it of course! and get the class vars
                    require PATH_THIRD . 'channel_videos/services/' . $classname . '/' . $classname . '.php';
                }

                $obj = new $final_class();

                // Is it enabled? ready to use?
                if (isset($obj->info['enabled']) == false or $obj->info['enabled'] == false) {
                    continue;
                }

                // Store it!
                $services[$classname] = $obj;

                // We need to be sure it's formatted correctly
                if (isset($obj->info['title']) == false) {
                    unset($services[$classname]);
                }
                if (isset($obj->info['name']) == false) {
                    unset($services[$classname]);
                }
            }
        }


        // -----------------------------------------
        // Parse URL
        // -----------------------------------------
        if ($url != false) {
            foreach ($services as $ss) {
                $res = $ss->parse_url($url);

                if (isset($res['id']) == false or $res['id'] == false) {
                    continue;
                } elseif ($res['id'] != false) {
                    break;
                }
            }

            //print_r($res);

            // Did we find anything?
            if (isset($res['id']) == false or $res['id'] == false) {
                $out = array('success' => 'no', 'body' => 'Your URL was not recognized.');
                exit(ee()->channel_videos_helper->generate_json($out));
            } else {
                $video_id = $res['id'];
                $service = $res['service'];
            }

            $video = $services[$service]->get_video_info($video_id);
        }

        // -----------------------------------------
        // Get Video INFO!
        // -----------------------------------------
        $video = $services[$service]->get_video_info($video_id);
        $video['video_id'] = 0;

        $vData = array();
        $vData['vid'] = (object) $video;
        $vData['order'] = 0;
        $vData['layout'] = ee()->input->get_post('field_layout');
        $vData['field_name'] = ee()->input->get_post('field_name');

        $out = array('success' => 'yes', 'body' => ee()->load->view('pbf_single_video.php', $vData, true), 'video' => $video);
        exit(ee()->channel_videos_helper->generate_json($out));
    }

    // ********************************************************************************* //

    public function delete_video()
    {
        $video_id = ee()->input->get_post('video_id');

        if ($video_id == 0) {
            exit('Video ID is FALSE');
        }

        ee()->db->where('video_id', $video_id);
        ee()->db->delete('exp_channel_videos');

        exit('DONE');
    }
}
// END CLASS

/* End of file ajax.channel_videos.php  */
/* Location: ./system/user/addons/channel_videos/modules/ajax.channel_videos.php */
