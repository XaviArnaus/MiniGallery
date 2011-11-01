<?php
/**
 * Base class to be extended.
 */
class BaseClass
{
	protected $_config = null;
	protected $_collection = null;
	protected $_error_message	= null;
	protected $_exception			= null;

	public function getConfig()
	{
		return $this->_config;
	}

	public function isError()
	{
		return ( is_null( $this->_error_message ) );
	}

	public function getErrorMessage()
	{
		return $this->_error_message;
	}

	public function getException()
	{
		return $this->_exception;
	}
}

function dump( $var )
{
	echo "<pre>";
	var_dump( $var );
	echo "</pre>";
}

?>