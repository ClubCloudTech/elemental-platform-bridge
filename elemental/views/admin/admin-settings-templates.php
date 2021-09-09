<?php
/**
 * Outputs the configuration settings for the video plugin
 *
 * @package MyVideoRoomExtrasPlugin\Views\Admin
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
use MyVideoRoomExtrasPlugin\Factory;
use MyVideoRoomExtrasPlugin\Library\AdminTemplates;
use MyVideoRoomExtrasPlugin\Library\Templates\SecurityButtons;
return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {

	$render = require __DIR__ . '/header.php';
	echo $render( $active_tab, $tabs, $messages );
	ob_start();
	// phpcs:ignore -- not needed as escaped in function
	echo Factory::get_instance( SecurityButtons::class )->site_wide_enabled();
	// phpcs:ignore -- not needed as escaped in function
	echo Factory::get_instance( AdminTemplates::class )->display_room_template_browser();

	return ob_get_clean();
};

