<?php
/**
 * Class to generate the views based on the data and using a template
 */
class ElementView extends BaseClass
{
	public static function build( $pic_data, $extra_data )
	{
		// Create a template object
		$template = new Template( 'element' );

		// Assignments.
		$template->assign( 'content', self::getPicOutput( $pic_data, $extra_data ) );

		// Fetch and destroy the template object.
		$output = $template->fetch();

		return $output;
	}

	protected static function getPicOutput( $item, $extra_data )
	{
		// Create a template object
		$pic_template = new Template( 'element_item' );

		// Assignments.
		$pic_template->assign( 'item_url', Url::itemLink( $item ) );
		$pic_template->assign( 'element_name', $item->getSlug() );
		$pic_template->assign( 'element_src', $item->getCachedUrl() );
		$pic_template->assign( 'back_url', $extra_data['back_url'] );
		$pic_template->assign( 'back_icon_src', $extra_data['back_icon_src'] );
		$pic_template->assign( 'back_text', $extra_data['back_text'] );

		// Fetch and destroy the template object.
		$output = $pic_template->fetch();
		unset( $pic_template );

		// Return
		return $output;
	}

	protected static function getExtraOutput( $item )
	{
		// Create a template object
		$pic_template = new Template( 'element_extra' );

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