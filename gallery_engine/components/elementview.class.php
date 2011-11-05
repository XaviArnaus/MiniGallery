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
		$template->assign( 'extra_content', self::getExtraOutput( $extra_data ) );

		// Fetch and destroy the template object.
		$output = $template->fetch();

		return $output;
	}

	protected static function getPicOutput( $item, $extra_data = null, $use_thumb = false )
	{
		// Create a template object
		$pic_template = new Template( ( $use_thumb ? 'element_extradata_item' : 'element_item' ) );

		// Assignments.
		$pic_template->assign( 'item_url', Url::itemLink( $item ) );
		$pic_template->assign( 'element_name', ( $use_thumb ? '' : $item->getSlug() ) );
		$pic_template->assign( 'element_src', ( $use_thumb ? $item->getThumbUrl() : $item->getCachedUrl() ) );
		if ( is_null( $extra_data ) )
		{
			$pic_template->assign( 'back_class', 'style="display:none;"' );
		}
		else
		{
			$pic_template->assign( 'back_class', '' );
			$pic_template->assign( 'back_url', $extra_data['back_url'] );
			$pic_template->assign( 'back_icon_src', $extra_data['back_icon_src'] );
			$pic_template->assign( 'back_text', $extra_data['back_text'] );
		}
		// Fetch and destroy the template object.
		$output = $pic_template->fetch();
		unset( $pic_template );

		// Return
		return $output;
	}

	protected static function getExtraOutput( $extra_data )
	{
		// Create a template object
		$template = new Template( 'element_extradata' );

		// Parse all siblings through the template
		foreach( $extra_data['siblings'] as $sibling_pos => $data )
		{
			$content[$sibling_pos] = '';
			foreach( $data as $sibling )
			{
				$content[$sibling_pos].= self::getPicOutput( $sibling, null, true );
			}
		}

		// Assignments.
		$template->assign( 'siblings_before', $content['before'] );
		$template->assign( 'siblings_after', $content['after'] );

		// Fetch and destroy the template object.
		$output = $template->fetch();

		return $output;
	}
}
?>