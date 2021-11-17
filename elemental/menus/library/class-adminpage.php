<?php
/**
 * Menu Handlers Switches Elemental.
 *
 * @package elemental/menus/library/class-adminpage.php
 */

namespace ElementalPlugin\Menus\Library;

/**
 * Class WCFM Connect
 */
class AdminPage {

	/**
	 * Register Settings for Admin.
	 */
	public function init() {
		/**
		 * Register our wporg_settings_init to the admin_init action hook.
		 */
		add_action( 'admin_init', array( $this, 'wporg_settings_init' ) );

		/**
		 * Register our wporg_options_page to the admin_menu action hook.
		 */
		add_action( 'admin_menu', array( $this, 'wporg_options_page' ) );
	}

	/**
	 * Custom option and settings.
	 */
	public function wporg_settings_init() {
		// Register a new setting for "wporg" page.
		register_setting( 'wporg', array( $this, 'wporg_options' ) );

		// Register a new section in the "wporg" page.
		add_settings_section(
			'wporg_section_developers',
			__( 'The Matrix has you.', 'wporg' ),
			array( $this, 'wporg_section_developers_callback' ),
			'wporg'
		);

		// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
		add_settings_field(
			'wporg_field_pill', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
				__( 'Pill', 'wporg' ),
			array( $this, 'wporg_field_pill_cb' ),
			'wporg',
			'wporg_section_developers',
			array(
				'label_for'         => 'wporg_field_pill',
				'class'             => 'wporg_row',
				'wporg_custom_data' => 'custom',
			)
		);
		/*
		add_settings_field(
			'wporg_field_pill2', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
				__( 'Second', 'wporg' ),
			array( $this, 'wporg_field_pill2_cb' ),
			'wporg',
			'wporg_section_developers',
			array(
				'label_for'         => 'wporg_field_pill2',
				'class'             => 'wporg_row2',
				'wporg_custom_data' => 'custom2',
			)
		);*/
	}

	/**
	 * Custom option and settings:
	 *  - callback functions
	 */


	/**
	 * Developers section callback function.
	 *
	 * @param array $args  The settings array, defining title, id, callback.
	 */
	public function wporg_section_developers_callback( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'wporg' ); ?></p>
		<?php
	}

	/**
	 * Pill field callbakc function.
	 *
	 * WordPress has magic interaction with the following keys: label_for, class.
	 * - the "label_for" key value is used for the "for" attribute of the <label>.
	 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
	 * Note: you can add custom key value pairs to be used inside your callbacks.
	 *
	 * @param array $args - the params.
	 */
	public function wporg_field_pill_cb( $args ) {
		// Get the value of the setting we've registered with register_setting().
		$options = get_option( 'wporg_options' );
		echo var_dump( $options).'ffs';
		?>
	<select
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
			name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
		<option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
			<?php esc_html_e( 'red pill', 'wporg' ); ?>
		</option>
		<option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
			<?php esc_html_e( 'blue pill', 'wporg' ); ?>
		</option>
	</select>
	<p class="description">
		<?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg' ); ?>
	</p>
	<p class="description">
		<?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg' ); ?>
	</p>
		<?php
	}

		/**
	 * Pill field callbakc function.
	 *
	 * WordPress has magic interaction with the following keys: label_for, class.
	 * - the "label_for" key value is used for the "for" attribute of the <label>.
	 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
	 * Note: you can add custom key value pairs to be used inside your callbacks.
	 *
	 * @param array $args - the params.
	 */
	public function wporg_field_pill2_cb( $args ) {
		// Get the value of the setting we've registered with register_setting().
		$options = get_option( 'wporg_options' );
		
		?>
	<select
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
			name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
		<option value="yellow" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'yellow', false ) ) : ( '' ); ?>>
			<?php esc_html_e( 'yellow pill', 'wporg' ); ?>
		</option>
		<option value="green" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'green', false ) ) : ( '' ); ?>>
			<?php esc_html_e( 'green pill', 'wporg' ); ?>
		</option>
	</select>
	<p class="description">
		<?php esc_html_e( 'You take the yellow pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg' ); ?>
	</p>
	<p class="description">
		<?php esc_html_e( 'You take the green pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg' ); ?>
	</p>
		<?php
	}

	/**
	 * Add the top level menu page.
	 */
	public function wporg_options_page() {
		add_menu_page(
			'WPOrg',
			'WPOrg Options',
			'manage_options',
			'wporg',
			array( $this, 'wporg_options_page_html' )
		);
	}


	/**
	 * Top level menu callback function
	 */
	public function wporg_options_page_html() {
		// check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Add error/update messages.

		// Check if the user have submitted the settings.
		// WordPress will add the "settings-updated" $_GET parameter to the url.
		if ( isset( $_GET['settings-updated'] ) ) {
			// Add settings saved message with the class of "updated".
			add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
		}

		// show error/update messages.
		settings_errors( 'wporg_messages' );
		?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// Output security fields for the registered setting "wporg".
			settings_fields( 'wporg' );
			// Output setting sections and their fields
			// (sections are registered for "wporg", each field is registered to a specific section).
			do_settings_sections( 'wporg' );
			// Output save settings button.
			submit_button( 'Save Settings' );
			?>
		</form>
	</div>
		<?php
	}

}
