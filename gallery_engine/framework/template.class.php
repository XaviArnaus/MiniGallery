<?php
/**
 * Class to manage the template.
 */
class Template extends BaseClass
{
	private $_template_path;
	private $_template_images_path;
	private $_template_css_path;
	private $_template_js_path;
	private $_template_images_url;
	private $_template_css_url;
	private $_template_js_url;
	private $_assigns	= array();
	private $_output;

	protected $template_name;
	protected $_css		= array();
	protected $_js		= array();

	protected $is_index;

	public function __construct( $template_name, $is_index = false )
	{
		// Adquire the config.
		$config												= Instance::getConfig();
		// Define a common separator.
		$url_sep											= '/';
		// Generate the properties.
		$this->template_name					= $template_name;
		$this->_template_path					= $config->gallery_path . DIR_SEPARATOR . $config->gallery_folder . DIR_SEPARATOR . $config->template_folder;
		$this->_template_images_path	= $this->_template_path . DIR_SEPARATOR . $config->template_images_folder;
		$this->_template_css_path			= $this->_template_path . DIR_SEPARATOR . $config->template_css_folder;
		$this->_template_js_path			= $this->_template_path . DIR_SEPARATOR . $config->template_js_folder;
		$this->_template_images_url		= $config->gallery_url . $config->gallery_folder . $url_sep . $config->template_folder . $url_sep . $config->template_images_folder . $url_sep;
		$this->_template_css_url			= $config->gallery_url . $config->gallery_folder . $url_sep . $config->template_folder . $url_sep . $config->template_css_folder . $url_sep;
		$this->_template_js_url				= $config->gallery_url . $config->gallery_folder . $url_sep . $config->template_folder . $url_sep . $config->template_js_folder . $url_sep;
		$this->is_index								= $is_index;
	}

	public function assign( $assign_name, $assign_value )
	{
		$this->_assigns[$assign_name] = $assign_value;
	}

	public function fetch()
	{
		// Can we read the template file?
		$template = $this->_template_path . DIR_SEPARATOR . $this->template_name . '.template.php';

		if ( !is_readable( $template ) )
		{
			throw new Error( "can't read the template '" . $template . "'" );
		}

		// Let's start.
		$this->_output = file_get_contents( $template );
		// Assign the head if needed.
		if ( $this->is_index )
		{
			$this->_output = str_replace( '{{ head }}', $this->generateHead(), $this->_output );
		}
		// Apply the body assignments.
		$this->generateBody();

		// Return what we have.
		return $this->_output;
	}

	protected function generateHead()
	{
		return $this->_compileCss() . $this->_compileJs();
	}
	protected function generateBody()
	{
		// This is quite different. WE should apply the assignments.
		foreach ( $this->_assigns as $tag => $value )
		{
			$this->_output = str_replace( '{{ ' . $tag . ' }}', $value, $this->_output );
		}
	}
	private function _compileJs()
	{
		$js_list = '';
		foreach ( $this->_js as $item )
		{
			$js_list = "<script type=\"text/javascript\" src=\"" . $this->_template_js_url . $item . "\"></script>\n";
		}
		return $js_list;
	}
	private function _compileCss()
	{
		$css_list = '';
		foreach ( $this->_css as $item )
		{
			$css_list = "<link rel=\"stylesheet\" href=\"" . $this->_template_css_url . $item[0] . "\" type=\"text/css\" media=\"" . $item[1] . "\" />";
		}
		return $css_list;
	}

	public function setCss( $css_file, $mode = 'screen' )
	{
		if ( !$this->is_index )
		{
			throw new Error( "You can't add CSS files to a non-index template" );
		}
		$this->_css[] = array( $css_file, $mode );
	}
	public function setJs( $js_file )
	{
		if ( !$this->is_index )
		{
			throw new Error( "You can't add CSS files to a non-index template" );
		}
		$this->_js[] = $js_file;
	}
}
?>