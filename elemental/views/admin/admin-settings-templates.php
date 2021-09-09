<?php
/**
 * Outputs the configuration settings for the video plugin
 *
 * @package ElementalPlugin\Views\Admin
 */

/**
 * Render the admin page
 *
 * @param string $active_tab
 * @param array $tabs
 * @param array $messages
 *
 * @return string
 */
use ElementalPlugin\Factory;
use ElementalPlugin\Library\AdminTemplates;
use ElementalPlugin\Library\Templates\SecurityButtons;
return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {

	$render = include __DIR__ . '/header.php';
	echo $render( $active_tab, $tabs, $messages );
	ob_start();
	// phpcs:ignore -- not needed as escaped in function
	echo Factory::get_instance( SecurityButtons::class )->site_wide_enabled();
	// phpcs:ignore -- not needed as escaped in function
	echo Factory::get_instance( AdminTemplates::class )->display_room_template_browser();

	return ob_get_clean();
};

