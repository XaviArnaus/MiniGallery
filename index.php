<?php
/**
 * MiniGallery
 *
 * @author: Xavier Arnaus Gil
 */

define( 'BASE_DIR',				dirname(  __FILE__ ) );
define( 'GALLERY_DIR',		'gallery_engine' );
define( 'DIR_SEPARATOR',	'/' );
define( 'ENVIRONMENT',		'DEVEL' );	// DEVEL | PRODUCTION

require_once( BASE_DIR . DIR_SEPARATOR . GALLERY_DIR . DIR_SEPARATOR . 'bootstrap.php' );

$boot = new Bootstrap();
$boot->init();
?>