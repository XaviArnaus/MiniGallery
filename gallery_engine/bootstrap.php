<?php
/**
 * Class to handle some pre-init configurations
 */

define( 'FRAMEWORK_DIR',	GALLERY_DIR . DIR_SEPARATOR . 'framework' );
define( 'COMPONENT_DIR',	GALLERY_DIR . DIR_SEPARATOR . 'components' );
define( 'CONFIG_DIR', GALLERY_DIR . DIR_SEPARATOR . 'config' );

class Bootstrap
{
	public function __construct()
	{}

	/**
	 * This is the Initialisation method. This is the first code to be run by the application.
	 *
	 * @param string $config_file Config file to be loaded. Optional.
	 */
	public function init( $config_file = "default" )
	{
		// Make some adjustments depending on what environment are we.
		$this->setByEnvironment();

		// Load the configuration to the Instance singleton.
		$config = $this->loadConfig( $config_file );
		Instance::init( $config );

		// Load the params.
		$params = Url::parseUrl( $_GET );
		Instance::setParams( $params );

		// Check the precence of needed data.
		$checks	= Install::checkStructure( $config_file );
		if ( Install::shouldShowInstall( $checks ) )
		{
			Install::show( $checks, $config_file );
			return;
		}

		// Execute the dispatcher.
		try
		{
			// This is not belonging to the framework. This acts as the main user controller.
			$dispatcher = new Dispatcher();
			$dispatcher->run();
		}
		catch( Exception $e )
		{
			AlertHelper::showError( "Run Error.", $e );
		}
	}

	/**
	 * Do some stuff depending on what environment are we.
	 */
	protected function setByEnvironment()
	{
		switch( ENVIRONMENT )
		{
			case PRODUCTION:
				ini_set('display_errors','Off');
				break;
			case DEVEL:
			default:
				break;
		}
	}

	/**
	 * Load the defined configuration file
	 *
	 * @param string $config_file Config file to be loaded. Optional.
	 * @return Config Object with the default/loaded configuration.
	 */
	protected function loadConfig( $config_file = null )
	{
		if ( is_null( $config_file ) )
		{
			$config_file = null;
		}
		else
		{
			$config_file = CONFIG_DIR . DIR_SEPARATOR . $config_file . '.config.php';
			if ( !is_readable( $config_file ) )
			{
				$config_file = null;
			}
		}

		try
		{
			return new Config( $config_file );
		}
		catch( Exception $e )
		{
			AlertHelper::showError( $e->getMessage() );
		}
	}
}

// Autoload has to be outside the class
function __autoload( $class_name )
{
	$framework = BASE_DIR . DIR_SEPARATOR . FRAMEWORK_DIR . DIR_SEPARATOR . strtolower( $class_name ) . '.class.php';
	$component = BASE_DIR . DIR_SEPARATOR . COMPONENT_DIR . DIR_SEPARATOR . strtolower( $class_name ) . '.class.php';

	// First check if we are calling a Component
	if ( is_readable( $component ) )
	{
		require_once( $component );
	}
	elseif( is_readable( $framework ) )
	{
		require_once( $framework );
	}
	else
	{
		sprintf( "Cannot load the required class '" . $class_name . "'" );
	}
}
?>