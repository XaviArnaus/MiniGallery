<?php
/**
 * Class to generate the views based on the data and using a template
 */
class GalleryView extends BaseClass
{
	public static function build( $folder_data, $pic_data )
	{
		// Start walking all the items.
		$content = '';
		// First add all the folders.
		if ( is_array( $folder_data ) && count( $folder_data ) > 0 )
		{
			foreach( $folder_data as $folder )
			{
				$content.= self::getFolderOutput( $folder );
			}
		}
		// Now add all the pics.
		if ( is_array( $pic_data ) && count( $pic_data ) > 0 )
		{
			foreach( $pic_data as $pic )
			{
				$content.= self::getPicOutput( $pic );
			}
		}
		// Now get the gallery main template with all the content.
		$content = self::getGalleryOutput( $content );

		return $content;
	}

	protected static function getGalleryOutput( $content )
	{
		// Create a template object
		$gal_template = new Template( 'gallery' );

		// Assignments.
		$gal_template->assign( 'content', $content );

		// Fetch and destroy the template object.
		$output = $gal_template->fetch();
		unset( $gal_template );

		// Return
		return $output;
	}

	protected static function getPicOutput( $item )
	{
		// Create a template object
		$pic_template = new Template( 'gallery_item' );

		// Assignments.
		$pic_template->assign( 'item_url', Url::itemLink( $item ) );
		$pic_template->assign( 'thumb_name', $item->getSlug() );
		$pic_template->assign( 'thumb_src', $item->getThumbUrl() );
		$pic_template->assign( 'item-type', 'element' );

		// Fetch and destroy the template object.
		$output = $pic_template->fetch();
		unset( $pic_template );

		// Return
		return $output;
	}

	protected static function getFolderOutput( $item )
	{
		// Create a template object
		$pic_template = new Template( 'gallery_item' );

		// Assignments.
		$slug = $item->getSlug();
		if ( '' === $slug )
		{
			$slug = '..';
		}
		$pic_template->assign( 'item_url', Url::itemLink( $item ) );
		$pic_template->assign( 'thumb_name', $slug );
		$pic_template->assign( 'thumb_src', $item->getIconUrl() );
		$pic_template->assign( 'item-type', 'folder' );

		// Fetch and destroy the template object.
		$output = $pic_template->fetch();
		unset( $pic_template );

		// Return
		return $output;
	}
}
?>