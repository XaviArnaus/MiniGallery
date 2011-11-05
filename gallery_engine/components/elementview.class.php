<?php
/**
 * Class to generate the views based on the data and using a template
 */
class ElementView extends BaseClass
{
	public static function build( $pic_data )
	{
		// Start walking all the items.
		$content = self::getPicOutput( $pic_data );

		// Now get the Item main template with all the content.
		$content = self::getItemOutput( $content );

		return $content;
	}

	protected static function getItemOutput( $content )
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
}
?>