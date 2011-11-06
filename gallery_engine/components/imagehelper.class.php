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

	public static function getCleanName( $file_name, $for_slug = false, $make_short = true )
	{
		// @todo: Remember that preg_replace is slower than str_replace!
		$name = '';
		if ( $for_slug )
		{
			$name = preg_replace("/[^a-zA-Z0-9]/", "", strtolower(  $file_name ) );
		}
		else
		{
			// Strip the extension.
			$name = $file_name;
			if ( strpos( $name, '.' ) > 0 && '..' !== $name )
			{
				$name = substr( $name, 0, strrpos( $name, '.' ) );
			}
			// Cut too long names.
			if ( $make_short && strlen( $name ) > 20 )
			{
				$first = substr( $name, 0, 5 );
				$last = substr( $name, strlen( $name ) - 5, 5 );
				$name = $first . '...' . $last;
			}
			// Not dashes at the begining or end.
			$name = preg_replace("/(^-|-$)/", "", $name );
		}
		return $name;
	}

	public static function getProportionalSizes( $image_object )
	{
		$data = array();
		if($image_object->landscape)
		{
			$data['thumb_width'] = Instance::getConfig()->thumb_forced_width;
			$data['thumb_height'] = intval( ( $data['thumb_width'] / $image_object->getWidth() ) * $image_object->getHeight() );
			
			$data['cached_width'] = Instance::getConfig()->cached_forced_width;
			$data['cached_height'] = intval( ( $data['cached_width'] / $image_object->getWidth() ) * $image_object->getHeight() );
		}
		else 
		{
			$data['thumb_height'] = Instance::getConfig()->thumb_forced_width;
			$data['thumb_width'] = intval( ( $data['thumb_height'] / $image_object->getHeight() ) * $image_object->getWidth() );
			
			$data['cached_height'] = Instance::getConfig()->cached_forced_width;
			$data['cached_width'] = intval( ( $data['cached_height'] / $image_object->getHeight() ) * $image_object->getWidth() );
		}
		return $data;
	}

	public static function generateProfilePaths( $pic, $profile = 'thumb' )
	{
		switch( $profile )
		{
			case 'thumb':
				$dir_target	= Instance::getConfig()->getThumbnailsFolder();
				break;
			case 'cached':
				$dir_target	= Instance::getConfig()->getCachedFolder();
				break;
		}
		return $dir_target . DIR_SEPARATOR . self::getCleanName( $pic->getRelativePathOnly(), true ) . '_' . self::getCleanName( $pic->getFileName(), true );
	}

	public static function getImageType( $pic )
	{
		switch( $pic->getMimeType() )
		{
			case 'image/png':
				return 'png';
			case 'image/jpeg':
			case 'image/pjpeg':
				return 'jpg';
			case 'image/gif':
				return 'gif';
			case 'image/bmp':
			case 'image/x-windows-bmp':
				return 'bmp';
		}

		// So Mime types are not enough? 
		if (strtolower(substr($file, strlen($file)-3)) == 'jpg' || strtolower(substr($file, strlen($file)-4)) == 'jpeg')
		{
			return 'jpg';
		}
		else if (strtolower(substr($file, strlen($file)-3)) == 'gif')
		{
			return 'gif';
		}
		else if (strtolower(substr($file, strlen($file)-3)) == 'png')
		{
			return 'png';
		}
		else if (strtolower(substr($file, strlen($file)-3)) == 'bmp')
		{
			return 'bmp';
		}
	}

	// Valid profiles: 'thumb', 'cached'
	public static function resizeToProfile( $pic, $profile = 'thumb' )
	{
		$file_origin	= $pic->getPath();
		$img_height		= $pic->getHeightByProfile( $profile );
		$watermark		= Instance::getConfig()->watermark;
		$file_target	= $pic->getProfilePath( $profile );
		

		// Ok, do we have to create this thumbnail/cache?
		if ( !file_exists( $file_target ) )
		{
			switch( $profile )
			{
				case 'thumb':
					$dir_target	= Instance::getConfig()->getThumbnailsFolder();
					break;
				case 'cached':
					$dir_target	= Instance::getConfig()->getCachedFolder();
					break;
			}
			// Is this folder already created?
			FileSystem::createFolderIfNotExist( $dir_target );

			// Generate the thumbnail/cache
			parent::resizeImage( $file_origin, self::getImageType( $pic ), $img_height, $waterMark, Instance::getConfig()->image_quality, $file_target );
		}
	}
}
?>