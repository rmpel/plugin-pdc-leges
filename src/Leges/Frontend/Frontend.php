<?php

namespace OWC\Leges\Frontend;

use Exception;
use OWC\Leges\Plugin\BasePlugin;
use OWC\Leges\Plugin\ServiceProvider;

class Frontend
{

	/**
	 * Instance of the plugin.
	 *
	 * @var \OWC\Leges\Plugin $plugin
	 */
	protected $plugin;

	/**
	 * Instance of the actions and filters loader.
	 *
	 * @var \OWC\Leges\Plugin\Loader $loader
	 */
	protected $loader;

	/**
	 * Frontend constructor.
	 *
	 * @param \OWC\Leges\Plugin\BasePlugin $plugin
	 */
	public function __construct(BasePlugin $plugin)
	{
		$this->plugin = $plugin;
		$this->loader = $plugin->loader;
	}

	/**
	 * Boot up the frontend.
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
		$services = $this->plugin->config->get('core.providers.frontend');

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
