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
		$template->assign( 'navbar_content', self::getNavbarOutput( $extra_data ) );
		$template->assign( 'content', self::getPicOutput( $pic_data ) );
		$template->assign( 'extra_content', self::getExtraOutput( $extra_data ) );

		// Fetch and destroy the template object.
		$output = $template->fetch();

		return $output;
	}

	protected static function getPicOutput( $item, $use_thumb = false )
	{
		// Create a template object
		$pic_template = new Template( ( $use_thumb ? 'element_extradata_item' : 'element_item' ) );

		// Assignments.
		$pic_template->assign( 'item_url', Url::itemLink( $item ) );
		$pic_template->assign( 'element_name', ( $use_thumb ? '' : $item->getSlug() ) );
		$pic_template->assign( 'element_src', ( $use_thumb ? $item->getThumbUrl() : $item->getCachedUrl() ) );

		// Fetch and destroy the template object.
		$output = $pic_template->fetch();
		unset( $pic_template );

		// Return
		return $output;
	}

	protected static function getNavbarOutput( $extra_data )
	{
		// Create a template object
		$template = new Template( 'navbar' );

		// Assignments.
		$template->assign( 'back_url', $extra_data['back_url'] );
		$template->assign( 'back_icon_src', $extra_data['back_icon_src'] );
		$template->assign( 'back_text', $extra_data['back_text'] );
		$template->assign( 'prev_style', $extra_data['prev_style'] );
		$template->assign( 'prev_url', $extra_data['prev_url'] );
		$template->assign( 'prev_icon_src', $extra_data['prev_icon_src'] );
		$template->assign( 'next_style', $extra_data['next_style'] );
		$template->assign( 'next_url', $extra_data['next_url'] );
		$template->assign( 'next_icon_src', $extra_data['next_icon_src'] );

		// Fetch and destroy the template object.
		$output = $template->fetch();

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
				$content[$sibling_pos].= self::getPicOutput( $sibling, true );
			}
		}

		// Assignments.
		$template->assign( 'siblings_before', $content['before'] );
		$template->assign( 'siblings_after', $content['after'] );
		$template->assign( 'element_full_url', $extra_data['element_full_url'] );
		$template->assign( 'element_width', $extra_data['element_width'] );
		$template->assign( 'element_height', $extra_data['element_height'] );

		// Fetch and destroy the template object.
		$output = $template->fetch();

		return $output;
	}
}
?>