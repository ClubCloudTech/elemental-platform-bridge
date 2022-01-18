<?php
/**
 * WCFM plugin view
 *
 * WCFM Shop Staffs View
 *
 * @author      WC Lovers
 * @package     elemental/wcfm/views/staff-shortcode/wcfmgs-view-staffs.php
 * @version   1.0.0
 */

global $WCFM;

?>

<div class="collapse wcfm-collapse" id="wcfm_shop_listing">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-user"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Shop Staff', 'wc-frontend-manager-groups-staffs' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e( 'Manage Staff', 'wc-frontend-manager-groups-staffs' ); ?></h2>
			
			<?php
			if ( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url( 'users.php?role=shop_staff' ); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-groups-staffs' ); ?>"><span class="fab fa-wordpress"></span></a>
				<?php
			}

			if ( $wcfm_is_allow_capability_controller = apply_filters( 'wcfm_is_allow_capability_controller', true ) ) {
				echo '<a id="wcfm_capability_settings" class="add_new_wcfm_ele_dashboard text_tip" href="' . get_wcfm_capability_url() . '" data-tip="' . __( 'Capability Controller', 'wc-frontend-manager' ) . '"><span class="wcfmfa fa-user-times"></span></a>';
			}

			if ( $has_new = apply_filters( 'wcfm_add_new_staff_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="' . get_wcfm_shop_staffs_manage_url() . '" data-tip="' . __( 'Add New Staff', 'wc-frontend-manager-groups-staffs' ) . '"><span class="wcfmfa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
			}
			?>
			
			<?php	echo apply_filters( 'wcfm_staffs_limit_label', '' ); ?>
			
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
			
		<?php do_action( 'before_wcfm_shop_staffs' ); ?>
		
		<div class="wcfm-container">
			<div id="wwcfm_shop_staffs_expander" class="wcfm-content">
				<table id="wcfm-shop-staffs" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php _e( 'Staff', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php echo apply_filters( 'wcfm_sold_by_label', '', __( 'Store', 'wc-frontend-manager' ) ); ?></th>
							<th><?php _e( 'Name', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Email', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-groups-staffs' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php _e( 'Staff', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php echo apply_filters( 'wcfm_sold_by_label', '', __( 'Store', 'wc-frontend-manager' ) ); ?></th>
							<th><?php _e( 'Name', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Email', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-groups-staffs' ); ?></th>
						</tr>
					</tfoot>
				</table>
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<?php
		do_action( 'after_wcfm_shop_staffs' );
		?>
	</div>
</div>
