<?php

return [

	/**
	 * Service Providers.
	 */
	'providers'    => [
		/**
		 * Global providers.
		 */
		OWC\PDC\Leges\PostType\LegesPostTypeServiceProvider::class,
		OWC\PDC\Leges\Shortcode\ShortcodeServiceProvider::class,
		/**
		 * Providers specific to the admin.
		 */
		'admin' => [
			OWC\PDC\Leges\Admin\QuickEdit\QuickEditServiceProvider::class,
			OWC\PDC\Leges\Metabox\MetaboxServiceProvider::class,
		],
	],

	/**
	 * Dependencies upon which the plugin relies.
	 *
	 * Should contain: label, version, file.
	 */
	'dependencies' => [
		[
			'label'   => 'OpenPDC Base',
			'file'    => 'pdc-base/pdc-base.php',
			'version' => '2.0.0',
			'type'    => ''
		]
	]
];
