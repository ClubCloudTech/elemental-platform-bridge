<?php
/**
 * Outputs the configuration settings for the Membership Area of the plugin
 *
 * @package views/admin/admin-settings-membership.php
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Membership\Membership;

/**
 * Render the admin page
 *
 * @param string $active_tab
 * @param array $tabs
 * @param array $messages
 *
 * @return string
 */

return function (): string {
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

