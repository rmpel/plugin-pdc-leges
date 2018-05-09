<?php

namespace OWC\PDC\Leges\Tests\Metabox;

use Mockery as m;
use OWC\PDC\Base\Foundation\Config;
use OWC\PDC\Base\Foundation\Loader;
use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Leges\Metabox\MetaboxServiceProvider;
use OWC\PDC\Leges\Tests\TestCase;
use WP_Mock;

class MetaboxServiceProviderTest extends TestCase
{

	public function setUp()
	{
		WP_Mock::setUp();
	}

	public function tearDown()
	{
		WP_Mock::tearDown();
	}

	/** @test */
	public function check_registration_of_metaboxes()
	{
		$config = m::mock(Config::class);
		$plugin = m::mock(Plugin::class);

		$plugin->config = $config;
		$plugin->loader = m::mock(Loader::class);

		$service = new MetaboxServiceProvider($plugin);

		$plugin->loader->shouldReceive('addFilter')->withArgs([
			'rwmb_meta_boxes',
			$service,
			'registerMetaboxes',
			10,
			1
		])->once();

		$service->register();

		$prefix = '_pdc-lege';

		$expected = [
			[
				'id'         => 'pdc-leges',
				'title'      => __('Lege settings', 'pdc-leges'),
				'post_types' => ['pdc-leges'],
				'context'    => 'normal',
				'priority'   => 'high',
				'autosave'   => true,
				'fields'     => [
					[
						'id'   => "{$prefix}-price",
						'name' => __('Lege price', 'pdc-leges'),
						'desc' => __('Price in &euro;', 'pdc-leges'),
						'type' => 'text',
					],
					[
						'id'   => "{$prefix}-new-price",
						'name' => __('Lege new price', 'pdc-leges'),
						'desc' => __('Price in &euro;', 'pdc-leges'),
						'type' => 'text',
					],
					[
						'id'         => "{$prefix}-active-date",
						'name'       => esc_html__('Date new lege active', 'pdc-leges'),
						'type'       => 'date',
						'js_options' => [
							'dateFormat'      => esc_html__('dd-mm-yy', 'pdc-leges'),
							'altFormat'       => 'yy-mm-dd',
							'changeMonth'     => true,
							'changeYear'      => true,
							'showButtonPanel' => true,
							'minDate'         => 0
						],
						'desc'       => esc_html__('(dd-mm-yy)', 'pdc-leges'),
					]
				]
			],
			[
				'id'    => 'pdc-leges',
				'title' => __('Lege settings', 'pdc-leges')
			]
		];

		$actual = [
			'id'    => 'pdc-leges',
			'title' => __('Lege settings', 'pdc-leges')
		];

		$this->assertContains($actual, $service->registerMetaboxes($expected));
	}
}
