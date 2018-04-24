<?php

namespace OWC\Leges;

use OWC\Leges\Plugin\BasePlugin;

class Plugin extends BasePlugin
{

	/**
	 * Name of the plugin.
	 *
	 * @var string
	 */
	const NAME = 'pdc-leges';

	/**
	 * Version of the plugin.
	 * Used for setting versions of enqueue scripts and styles.
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Boot the plugin.
	 * Called on plugins_loaded event
	 */
	public function boot()
	{
		$this->config->setPluginName(self::NAME);
		$this->config->setFilterExceptions(['core']);
		$this->config->boot();

		$this->loader->addAction('init', $this->config, 'filter', 9);
		$this->loader->register();
	}

	/**
	 * Get settings from config file, and allow to hook into it.
	 *
	 * @return array
	 */
	public function getSettings()
	{
		return $this->config->get('core.settings');
	}

}
