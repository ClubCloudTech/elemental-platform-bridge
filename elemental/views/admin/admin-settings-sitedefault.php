<?php
/**
 * Outputs the configuration settings for the video plugin
 *
 * @package ElementalPlugin\Views\Admin
 */

use ElementalPlugin\Factory;
use ElementalPlugin\Membership\Membership;

/**
 * Render the admin page
 *
 * @param string $active_tab
 * @param array $tabs
 * @param array $messages
 *
 * @return string
 */

return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {

	$render = include __DIR__ . '/header.php';
	echo $render( $active_tab, $tabs, $messages );

	ob_start();
	?>
<div class="wrap">
	<?php
	//phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped;
	echo Factory::get_instance( Membership::class )->render_membership_config_page();

	?>


</div>

	<?php
	return ob_get_clean();
};

