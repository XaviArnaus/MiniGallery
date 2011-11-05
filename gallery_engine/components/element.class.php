<?php
/**
 * Class to manage an Element
 */
class Element extends BaseClass
{
	protected $html_content;

	public function getContent()
	{
		return $this->html_content;
	}

	public function __construct( $item_to_parse = '' )
	{
		// Generate the object.
		$element		= $this->getFile( $item_to_parse );
		// Generate the extra data for this element.
		$extra_data	= $this->getExtraData( $element );

		// Render the template
		$this->html_content = ElementView::build( $element, $extra_data );

		unset($element);
	}

	/**
	 * Data related to the element but not meaning the pic itself.
	 */
	protected function getExtraData( $element )
	{
		// We need to be able to return to the gallery.
		$folder = new Folder();
		$folder->loadData( array(	// Only basic data to be able to create links.
			'path'							=> Url::refillRelativePath( $element->getRelativePathOnly() ),
			'url_access_name'		=> Instance::getConfig()->url_folder_name,
			'relative_url'			=> $element->getRelativePathOnly(),
		) );

		$extra_data = array(
			'back_url'			=> Url::itemLink( $folder ),
			'back_icon_src'	=> Url::discoverUrl( Instance::getConfig()->gallery_path . DIR_SEPARATOR .
														Instance::getConfig()->gallery_folder . DIR_SEPARATOR .
														Instance::getConfig()->template_folder . DIR_SEPARATOR .
														Instance::getConfig()->template_images_folder . DIR_SEPARATOR . 'back.png' ),
			'back_text'			=> $element->getRelativePathOnly(),
			'siblings'			=> $this->getSiblings( $element )
		);

		return $extra_data;
	}

	protected function getSiblings( $element, $number_of_siblings_per_side = 2 )
	{
		$folder_path = Url::refillRelativePath( $element->getRelativePathOnly() );
		$folder_path = FileSystem::stripTailingSlash( $folder_path );
		$tree	= FileSystem::getAlbumTree( $folder_path, false );
		$pics	= $tree['files'];
		unset( $tree );

		// Find the element inside the list of elements.
		$element_pos = array_search( $element->getPath(), $pics );

		$result =	array(
			'before'	=> array(),
			'after'		=> array()
		);
		// Did we found it?
		if ( false === $element_pos )
		{
			return $result;
		}
		// Found, so lets take some before and after.
		$before	= $this->_getBeforeSiblings( $pics, $element_pos, $number_of_siblings_per_side );
		$after	= $this->_getAfterSiblings( $pics, $element_pos, $number_of_siblings_per_side );
		// When using float:right in style, it auto-reverses the order!!
		$after = array_reverse( $after );
		// Load Pic objects.
		foreach( $before as $item )
		{
			$result['before'][]=$this->getFile( $item ); 
		}
		foreach( $after as $item )
		{
			$result['after'][]=$this->getFile( $item ); 
		}
		return $result;
	}

	private function _getBeforeSiblings( $array, $element_position, $number_of_siblings_per_side )
	{
		if ( $number_of_siblings_per_side > $element_position )
		{
			$start	= 0;
			$length	= $element_position;
		}
		else
		{
			$start	= $element_position - $number_of_siblings_per_side;
			$length = $number_of_siblings_per_side;
		}
		return array_slice( $array, $start, $length );
	}
	private function _getAfterSiblings( $array, $element_position, $number_of_siblings_per_side )
	{
		if ( $number_of_siblings_per_side > ( count( $array ) -1 ) - $element_position )
		{
			$start	= $element_position + 1;
			$length	= ( count( $array ) -1 ) - $element_position;
		}
		else
		{
			$start	= $element_position + 1;
			$length = $number_of_siblings_per_side;
		}
		return array_slice( $array, $start, $length );
	}

	// This can become the source on the gallery part!
	protected function getFile( $file_item_path )
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
		$thumb_cached_sizes = ImageHelper::getProportionalSizes( $pic );
		$pic->loadData( $thumb_cached_sizes );

		// Generate & Check / Point Thumb & Cache
		$pic->loadData( array(
			'thumb_path'	=> ImageHelper::generateProfilePaths( $pic, 'thumb' ),
			'cached_path'	=> ImageHelper::generateProfilePaths( $pic, 'cached' )
		) );
		ImageHelper::resizeToProfile( $pic, 'thumb' );
		ImageHelper::resizeToProfile( $pic, 'cached' );

		// Add extra data
		$url_data = Url::discoverPicUrls( $pic );
		$pic->loadData( array(
			'url'									=> $url_data['url'],
			'thumb_url'						=> $url_data['thumb_url'],
			'cached_url'					=> $url_data['cached_url'],
			'relative_url'				=> Url::getRelative( $image_properties['path'] ),
			'relative_path_only'	=> Url::getRelativeWithoutFile( $image_properties['path'], $file_name ),
			'url_access_name'			=> Instance::getConfig()->url_item_name
		) );

		return $pic;
	}
}
?>