<?php
/**
 * Class WCFMShortcodes - Display Shortcodes for WCFM Integration
 *
 * @package wcfm/library/class-wcfmshortcodes.php
 */

namespace ElementalPlugin\Module\WCFM\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;


/**
 * Class WCFMShortcodes - Display Shortcodes for WCFM Integration
 */
class WCFMShortcodes {

	const SHORTCODE_SHOW_STAFF   = 'elemental_show_staff';
	const SHORTCODE_STORELINK    = 'elemental_storelink';
	const SHORTCODE_GETNAME      = 'elemental_wcfm_storename';
	const SHORTCODE_STORE_FIELDS = 'wcfm_store_fields';
	const SHORTCODE_STAFF_ADMIN  = 'elemental_staffadmin';

	// Backward Compatible legacy shortcode names.
	const SHORTCODE_BACK_COMPAT_GETNAME = 'ccmenu';

	/**
	 * Runtime Shortcodes and Setup
	 */
	public function init() {
		add_shortcode( self::SHORTCODE_STORE_FIELDS, array( $this, 'wcfm_store_display' ) );
		add_shortcode( self::SHORTCODE_SHOW_STAFF, array( $this, 'show_wcfm_staff' ) );
		add_shortcode( self::SHORTCODE_GETNAME, array( $this, 'elemental_getname' ) );

		// Backward Compatible legacy shortcode declarations.
		add_shortcode( self::SHORTCODE_BACK_COMPAT_GETNAME, array( $this, 'elemental_getname' ) );
	}

	/**
	 * Show WCFM Staff Shortcode Handler (or Staff and Owner).
	 *
	 * @param array $attr - Shortcode Attributes.
	 * @return string
	 */
	public function show_wcfm_staff( $attr ): string {
		if ( isset( $attr['show_owner'] ) ) {
			$show_owner = $attr['show_owner'];
		}

		return $this->show_wcfm_staff_worker( $show_owner );
	}

	/**
	 * Show WCFM Staff (and optionally Owner).
	 *
	 * @param bool $show_owner - Whether to Return the Owner as well as team.
	 * @return string
	 */
	public function show_wcfm_staff_worker( bool $show_owner = null ): string {

			$vendor_id = Factory::get_instance( WCFMTools::class )->get_wcfm_page_owner();

			$staff_user_role = apply_filters( 'wcfm_staff_user_role', 'shop_staff' );
			$args            = array(
				'role__in'    => array( $staff_user_role ),
				'orderby'     => 'ID',
				'order'       => 'ASC',
				'offset'      => 0,
				'number'      => -1,
				'meta_key'    => '_wcfm_vendor',
				'meta_value'  => $vendor_id,
				'count_total' => false,
			);

			$wcfm_staff   = get_users( $args );
			$result_count = count( $wcfm_staff );

			if ( $show_owner ) {
				$shop_staff_html = $vendor_id;
				$is_first        = false;
			} else {
				$shop_staff_html = '';
				$is_first        = true;
			}

			if ( $result_count >= 1 ) {

				foreach ( $wcfm_staff as $wcfm_staff_member ) {
					if ( ! $is_first ) {
						$shop_staff_html .= ', ';
					}
					$shop_staff_html .= $wcfm_staff_member->ID;
					$is_first         = false;
				}

				return do_shortcode( '[youzer_members type="alphabetical" include="' . $shop_staff_html . '" ]' );
			}
			return '<h1>' . esc_html__( 'This Organisation has no Member Accounts', 'myvideoroom' ) . '</h1>';
	}

	/**
	 * Elemental Get User Name for Merchants and Staff Shortcode.
	 *
	 * @param array $attr - shortcode attributes.
	 * @return string|null
	 */
	public function elemental_getname( $attr = array() ): ?string {
		if ( isset( $attr['user_id'] ) ) {
			$user_id = $attr['user_id'];
		}
		return $this->elemental_getname_worker( $user_id );
	}
	/**
	 * Elemental Get User Name for Merchants and Staff.
	 *
	 * @param int $user_id - the user id( can be left null to try current logged in user ).
	 * @return string|null
	 */
	public function elemental_getname_worker( int $user_id = null ): ?string {

		if ( ! \function_exists( 'wcfmmp_get_store' ) ) {
			return null;
		}

		if ( $user_id ) {
			$user = get_user_by( 'id', $user_id );
		} else {
			$user = wp_get_current_user();
		}

		if ( ! $user ) {
			return null;
		}

		$user_roles = Factory::get_instance( UserRoles::class );

		if ( $user_roles->is_wcfm_vendor() ) {
			return ucwords( $user->user_nicename );

		} elseif ( $user_roles->is_wcfm_shop_staff() ) {

			$parent_id  = $user->_wcfm_vendor;
			$store_user = wcfmmp_get_store( $parent_id );
			$store_info = $store_user->get_shop_info();

			return ucwords( $store_info['store_slug'] );
		}

		return ucwords( $user->user_nicename );
	}

