<?php

return [

	/**
	 * Service Providers.
	 */
	'providers' => [
		/**
		 * Global providers.
		 */
		OWC\Leges\PostType\LegesPostTypeServiceProvider::class,
		OWC\Leges\Metabox\MetaboxServiceProvider::class,
		OWC\Leges\Admin\QuickEdit\QuickEditServiceProvider::class,
		OWC\Leges\Shortcode\ShortcodeServiceProvider::class,
		/**
		 * Providers specific to the admin.
		 */
		'admin'    => [],

		/**
		 * Providers specific to the network admin.
		 */
		'network'  => [],

		/**
		 * Providers specific to the frontend.
		 */
		'frontend' => []
	]
];
