<?php

namespace OWC\PDC\Leges\Admin;

use Exception;
use OWC\PDC\Leges\Plugin\BasePlugin;

class Admin
{

	/**
	 * Instance of the plugin.
	 *
	 * @var $plugin \OWC\PDC\Leges\Plugin
	 */
	protected $plugin;

	/**
	 * Instance of the actions and filters loader.
	 *
	 * @var $plugin \OWC\PDC\Leges\Plugin\Loader
	 */
	protected $loader;

	/**
	 * Admin constructor.
	 *
	 * @param \OWC\PDC\Leges\Plugin\BasePLugin $plugin
	 */
	public function __construct(BasePlugin $plugin)
	{
		$this->plugin = $plugin;
		$this->loader = $plugin->loader;
	}

	/**
	 * Boot up the frontend
	 * @throws Exception
	 */
	public function boot()
	{

	}
}
