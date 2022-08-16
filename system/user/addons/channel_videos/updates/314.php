<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ChannelVideosUpdate_314
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
        //----------------------------------------
        // EXP_MODULES
        // The settings column, Ellislab should have put this one in long ago.
        // No need for a seperate preferences table for each module.
        //----------------------------------------
        if (ee()->db->field_exists('settings', 'modules') == false) {
            ee()->dbforge->add_column('modules', array('settings' => array('type' => 'TEXT') ));
        }
    }

    // ********************************************************************************* //
}

/* End of file 200.php */
/* Location: ./system/user/addons/channel_videos/updates/200.php */
