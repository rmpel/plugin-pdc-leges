<?php

namespace OWC\Leges\Admin\QuickEdit;

use OWC\Leges\Plugin\ServiceProvider;

class QuickEditServiceProvider extends ServiceProvider
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
	 * @var array
	 */
	protected $quickEditHandlers = [];

	/**
	 * Register the service provider.
	 */
	public function register()
	{

		$this->quickEditHandlers = $this->setQuickEditHandlers();

		$this->plugin->loader->addAction('quick_edit_custom_box', $this, 'registerQuickEditHandler', 10, 2);
		$this->plugin->loader->addAction('save_post', $this, 'registerSavePostHandler', 10, 2);
		$this->plugin->loader->addAction('admin_footer', $this, 'printFooterScript', 10, 1);
		$this->plugin->loader->addFilter('post_row_actions', $this, 'addRowActions', 10, 2);
		$this->plugin->loader->addAction('admin_enqueue_scripts', $this, 'register_admin_scripts');
		$this->plugin->loader->addAction('admin_head-edit.php', $this, 'removeUnneccesaryQuickEdits');
	}

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts()
	{
		wp_enqueue_style('jquery-ui-datepicker', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css');
		wp_enqueue_script('jquery-ui-datepicker');
	}

	/**
	 * @param $actions
	 * @param $post
	 *
	 * @return mixed
	 */
	public function addRowActions($actions, $post)
	{

		foreach ( $this->quickEditHandlers as $key => $handler ) {
			$foundValue = get_post_meta($post->ID, $handler['metaboxKey'], true);

			if ( $foundValue ) {
				if ( isset($actions['inline hide-if-no-js']) ) {
					$new_attribute                   = sprintf('data-%s="%s"', esc_attr($key), esc_attr($foundValue));
					$actions['inline hide-if-no-js'] = str_replace('class=', "$new_attribute class=", $actions['inline hide-if-no-js']);
				}
			}
		}

		return $actions;
	}

	/**
	 *
	 */
	public function printFooterScript()
	{
		$current_screen = get_current_screen();
		if ( $current_screen->id !== 'edit-' . $this->postType || $current_screen->post_type !== $this->postType ) {
			return;
		}

		// Ensure jQuery library loads
		wp_enqueue_script('jquery');
		?>
		<script type="text/javascript">
			jQuery(function ($) {

				$(function () {
					$('#_pdc-lege-active-date').datepicker({
						altField: "#_pdc-lege-active-date",
						dateFormat: 'dd-mm-yy',
						changeMonth: true,
						changeYear: true,
						showButtonPanel: true,
						minDate: 0
					});
				});

				$('#the-list').on('click', 'a.editinline', function (e) {
					e.preventDefault();
					inlineEditPost.revert();
					<?php foreach( $this->quickEditHandlers as $key => $handler ) { ?>
					var value = $(this).data('<?php echo $key; ?>');
					$('#<?php echo $handler['metaboxKey']; ?>').val(value ? value : '');
					<?php } ?>
				});
			});
		</script>
		<?php
	}

	/**
	 * @param $columnName
	 * @param $postType
	 */
	public function registerQuickEditHandler($columnName, $postType)
	{
		if ( ! in_array($columnName, array_keys($this->quickEditHandlers)) ) {
			return;
		}

		echo '<fieldset class="inline-edit-col-left clear">
				<div class="inline-edit-col">';

		foreach ( $this->quickEditHandlers as $key => $handler ) {

			if ( $columnName !== $key ) {
				continue;
			}

			$method = explode('-', $key);
			$method = array_map('ucfirst', $method);
			$method = implode('', $method);

			if ( method_exists($this, $method) ) {
				$this->$method($handler);
			}
		}

		echo '</div>
			</fieldset>';
	}

	/**
	 * @param $item
	 */
	protected function ActiveDate($item)
	{
		$value = get_post_meta(get_the_ID(), $item['metaboxKey'], true); ?>
		<label class="aligncenter" for="<?php echo $item['metaboxKey']; ?>">
			<span class="title"><?php echo $item['label']; ?></span>
			<span class="input-text-wrap"><input type="text" id="<?php echo $item['metaboxKey']; ?>" name="<?php echo $item['metaboxKey']; ?>" value="<?php echo $value; ?>"></span>
		</label>
		<?php
	}

	/**
	 * @param $item
	 */
	protected function Price($item)
	{
		$value = get_post_meta(get_the_ID(), $item['metaboxKey'], true); ?>
		<label class="aligncenter" for="<?php echo $item['metaboxKey']; ?>">
			<span class="title"><?php echo $item['label']; ?></span>
			<span class="input-text-wrap"><input type="text" id="<?php echo $item['metaboxKey']; ?>" name="<?php echo $item['metaboxKey']; ?>" value="<?php echo $value; ?>"></span>
		</label>
		<?php
	}

	/**
	 * @param $item
	 */
	protected function NewPrice($item)
	{
		$value = get_post_meta(get_the_ID(), $item['metaboxKey'], true); ?>
		<label class="aligncenter" for="<?php echo $item['metaboxKey']; ?>">
			<span class="title"><?php echo $item['label']; ?></span>
			<span class="input-text-wrap"><input type="text" id="<?php echo $item['metaboxKey']; ?>" name="<?php echo $item['metaboxKey']; ?>" value="<?php echo $value; ?>"></span>
		</label>
		<?php
	}

	/**
	 * @return void
	 */
	public function removeUnneccesaryQuickEdits()
	{

		$current_screen = get_current_screen();
		if ( $current_screen->id != 'edit-' . $this->postType || $current_screen->post_type != $this->postType ) {
			return;
		}
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$('input[name="post_password"]').each(function (i) {
					$(this).parent().parent().parent().remove();
				});
				$('input[name="post_name"]').each(function (i) {
					$(this).parent().parent().remove();
				});
			});
		</script>
		<?php
	}

	/**
	 * Returns the handlers for the quick edit.
	 *
	 * @return array
	 */
	private function setQuickEditHandlers()
	{
		return [
			'new-price'   => [
				'metaboxKey' => sprintf('%s-%s', $this->prefix, 'new-price'),
				'label'      => __('New price', 'pdc-lege')
			],
			'price'       => [
				'metaboxKey' => sprintf('%s-%s', $this->prefix, 'price'),
				'label'      => __('Price', 'pdc-lege')
			],
			'active-date' => [
				'metaboxKey' => sprintf('%s-%s', $this->prefix, 'active-date'),
				'label'      => __('Date new lege active', 'pdc-lege')
			]
		];
	}

	/**
	 * @param $post_id
	 * @param $post
	 */
	public function registerSavePostHandler($post_id, $post)
	{
		// if called by autosave, then bail here
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return;
		}

		// if this correct post type?
		if ( $post->post_type !== $this->postType ) {
			return;
		}

		// does this user have permissions?
		if ( ! current_user_can('edit_post', $post_id) ) {
			return;
		}

		foreach ( $this->quickEditHandlers as $key => $handler ) {
			// update!
			if ( isset($_POST["{$this->prefix}-{$key}"]) ) {
				update_post_meta($post_id, "{$this->prefix}-{$key}", $_POST["{$this->prefix}-{$key}"]);
			}
		}
	}
}
