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
			case 'photo':
				$button_label = '<span title ="' . esc_html__( 'Update Profile Picture across site.', 'elementalplugin' ) . '" class="elemental-dashicons dashicons-camera"></span>';
				$button_class = 'elemental-ul-style-menu elemental-button-separation elemental-button-override';
				$a_class      = '';
				$id           = 'elemental-photo-image';
				break;

			case 'forgetme':
				$button_label = '<span title ="' . esc_html__( 'Delete your picture, and clear temporary information', 'elementalplugin' ) . '" class="elemental-dashicons dashicons-dismiss"></span>';
				$button_class = 'elemental-ul-style-menu elemental-button-separation elemental-button-override';
				$a_class      = '';
				$id           = 'elemental-forget-me';
				break;
			case 'deletefile':
				$button_label = '<span title ="' . esc_html__( 'Delete your file', 'elementalplugin' ) . '" class="elemental-dashicons dashicons-dismiss"></span>';
				$button_class = 'elemental-ul-style-menu elemental-icon elemental-button-separation elemental-button-override';
				$a_class      = '';
				$id           = 'elemental-delete-file';
				break;
			case 'close_window':
				$button_label = '<span title ="' . esc_html__( 'Close the Window', 'elementalplugin' ) . '" class="elemental-dashicons dashicons-no"></span>';
				$button_class = 'elemental-ul-style-menu elemental-button-separation elemental-button-override';
				$a_class      = '';
				$id           = 'elemental-close-window';
				break;
		}

		return '<button id=' . $id . \wp_rand( 1, 10000 ) . ' class=" ' . $button_class . ' ' . $id . ' " ' . $payload . ' >
			<a class="' . $a_class . '">' . $button_label . '</a>
			</button>';

	}



}
