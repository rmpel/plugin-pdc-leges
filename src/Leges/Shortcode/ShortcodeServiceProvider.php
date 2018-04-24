<?php

namespace OWC\Leges\Shortcode;

use OWC\Leges\Plugin\ServiceProvider;

class ShortcodeServiceProvider extends ServiceProvider
{

	/**
	 * Default fields for leges.
	 *
	 * @var array
	 */
	protected $defaults = [
		'_pdc-lege-active-date' => null,
		'_pdc-lege-price'       => null,
		'_pdc-lege-new-price'   => null,
	];

	/**
	 * @var string
	 */
	protected static $shortcode = 'pdc::leges';

	/**
	 * Register the shortcode.
	 */
	public function register()
	{
		add_shortcode(self::$shortcode, [$this, 'addShortcode']);
	}

	/**
	 * @param null $id
	 *
	 * @return string
	 */
	public static function generateShortcode($id = null)
	{
		$shortcode = sprintf('[%s id="%d"]', self::$shortcode, $id);

		return sprintf('<code>%s</code>', $shortcode);
	}

	/**
	 * Add the shortcode rendering.
	 *
	 * @param $attributes
	 *
	 * @return string
	 */
	public function addShortcode($attributes)
	{

		$attributes = shortcode_atts([
			'id' => 0
		], $attributes);

		if ( ! isset($attributes['id']) OR empty($attributes['id']) OR ( count($attributes['id']) < 1 ) ) {
			return false;
		}

		if ( ! $this->postExists($attributes['id']) ) {
			return false;
		}

		$id         = absint($attributes['id']);
		$metaData   = $this->mergeWithDefaults(get_metadata('post', $id));
		$price      = $metaData['_pdc-lege-price'];
		$newPrice   = $metaData['_pdc-lege-new-price'];
		$dateActive = $metaData['_pdc-lege-active-date'];

		if ( $this->hasDate($dateActive) AND $this->dateIsNow($dateActive) ) {
			$price = $newPrice;
		}

		$format = apply_filters('owc/pdc/leges/shortcode/format', '<span>&euro; %d</span>');
		$output = sprintf($format, $price);

		return $output;
	}

	/**
	 * Determines if a post, identified by the specified ID, exist
	 * within the WordPress database.
	 *
	 * @param    int $id The ID of the post to check
	 *
	 * @return   bool          True if the post exists; otherwise, false.
	 */
	protected function postExists($id)
	{
		return is_string(get_post_status($id));
	}

	/**
	 * @param $metaData
	 *
	 * @return array
	 */
	private function mergeWithDefaults($metaData)
	{
		$output = [];
		foreach ( $metaData as $key => $data ) {

			if ( ! in_array($key, array_keys($this->defaults)) ) {
				continue;
			}

			$output[ $key ] = ( ! is_array($data) ) ? $data : $data[0];
		}

		return $output;
	}

	/**
	 * Readable check if date is not empty.
	 *
	 * @param $dateActive
	 *
	 * @return bool
	 */
	private function hasDate($dateActive)
	{
		return ! empty($dateActive);
	}

	/**
	 * Return true if date from lege is smaller or equal to current date.
	 *
	 * @param $dateActive
	 *
	 * @return bool
	 */
	private function dateIsNow($dateActive)
	{
		return ( new \DateTime($dateActive) <= new \DateTime('now') );
	}
}
