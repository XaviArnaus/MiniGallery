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

	public function __construct( Config $config )
	{
		// Inyect the config.
		$this->_config = $config;

		// Get the full tree (photos and dirs)
		$file_data = $this->_getFiles();

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
				'path'					=> $this->getConfig()->gallery_path . DIR_SEPARATOR .
														$this->getConfig()->gallery_folder . DIR_SEPARATOR .
														$this->getConfig()->template_folder . DIR_SEPARATOR .
														$this->getConfig()->template_images_folder . DIR_SEPARATOR . 'folder.png',
				'title'					=> ImageHelper::getCleanName( $file_name ),
				'dirname'				=> $dir_name
			);

			// Instantiate Object
			$folder = new Folder( $slug );
			$folder->loadData( $image_properties );

			// Add extra data
			$url_data = FileSystem::discoverUrl( $folder, $this->getConfig() );
			$folder->loadData( array(
				'url'	=>	$url_data['url']
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
				'path'					=> $file_item_path,							// CHECK!
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
			$url_data = FileSystem::discoverUrl( $pic, $this->getConfig() );
			$pic->loadData( array(
				'url'					=>	$url_data['url'],
				'thumb_url'		=>	$url_data['thumb_url'],
				'chached_url'	=>	$url_data['chached_url']
			) );

			// --Write an XML?--

			$pic_list[]=$pic;
		}
		return $pic_list;
	}

	private function _getFiles( $is_root = false )
	{
		// Modifying depending on being root.
		$config->include_back_folder = !$is_root;
		return FileSystem::getAlbumTree( $this->getConfig()->gallery_path, $this->getConfig() );
	}
}
?>