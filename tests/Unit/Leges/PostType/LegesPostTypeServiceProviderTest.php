<?php

namespace OWC\PDC\Leges\Tests\PostType;

use Mockery as m;
use Extended_CPT;
use OWC\PDC\Base\Foundation\Config;
use OWC\PDC\Base\Foundation\Loader;
use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Leges\PostType\LegesPostTypeServiceProvider;
use OWC\PDC\Leges\Tests\Unit\TestCase;
use WP_Mock;
use OWC\PDC\Leges\PostType\Commands\UpdatePrices;

class LegesPostTypeServiceProviderTest extends TestCase
{

    /**
     * @var
     */
    protected $service;

    /**
     * @var
     */
    protected $plugin;

    public function setUp(): void
    {
        Parent::setUp();

        $config       = m::mock(Config::class);
        $this->plugin = m::mock(Plugin::class);

        $this->plugin->config = $config;
        $this->plugin->loader = m::mock(Loader::class);

        $this->service = new LegesPostTypeServiceProvider($this->plugin);
    }

    public function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function check_registration_of_posttype()
    {
        $this->plugin->loader->shouldReceive('addAction')->withArgs([
            'init',
            $this->service,
            'registerPostType'
        ])->once();

        $register = $this->service->register();

        $this->assertTrue(true);
    }

    /** @test */
    public function lege_active_date_should_update_correct_format()
    {
        \WP_Mock::userFunction('get_post_meta', [
            'return' => '03-05-2022'
        ]);

        $actual = (new UpdatePrices())->shouldUpdate(1);
        $expected = true;

        $this->assertEquals($actual, $expected);
    }

    /** @test */
    public function lege_active_date_should_not_update_wrong_format()
    {
        \WP_Mock::userFunction('get_post_meta', [
            'return' => '2022-03-05'
        ]);

        m::mock('alias:WP_CLI')->shouldReceive('warning')->andReturnArg(0);
    
        $actual = (new UpdatePrices())->shouldUpdate(1);
        $expected = false;
    
        $this->assertEquals($actual, $expected);
    }

    /** @test */
    public function lege_active_date_empty_should_not_update()
    {
        \WP_Mock::userFunction('get_post_meta', [
            'return' => ''
        ]);

        m::mock('alias:WP_CLI')->shouldReceive('warning')->andReturnArg(0);
        
        $actual = (new UpdatePrices())->shouldUpdate(1);
        $expected = false;
        
        $this->assertEquals($actual, $expected);
    }
}
