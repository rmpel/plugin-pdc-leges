<?php

namespace OWC\PDC\Leges;

class Autoloader
{

	/**
	 * Autoloader constructor.
	 * PSR autoloader
	 */
	public function __construct()
	{
		spl_autoload_register(function ($className) {
			$baseDir = __DIR__.'/src/';
			$namespace = str_replace("\\", "/", __NAMESPACE__);
			$className = str_replace("\\", "/", $className);
			$class = $baseDir.(empty($namespace) ? "" : $namespace."/").$className.'.php';
			$class = str_replace('/OWC/PDC/Leges/OWC/PDC/Leges/', '/Leges/', $class);
			if (file_exists($class)) {
				require_once($class);
			}
		});
	}
}
