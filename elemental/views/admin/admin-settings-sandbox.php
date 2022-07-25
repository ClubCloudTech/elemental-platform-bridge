<?php
/**
 * Outputs the configuration settings for the video plugin
 *
 * @package ElementalPlugin\Views\Admin
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Membership\Membership;
use ElementalPlugin\Module\Sandbox\Sandbox;

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
	echo Factory::get_instance( Sandbox::class )->render_sandbox_config_page();

	?>


</div>

	<?php
	return ob_get_clean();
};

