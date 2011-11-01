<?php
/**
 * Helper to show messages
 */
class AlertHelper extends BaseClass
{
	public static function showError( $message, $exception = null )
	{
		echo "<br /><b>".$message."</b><br />";
		if ( !is_null( $exception ) )
		{
			//self::_htmlDumpBox( $exception );
			self::_htmlDumpException( $exception );
		}
	}

	private static function _htmlDumpException( $e )
	{
		echo "<pre><b>" . $e->getMessage() . "</b><br />";
		echo '<i>' . $e->getFile() . "[".$e->getLine()."]<i><br /><br />";
		foreach( $e->getTrace() as $item )
		{
			echo $item['class'] . $item['type'] . $item['function'] . "<br />";
			echo "<li><i>" . $item['file'] . "[" . $item['line'] . "]</i><br />";
		}
		echo '</pre>';
	}
	
	private static function _htmlDumpBox( $variable, $height="500px" )
	{
		echo "<pre style=\"border: 1px solid #000; height: " . $height ."; overflow: auto; margin: 0.5em;\">";
		var_dump($variable);
		echo "</pre>\n";
	}
}
?>