<?php

namespace Fwt\Utils;

class Debug
{
	private static $messages = array();

	public static function add ( $message, $label = '' )
	{
		static::$messages[] = ( !empty($label) ? "<b>{$label}: </b>" : '' ) . $message;
	}

	public static function output ( $format = "%s\n")
	{
		foreach ( static::$messages as $msg )
		{
			printf( $format, $msg );
		}
	}

	public static function globals ()
	{
		echo "<div id=\"debug\">\r\n";

			echo "<div id=\"session\">\r\n";
				echo "<h4>Session</h4>\r\n";
				var_dump( $_SESSION );
			echo "</div>\r\n";

			echo "<div id=\"cookies\">\r\n";
				echo "<h4>Cookies</h4>\r\n";
				var_dump( $_COOKIES );
			echo "</div>\r\n";

			echo "<div id=\"post\">\r\n";
				echo "<h4>Post</h4>\r\n";
				var_dump( $_POST );
			echo "</div>\r\n";

		echo "</div>\r\n";
	}
}
