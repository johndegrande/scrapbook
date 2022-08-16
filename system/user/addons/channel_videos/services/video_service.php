<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Video Service File
 *
 * @package         ChannelVideos
 * @author          EEHarbor <help@eeharbor.com>
 * @copyright       Copyright (c) 2007-2019 EEHarbor <https://eeharbor.com>
 * @license         https://eeharbor.com/license/
 * @link            https://eeharbor.com/channel-videos/
 */
class Video_Service
{
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        $this->field_name = 'channel_images[action_groups][][actions][' . $this->info['name'] . ']';
    }

    // ********************************************************************************* //

    public function search($search = array())
    {
        return array();
    }

    // ********************************************************************************* //

    public function parse_url($url)
    {
        return array();
    }

    // ********************************************************************************* //

    public function get_video_info($video_id)
    {
        return false;
    }

    // ********************************************************************************* //
}
// END CLASS

/* End of file video_service.php  */
/* Location: ./system/user/addons/channel_videos/services/video_service.php */
