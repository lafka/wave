<?php


function setHTTPStatus ($code) {
	$codes = array(
		200 => 'OK',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error'
	);

	if (array_key_exists($code, $codes)) {
		header("HTTP/1.1 {$code} {$codes[$code]}");
	} else {
		header("HTTP/1.1 500 Internal Server Error");
		die("could not find http header '{$code}'");
	}
}

