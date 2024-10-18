<?php

/**
 * Unschedule WP Cron Event(s)
 */
if (! function_exists('owc_pdc_leges_deactivate'))
{
	function owc_pdc_leges_deactivate(): void
	{
		$timestamp = wp_next_scheduled('owc_pdc_leges_update_cron');

		if (! $timestamp) {
			return;
		}

		wp_unschedule_event($timestamp, 'owc_pdc_leges_update_cron');
	}
}
