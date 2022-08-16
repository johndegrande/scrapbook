<?php if (!defined('BASEPATH')) {
    die('No direct script access allowed');
}

/**
 * Channel Images RESIZE PERCENT ADAPTIVE action
 *
 * @package         EEHarbor_ChannelImages
 * @author          EEHarbor <https://eeharbor.com> - Lead Developer @ Parscale Media
 * @copyright       Copyright (c) 2007-2011 Parscale Media <http://www.parscale.com>
 * @license         https://eeharbor.com/license
 * @link            https://eeharbor.com/channel-images
 */
class ImageAction_resize_percent_adaptive extends ImageAction
{

    /**
     * Action info - Required
     *
     * @access public
     * @var array
     */
    public $info = array(
        'title'     =>  'Resize (Adaptive Percentage)',
        'name'      =>  'resize_percent_adaptive',
        'version'   =>  '1.0',
        'enabled'   =>  true,
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

    public function run($file, $temp_dir)
    {
        @set_include_path(PATH_THIRD.'channel_images/libraries/PHPThumb/');
        @set_include_path(PATH_THIRD.'channel_images/libraries/PHPThumb/thumb_plugins/');

        // Include the library
        if (class_exists('PhpThumbFactory') == false) {
            require_once PATH_THIRD.'channel_images/libraries/PHPThumb/ThumbLib.inc.php';
        }

        $progressive = (isset($this->settings['field_settings']['progressive_jpeg']) === true && $this->settings['field_settings']['progressive_jpeg'] == 'yes') ? 'yes' : 'no';

        // Create Instance
        $thumb = PhpThumbFactory::create($file, array('resizeUp' => false, 'jpegQuality' => 100, 'jpegProgressive' => $progressive));

        // Resize it!
        $thumb->adaptiveResizePercent($this->settings['width'], $this->settings['height'], $this->settings['percent']);

        // Save it
        $thumb->save($file);

        return true;
    }

    // ********************************************************************************* //

    public function settings($settings)
    {
        $vData = $settings;

        if (isset($vData['width']) == false) {
            $vData['width'] = '100';
        }
        if (isset($vData['height']) == false) {
            $vData['height'] = '100';
        }
        if (isset($vData['percent']) == false) {
            $vData['percent'] = '20';
        }


        return ee()->load->view('actions/resize_percent_adaptive', $vData, true);
    }

    // ********************************************************************************* //
}

/* End of file action.resize_percent_adaptive.php */
/* Location: ./system/expressionengine/third_party/channel_images/actions/action.resize_percent_adaptive.php */
