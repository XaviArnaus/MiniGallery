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

	public function build( $folder_to_parse = '' )
	{
		// Get the full tree (photos and dirs)
		$file_data = $this->_getFiles( $folder_to_parse );

		// Generate the objects collection
		$pic_array 			= $this->_generatePicCollection( $file_data['files'] );
		$folder_array 	= $this->_generateFolderCollection( $file_data['dirs'] );
		unset( $file_data );

		// Generate the HTML output
		$this->html_content = GalleryView::build( $folder_array, $pic_array );

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
			$icon_path	= Instance::getConfig()->gallery_path . DIR_SEPARATOR .
										Instance::getConfig()->gallery_folder . DIR_SEPARATOR .
										Instance::getConfig()->template_folder . DIR_SEPARATOR .
										Instance::getConfig()->template_images_folder . DIR_SEPARATOR . 'folder.png';
			$url_data		= Url::discoverPicUrls( $folder );
			$folder->loadData( array(
				'url'									=> $url_data['url'],
				'thumb_url'						=> $url_data['url'],
				'chached_url'					=> $url_data['url'],
				'relative_url'				=> Url::getRelative( $image_properties['path'] ),
				'relative_path_only'	=> Url::getRelative( $image_properties['path'] ),
				'icon_url'						=> Url::discoverUrl( $icon_path ),
				'url_access_name'			=> Instance::getConfig()->url_folder_name
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
		// Main building loop, for each found item in the given path
		$pic_list = array();
		foreach( $file_data as $file_item_path )
		{
			$pic_list[]=Element::getFile( $file_item_path );
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
		$include_back_folder = Instance::getConfig()->include_back_folder;
		$folder = $folder_to_parse;

		if ( $this->_isRootFolder( $folder_to_parse ) )
		{
			$include_back_folder = false;
			$folder = Instance::getConfig()->gallery_path;
		}

		return FileSystem::getAlbumTree( $folder, $include_back_folder );
	}

	private function _isRootFolder( $folder_to_parse )
	{
		if ( '' === $folder_to_parse )
		{
			return true;
		}
		if ( Instance::getConfig()->gallery_path === $folder_to_parse )
		{
			return true;
		}
		return false;
	}
}
?>