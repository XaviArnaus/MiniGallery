<?php
/**
 * class to manage Image Editing
 */
class ImageHelper extends ImageResizer
{
	private static $_slug_chars = "abcdefghijklmnopqrstuuwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	private static $_slug_length = 8;

	public static function generateSlug()
	{
		$chars_length = strlen( self::$_slug_chars );
		$slug = '';
		for ( $i = 0; $i < self::$_slug_length; $i++ )
		{
			$slug = $slug.self::$_slug_chars[ rand( 0, $chars_length ) ];
		}
		$this->slug = $slug;
	}

	public static function getProperties( $image_path )
	{
		$photo_info = @getimagesize( $image_path );
		if ( !$photo_info )
		{
			return false;
		}
		
		return array(
			'width'		=> $photo_info[0],
			'height'	=> $photo_info[1],
			'mime'		=> $photo_info['mime']
		);
	}

	public static function isLandscape( $width, $height )
	{
		if( $width >= $height )
		{
			return true;
		}
		return false;
	}

	public static function getFileName( $image_path )
	{
		$pieces = explode('/',$image_path);
		return $pieces[count($pieces)-1];
	}

	public static function getCleanName( $file_name, $for_slug = false )
	{
		// @todo: Remember that preg_replace is slower than str_replace!
		$name = '';
		if ( $for_slug )
		{
			$name = preg_replace("/[^a-zA-Z0-9]/", "", strtolower(  $file_name ) );
		}
		else
		{
			// Cleaning to '-'
			$name = preg_replace("/[^\w\.-]/", "-", strtolower(  $file_name ) );
			// Too much '-' ?
			$name = preg_replace("/[-]+/", "-", $name );
			// Not at the begining.
			$name = preg_replace("/(^-|-$)/", "", $name );
		}
		return $name;
	}

	public static function getProportionalSizes( $image_object, $config )
	{
		$data = array();
		if($image_object->landscape)
		{
			$data['thumb_width'] = $config->thumb_forced_width;
			$data['thumb_height'] = intval( ( $data['thumb_width'] / $image_object->getWidth() ) * $image_object->getHeight() );
			
			$data['cached_width'] = $config->cached_forced_width;
			$data['cached_height'] = intval( ( $data['cached_width'] / $image_object->getWidth() ) * $image_object->getHeight() );
		}
		else 
		{
			$data['thumb_height'] = $config->thumb_forced_width;
			$data['thumb_width'] = intval( ( $data['thumb_height'] / $image_object->getHeight() ) * $image_object->getWidth() );
			
			$data['cached_height'] = $config->cached_forced_width;
			$data['cached_width'] = intval( ( $data['cached_height'] / $image_object->getHeight() ) * $image_object->getWidth() );
		}
		return $data;
	}

	public static function generateProfilePaths( $pic, $config, $profile = 'thumb' )
	{
		switch( $profile )
		{
			case 'thumb':
				$dir_target	= $config->getThumbnailsFolder();
				break;
			case 'cached':
				$dir_target	= $config->getCachedFolder();
				break;
		}
		return $dir_target . DIR_SEPARATOR . self::getCleanName( $pic->getPathAlone() ) . '_' . self::getCleanName( $pic->getFileName() );
	}

	// Valid profiles: 'thumb', 'cached'
	public static function resizeToProfile( $pic, $config, $profile = 'thumb' )
	{
		$file_origin	= $pic->getPath();
		$img_height		= $pic->getHeightByProfile( $profile );
		$watermark		= $config->watermark;
		$file_target	= $pic->getProfilePath( $profile );
		

		// Ok, do we have to create this thumbnail/cache?
		if ( !file_exists( $file_target ) )
		{
			switch( $profile )
			{
				case 'thumb':
					$dir_target	= $config->getThumbnailsFolder();
					break;
				case 'cached':
					$dir_target	= $config->getCachedFolder();
					break;
			}
			// Is this folder already created?
			FileSystem::createFolderIfNotExist( $dir_target );

			// Generate the thumbnail/cache
			parent::resizeImage( $file_origin, $img_height, $waterMark, $config->image_quality, $file_target );
		}
	}
}
?>