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
	public $template_icons_folder		= 'icons';
	public $template_css_folder			= 'css';
	public $template_js_folder			= 'js';
	public $gallery_thumbs_folder		= 'thumbs';
	public $gallery_cached_folder		= 'cached';
	public $gallery_xml_folder			= 'xml';
	public $url_folder_name					= 'folder';
	public $url_item_name						= 'item';
	public $banned_folders					= array('gallery_engine');
	public $thumb_forced_width			= 128;		// Only numbers. Height autocalculated.
	public $cached_forced_width			= 600;		// Only numbers. Height autocalculated.
	public $image_quality						= 80;
	public $force_bigger						= false;	// TRUE resizes to fit the sizes, increasing and decreaseng. FALSE only decreasing.
	public $watermark								= '';

	public function __construct( $config_file )
	{
		if ( !is_null( $config_file ) )
		{
			$this->load( $config_file );
		}
		// Corrections.
		if ( is_null( $this->gallery_url ) )
		{
			$this->gallery_url = Url::discoverCleanURL();
		}
		$this->include_back_folder	= (bool)$this->include_back_folder;
		$this->force_bigger					= (bool)$this->force_bigger;
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

	protected function load( $config_file )
	{
		$file_contents = include( $config_file );

		foreach( $file_contents as $attr => $value )
		{
			$this->$attr = $value;
		}
	}
}
?>