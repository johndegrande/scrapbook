<?php if (!defined('BASEPATH')) die('No direct script access allowed');

/**
 * Channel Files CLOUDFILES location
 *
 * @package			DevDemon_ChannelFiles
 * @author			DevDemon <http://www.devdemon.com> - Lead Developer @ Parscale Media
 * @copyright 		Copyright (c) 2007-2011 Parscale Media <http://www.parscale.com>
 * @license 		http://www.devdemon.com/license/
 * @link			http://www.devdemon.com/channel_files/
 */
class CF_Location_cloudfiles extends Cfile_Location
{

	/**
	 * Constructor
	 *
	 * @access public
	 *
	 * Calls the parent constructor
	 */
	public function __construct($settings=array())
	{
		parent::__construct();

		$this->lsettings = $settings;
	}

	// ********************************************************************************* //

	public function create_dir($dir)
	{
		$this->init();
		$this->CF_CONT = $this->CF_CONN->get_container($this->lsettings['container']);

		try
		{
			$this->CF_CONT->get_object($dir);
		}
		catch (NoSuchObjectException $e)
		{
			$OBJECT = $this->CF_CONT->create_object($dir);
			$OBJECT->content_type = 'application/directory';
			$OBJECT->write(".", 1); // CLOUDFILES HATES EMPTY FILES!
			unset($OBJECT);
		}

		return TRUE;
	}

	// ********************************************************************************* //

	public function delete_dir($dir)
	{
		$this->init();
		$this->CF_CONT = $this->CF_CONN->get_container($this->lsettings['container']);

		try
		{
			$objects = $this->CF_CONT->list_objects(0,NULL,NULL,$dir);
		}
		catch (NoSuchObjectException $e)
		{
			return FALSE;
		}

		foreach ($objects as $key => $value)
		{
			$this->CF_CONT->delete_object($value);
		}

		try {
			// Delete dir
			$this->CF_CONT->delete_object($dir);
		} catch (Exception $e) {

		}


		return TRUE;
	}

	// ********************************************************************************* //

	public function upload_file($source_file, $dest_filename, $dest_folder, $checkExists=true)
	{
		$out = array('success' => FALSE, 'filename' => '');

		$this->init();
		$this->CF_CONT = $this->CF_CONN->get_container($this->lsettings['container']);

		if ($dest_folder !== FALSE) $dest_folder .= '/';
		else $dest_folder = '';

		// Extension
		$extension = substr( strrchr($source_file, '.'), 1);

		$filename_no_ext = str_replace('.'.$extension, '', $dest_filename);

		if ($checkExists) {
			// Does it already exists?
			try
			{
				$this->CF_CONT->get_object($dest_folder.$dest_filename, TRUE, TRUE);

				// It exists..
				for ($i=2; $i < 30; $i++)
				{
					try { $this->CF_CONT->get_object($dest_folder."{$filename_no_ext}_{$i}.{$extension}", TRUE, TRUE);}
					catch(NoSuchObjectException $e){
						$dest_filename = "{$filename_no_ext}_{$i}.{$extension}";
						break;
					}
				}

			}
			catch(NoSuchObjectException $e){ }
		}

		// Mime type
		if (class_exists('CFMimeTypes') == FALSE) include PATH_THIRD.'channel_files/libraries/mimetypes.class.php';
		$MIME = new CFMimeTypes();
		$filemime = $MIME->get_mimetype($extension);

		// Create Object
		$OBJECT = $this->CF_CONT->create_object($dest_folder.$dest_filename);
		$OBJECT->content_type = $filemime;

		// UPLOAD FREAKING FILE!
		$fp = fopen($source_file, "r");
		$size = (float) sprintf("%u", @filesize($source_file));
		$OBJECT->write($fp, $size, TRUE);
		fclose($fp);

		$out['success'] = TRUE;
    	$out['filename'] = $dest_filename;

		return $out;
	}

	// ********************************************************************************* //

	public function download_file($dir, $filename, $dest_folder)
	{
		$this->init();

		if ($dir !== FALSE) $dir .= '/';
		else $dir = '';

		$this->CF_CONT = $this->CF_CONN->get_container($this->lsettings['container']);
		$OBJ = $this->CF_CONT->get_object($dir.$filename);

		ee()->load->helper('file');
		write_file($dest_folder.$filename, $OBJ->read());

		return TRUE;
	}

	// ********************************************************************************* //

	public function delete_file($dir, $filename)
	{
		$this->init();
		$this->CF_CONT = $this->CF_CONN->get_container($this->lsettings['container']);

		if ($dir !== FALSE) $dir .= '/';
		else $dir = '';

		try {
			$this->CF_CONT->delete_object($dir.$filename);
		} catch (Exception $e) {

		}


		return TRUE;
	}

	// ********************************************************************************* //

