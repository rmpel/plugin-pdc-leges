<?php

namespace OWC\Leges\Tests\Config;

use Mockery as m;
use OWC\Leges\Plugin\BasePlugin;
use OWC\Leges\Plugin\Loader;
use OWC\Leges\Shortcode\Shortcode;
use OWC\Leges\Tests\TestCase;
use OWC_PDC_Base\Core\Config;

class TestShortcode extends TestCase
{

	/**
	 * @var Shortcode
	 */
	protected $service;

	/**
	 * @var
	 */
	protected $config;

	/**
	 * @var
	 */
	protected $plugin;

	public function setUp()
	{
		\WP_Mock::setUp();

		$this->config = m::mock(Config::class);

		$this->plugin         = m::mock(BasePlugin::class);
		$this->plugin->config = $this->config;
		$this->plugin->loader = m::mock(Loader::class);

		$this->service = new Shortcode();
	}

	public function tearDown()
	{
		\WP_Mock::tearDown();
	}

	/** @test */
	public function shortcode_is_rendered_correctly()
	{

		$id = 10;

		\WP_Mock::passthruFunction('shortcode_atts', [
			'return_arg' => 1
		]);

		\WP_Mock::userFunction('get_post_status', [
				'args'   => $id,
				'return' => true
			]
		);

		$attributes = [
			'id' => $id
		];

		$shortcode = $this->service->addShortcode($attributes);

		$this->assertTrue($shortcode);
	}
}
