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

</div>

<?php
	return ob_get_clean();
};

