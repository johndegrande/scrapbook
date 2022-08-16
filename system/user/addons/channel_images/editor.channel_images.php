<?php

// include config file
include_once dirname(__FILE__).'/config.php';

/**
 * Channel Images for Editor
 *
 * @package         EEHarbor_ChannelImages
 * @author          EEHarbor <https://eeharbor.com> - Lead Developer @ Parscale Media
 * @copyright       Copyright (c) 2007-2011 Parscale Media <http://www.parscale.com>
 * @license         https://eeharbor.com/license
 * @link            https://eeharbor.com/editor/
 */
class Channel_images_ebtn extends Editor_button
{
    /**
     * Button info - Required
     *
     * @access public
     * @var array
     */
    public $info = array(
        'name'      => 'Channel Images',
        'author'    => 'EEHarbor',
        'author_url' => 'https://eeharbor.com',
        'description'=> 'Use images uploaded through Channel Images in Editor',
        'version'   => CHANNEL_IMAGES_VERSION,
        'font_icon' => 'dicon-images',
    );

    /**
     * Constructor
     *
     * @access public
     *
     * Calls the parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    // ********************************************************************************* //
}

/* End of file editor.link.php */
/* Location: ./system/user/addons/channel_images/editor.link.php */