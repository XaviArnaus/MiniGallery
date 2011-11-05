<?php
/**
 * Class to manage all the instance configuration and handling. It is an Singleton.
 */
class Instance
{
	/**
	 * Where to store all the received params.
	 */
	static protected $_params;
	/**
	 * Where to store the loaded/generated config.
	 */
	static protected $_config;

	/**
	 * Singleton intialisation.
	 *
	 * @param object $config Object with all the configuration.
	 */
	public static function init( $config )
	{
		self::$_config = $config;
	}

	/**
	 * Returns the Config singleton.
	 *
	 * @return object The Configuration object.
	 */
	public static function getConfig()
	{
		return self::$_config;
	}

	/**
	 * Sets the params to the instance.
	 *
	 * @param object $params Object with all the params.
	 */
	public static function setParams( $params )
	{
		self::$_params = $params;
	}

	/**
	 * Returns the params singleton.
	 *
	 * @return object The Params object.
	 */
	public static function getParams()
	{
		return self::$_params;
	}
	
}
?>