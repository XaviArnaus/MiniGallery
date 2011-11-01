<?php
/**
 * Class to manage configurations
 */

class Config
{
	public $gallery_name						= "Gallery Name";
	public $gallery_description			= "Gallery Description";
	public $gallery_url							= null;		// To be loaded in the construct.
	public $gallery_path						= BASE_DIR;
	public $include_back_folder			= true;
	public $valid_extensions				= array('GIF','JPG','JPEG','PNG','BMP');
	public $gallery_folder					= 'gallery_engine';		// Where we'll store all the generated content.
	public $template_folder					= 'template';
	public $template_images_folder	= 'images';
	public $template_css_folder			= 'css';
	public $template_js_folder			= 'js';
	public $gallery_thumbs_folder		= 'thumbs';
	public $gallery_cached_folder		= 'cached';
	public $gallery_xml_folder			= 'xml';
	public $banned_folders					= array('gallery_engine');
	public $thumb_forced_width			= 128;		// Only numbers. Height autocalculated.
	public $cached_forced_width			= 600;		// Only numbers. Height autocalculated.
	public $image_quality						= 80;
	public $force_bigger						= false;	// TRUE resizes to fit the sizes, increasing and decreaseng. FALSE only decreasing.
	public $watermark								= '';

	public function __construct( $config_file )
	{
		$this->gallery_url = $this->_discoverCleanURL();

		if ( !is_null( $config_file ) )
		{
			$this->load( $config_file );
		}
	}

	private function _discoverCleanURL()
	{
		// Data about the URL
		$self_pieces	= explode( '/', $_SERVER['PHP_SELF'] );
		$last_piece		= $self_pieces[count( $self_pieces )-1];
		// Data about the ScriptFile
		$scr_pieces		= explode( '/', $_SERVER['SCRIPT_FILENAME'] );
		$script_name	= $scr_pieces[count( $scr_pieces )-1];
		// If self contanins the script file clean it.
		if ( strpos( $_SERVER['PHP_SELF'], $script_name ) > -1 )
		{
			$post_domain = substr( $_SERVER['PHP_SELF'], 0, -(strlen( $script_name )) );
		}
		else
		{
			$post_domain = $_SERVER['PHP_SELF'];
		}
		return 'http://' . $_SERVER['HTTP_HOST'] . $post_domain;
		
	}

	public function getThumbnailsFolder()
	{
		return $this->gallery_path . DIR_SEPARATOR . $this->gallery_folder . DIR_SEPARATOR . $this->gallery_thumbs_folder;
	}

	public function getCachedFolder()
	{
		return $this->gallery_path . DIR_SEPARATOR . $this->gallery_folder . DIR_SEPARATOR . $this->gallery_cached_folder;
	}

	public function getXMLFolder()
	{
		return $this->gallery_path . DIR_SEPARATOR . $this->gallery_folder . DIR_SEPARATOR . $this->gallery_xml_folder;
	}

	protected function load( $config_path )
	{
		$file_contents = include( $config_path );
		foreach( $file_contents as $attr => $value )
		{
			$this->$attr = $value;
		}
	}
}
?>