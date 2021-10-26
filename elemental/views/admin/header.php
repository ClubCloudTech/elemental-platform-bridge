<?php
/**
 * Outputs the header for admin pages.
 *
 * @package ElementalPlugin\Views\Admin
 */

return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {
	ob_start();
	?>
		<header>
		<h1 class="myvideoroom-header-config-title">
	<?php \esc_html_e( 'Elemental Bridge Settings and Configuration', 'myvideoroom' ); ?>
		</h1>

		<div class="overview">
			<strong>
				<?php echo \esc_html__( 'Elemental is the plugin from ClubCloud that allows Membership Management to a whole new level', 'myvideoroom' ); ?>
			</strong>

			<em>
				<?php \esc_html_e( 'MyVideoRoom by ClubCloud.' ); ?>
			</em>
<<<<<<< HEAD
	<?php
	foreach ( $messages as $message ) {
		echo '<li class="notice ' . esc_attr( $message['type'] ) . '"><p>' . esc_html( $message['message'] ) . '</p></li>';
	}
	?>
=======
			<?php
			foreach ( $messages as $message ) {
				echo '<li class="notice ' . esc_attr( $message['type'] ) . '"><p>' . esc_html( $message['message'] ) . '</p></li>';
			}
			?>
>>>>>>> a6fd707ad028a28273f93e9e7f27caddb908fd31

		</div>

		<img src="<?php echo \esc_url( \plugins_url( '/img/screen-1.png', \realpath( __DIR__ . '/../' ) ) ); ?>"
			alt="" />
	</header>

	<h2 class="nav-tab-wrapper">
	<?php
	foreach ( $tabs as $tab_key => $tab_name ) {
		$active = '';
		if ( $active_tab === $tab_key ) {
			$active = ' nav-tab-active';
		}

		echo '<a class="nav-tab' . esc_attr( $active ) . '" href="?page=my-video-room-extras&tab=' . esc_attr( $tab_key ) . '">' . esc_html( $tab_name ) . '</a>';
	}
	?>
	</h2>


	<?php
	return ob_get_clean();
};


