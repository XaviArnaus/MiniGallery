<?php
/**
 * Main class to manage requests and answers
 */

define( 'CONFIG_DIR', GALLERY_DIR . DIR_SEPARATOR . 'config' );

require_once( BASE_DIR . DIR_SEPARATOR . FRAMEWORK_DIR . DIR_SEPARATOR . 'baseclass.class.php' );

// Autoload has to be outside the class
function __autoload( $class_name )
{
	$to_include = BASE_DIR . DIR_SEPARATOR . FRAMEWORK_DIR . DIR_SEPARATOR . strtolower( $class_name ) . '.class.php';

	// This if is probably super slow.
	if ( is_readable( $to_include ) )
	{
		require_once( $to_include );
	}
	{
		sprintf( "Cannot load the required class '" . $to_include . "'" );
	}
}

class Dispatcher extends BaseClass
{
	public function __construct()
	{}

	public function loadConfig( $config_file = null )
	{
		if ( !is_null( $config_file ) && is_readable( $config_file ) )
		{
			$config_file = BASE_DIR . DIR_SEPARATOR . FRAMEWORK_DIR . DIR_SEPARATOR . $config_file . '.config.php';
		}
		else
		{
			$config_file = null;
		}
		try
		{
			$this->_config = new Config( $config_file );
		}
		catch( Exception $e )
		{
			AlertHelper::showError( $e->getMessage() );
		}
	}

	public function runGallery()
	{
		$gallery	= new Gallery( $this->getConfig() );
		$output		= $gallery->getContent();
		unset( $gallery );

		return $output;
	}

	public function run( $type = 'gallery' )
	{
		try
		{
			switch( $type )
			{
				case 'gallery':
					header( 'Content-Type: text/html; charset=UTF-8' );
					print $this->runGallery();
					break;
			}
		}
		catch( Exception $e )
		{
			AlertHelper::showError( "Run Error.", $e );
		}
	}

	
}
?>