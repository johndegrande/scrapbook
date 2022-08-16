<?php

use EEHarbor\ChannelVideos\FluxCapacitor\Base\Ft;

class Channel_videos_ft extends Ft
{
    public $settings = array();

    public function __construct()
    {
        parent::__construct();

        if (ee()->input->cookie('cp_last_site_id')) {
            $this->site_id = ee()->input->cookie('cp_last_site_id');
        } elseif (ee()->input->get_post('site_id')) {
            $this->site_id = ee()->input->get_post('site_id');
        } else {
            $this->site_id = ee()->config->item('site_id');
        }

        ee()->load->add_package_path(PATH_THIRD . 'channel_videos/');
        ee()->lang->loadfile('channel_videos');
        ee()->load->library('channel_videos_helper');
        ee()->config->load('cv_config');
    }

    // ********************************************************************************* //

    /**
     * Display the field in the publish form
     *
     * @access public
     * @param $data String Contains the current field data. Blank for new entries.
     * @return String The custom field HTML
     */
    public function display_field($data)
    {
        //----------------------------------------
        // Global Vars
        //----------------------------------------
        $vData = array();
        $vData['field_name'] = $this->field_name;
        $vData['field_id'] = $this->field_id;
        $vData['site_id'] = $this->site_id;
        $vData['channel_id'] = (ee()->input->get_post('channel_id') != false) ? ee()->input->get_post('channel_id') : 0;
        //$vData['entry_id'] = (ee()->input->get_post('entry_id') != FALSE) ? ee()->input->get_post('entry_id') : FALSE;
        $vData['videos'] = array();
        $vData['total_videos'] = 0;
        $vData['entry_id'] = $this->content_id();
        //ee()->input->get_post('entry_id');


        // Post DATA?
        if (isset($_POST[$this->field_name])) {
            $data = $_POST[$this->field_name];
        }

        //----------------------------------------
        // Add Global JS & CSS & JS Scripts
        //----------------------------------------
        ee()->channel_videos_helper->addMcpAssets('gjs');
        ee()->channel_videos_helper->addMcpAssets('css', 'css/colorbox.css?v=' . $this->version, 'jquery', 'colorbox');
        ee()->channel_videos_helper->addMcpAssets('css', 'css/pbf.css?v=' . $this->version, 'channel_videos', 'pbf');
        ee()->channel_videos_helper->addMcpAssets('js', 'js/jquery.colorbox.js?v=' . $this->version, 'jquery', 'colorbox');
        ee()->channel_videos_helper->addMcpAssets('js', 'js/json3.min.js?v=' . $this->version, 'json3', 'json3', 'lte IE 8');
        $settingsv = array();
        $query = ee()->db->query("SELECT settings FROM exp_modules WHERE module_name = 'Channel_videos'");
        if ($query->row('settings') != false) {
            $settingsv = @unserialize($query->row('settings'));
        }
        $youtube_api_key = "";
        if (isset($settingsv['site:' . $this->site_id])) {
            $youtube_api_key = $settingsv['site:' . $this->site_id]['players']['youtube']['apikey'];
        }

        if (!trim($youtube_api_key)) {
            $youtube_api_key = 'AIzaSyD-ew6Tm9wIJIoMCMZuReb3xvs0ccNHvl4';
        }

        ee()->javascript->output("EE.youtubeKey = '" . $youtube_api_key . "';");
        if (ee()->config->item('channel_videos_debug') == 'yes') {
            ee()->channel_videos_helper->addMcpAssets('js', 'js/pbf.js?v=' . $this->version, 'channel_videos', 'pbf');
        } else {
            ee()->channel_videos_helper->addMcpAssets('js', 'js/pbf.js?v=' . $this->version, 'channel_videos', 'pbf');
        }

        ee()->cp->add_js_script(array('ui' => array('sortable')));
        //----------------------------------------
        // Settings
        //----------------------------------------
        $vData['config'] = ee()->config->item('cv_columns');
        $settings = $this->settings;
        $this->settings['entry_id'] = $this->content_id();
        if (isset($this->settings) == true) {
            $vData['config'] = array_merge($vData['config'], $this->settings);
        }
        //$settings = (isset($settings['channel_videos']) == TRUE) ? $settings['channel_videos'] : array();
        $defaults = ee()->config->item('cv_defaults');
        // Columns?
        if (isset($settings['columns']) == false) {
            $settings['columns'] = ee()->config->item('cv_columns');
        }

        // Limit Videos
        if (isset($settings['video_limit']) == false or trim($settings['video_limit']) == false) {
            $settings['video_limit'] = 999999;
        }


        $vData['settings'] = array_merge($defaults, $settings);
        if (isset($vData['settings']['cv_services']) == false) {
            $vData['settings']['cv_services'] = array('youtube', 'vimeo');
        }

        /*
     // Sometimes you forget to fill in field
       // and you will send back to the form
      // We need to fil lthe values in again.. *Sigh* (anyone heard about AJAX!)
     if (is_array($data) == TRUE && isset($data['tags']) == TRUE)
       {
          foreach ($data['tags'] as $tag)
            {
              $vData['assigned_tags'][] = $tag;
          }

         return ee()->load->view('pbf_field', $vData, TRUE);
       }
        */

        //----------------------------------------
        // JSON
        //----------------------------------------
        $vData['json'] = array();
        $vData['json']['layout'] = (isset($settings['cv_layout']) == true) ? $settings['cv_layout'] : 'table';
        $vData['json']['field_name'] = $this->field_name;
        $vData['json']['services'] = $settings['cv_services'];
        $vData['json'] = ee()->channel_videos_helper->generate_json($vData['json']);
        $vData['layout'] = (isset($settings['cv_layout']) == true) ? $settings['cv_layout'] : 'table';
        //----------------------------------------
        // Auto-Saved Entry?
        //----------------------------------------
        if (ee()->input->get('use_autosave') == 'y') {
            $vData['entry_id'] = false;
            $old_entry_id = $this->content_id();
            //ee()->input->get_post('entry_id');
            $query = ee()->db->select('original_entry_id')->from('exp_channel_entries_autosave')->where('entry_id', $old_entry_id)->get();
            if ($query->num_rows() > 0 && $query->row('original_entry_id') > 0) {
                $vData['entry_id'] = $query->row('original_entry_id');
            }
        }

        // Grab Assigned Videos
        if ($vData['entry_id'] != false) {
            // Grab all the files from the DB
            ee()->db->select('*');
            ee()->db->from('exp_channel_videos');
            ee()->db->where('entry_id', $vData['entry_id']);
            ee()->db->where('field_id', $vData['field_id']);
            ee()->db->order_by('video_order');
            $query = ee()->db->get();
            $vData['videos'] = $query->result();
            $vData['total_videos'] = $query->num_rows();
            $query->free_result();
        }


        return ee()->load->view('pbf_field', $vData, true);
    }

