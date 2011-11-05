<?php
/**
 * Class to generate a working .htaccess
 */
class Htaccess
{
	protected static $filename = '.htaccess';

	public static function generate()
	{
		$template = new Template( 'htaccess' );

		$template->assign( 'subdir', self::getSubDir() );

		return $template->fetch();
	}

	public static function exists()
	{
		return file_exists( self::$filename );
	}

	public static function getSubDir()
	{
		return FileSystem::stripTailingSlash( $_SERVER['REQUEST_URI'] );
	}
}
?>