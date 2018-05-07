<?php

namespace OWC\Leges\PostType;

use OWC\Leges\Plugin\ServiceProvider;
use OWC\Leges\Shortcode\ShortcodeServiceProvider;

class LegesPostTypeServiceProvider extends ServiceProvider
{

	/**
	 * @var string
	 */
	protected $prefix = '_pdc-lege';

	/**
	 * Name of posttype.
	 *
	 * @var string
	 */
	protected $postType = 'pdc-leges';

	/**
	 * Register the service provider.
	 */
	public function register()
	{
		$this->plugin->loader->addAction('init', $this, 'registerPostType');
	}

	/**
	 * Register the Leges posttype.
	 */
	public function registerPostType()
	{

		if ( ! function_exists('register_extended_post_type') ) {
			throw new \Exception('function register_extended_post_type must be registered.');
		}

		$labels = [
			'name'               => _x('PDC leges', 'post type general name', 'pdc-leges'),
			'singular_name'      => _x('PDC lege', 'post type singular name', 'pdc-leges'),
			'menu_name'          => _x('PDC leges', 'admin menu', 'pdc-leges'),
			'name_admin_bar'     => _x('PDC leges', 'add new on admin bar', 'pdc-leges'),
			'add_new'            => _x('Add new pdc lege', 'lege', 'pdc-leges'),
			'add_new_item'       => __('Add new pdc lege', 'pdc-leges'),
			'new_item'           => __('New PDC lege', 'pdc-leges'),
			'edit_item'          => __('Edit PDC lege', 'pdc-leges'),
			'view_item'          => __('View PDC lege', 'pdc-leges'),
			'all_items'          => __('All PDC leges', 'pdc-leges'),
			'search_items'       => __('Search PDC leges', 'pdc-leges'),
			'parent_item_colon'  => __('Parent PDC leges:', 'pdc-leges'),
			'not_found'          => __('No PDC leges found.', 'pdc-leges'),
			'not_found_in_trash' => __('No PDC leges found in Trash.', 'pdc-leges')
		];

		$args = [
			'labels'             => $labels,
			'description'        => __('PDC leges', 'pdc-leges'),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => ['title'],
			'show_in_feed'       => false,
			'archive'            => false,
			'admin_cols'         => [
				'price'          => [
					'title'    => __('Lege price (in &euro;)', 'pdc-leges'),
					'meta_key' => "{$this->prefix}-price",
				],
				'new-price'      => [
					'title'    => __('Lege new price (in &euro;)', 'pdc-leges'),
					'meta_key' => "{$this->prefix}-new-price",
				],
				'active-date'    => [
					'title'       => __('Date new lege active', 'pdc-leges'),
					'meta_key'    => "{$this->prefix}-active-date",
					'date_format' => 'd/m/Y'
				],
				'code-preview'   => [
					'title'    => __('Lege shortcode', 'pdc-leges'),
					'function' => function() {
						echo ShortcodeServiceProvider::generateShortcode(get_the_ID());
					}
				],
				'published'      => [
					'title'       => __('Published', 'pdc-leges'),
					'post_field'  => 'post_date',
					'date_format' => 'd M Y'
				]
			],
		];

		register_extended_post_type($this->postType, $args, $labels);
	}
}