    // ********************************************************************************* //

    /**
     * Validates the field input
     *
     * @param $data Contains the submitted field data.
     * @access public
     * @return mixed Must return TRUE or an error message
     */
    public function validate($data)
    {
        // Is this a required field?
        if ($this->settings['field_required'] == 'y') {
            if (isset($data['videos']) == false or empty($data['videos']) == true) {
                return ee()->lang->line('video:required_field');
            }
        }

        return true;
    }

    // ********************************************************************************* //

    /**
     * Preps the data for saving
     *
     * @param $data Contains the submitted field data.
     * @return string Data to be saved
     */
    public function save($data)
    {
        return $data;
    }

    // ********************************************************************************* //

    /**
     * Handles any custom logic after an entry is saved.
     * Called after an entry is added or updated.
     * Available data is identical to save, but the settings array includes an entry_id.
     *
     * @param $data Contains the submitted field data. (Returned by save())
     * @access public
     * @return void
     */
    public function post_save($data)
    {
        ee()->load->library('channel_videos_helper');

        if (isset($data) == false) {
            return;
        }

        $entry_id = $this->content_id();
        $channel_id = ee()->input->post('channel_id');
        $field_id = $this->field_id;

        // Grab all Videos From DB
        ee()->db->select('*');
        ee()->db->from('exp_channel_videos');
        ee()->db->where('entry_id', $entry_id);
        ee()->db->where('field_id', $field_id);
        $query = ee()->db->get();

        // Check for videos
        if (isset($data['videos']) == false or is_array($data['videos']) == false) {
            $data['videos'] = array();
        }

        if ($query->num_rows() > 0) {
            // Not fresh, lets see whats new.
            foreach ($data['videos'] as $order => $video) {
                // Check for duplicate Videos!
                if (!empty($video['video_id'])) {
                    // Update Video
                    $data = array(  'video_order'   =>  $order,
                                    'video_cover'   =>  0,
                                );

                    ee()->db->update('exp_channel_videos', $data, array('video_id' => $video['video_id']));
                } else {
                    // ee()->channel_videos_helper->in_multi_array($video['data']->hash_id, $query->result_array()) === FALSE)
                    $video = ee()->channel_videos_helper->decode_json($video['data']);

                    // New Video!
                    $data = array(  'site_id'   =>  $this->site_id,
                                    'entry_id'  =>  $entry_id,
                                    'channel_id' =>  $channel_id,
                                    'field_id'  =>  $field_id,
                                    'service'   =>  $video->service,
                                    'service_video_id'  =>  $video->service_video_id,
                                    'video_title'   =>  $video->video_title,
                                    'video_desc'    =>  $video->video_desc,
                                    'video_username' =>  $video->video_username,
                                    'video_author'  =>  $video->video_author,
                                    'video_author_id' => $video->video_author_id,
                                    'video_date'    =>  $video->video_date,
                                    'video_views'   =>  $video->video_views ?: 1,
                                    'video_duration' =>  $video->video_duration,
                                    'video_url'     =>  $video->video_url,
                                    'video_img_url' =>  $video->video_img_url,
                                    'video_order'   =>  $order,
                                    'video_cover'   =>  0,
                                );

                    ee()->db->insert('exp_channel_videos', $data);
                }
            }
        } else {
            foreach ($data['videos'] as $order => $video) {
                $video = ee()->channel_videos_helper->decode_json($video['data']);

                // New Video
                $data = array(  'site_id'   =>  $this->site_id,
                                'entry_id'  =>  $entry_id,
                                'channel_id' =>  $channel_id,
                                'field_id'  =>  $field_id,
                                'service'   =>  $video->service,
                                'service_video_id'  =>  $video->service_video_id,
                                'video_title'   =>  $video->video_title,
                                'video_desc'    =>  $video->video_desc,
                                'video_username' =>  $video->video_username,
                                'video_author'  =>  $video->video_author,
                                'video_author_id' => $video->video_author_id,
                                'video_date'    =>  $video->video_date,
                                'video_views'   =>  $video->video_views ?: 1,
                                'video_duration' =>  $video->video_duration,
                                'video_url'     =>  $video->video_url,
                                'video_img_url' =>  $video->video_img_url,
                                'video_order'   =>  $order,
                                'video_cover'   =>  0,
                            );

                ee()->db->insert('exp_channel_videos', $data);
            }
        }

        return;
    }

