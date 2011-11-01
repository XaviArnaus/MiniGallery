<?php
/**
 * Class to manage each picture
 */
class Pic extends Item
{
	protected $filename;
	protected $width;
	protected $height;
	protected $mime;
	protected $is_landscape;
	protected $thumb_path;
	protected $thumb_url;
	protected $thumb_height;
	protected $thumb_width;
	protected $cached_path;
	protected $cached_url;
	protected $cached_height;
	protected $cached_width;

	public function getThumbUrl()
	{
		return $this->thumb_url;
	}

	public function getProfilePath( $profile )
	{
		return $this->{$profile . '_path'};
	}

	public function getCachedPath()
	{
		return $this->thumb_path;
	}

	public function getHeight()
	{
		return $this->height;
	}

	public function getWidth()
	{
		return $this->width;
	}

	public function isLandscape()
	{
		return $this->is_landscape;
	}

	public function getMimeType()
	{
		return $this->mime;
	}

	public function getFileName()
	{
		return $this->filename;
	}

	public function getHeightByProfile( $profile )
	{
		return $this->{$profile . '_height'};
	}

	public function getPathAlone()
	{
		if ( is_null(  $this->path_alone) )
		{
			$this->path_alone = FileSystem::getRelativePathAlone( $this->path, $this->filename );
		}
		return $this->path_alone;
	}
}
?>