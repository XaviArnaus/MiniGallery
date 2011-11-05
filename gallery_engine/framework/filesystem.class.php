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
	public static function getAlbumTree( $base_path, $include_back_folder, $only_current_level = true )
	{
		// Adquiring the params.
		$valid_extensions			= Instance::getConfig()->valid_extensions;
		$banned_dirs					= Instance::getConfig()->banned_folders;

		// Start to handle.
		$dir_handle = opendir($base_path);

		$tree = array();
		$tree['files'] = array();
		$tree['dirs'] = array();
		// Should we add the BACK folder?
		if( $include_back_folder ) $tree['dirs'][]= $base_path . DIR_SEPARATOR . '..';
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
				if( is_dir( $base_path. DIR_SEPARATOR . $f ) && !self::isBannedDir( $f ) )
				{
					// Add it to the array
					$tree['dirs'][]= $base_path. DIR_SEPARATOR . $f;

					if ( !$only_current_level )
					{
						// Let's parse this directory as well
						$subtree = self::getAlbumTree( $base_path. DIR_SEPARATOR . $f, $include_back_folder, $only_current_level );

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

	public static function isBannedDir( $current_dir )
	{
		$banned_dirs = Instance::getConfig()->banned_folders;
		// First let us know if this folder starts with a dot (so should be hidden)
		if ( '.' === $current_dir[0] && '..' !== $current_dir )
		{
			return true;
		}
		// Now let's see the list of banned dirs
		foreach( $banned_dirs as $banned )
		{
			if ( $current_dir === $banned )
			{
				return true;
			}
		}
		// Not found
		return false;
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

	public static function stripTailingSlash( $path )
	{
		if ( '/' === $path[ strlen( $path ) - 1 ] )
		{
			return substr( $path, 0, -1 );
		}
		return $path;
	}
}
?>