    // ********************************************************************************* //

    /**
     * Handles any custom logic after an entry is deleted.
     * Called after one or more entries are deleted.
     *
     * @param $ids array is an array containing the ids of the deleted entries.
     * @access public
     * @return void
     */
    public function delete($ids)
    {
        foreach ($ids as $item_id) {
            $this->entry_id = $this->content_id();
            //$this->settings['entry_id']=$item_id;
            ee()->db->where('entry_id', $item_id);
            ee()->db->delete('exp_channel_videos');
        }
    }

    // ********************************************************************************* //

    /**
     * Display the settings page. The default ExpressionEngine rows can be created using built in methods.
     * All of these take the current $data and the fieltype name as parameters:
     *
     * @param $data array
     * @access public
     * @return void
     */
    public function display_settings($data)
    {

        // Does our settings exist?
        if (isset($data['cv_services']) == true) {
            if (is_string($data['cv_services']) == true) {
                $d = array($data['cv_services']);
            } elseif (is_array($data['cv_services']) == true) {
                $d = $data['cv_services'];
            } else {
                $d = array(lang('cv:service:youtube'),lang('cv:service:vimeo'));
            }
        } else {
            $d =  array(
                                            lang('cv:service:youtube') => lang('cv:service:youtube'),
                                            lang('cv:service:vimeo') => lang('cv:service:vimeo')
                                          );
        }


        $settings = array(

                            array(
                                 'title' => lang('cv:services_option'),
                            'fields' => array(
                                'cv_services' => array(
                                      'type' => 'checkbox',
                                      'choices' => array(
                                            lang('cv:service:youtube') => lang('cv:service:youtube'),
                                            lang('cv:service:vimeo') => lang('cv:service:vimeo')
                                          ),
                                      'value' => $d                            ,
                                     // 'nested' => TRUE,
                                    // 'wrap' => TRUE
                              ),
                            )),

                       array(
                                 'title' => lang('cv:layout'),
                           'fields' => array(
                              'cv_layout' => array(
                                      'type' => 'inline_radio',
                                      'choices' => array(
                                           lang('cv:layout:table') => lang('cv:layout:table'),
                                           lang('cv:layout:tiles') => lang('cv:layout:tiles')),
                                      'value' => ((isset($data['cv_layout']) == true) ? $data['cv_layout'] : lang('cv:layout:table')),

                              )
                            ),
                        ),
                     array(
                                 'title' => 'ACT URL',
                           'fields' => array(
                              'act_url' => array(
                                      'type' => 'html',
                                      'content' => '<a href="' . ee()->channel_videos_helper->getRouterUrl() . '" target="_blank">' . ee()->channel_videos_helper->getRouterUrl() . '</a>',

                              )
                            ),
                        ),
                );
        /*$row  = form_checkbox('cv_services[]', 'youtube', in_array('youtube', $d)) .NBS.NBS. lang('cv:service:youtube').NBS.NBS;
                $row .= form_checkbox('cv_services[]', 'vimeo', in_array('vimeo', $d)) .NBS.NBS. lang('cv:service:vimeo').NBS.NBS;
             //$row .= form_checkbox('cv_services[]', 'revver', in_array('revver', $d)) .NBS.NBS. lang('video:service:revver');
             ee()->table->add_row( lang('cv:services_option', 'cv_services'), $row);

               $layout = (isset($data['cv_layout']) == TRUE) ? $data['cv_layout'] : 'table';

             $row  = form_radio('cv_layout', 'table', (($layout == 'table') ? TRUE : FALSE)) .NBS.NBS. lang('cv:layout:table').NBS.NBS;
             $row .= form_radio('cv_layout', 'tiles', (($layout == 'tiles') ? TRUE : FALSE)) .NBS.NBS. lang('cv:layout:tiles').NBS.NBS;
             //$row .= form_checkbox('cv_services[]', 'revver', in_array('revver', $d)) .NBS.NBS. lang('video:service:revver');

                ee()->table->add_row( lang('cv:layout', 'cv_services'), $row);
             ee()->table->add_row('ACT URL', '<a href="'.ee()->channel_videos_helper->getRouterUrl().'" target="_blank">'.ee()->channel_videos_helper->getRouterUrl().'</a>');
                        */



        return array('field_options_channel_videos' => array(
            'label' => 'field_options',
            'group' => 'channel_videos',
            'settings' => $settings
               ));
    }

    // ********************************************************************************* //

    /**
     * Save the fieldtype settings.
     *
     * @param $data array Contains the submitted settings for this field.
     * @access public
     * @return array
     */
    public function save_settings($data)
    {
        return array(
            'cv_services' => ee()->input->post('cv_services'),
            'cv_layout' => ee()->input->post('cv_layout'),
                        'field_wide' => true,
        );
    }

    // ********************************************************************************* //
}

/* End of file ft.channel_videos.php */
/* Location: ./system/user/addons/channel_videos/ft.channel_videos.php */
