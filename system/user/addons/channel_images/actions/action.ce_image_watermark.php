<?php if (!defined('BASEPATH')) {
    die('No direct script access allowed');
}

/**
 * Channel Images CE IMAGE SOBEL EDGIFY action
 *
 * @package         EEHarbor_ChannelImages
 * @author          EEHarbor <https://eeharbor.com> - Lead Developer @ Parscale Media
 * @copyright       Copyright (c) 2007-2011 Parscale Media <http://www.parscale.com>
 * @license         https://eeharbor.com/license
 * @link            https://eeharbor.com/channel-images
 */
class ImageAction_ce_image_watermark extends ImageAction
{

    /**
     * Action info - Required
     *
     * @access public
     * @var array
     */
    public $info = array(
        'title'     =>  'CE Image: Watermark',
        'name'      =>  'ce_image_watermark',
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

        if (file_exists(PATH_THIRD.'ce_img/pi.ce_img.php') != false) {
            $this->info['enabled'] = true;
        }
    }

    // ********************************************************************************* //

    public function run($file, $temp_dir)
    {
        if (isset($this->settings['param']) == false) {
            $this->settings['param'] = '';
        }
        $this->settings['param'] = trim($this->settings['param']);
        if ($this->settings['param'] == false) {
            return;
        }

        $watermark = explode('#', $this->settings['param']);
        foreach ($watermark as $index => $wm) {
            $wm = explode('|', $wm);
            foreach ($wm as $i => $w) {
                if (strpos($w, ',') !== false) {
                    $wm[$i] = explode(',', $w);
                }
            }
            $watermark[$index] = $wm;
        }

        if (class_exists('Ce_image') == false) {
            include PATH_THIRD.'ce_img/libraries/Ce_image.php';
        }
        $CE = new Ce_image(array('cache_dir' => '', 'unique' => 'none', 'overwrite_cache' => true, 'allow_overwrite_original' => true, 'watermark' => $watermark));

        $CE->make($file);

        $CE->close();

        return true;
    }

    // ********************************************************************************* //

    public function settings($settings)
    {
        $vData = $settings;

        if (isset($vData['param']) == false) {
            $vData['param'] = '';
        }


        return ee()->load->view('actions/ce_image_watermark', $vData, true);
    }

    // ********************************************************************************* //
}

/* End of file action.ce_image_sobel_edgify.php */
/* Location: ./system/expressionengine/third_party/channel_images/actions/action.ce_image_sobel_edgify.php */