	/**
	 * Display Store Display Information Name or Slug
	 *
	 * @param  array $attributes - the attributes.
	 * @return ?string
	 */
	public function wcfm_store_display( array $attributes ): ?string {
		$input_type = $attributes['type'];
		$store_id   = $attributes['id'];

		if ( ! $store_id ) {
			$store_id = Factory::get_instance( WCFMTools::class )->get_wcfm_page_owner();
		}

		$store_user = \wcfmmp_get_store( $store_id );
		$store_info = $store_user->get_shop_info();

		switch ( $input_type ) {
			case 'slug':
				return $store_info['store_slug'];
			case 'name':
				return $store_info['store_name'];
			case 'description':
				return $store_info['shop_description'];
			default:
				return $store_info['store_name'];
		}
	}

	/**
	 * Jquery dataTable library
	 */
	function load_datatable_lib() {
		global $WCFM;

		// JS
		wp_enqueue_script( 'dataTables_js', $WCFM->plugin_url . 'includes/libs/datatable/js/jquery.dataTables.min.js', array( 'jquery' ), $WCFM->version, true );
		wp_enqueue_script( 'dataTables_responsive_js', $WCFM->plugin_url . 'includes/libs/datatable/js/dataTables.responsive.min.js', array( 'jquery', 'dataTables_js' ), $WCFM->version, true );

		$dataTables_language = '{"processing": "' . __( 'Processing...', 'wc-frontend-manager' ) . '" , "search": "' . __( 'Search:', 'wc-frontend-manager' ) . '", "lengthMenu": "' . __( 'Show _MENU_ entries', 'wc-frontend-manager' ) . '", "info": " ' . __( 'Showing _START_ to _END_ of _TOTAL_ entries', 'wc-frontend-manager' ) . '", "infoEmpty": "' . __( 'Showing 0 to 0 of 0 entries', 'wc-frontend-manager' ) . '", "infoFiltered": "' . __( '(filtered _MAX_ entries of total)', 'wc-frontend-manager' ) . '", "loadingRecords": "' . __( 'Loading...', 'wc-frontend-manager' ) . '", "zeroRecords": "' . __( 'No matching records found', 'wc-frontend-manager' ) . '", "emptyTable": "' . __( 'No data in the table', 'wc-frontend-manager' ) . '", "paginate": {"first": "' . __( 'First', 'wc-frontend-manager' ) . '", "previous": "' . __( 'Previous', 'wc-frontend-manager' ) . '", "next": "' . __( 'Next', 'wc-frontend-manager' ) . '", "last": "' . __( 'Last', 'wc-frontend-manager' ) . '"}, "buttons": {"print": "' . __( 'Print', 'wc-frontend-manager' ) . '", "pdf": "' . __( 'PDF', 'wc-frontend-manager' ) . '", "excel": "' . __( 'Excel', 'wc-frontend-manager' ) . '", "csv": "' . __( 'CSV', 'wc-frontend-manager' ) . '"}}';
		wp_localize_script( 'dataTables_js', 'dataTables_language', $dataTables_language );

		wp_localize_script(
			'dataTables_js',
			'dataTables_config',
			array(
				'pageLength'             => apply_filters( 'wcfm_datatable_page_length', 25 ),
				'is_allow_hidden_export' => apply_filters(
					'wcfm_is_allow_datatable_hidden_export',
					false
				),
			)
		);

		// CSS
		// wp_enqueue_style( 'wcfm_responsive_css',  $this->css_lib_url . 'wcfm-style-responsive.css', array('wcfm_menu_css'), $WCFM->version );
		wp_enqueue_style( 'dataTables_css', $WCFM->plugin_url . 'includes/libs/datatable/css/jquery.dataTables.min.css', array(), $WCFM->version );
		wp_enqueue_style( 'dataTables_responsive_css', $WCFM->plugin_url . 'includes/libs/datatable/css/responsive.dataTables.min.css', array(), $WCFM->version );
	}


}
