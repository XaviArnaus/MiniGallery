<?php
/**
 * Main class to manage requests and answers
 */

class Dispatcher extends BaseClass
{
	/**
	 * This runs the application.
	 */
	public function run()
	{
		// Instantiate the main controller.
		$controller = new MainController();
		// Define the header.
		header( 'Content-Type: text/html; charset=UTF-8' );
		// Build the controller and print the obtained output.
		print $controller->build();
	}
}
?>