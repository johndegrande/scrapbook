<?php if (!defined('BASEPATH')) {
    die('No direct script access allowed');
}

/**
 * Channel Images CE IMAGE SHARPEN action
 *
 * @package         EEHarbor_ChannelImages
 * @author          EEHarbor <https://eeharbor.com> - Lead Developer @ Parscale Media
 * @copyright       Copyright (c) 2007-2011 Parscale Media <http://www.parscale.com>
 * @license         https://eeharbor.com/license
 * @link            https://eeharbor.com/channel-images
 */
class ImageAction_im_image_unsharpmask extends ImageAction
{

    /**
     * Action info - Required
     *
     * @access public
     * @var array
     */
    public $info = array(
        'title'     =>  'Imagick: Unsharp Mask',
        'name'      =>  'im_image_unsharpmask',
        'version'   =>  '1.0',
        'enabled'   =>  false,
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

        if (class_exists('Imagick')) {
            $this->info['enabled'] = true;
        }
    }

    // ********************************************************************************* //

    public function run($file, $temp_dir)
    {
        $image = new Imagick();
        $image->readImage($file);
        $image->unsharpMaskImage($this->settings['radius'], $this->settings['sigma'], $this->settings['amount'], $this->settings['threshold']);
        $image->writeImage($file);

        return true;
    }

    // ********************************************************************************* //

    public function settings($settings)
    {
        $vData = $settings;

        if (isset($vData['radius']) == false) {
            $vData['radius'] = '1.5';
        }
        if (isset($vData['sigma']) == false) {
            $vData['sigma'] = '0.75';
        }
        if (isset($vData['amount']) == false) {
            $vData['amount'] = '1.7';
        }
        if (isset($vData['threshold']) == false) {
            $vData['threshold'] = '0.02';
        }

        return ee()->load->view('actions/im_image_unsharpmask', $vData, true);
    }

    // ********************************************************************************* //
}

/* End of file action.ce_image_sharpen.php */
/* Location: ./system/expressionengine/third_party/channel_images/actions/action.ce_image_sharpen.php */
