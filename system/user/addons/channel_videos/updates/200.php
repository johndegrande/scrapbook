<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ChannelVideosUpdate_200
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
        // Load dbforge
        ee()->load->dbforge();
    }

    // ********************************************************************************* //

    public function do_update()
    {
    }

    // ********************************************************************************* //
}

/* End of file 200.php */
/* Location: ./system/user/addons/channel_videos/updates/200.php */
