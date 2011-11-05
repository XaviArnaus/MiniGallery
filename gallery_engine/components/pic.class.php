<?php
/**
 * Class to manage each picture
 */
class Pic extends Item
{
	protected $width;
	protected $height;
	protected $mime;
	protected $is_landscape;

	protected $thumb_height;
	protected $thumb_width;
	protected $cached_height;
	protected $cached_width;

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

	public function getHeightByProfile( $profile )
	{
		return $this->{$profile . '_height'};
	}
}
?>