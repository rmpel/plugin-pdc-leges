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
		OWC\Leges\Shortcode\ShortcodeServiceProvider::class,
		/**
		 * Providers specific to the admin.
		 */
		'admin' => [
			OWC\Leges\Admin\QuickEdit\QuickEditServiceProvider::class,
			OWC\Leges\Metabox\MetaboxServiceProvider::class,
		],
	]
];
