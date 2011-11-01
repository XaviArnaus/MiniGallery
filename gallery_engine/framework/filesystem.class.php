<?php
/**
 * Class to manage FileSystem operations
 */
class FileSystem
{
	public static function isValidExtension( $file_path, $allowed_extensions = array() )
	{
		$exploded = explode( '.', $file_path );
		return ( in_array( strtoupper( $exploded[ count( $exploded )-1 ] ), $allowed_extensions ) );
	}

	// Caution: this is recursive.
	public static function getAlbumTree( $base_path, $config, $only_current_level = true )
	{

		// Adquiring the params.
		$include_back_folder	= $config->include_back_folder;
		$valid_extensions			= $config->valid_extensions;
		$banned_dirs					= $config->banned_folders;

		// Start to handle.
		$dir_handle = opendir($base_path);

		$tree = array();
		$tree['files'] = array();
		$tree['dirs'] = array();
		// Should we add the BACK folder?
		if( $include_back_folder 	) $tree['dirs'][]= $base_path . DIR_SEPARATOR . '..';
		// Walk all the files/dirs here.
		while ($f = readdir($dir_handle))
		{
			// Exclude dir indicators and banned folders
			if ($f != '.' && $f != '..' && !in_array( $f, $banned_dirs) )
			{
				// Is this a file and a has a valid extension?
				if( is_file( $base_path. DIR_SEPARATOR . $f ) && self::isValidExtension( $f, $valid_extensions) )
				{
					// Add it to the array
					$tree['files'][]= $base_path. DIR_SEPARATOR . $f;
				}
				// Is this a directory?
				if( is_dir( $base_path. DIR_SEPARATOR . $f ) )
				{
					// Add it to the array
					$tree['dirs'][]= $base_path. DIR_SEPARATOR . $f;

					if ( !$only_current_level )
					{
						// Let's parse this directory as well
						$subtree = self::getAlbumTree( $base_path. DIR_SEPARATOR . $f, $config, $only_current_level );

						// If we got any results, parse them.
						if ( !is_null( $subtree ) )
						{
							// Add the results of this parsing to the array.
							foreach( $subtree as $type => $data )
							{
								foreach( $data as $item )
								{
									$tree[$type][]= $item;
								}
							}
						}
					}
				}
			}
		}
		
		ksort($tree['files']);
		reset($tree['files']);
		closedir($dir_handle);

		return $tree;
	}

	public static function discoverUrl( $item, $config )
	{
		// Expecting that the Pic path is absolute, so we compare with the gallery user defined path.
		$pos = strpos( $item->getPath(), $config->gallery_path );
		if ( false === $pos )
		{
			throw new Error( 'The path defined for the gallery and the file path does not match' );
		}
		if ( $pos > 0 )
		{
			throw new Error( 'We were expecting absolute paths!' );
		}
		$length								= strlen( $config->gallery_path );
		$result = array(
			'url'	=> $config->gallery_url . substr( $item->getPath(), $length + 1 )
		);
		if ( $item instanceof Pic )
		{
			$result['thumb_url']	= $config->gallery_url . substr( $item->getProfilePath( 'thumb' ), $length + 1 );
			$result['cached_url']	= $config->gallery_url . substr( $item->getProfilePath( 'cached' ), $length + 1 );
		}
		return $result;
	}

	public static function createFolderIfNotExist( $folder )
	{
		if ( file_exists( $folder ) && is_dir( $folder ) )
		{
			return $folder;
		}
		if ( false === mkdir( $folder ) )
		{
			throw new Error( "Can not create the directory '" . $folder . "'. Check the permissions!" );
		}
	}

	public static function getRelativePathAlone( $path, $filename )
	{
		// Convert absolute to relative.
		$path = str_replace( BASE_DIR, '', $path );
		// Get only the relative path
		$pos = strpos( $path, $filename );
		return substr( $path, 0, - ( strlen( $filename ) ) );
	}

	// Unused
	public static function getRealURLfromPathRaw()
	{
		$pieces = explode('/',$_SERVER['SCRIPT_FILENAME']);
		$pieces = array_slice($pieces,0,count($pieces)-1);
		$script_path = implode('/',$pieces);
		
		$pos = strpos($this->path,$script_path) + strlen($script_path);
		
		$request_uri = $_SERVER['REQUEST_URI'];
		
		$url_pieces = array();
		foreach(explode('/',$request_uri) as $pieces)
		{
			if(strpos($pieces,'?')) break;
			else $url_pieces[]=$pieces;
		}
		$url = 'http://'.$_SERVER['SERVER_NAME'].implode('/',$url_pieces).substr($this->path,$pos);
		
		$this->url = $url;
	}
}
?>