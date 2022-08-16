<?php if (!defined('BASEPATH')) {
    die('No direct script access allowed');
}

/**
 * Channel Images FLIP action
 *
 * @package         EEHarbor_ChannelImages
 * @author          EEHarbor <https://eeharbor.com> - Lead Developer @ Parscale Media
 * @copyright       Copyright (c) 2007-2011 Parscale Media <http://www.parscale.com>
 * @license         https://eeharbor.com/license
 * @link            https://eeharbor.com/channel-images
 */
class ImageAction_flip extends ImageAction
{

    /**
     * Action info - Required
     *
     * @access public
     * @var array
     */
    public $info = array(
        'title'     =>  'Flip Image',
        'name'      =>  'flip',
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

    public function settings($settings)
    {
        $vData = $settings;

        if (isset($vData['axis']) == false) {
            $vData['axis'] = 'horizontal';
        }

        return ee()->load->view('actions/flip', $vData, true);
    }

    // ********************************************************************************* //

    public function run($file, $temp_dir)
    {
        $res = $this->open_image($file);
        //  echo "Fli --> ".(boolean)$res;
        if ($res != true) {
            return false;
        }

        $this->image_progressive = (isset($this->settings['field_settings']['progressive_jpeg']) === true && $this->settings['field_settings']['progressive_jpeg'] == 'yes') ? true : false;

        $width = self::$imageResource_dim['width'];
        $height = self::$imageResource_dim['height'];

        $imgdest = imagecreatetruecolor($width, $height);

        if (imagetypes() & IMG_PNG) {
            imagesavealpha($imgdest, true);
            imagealphablending($imgdest, false);
        }

        for ($x=0 ; $x<$width ; $x++) {
            for ($y=0 ; $y<$height ; $y++) {
                if ($this->settings['axis'] == 'both') {
                    imagecopy($imgdest, self::$imageResource, $width-$x-1, $height-$y-1, $x, $y, 1, 1);
                } elseif ($this->settings['axis'] == 'horizontal') {
                    imagecopy($imgdest, self::$imageResource, $width-$x-1, $y, $x, $y, 1, 1);
                } elseif ($this->settings['axis'] == 'vertical') {
                    imagecopy($imgdest, self::$imageResource, $x, $height-$y-1, $x, $y, 1, 1);
                }
            }
        }

        self::$imageResource = $imgdest;



        $this->save_image($file);

        return true;
    }

    // ********************************************************************************* //
}

/* End of file action.flip.php */
/* Location: ./system/expressionengine/third_party/channel_images/actions/action.flip.php */
