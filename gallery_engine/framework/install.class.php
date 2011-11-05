<?php
/**
 * Class to help the user to perform the installing processes.
 */
class Install
{
	/**
	 * This checks the presence of previous executions of the app
	 *
	 * @return array List of checks, on each: TRUE if all the needed data is set, FALSE otherwise.
	 */
	public static function checkStructure( $config_file )
	{
		$result = array();
		// Check if .htaccess is created.
		$result['htaccess'] = self::checkHtaccessPresent();
		// Check if the defined config file is created.
		$result['config']		= self::checkConfigPresent( $config_file );
		// Check if thumbs directory is created.
		$result['thumbs']		= self::checkThumbsPresent();
		// Check if cached directory is created.
		$result['cached']		= self::checkCachedPresent();
		// Check if the gallery directory is writable to be able to generate thumbnails.
		$result['writable']	= self::checkGalleryWritable();
	
		return $result;
	}

	public static function show( $checks, $config_file )
	{
		$template = new Template( 'install' );

		// First assign the results of the checkings.
		foreach( $checks as $check => $result )
		{
			$template->assign( 'result_' . $check, ( $result ? 'green' : 'red' ) );
		}
		// Other assignments.
		$template->assign( 'htaccess_subdir', self::getSubDir() );
		$template->assign( 'htaccess_content', self::generateHtaccess() );
		$template->assign( 'config_file', $config_file );
		$template->assign( 'config_dir', CONFIG_DIR );
		$template->assign( 'config_content', self::generateConfig() );
		$template->assign( 'thumbs_dir', Instance::getConfig()->getThumbnailsFolder() );
		$template->assign( 'cache_dir', Instance::getConfig()->getCachedFolder() );
		$template->assign( 'gallery_dir', GALLERY_DIR );
		$template->assign( 'gallery_has_rights', ( $checks['writable'] ? '' : ' NOT' ) );

		header( 'Content-Type: text/html; charset=UTF-8' );
		print $template->fetch();
	}

	public static function generateHtaccess()
	{
		$template = new Template( 'htaccess' );

		$template->assign( 'subdir', self::getSubDir() );

		return $template->fetch();
	}

	/**
	 * Generated a config file with all the current params using a template.
	 */
	public static function generateConfig()
	{
		$template = new Template( 'config' );

		$params		= get_class_vars( get_class( Instance::getConfig() ) );
		// Some corrections
		$params['gallery_url']					= Instance::getConfig()->gallery_url;
		$content	= '';
		foreach( $params as $param_name => $param_value )
		{
			$content.= "\t'" . $param_name . "' => " . (
				is_array($param_value) ?
				"array( '" . implode( "','", $param_value ) . "' )" :
						"'" . $param_value . "'"
				) . ",\n";
		}

		$template->assign( 'params', $content );

		return $template->fetch();
	}

	public static function shouldShowInstall( $checks )
	{
		return !( $checks['htaccess'] && $checks['config'] && $checks['thumbs'] && $checks['cached'] );
	}

	public static function getSubDir()
	{
		return FileSystem::stripTailingSlash( $_SERVER['REQUEST_URI'] );
	}

	protected static function checkGalleryWritable()
	{
		return is_writable( GALLERY_DIR );
	}

	protected static function checkCachedPresent()
	{
		return file_exists( Instance::getConfig()->getCachedFolder() );
	}

	protected static function checkThumbsPresent()
	{
		return file_exists( Instance::getConfig()->getThumbnailsFolder() );
	}

	protected static function checkConfigPresent( $config_file )
	{
		return is_readable( CONFIG_DIR . DIR_SEPARATOR . $config_file . '.config.php' );
	}

	protected static function checkHtaccessPresent()
	{
		return is_readable( '.htaccess' );
	}
}
?>