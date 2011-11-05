<?php
/**
 * Main Controller, who will perform inner computing and main templating.
 */
class MainController extends BaseClass
{
	protected $_params = array();

	/**
	 * Get the Instance params to the class.
	 */
	public function __construct()
	{
		$this->_params = Instance::getParams();
	}

	/**
	 * Constructs the main output.
	 */
	protected function parseMainTemplate()
	{
		// Create the main template object.
		$main_template = new Template( 'index', true );

		// Assign main bits.
		$main_template->setCss( 'style.css' );
		$main_template->assign( 'site_title', Instance::getConfig()->gallery_name );
		$main_template->assign( 'site_url', Instance::getConfig()->gallery_url );
		$main_template->assign( 'site_slogan', Instance::getConfig()->gallery_desc );
		$main_template->assign( 'content_head', 'Nom del directori actual' );
		$main_template->assign( 'footer', self::getFooterOutput() );

		return $main_template;
	}

	protected static function getFooterOutput()
	{
		// Create a template object
		$template = new Template( 'footer' );

		// Fetch and destroy the template object.
		$output = $template->fetch();
		unset( $template );

		// Return
		return $output;
	}

	public function runGallery()
	{
		// Before anything, we have to obtain the absolute path of the received relative.
		$this->_params['folder'] = Url::refillRelativePath( $this->_params['folder'] );
		$gallery	= new Gallery();
		$gallery->build( $this->_params['folder'] );
		$output		= $gallery->getContent();
		unset( $gallery );

		return $output;
	}

	public function runItem()
	{
		// Before anything, we have to obtain the absolute path of the received relative.
		$this->_params['item'] = Url::refillRelativePath( $this->_params['item'] );
		$element	= new Element();
		$element->build( $this->_params['item'] );
		$output		= $element->getContent();
		unset( $item );

		return $output;
	}

	public function build()
	{
		// Generate the main Template object.
		$main_template = $this->parseMainTemplate();

		// Obtain the needed partial.
		switch( $this->_params['type'] )
		{
			case 'folder':
				$output = $this->runGallery();
				break;
			case 'item':
				$output = $this->runItem();
				break;
		}

		// Assign the obtained content.
		$main_template->assign( 'content_body', $output );
		
		$output = $main_template->fetch();

		return $output;
	}
}
?>