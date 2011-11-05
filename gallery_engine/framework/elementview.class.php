<?php
/**
 * Class to generate the views based on the data and using a template
 */
class ElementView extends BaseClass
{
	public static function build( $pic_data )
	{
		// Create the main template object.
		$main_template = new Template( 'index', true );

		// Assign main bits.
		$main_template->setCss( 'style.css' );
		$main_template->assign( 'site_title', Instance::getConfig()->gallery_name );
		$main_template->assign( 'site_url', Instance::getConfig()->gallery_url );
		$main_template->assign( 'site_slogan', Instance::getConfig()->gallery_desc );

		// Start walking all the items.
		$content = self::getPicOutput( $pic_data );

		// Now get the gallery main template with all the content.
		$content = self::getGalleryOutput( $content );
		// Assign this content to the main template.
		$main_template->assign( 'content_body', $content );
		$main_template->assign( 'content_head', 'Nom del directori actual' );
		$main_template->assign( 'footer', self::getFooterOutput() );
		// Fetching the output.
		$output = $main_template->fetch();
		unset( $main_template );
		return $output;
	}

	protected static function getGalleryOutput( $content )
	{
		// Create a template object
		$gal_template = new Template( 'element' );

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
		$pic_template = new Template( 'element_item' );

		// Assignments.
		$pic_template->assign( 'item_url', Url::itemLink( $item ) );
		$pic_template->assign( 'element_name', $item->getSlug() );
		$pic_template->assign( 'element_src', $item->getCachedUrl() );

		// Fetch and destroy the template object.
		$output = $pic_template->fetch();
		unset( $pic_template );

		// Return
		return $output;
	}

	protected static function getFooterOutput()
	{
		// Create a template object
		$template = new Template( 'footer' );

		// Assignments.

		// Fetch and destroy the template object.
		$output = $template->fetch();
		unset( $template );

		// Return
		return $output;
	}
}
?>