<?php

namespace OWC\Leges\Network;

use Exception;
use OWC\Leges\Plugin\BasePlugin;
use OWC\Leges\Plugin\ServiceProvider;

class Network
{

	/**
	 * Instance of the plugin.
	 *
	 * @var $plugin \OWC\Leges\Plugin
	 */
	protected $plugin;

	/**
	 * Instance of the actions and filters loader.
	 *
	 * @var $plugin \OWC\Leges\Plugin\Loader
	 */
	protected $loader;

	/**
	 * Admin constructor.
	 *
	 * @param \OWC\Leges\Plugin\BasePLugin $plugin
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
		$this->bootServiceProviders();
	}

	/**
	 * Boot service providers
	 * @throws Exception
	 */
	private function bootServiceProviders()
	{
		$services = $this->plugin->config->get('core.providers.network');

		foreach ( $services as $service ) {
			$service = new $service($this->plugin);

			if ( ! $service instanceof ServiceProvider ) {
				throw new Exception('Provider must extend ServiceProvider.');
			}

			/**
			 * @var \OWC\Leges\Plugin\ServiceProvider $service
			 */
			$service->register();
		}
	}

}
