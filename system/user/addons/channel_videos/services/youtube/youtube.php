<?php

if (!defined('BASEPATH')) {
    die('No direct script access allowed');
}

/**
 * Channel Videos YOUTUBE service
 *
 * @package         ChannelVideos
 * @author          EEHarbor <help@eeharbor.com>
 * @copyright       Copyright (c) 2007-2019 EEHarbor <https://eeharbor.com>
 * @license         https://eeharbor.com/license/
 * @link            https://eeharbor.com/channel-videos/
 */
class Video_Service_youtube extends Video_Service
{

    /**
     * Service info - Required
     *
     * @access public
     * @var array
     */
    public $info = array(
        'title'     =>  'YouTube',
        'name'      =>  'youtube',
        'version'   =>  '1.0',
        'enabled'   =>  true,
    );

    /**
     * Constructor
     * Calls the parent constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // ********************************************************************************* //

    public function search($search = array())
    {
        $videos = array();
        return $videos;

        //http://code.google.com/apis/youtube/2.0/reference.html
        $key = 'AI39si7C2SGsy0Kqit-am7Bg0dMfXFXNVZLPsXTxHyf3VBEEYYXjeElrjJkNTJ-uybKJqQdoMtKFf9CavpsO80_143BghD5pZg';
        $url = 'http://gdata.youtube.com/feeds/api/videos?v=2&key=' . $key;

        // Do we have an author?
        if ($search['author'] != false) {
            $search['author'] = "&author={$search['author']}";
        }

        // Execute the search
        $response = ee()->channel_videos_helper->fetch_url_file("{$url}&q={$search['keywords']}{$search['author']}&max-results={$search['limit']}&format=5");

        // Parse XML
        $response = @simplexml_load_string($response);

        // Failed?
        if (isset($response->entry[0]) == false) {
            return false;
        }

        // -----------------------------------------
        // Loop over all videos
        // -----------------------------------------
        foreach ($response->entry as $vid) {
            $id = explode(':', (string) $vid->id);
            $id = end($id);

            $temp = array();
            $temp['id'] = $id;
            $temp['title']  = (string) $vid->title;
            $temp['img_url'] = 'https://i.ytimg.com/vi/' . $id . '/0.jpg';
            $temp['vid_url'] = 'https://www.youtube.com/embed/' . $id;

            $videos[] = $temp;
        }

        return $videos;
    }

    // ********************************************************************************* //

    public function parse_url($url)
    {
        $res = array('id' => false, 'service' => 'youtube');

        // Is this YouTube?
        if (strpos($url, 'youtube') === false and strpos($url, 'youtu.be') === false) {
            return $res;
        }

        // Quick Way
        if (strpos($url, 'youtube.com/watch') !== false) {
            parse_str(parse_url($url, PHP_URL_QUERY));
            if (isset($v) == true) {
                $res['id'] = $v;
            }
        } elseif (strpos($url, 'youtu.be') !== false) {
            // Short URL (eg: http://youtu.be/dDXvJDyAG5E)
            $url = explode('/', $url); //print_r($url);
            $res['id'] = end($url);
        } elseif (strpos($url, 'youtube.com/embed/') !== false) {
            // Embed? (eg: https://www.youtube.com/embed/dDXvJDyAG5E)
            $url = explode('/', $url); //print_r($url);
            $res['id'] = end($url);
        } else {
            // Nothing? Quit
            return $res;
        }

        //exit(print_r($res));

        return $res;
    }

    // ********************************************************************************* //

    public function get_video_info($video_id)
    {
        // http://www.ibm.com/developerworks/xml/library/x-youtubeapi/
        // http://code.google.com/apis/youtube/2.0/developers_guide_protocol_video_entries.html

        $key = 'AI39si7C2SGsy0Kqit-am7Bg0dMfXFXNVZLPsXTxHyf3VBEEYYXjeElrjJkNTJ-uybKJqQdoMtKFf9CavpsO80_143BghD5pZg';
        $RAWresponse = ee()->channel_videos_helper->fetch_url_file("http://gdata.youtube.com/feeds/api/videos/{$video_id}?v=2&key=" . $key);

        // Parse XML
        $response = @simplexml_load_string($RAWresponse);
        $media = $response->children('http://search.yahoo.com/mrss/');
        $yt = $response->children('http://gdata.youtube.com/schemas/2007');

        // Get Duration
        $yt = $media->children('http://gdata.youtube.com/schemas/2007');
        $attrs = $yt->duration->attributes();
        $duration = (string) $attrs['seconds'];

        // Get Views
        $yt = $response->children('http://gdata.youtube.com/schemas/2007');

        // Sometimes the viewcount is 0, then this fails..
        if ($yt->statistics && $yt->statistics->attributes()) {
            $attrs = $yt->statistics->attributes();
            $viewCount = (string) $attrs['viewCount'];
        } else {
            $viewCount = 0;
        }

        // Video Array
        $video = array();
        $video['service'] = 'youtube';
        $video['service_video_id']  = $video_id;
        $video['video_title']   = (string) $response->title;
        $video['video_desc']    = (string) $media->group->description;
        $video['video_username'] = (string) $response->author->name;
        $video['video_author'] = (string) $response->author->name;
        $video['video_author_id'] = 0;
        $video['video_date']    = ee()->channel_videos_helper->tstamptotime((string) $response->published);
        $video['video_views']   = $viewCount;
        $video['video_duration'] = $duration;
        $video['video_img_url'] = 'https://i.ytimg.com/vi/' . $video_id . '/default.jpg';
        $video['video_url'] = 'https://www.youtube.com/embed/' . $video_id;

        return $video;
    }

    // ********************************************************************************* //

    public function render_player($video_id = 0, $settings = array(), $hd = false)
    {
        $attr_id = ee()->TMPL->fetch_param('attr:id');
        $attr_class = ee()->TMPL->fetch_param('attr:class');

        if (isset($settings['width']) === true) {
            $width = (isset(ee()->TMPL->tagparams['embed_width']) == true) ? ee()->TMPL->tagparams['embed_width'] : $settings['width'];
            $height = (isset(ee()->TMPL->tagparams['embed_height']) == true) ? ee()->TMPL->tagparams['embed_height'] : $settings['height'];
            unset($settings['width'], $settings['height']);

            $params = '';
            if ($hd == true) {
                $settings['hd'] = 1;
                $settings['vq'] = 'hd720';
            }
            foreach ($settings as $key => $val) {
                $params .= "&amp;{$key}={$val}";
            }

            $url = 'https://www.youtube.com/embed/' . $video_id . '?wmode=transparent' . $params;

            return "<iframe width='{$width}' height='{$height}' id='{$attr_id}' class='{$attr_class}' src=\"{$url}\" frameborder='0' webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>";
        } else {
            $width = (isset(ee()->TMPL->tagparams['embed_width']) == true) ? ee()->TMPL->tagparams['embed_width'] : 560;
            $height = (isset(ee()->TMPL->tagparams['embed_height']) == true) ? ee()->TMPL->tagparams['embed_height'] : 349;
            $url_params = (isset(ee()->TMPL->tagparams['youtube:url_params']) == true) ? ee()->TMPL->tagparams['youtube:url_params'] : '';
            if ($hd == true) {
                'hd=1&amp;vq=hd720&amp;' . $url_params;
            }
            return "<iframe width='{$width}' height='{$height}' id='{$attr_id}' class='{$attr_class}' src='https://www.youtube.com/embed/{$video_id}?wmode=transparent&amp;{$url_params}' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>";
        }
    }

    // ********************************************************************************* //
}

/* End of file youtube.php */
/* Location: ./system/user/addons/channel_videos/services/youtube/youtube.php */
