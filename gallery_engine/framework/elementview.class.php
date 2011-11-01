<?php
/**
 * Class to generate the views based on the data and using a template
 */
class ElementView extends BaseClass
{
	public static function build( $pic_data, $config )
	{
		// Create the main template object.
		$main_template = new Template( 'index', $config, true );

		// Assign main bits.
		$main_template->setCss( 'style.css' );
		$main_template->assign( 'site_title', $config->gallery_name );
		$main_template->assign( 'site_url', $config->gallery_url );
		$main_template->assign( 'site_slogan', $config->gallery_desc );

		// Start walking all the items.
		$content = self::getPicOutput( $pic_data, $config );

		// Now get the gallery main template with all the content.
		$content = self::getGalleryOutput( $content, $config );
		// Assign this content to the main template.
		$main_template->assign( 'content_body', $content );
		$main_template->assign( 'content_head', 'Nom del directori actual' );
		$main_template->assign( 'footer', self::getFooterOutput( $config ) );
		// Fetching the output.
		$output = $main_template->fetch();
		unset( $main_template );
		return $output;
	}

	protected static function getGalleryOutput( $content, $config )
	{
		// Create a template object
		$gal_template = new Template( 'element', $config );

		// Assignments.
		$gal_template->assign( 'content', $content );

		// Fetch and destroy the template object.
		$output = $gal_template->fetch();
		unset( $gal_template );

		// Return
		return $output;
	}

	protected static function getPicOutput( $item, $config )
	{
		// Create a template object
		$pic_template = new Template( 'element_item', $config );

		// Assignments.
		$pic_template->assign( 'item_url', Url::itemLink( $item, $config ) );
		$pic_template->assign( 'element_name', $item->getSlug() );
		$pic_template->assign( 'element_src', $item->getCachedUrl() );

		// Fetch and destroy the template object.
		$output = $pic_template->fetch();
		unset( $pic_template );

		// Return
		return $output;
	}

	protected static function getFooterOutput( $config )
	{
		// Create a template object
		$template = new Template( 'footer', $config );

		// Assignments.

		// Fetch and destroy the template object.
		$output = $template->fetch();
		unset( $template );

		// Return
		return $output;
	}
}
?>