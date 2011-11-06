<?php
/**
 * Class to manage each item, Picture or folder.
 */
class Item
{
	protected $slug = null;
	protected $title;
	protected $filename;

	protected $url_access_name;
	protected $relative_url;
	protected $url;
	protected $path;

	protected $thumb_path;
	protected $thumb_url;
	protected $thumb_relative_url;
	protected $cached_path;
	protected $cached_url;
	protected $cached_relative_url;
	
	protected $relative_path_only;

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

	public function getFileName()
	{
		return $this->filename;
	}
	public function getUrlAccessName()
	{
		return $this->url_access_name;
	}

	public function getThumbUrl( $relative = false )
	{
		if ( $relative )
		{
			return $this->thumb_relative_url;
		}
		return $this->thumb_url;
	}
	public function getCachedUrl( $relative = false )
	{
		if ( $relative )
		{
			return $this->cached_relative_url;
		}
		return $this->cached_url;
	}
	public function getRealUrl( $relative = false )
	{
		if ( $relative )
		{
			return $this->relative_url;
		}
		return $this->url;
	}

	public function getPath()
	{
		return $this->path;
	}
	public function getProfilePath( $profile )
	{
		return $this->{$profile . '_path'};
	}

	public function getRelativePathOnly()
	{
		if ( is_null( $this->relative_path_only) )
		{
			$this->relative_path_only = Url::getRelativeWithoutFile( $this->path, $this->filename );
		}
		return $this->relative_path_only;
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
}
?>