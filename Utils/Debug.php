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
}
