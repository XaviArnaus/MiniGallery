<?php
/**
 * Class to manage the Gallery
 */
class Gallery extends BaseClass
{
	protected $html_content;

	public function getContent()
	{
		return $this->html_content;
	}

	public function __construct( Config $config, $folder_to_parse = '' )
	{
		// Inyect the config.
		$this->_config = $config;

		// Get the full tree (photos and dirs)
		$file_data = $this->_getFiles( $folder_to_parse );

		// Generate the objects collection
		$pic_array 			= $this->_generatePicCollection( $file_data['files'] );
		$folder_array 	= $this->_generateFolderCollection( $file_data['dirs'] );
		unset( $file_data );

		// Generate the HTML output
		$this->html_content = GalleryView::build( $folder_array, $pic_array, $this->getConfig() );

		// Last destroying.
		unset( $pic_array );
		unset( $folder_array );
	}

	private function _generateFolderCollection( $file_data )
	{
		// Work only if is what we want.
		if ( !is_array( $file_data ) || count( $file_data ) == 0 )
		{
			return false;
		}
		// --Do we have an XML?--

		// Main building loop, for each found item in the given path
		$folder_list = array();
		foreach( $file_data as $file_item_path )
		{
			// Discover Image Properties --Is it in the XML?--
			$dir_name				= ImageHelper::getFileName( $file_item_path );
			$slug						= ImageHelper::getCleanName( $dir_name, true );
	
			// Pack Image Data.
			$image_properties = array(
				'path'					=> $file_item_path,
				'title'					=> ImageHelper::getCleanName( $file_name ),
				'filename'			=> $dir_name
			);

			// Instantiate Object
			$folder = new Folder( $slug );
			$folder->loadData( $image_properties );

			// Add extra data
			$icon_path	= $this->getConfig()->gallery_path . DIR_SEPARATOR .
										$this->getConfig()->gallery_folder . DIR_SEPARATOR .
										$this->getConfig()->template_folder . DIR_SEPARATOR .
										$this->getConfig()->template_images_folder . DIR_SEPARATOR . 'folder.png';
			$url_data		= Url::discoverPicUrls( $folder, $this->getConfig() );
			$folder->loadData( array(
				'url'									=> $url_data['url'],
				'thumb_url'						=> $url_data['url'],
				'chached_url'					=> $url_data['url'],
				'relative_url'				=> Url::getRelative( $image_properties['path'] ),
				'relative_path_only'	=> Url::getRelative( $image_properties['path'] ),
				'icon_url'						=> Url::discoverUrl( $icon_path, $this->getConfig() ),
				'url_access_name'			=> $this->getConfig()->url_folder_name
			) );

			// --Write an XML?--

			$folder_list[]=$folder;
		}
		return $folder_list;
	}

	private function _generatePicCollection( $file_data )
	{
		// Work only if is what we want.
		if ( !is_array( $file_data ) || count( $file_data ) == 0 )
		{
			return false;
		}
		// --Do we have an XML?--

		// Main building loop, for each found item in the given path
		$pic_list = array();
		foreach( $file_data as $file_item_path )
		{
			// Discover Image Properties --Is it in the XML?--
			$file_name				= ImageHelper::getFileName( $file_item_path );
			$slug							= ImageHelper::getCleanName( $file_name, true );
			$image_properties	= ImageHelper::getProperties( $file_item_path );

			// Pack Image Data.
			$image_properties = array_merge( $image_properties, array(
				'path'					=> $file_item_path,
				'is_landscape'	=> ImageHelper::isLandscape( $image_properties['width'], $image_properties['height'] ),
				'title'					=> ImageHelper::getCleanName( $file_name ),
				'filename'			=> $file_name
			) );

			// Instantiate Object
			$pic = new Pic( $slug );
			$pic->loadData( $image_properties );

			// Calculate Thumb / Cached sizes
			$thumb_cached_sizes = ImageHelper::getProportionalSizes( $pic, $this->getConfig() );
			$pic->loadData( $thumb_cached_sizes );

			// Generate & Check / Point Thumb & Cache
			$pic->loadData( array(
				'thumb_path'	=> ImageHelper::generateProfilePaths( $pic, $this->getConfig(), 'thumb' ),
				'cached_path'	=> ImageHelper::generateProfilePaths( $pic, $this->getConfig(), 'cached' )
			) );
			ImageHelper::resizeToProfile( $pic, $this->getConfig(), 'thumb' );
			ImageHelper::resizeToProfile( $pic, $this->getConfig(), 'cached' );

			// Add extra data
			$url_data = Url::discoverPicUrls( $pic, $this->getConfig() );
			$pic->loadData( array(
				'url'									=> $url_data['url'],
				'thumb_url'						=> $url_data['thumb_url'],
				'chached_url'					=> $url_data['chached_url'],
				'relative_url'				=> Url::getRelative( $image_properties['path'] ),
				'relative_path_only'	=> Url::getRelativeWithoutFile( $image_properties['path'], $file_name ),
				'url_access_name'			=> $this->getConfig()->url_item_name
			) );

			// --Write an XML?--

			$pic_list[]=$pic;
		}
		return $pic_list;
	}

	private function _getFiles( $folder_to_parse )
	{
		if ( DIR_SEPARATOR === $folder_to_parse[ strlen( $folder_to_parse ) - 1 ] )
		{
			$folder_to_parse = substr( $folder_to_parse, 0, -1 );
		}

		// Modifying depending on being root.
		$config = $this->getConfig();
		$config->include_back_folder = true;
		$folder = $folder_to_parse;

		if ( $this->_isRootFolder( $folder_to_parse ) )
		{
			$config->include_back_folder = false;
			$folder = $config->gallery_path;
		}

		return FileSystem::getAlbumTree( $folder, $config );
	}

	private function _isRootFolder( $folder_to_parse )
	{
		if ( '' === $folder_to_parse )
		{
			return true;
		}
		if ( $this->getConfig()->gallery_path === $folder_to_parse )
		{
			return true;
		}
		return false;
	}
}
?>