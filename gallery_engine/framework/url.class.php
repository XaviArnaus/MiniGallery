<?php
/**
 * Class to manage URLs
 */
class Url extends BaseClass
{
	public static function itemLink( $item )
	{
		$_url	= $item->getRealUrl( true );
		$url	= Instance::getConfig()->gallery_url . ( '/' === $_url ? '' : $item->getUrlAccessName() . $_url );
		return $url;
	}

	public static function discoverCleanURL()
	{
		// Data about the URL
		$self_pieces	= explode( '/', $_SERVER['PHP_SELF'] );
		$last_piece		= $self_pieces[count( $self_pieces )-1];
		// Data about the ScriptFile
		$scr_pieces		= explode( '/', $_SERVER['SCRIPT_FILENAME'] );
		$script_name	= $scr_pieces[count( $scr_pieces )-1];
		// If self contanins the script file clean it.
		if ( strpos( $_SERVER['PHP_SELF'], $script_name ) > -1 )
		{
			$post_domain = substr( $_SERVER['PHP_SELF'], 0, -(strlen( $script_name )) );
		}
		else
		{
			$post_domain = $_SERVER['PHP_SELF'];
		}
		return 'http://' . $_SERVER['HTTP_HOST'] . $post_domain;
	}

	public static function getRelative( $path )
	{
		// Convert absolute to relative.
		$path = str_replace( BASE_DIR, '', $path );
		return $path;
	}

	public static function getRelativeWithoutFile( $path, $filename )
	{
		// Convert absolute to relative.
		$path	= self::getRelative( $path );
		// Get only the relative path
		$pos	= strpos( $path, $filename );
		return substr( $path, 0, - ( strlen( $filename ) ) );
	}

	public static function discoverUrl( $path )
	{
		// Expecting that the Pic path is absolute, so we compare with the gallery user defined path.
		$pos = strpos( $path, Instance::getConfig()->gallery_path );
		if ( false === $pos )
		{
			throw new Error( 'The path defined for the gallery and the file path does not match' );
		}
		if ( $pos > 0 )
		{
			throw new Error( 'We were expecting absolute paths!' );
		}
		$length	= strlen( Instance::getConfig()->gallery_path );

		return Instance::getConfig()->gallery_url . substr( $path, $length + 1 );
	}

	public static function discoverPicUrls( $item )
	{
		$result = array(
			'url'	=> self::discoverUrl( $item->getPath() )
		);

		$length	= strlen( Instance::getConfig()->gallery_path );

		$result['thumb_url']	= Instance::getConfig()->gallery_url . substr( $item->getProfilePath( 'thumb' ), $length + 1 );
		$result['cached_url']	= Instance::getConfig()->gallery_url . substr( $item->getProfilePath( 'cached' ), $length + 1 );

		return $result;
	}

	private static function _understandRequest( $path_request )
	{
		$request_pieces = explode( '/', $path_request );
		$action					= $request_pieces[0];
		$param					= implode( '/', array_slice( $request_pieces, 1 ) );
		return array(
			'type'	=> $action,
			'param'	=> $param
		);
	}

	public static function parseUrl( $params_get )
	{
		// Here we should filter for XSS!!
		$params = $params_get;

		if ( isset( $params['request'] ) && '' !== $params['request'] )
		{
			$request										= self::_understandRequest( $params['request'] );
			$params['type']							= $request['type'];
			$params[ $request['type'] ]	= $request['param'];
		}
		else
		{
			// By default, show the gallery.
			$params['folder'] = '';
			$params['type']		= 'folder';
		}

		return $params;
	}

	public static function refillRelativePath( $relative_path )
	{
		$separator = '/';
		if ( '/' === $relative_path[0] )
		{
			$separator = '';
		}
		return Instance::getConfig()->gallery_path . $separator . $relative_path;
	}
}
?>