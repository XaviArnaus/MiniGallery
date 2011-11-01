<?php
/**
 * MiniGallery
 *
 * @author: xavier@arnaus.net
 */

define( 'BASE_DIR',				dirname(  __FILE__ ) );
define( 'GALLERY_DIR',		'gallery_engine' );
define( 'DIR_SEPARATOR',	'/' );
define( 'FRAMEWORK_DIR',	GALLERY_DIR . DIR_SEPARATOR . 'framework' );

require_once( BASE_DIR . DIR_SEPARATOR . FRAMEWORK_DIR . DIR_SEPARATOR . 'dispatcher.class.php' );

$dispatcher = new Dispatcher();
$dispatcher->loadConfig( "default.config" );
$dispatcher->run();
?>