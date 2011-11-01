<?php
/**
 * Class to manage each item, Picture or folder.
 */
class Item
{
	protected $slug = null;
	protected $url;
	protected $path;
	protected $path_alone;
	protected $cached_url;
	protected $title;

	public function __construct( $slug = null )
	{
		$this->slug = $slug;
	}

	public function loadData( $data = array() )
	{
		foreach( $data as $attr => $value )
		{
				$this->{$attr} = $value;
		}
	}

	public function getSlug()
	{
		if ( is_null( $this->slug ) )
		{
			$this->slug = ImageHelper::generateSlug();
		}
		return $this->slug;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getUrl( $cached = true )
	{
		return ( $cached ? $this->cached_url : $this->url );
	}

	public function getPath()
	{
		return $this->path;
	}
}
?>