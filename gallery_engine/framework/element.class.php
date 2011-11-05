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
		$element = $this->getFile( $item_to_parse );

		// Render the template
		$this->html_content = ElementView::build( $element );

		unset($element);
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

		// --Write an XML?--

		return $pic;
	}
}
?>