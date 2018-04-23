<?php

namespace OWC\Leges\Admin\Settings;

use OWC\Leges\Plugin\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 */
	public function register()
	{
		$this->plugin->loader->addFilter('owc/pdc_base/config/settings_pages', $this, 'addTab', 10, 1);
		$this->plugin->loader->addFilter('owc/pdc_base/config/settings', $this, 'addSettings', 10, 1);
	}

	/**
	 * Register the service provider.
	 */
	public function boot()
	{
		// TODO: Implement register() method.
	}

	/**
	 * @param $settings
	 *
	 * @return array
	 */
	public function addTab($settings)
	{
		$settings['base']['tabs']['leges'] = __('Leges', '');

		return $settings;
	}

	/**
	 * Register metaboxes for settings page
	 *
	 * @param $metaboxes
	 *
	 * @return array
	 */
	public function addSettings($metaboxes)
	{
		$configMetaboxes = [
			'leges' => [
				'id'             => 'leges',
				'title'          => __('Leges', 'owc-leges'),
				'settings_pages' => '_owc_pdc_base_settings',
				'tab'            => 'leges',
				'fields'         => [
					'leges' => [
						'url'    => [
							'id'   => 'setting_leges_url',
							'name' => __('Instance url', 'owc-leges'),
							'desc' => __('URL inclusief http(s)://', 'owc-leges'),
							'type' => 'text'
						],
						'shield' => [
							'id'   => 'setting_leges_shield',
							'name' => __('Instance shield', 'owc-leges'),
							'desc' => __('URL inclusief http(s)://', 'owc-leges'),
							'type' => 'text'
						],
						'prefix' => [
							'id'   => 'setting_leges_prefix',
							'name' => __('Instance prefix', 'owc-leges'),
							'desc' => __('', 'owc-leges'),
							'type' => 'text'
						]
					]
				]
			]
		];

		$configMetaboxes = array_merge($metaboxes, $configMetaboxes);

		return $configMetaboxes;
	}
}
