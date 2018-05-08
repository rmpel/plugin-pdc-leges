<?php

namespace OWC\Leges\Tests\Config;

use Mockery as m;
use OWC\Leges\Plugin\BasePlugin;
use OWC\Leges\Plugin\Loader;
use OWC\Leges\Shortcode\Shortcode;
use OWC\Leges\Shortcode\ShortcodeServiceProvider;
use OWC\Leges\Tests\TestCase;
use OWC_PDC_Base\Core\Config;

class ShortcodeServiceProviderTest extends TestCase
{

	/**
	 * @var ShortcodeServiceProvider
	 */
	protected $service;

	/**
	 * Shortcode object.
	 * @var \OWC\Leges\Shortcode\Shortcode
	 */
	private $shortcode;

	/**
	 * Shortcode tag.
	 * @var string
	 */
	private $tag = 'pdc::leges';

	/**
	 * @var
	 */
	protected $config;

	/**
	 * @var
	 */
	protected $plugin;

	/**
	 * @var int
	 */
	protected $postID = 10;

	public function setUp()
	{
		\WP_Mock::setUp();

		$this->config         = m::mock(Config::class);
		$this->plugin         = m::mock(BasePlugin::class);
		$this->plugin->config = $this->config;
		$this->plugin->loader = m::mock(Loader::class);

		$this->service = new ShortcodeServiceProvider($this->plugin);

		$this->shortcode = new Shortcode();
	}

	public function tearDown()
	{
		\WP_Mock::tearDown();

		$this->shortcode = null;
	}

	/** @test */
	public function it_registers_the_shortcode_correctly()
	{

		\WP_Mock::userFunction('add_shortcode', [
			'times' => 1,
			'args'  => [
				$this->tag,
				[
					$this->shortcode,
					'addShortcode'
				]
			],
		]);

		$this->service->register();

		$this->assertTrue(true);
	}

	/** @test */
	public function it_generate_the_code_correctly()
	{
		$expected = sprintf('<code>[%s id="%d"]</code>', 'pdc::leges', 5);

		$actual = $this->service::generateShortcode(5);

		$this->assertEquals($expected, $actual);
	}
}