	public function parse_file_url($dir, $filename)
	{
		$cdn_uri = (isset($this->lsettings['cdn_uri']) == TRUE && $this->lsettings['cdn_uri'] != FALSE) ? $this->lsettings['cdn_uri'] : FALSE;

		if (isset($this->CF_CONT) == FALSE && $cdn_uri == FALSE)
		{
			$this->init();
			$this->CF_CONT = $this->CF_CONN->get_container($this->lsettings['container']);
		}

		if ($cdn_uri) $first_url = $cdn_uri;
		else $first_url = $this->CF_CONT->cdn_uri;



		if ($dir !== FALSE) $dir .= '/';
		else $dir = '';

		try
		{
			$url = $first_url.'/'.$dir.$filename;
		}
		catch (NoSuchObjectException $e)
		{
			$url = '';
		}

		return $url;
	}

	// ********************************************************************************* //

	public function test_location()
	{
		error_reporting(-1);

		if ($this->init() == FALSE)
		{
			exit('CLOUDFILES INIT FAILED. <br /> Check USERNAME, API KEY, CONTAINER?');
		}

		$file = uniqid(mt_rand()).'.tmp';

		$o = '<style type="text/css">.good {font-weight:bold; color:green} .bad {font-weight:bold; color:red}</style>';

		// Check for Safe Mode?
		$safemode = strtolower(@ini_get('safe_mode'));
		if ($safemode == 'on' || $safemode == 'yes' || $safemode == 'true' ||  $safemode == 1)	$o .= "PHP Safe Mode (OFF): <span class='bad'>Failed</span> <br>";
		else $o .= "PHP Safe Mode (OFF): <span class='good'>Passed</span> <br>";


		// Does the Container Exist?
		try
		{
			$this->CF_CONT = $this->CF_CONN->get_container($this->lsettings['container']);
			$o .= 'Container Exists?: ' . '<span class="good">Yes</span> <br />';
		}
		catch (NoSuchContainerException $e)
		{
			$o .= 'Container Exists: ' . '<span class="bad">No</span> <br />';

			// Create Container
			try
			{
				$this->CF_CONT = $this->CF_CONN->create_container($this->lsettings['container']);
				$this->CF_CONT->make_public();
				$o .= 'Container Creation: ' . '<span class="good">Passed</span> <br />';
			}
			catch (Exception $e)
			{
				$o .= 'Container Creation: ' . '<span class="bad">Failed</span> <br />';
				$o .= '<em>' . $e->getMessage() . '</em>';
				exit($o);
			}
		}
		catch (Exception $e)
		{
			$o .= '<em>' . $e->getMessage() . '</em>';
			exit($o);
		}

		// Create The File
		$this->CF_OBJ = $this->CF_CONT->create_object($file);
		$this->CF_OBJ->content_type = 'text/html';

		try
		{
			$this->CF_OBJ->write('test');
			$o .= 'Write Test File: ' . '<span class="good">Passed</span> <br />';
		}
		catch (Exception $e)
		{
			$o .= 'Write Test File: ' . '<span class="bad">Failed</span> <br />';
			$o .= '<em>' . $e->getMessage() . '</em>';
			exit($o);
		}


		// Delete The File
		try
		{
			$this->CF_CONT->delete_object($file);
			$o .= 'Delete Test File: ' . '<span class="good">Passed</span> <br />';
		}
		catch (Exception $e)
		{
			$o .= 'Delete Test File: ' . '<span class="bad">Failed</span> <br />';
			$o .= '<em>' . $e->getMessage() . '</em>';
			exit($o);
		}

		$o .= "<br /> Even if all tests PASS, uploading can still fail due Apache/htaccess misconfiguration";

		return $o;
	}

	// ********************************************************************************* //

	private function init()
	{
		if (isset($this->CF_CONN) == TRUE)
		{
			return TRUE;
		}
		else
		{
			if ($this->lsettings['username'] == FALSE OR $this->lsettings['api'] == FALSE OR $this->lsettings['container'] == FALSE)
			{
				return FALSE;
			}

			// Include the SDK
			if (class_exists('CF_Authentication') == FALSE)
			{
				require_once PATH_THIRD.'channel_files/locations/cloudfiles/sdk/cloudfiles.php';
			}

			// Which Region?
			if ($this->lsettings['region'] == 'uk') $this->lsettings['region'] = constant('UK_AUTHURL');
			else $this->lsettings['region'] = constant('US_AUTHURL');

			// Instantiate the Cloudfiles class
			$this->CF_AUTH = new CF_Authentication($this->lsettings['username'], $this->lsettings['api'], NULL, $this->lsettings['region']);

			try
			{
				$this->CF_AUTH->ssl_use_cabundle();
				$this->CF_AUTH->authenticate();
			}
			catch (AuthenticationException $e)
			{
				return FALSE;
			}

        	$this->CF_CONN = new CF_Connection($this->CF_AUTH);
        	$this->CF_CONN->ssl_use_cabundle();

			return TRUE;
		}
	}

	// ********************************************************************************* //
}

/* End of file local.php */
/* Location: ./system/expressionengine/third_party/channel_files/locations/cloudfiles/cloudfiles.php */
