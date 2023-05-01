<?php
/**
 * Outputs the header for admin pages.
 *
 * @package ElementalPlugin\Views\Admin
 */

use ElementalPlugin\Library\Plugin;

return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {
	ob_start();
	?>
		<header>
		<h1 class="elemental-header-config-title">
	<?php \esc_html_e( 'Elemental Platform Settings and Configuration', 'elemental' ); ?>
		</h1>

		<div class="overview">
			<strong>
				<?php echo \esc_html__( 'Elemental is a control and integration plugin connecting several plugins together', 'elemental' ); ?>
			</strong>
			<?php
			foreach ( $messages as $message ) {
				echo '<li class="notice ' . esc_attr( $message['type'] ) . '"><p>' . esc_html( $message['message'] ) . '</p></li>';
			}
			?>

		</div>
	</header>

	<h2 class="nav-tab-wrapper">
	<?php
	foreach ( $tabs as $tab_key => $tab_name ) {
		$active = '';
		if ( $active_tab === $tab_key ) {
			$active = ' nav-tab-active';
		}

		echo '<a class="nav-tab' . esc_attr( $active ) . '" href="?page=' . esc_textarea( Plugin::ELEMENTAL_SLUG ) . '&tab=' . esc_attr( $tab_key ) . '">' . esc_html( $tab_name ) . '</a>';
	}
	?>
	</h2>


	<?php
	return ob_get_clean();
};


