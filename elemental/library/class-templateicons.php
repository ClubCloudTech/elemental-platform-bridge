<?php
/**
 * Display Icon Templates in Header of Meetings
 *
 * @package ElementalPlugin\Library
 */

namespace ElementalPlugin\Library;

/**
 * Class TemplateIcons
 * Provides Iconography for Header Display Information in Front end.
 */
class TemplateIcons {



	/**
	 * Filter for Adding Template Buttons to Shortcode Builder
	 *
	 * @param ?string $type The type of button to use.
	 * @param string $payload - Extra content to add to HTML tag.
	 *
	 * @return string
	 */
	public function format_button_icon( ?string $type, string $payload = null ): string {
		switch ( $type ) {
			case 'login':
				$button_label = '<span title ="' . esc_html__( 'Login to access your room settings', 'elementalplugin' ) . '" class="elemental-dashicons dashicons-admin-network"></span>';
				$button_class = 'elemental-ul-style-menu elemental-button-separation elemental-button-override';
				$a_class      = '';
				$id           = 'elemental-button-login';
				break;
			case 'photo':
				$button_label = '<span title ="' . esc_html__( 'Update Profile Picture across site.', 'elementalplugin' ) . '" class="elemental-dashicons dashicons-camera"></span>';
				$button_class = 'elemental-ul-style-menu elemental-button-separation elemental-button-override';
				$a_class      = '';
				$id           = 'elemental-photo-image';
				break;
			case 'name':
				$button_label = '<span title ="' . esc_html__( 'You need to identify yourself for the meeting. Please enter a short name', 'elementalplugin' ) . '" class="elemental-dashicons dashicons-format-chat"></span>';
				$button_class = 'elemental-ul-style-menu elemental-button-separation elemental-button-override';
				$a_class      = '';
				$id           = 'elemental-name-user';
				break;
			case 'checksound':
				$button_label  = '<span title ="' . esc_html__( 'Lets get your sound, and camera checked out and ready', 'elementalplugin' ) . '" class="elemental-dashicons dashicons-admin-generic"></span>';
				$button_label .= '<span title ="' . esc_html__( 'Lets get your sound, and camera checked out and ready', 'elementalplugin' ) . '" class="elemental-dashicons dashicons-controls-volumeon"></span>';
				$button_class  = 'elemental-ul-style-menu elemental-button-separation elemental-button-override';
				$a_class       = '';
				$id            = 'elemental-check-sound';
				break;
			case 'forgetme':
				$button_label = '<span title ="' . esc_html__( 'Delete your picture, and clear temporary information', 'elementalplugin' ) . '" class="elemental-dashicons dashicons-dismiss"></span>';
				$button_class = 'elemental-ul-style-menu elemental-button-separation elemental-button-override';
				$a_class      = '';
				$id           = 'elemental-forget-me';
				break;
			case 'deletefile':
				$button_label = '<span title ="' . esc_html__( 'Delete your file', 'elementalplugin' ) . '" class="elemental-dashicons dashicons-dismiss"></span>';
				$button_class = 'elemental-ul-style-menu elemental-button-separation elemental-button-override';
				$a_class      = '';
				$id           = 'elemental-delete-file';
				break;
		}

		return '<button id=' . $id . \wp_rand( 1, 10000 ) . ' class=" ' . $button_class . ' ' . $id . ' " ' . $payload . ' >
			<a class="' . $a_class . '">' . $button_label . '</a>
			</button>';

	}



}
