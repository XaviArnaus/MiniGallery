<?php
/**
 * Main class to manage requests and answers
 */

class Dispatcher extends BaseClass
{
	protected $_params = array();

	/**
	 * Get the Instance params to the class.
	 */
	public function __construct()
	{
		$this->_params = Instance::getParams();
	}

	public function runGallery()
	{
		// Before anything, we have to obtain the absolute path of the received relative.
		$this->_params['folder'] = Url::refillRelativePath( $this->_params['folder'] );
		$gallery	= new Gallery( $this->_params['folder'] );
		$output		= $gallery->getContent();
		unset( $gallery );

		return $output;
	}

	public function runItem()
	{
		// Before anything, we have to obtain the absolute path of the received relative.
		$this->_params['item'] = Url::refillRelativePath( $this->_params['item'] );
		$element	= new Element( $this->_params['item'] );
		$output		= $element->getContent();
		unset( $item );

		return $output;
	}

	public function run()
	{
		switch( $this->_params['type'] )
		{
			case 'folder':
				header( 'Content-Type: text/html; charset=UTF-8' );
				print $this->runGallery();
				break;
			case 'item':
				header( 'Content-Type: text/html; charset=UTF-8' );
				print $this->runItem();
				break;
		}
	}
	
}
?>