<?php
namespace Fwt\Utils;

class Filesystem
{
	public static function find ( $args )
	{
		exec( "find {$args}", $output );
		return $output;
	}
}
