<?php
namespace Wave\Package;

use stdClass, CachingIterator, ArrayIterator;

abstract class Configurable extends \Wave\Package {

	public $config;
	protected $env;

	/**
	 * Construct a new package based on path
	 *
	 * @param string $path The relative path to package from current working directory
	 * @return \Wave\Package
	 */
	public function __construct ($path) {
		parent::__construct($path);

		$this->config = new stdClass();
		if (!array_key_exists( 'WAVE_ENV', $_SERVER )) {
			$_SERVER['WAVE_ENV'] = 'prod';
		}

		$this->env = $_SERVER['WAVE_ENV'];

		if (false === ($file = $this->autoloader->load('resources/config', '.ini', true))) {
			__debug('No configuration found for ' . $this->package . ' (looking for resources/config.ini)');
			return;
		}

		$this->parseIniFile($file);
		
		if (false !== ($file = $this->autoloader->load('resources/config.' . gethostname(), '.ini', true, 0))) {
			$this->parseIniFile($file, false);
		}

		unset($ini);
	}

	protected function parseIniFile ($file, $grouped = true) {
		$ini = parse_ini_file($file, $grouped);

		if (!$grouped)
			$ini = array($this->env => $ini);
		
		// Set default from prod
		if ($this->env !== 'prod') {
			foreach ($ini['prod'] as $k => $v) {
				$ini[$this->env][$k] = $v;
			}
		}

		foreach ($ini[$this->env] as $k => $v) {
			$parts = explode('.', $k);
			$ref   = $this->config;

			$iter = new CachingIterator(new ArrayIterator($parts));

			foreach ($iter as $i) {
				if ($iter->hasNext()) {
					if (!isset($ref->{$i}))
						$ref->{$i} = new stdClass;
					$ref = $ref->{$i};
				} else {
					if (!is_numeric($v)) {
						$ref->{$i} = $v;
					} else if(is_float($v)) {
						$ref->{$i} = (float) $v;
					} else {
						$ref->{$i} = (int) $v;
					}
				}
			}

			unset($iter);
		}
	}

	/**
	 * Retrieve config variable
	 *
	 * @param string $key The key to fetch, dots seperates groups
	 * @param boolean $strict Whetever to throw exception if not found
	 * @return mixed|null The config variable or null if not found
	 * @throws \OutOfBoundsException If config value is not found and $strict is not false
	 */
	public function config ($key) {
		$parts = explode('.', $key);
		$ref   = $this->config;

		$iter = new CachingIterator(new ArrayIterator($parts));

		foreach ($iter as $i) {
			if ($iter->hasNext()) {
				if (isset($ref->{$i}))
					$ref = $ref->{$i};
			} else {
				if (isset($ref->{$i}))
					return $ref->{$i};
			}
		}

		if ($strict)
			throw new OutOfBoundsException("Configuration key {$key} was not found");
		return null;
	}
}