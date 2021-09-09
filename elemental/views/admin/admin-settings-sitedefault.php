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
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Factory;
use ElementalPlugin\Library\Templates\SecurityButtons;
use ElementalPlugin\Setup\RoomAdmin;
use ElementalPlugin\DAO\ModuleConfig;
use ElementalPlugin\Setup\Setup;
use ElementalPlugin\DAO\RoomMap;
use ElementalPlugin\Shortcode\UserVideoPreference;
return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {

	$render = include __DIR__ . '/header.php';
	echo $render( $active_tab, $tabs, $messages );

	ob_start();

	// Check to see if Default settings exist on entry- reinitialise if missing
	if ( ! \ElementalPlugin\Factory::get_instance( \ElementalPlugin\Setup\RoomAdmin::class )->check_default_settings_exist() ) {

		\ElementalPlugin\Factory::get_instance( \ElementalPlugin\Setup\Setup::class )->initialise_default_video_settings();
	}

	?>

<div class="wrap">

<h1 style ="display: inline">Video Default Configuration</h1>
	<?php echo Factory::get_instance( SecurityButtons::class )->site_wide_enabled(); ?>
<p><b> The Following Settings Define Site Wide Video Default Parameters. These defaults will be used if a user has not selected a setting for the room configuration.</b><br>    </p>
<p> Use the Template Browser tab to view room selection templates<br>    </p>
	<?php
	$layout_setting = \ElementalPlugin\Factory::get_instance( \ElementalPlugin\Shortcode\UserVideoPreference::class )->choose_settings(
		\ElementalPlugin\Core\SiteDefaults::USER_ID_SITE_DEFAULTS,
		\ElementalPlugin\Core\SiteDefaults::ROOM_NAME_SITE_DEFAULT,
		array( 'basic', 'premium' )
	);
	echo $layout_setting;
	?>
</div>

<hr>

		<div>
	<?php
				\ElementalPlugin\Factory::get_instance( \ElementalPlugin\Library\ShortcodeDocuments::class )->render_general_shortcode_docs();
	?>
		</div>


	<?php
	return '';

	return ob_get_clean();
};

