<?php
/**
 * Shortcodes for headers
 * This area contains all of the functions pertaining to switching My Video Room monitors
 *
 * @package ElementalPlugin\Core
 */

namespace ElementalPlugin\Core;

use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\Shortcode\MyVideoRoomMonitor;
use ElementalPlugin\WoocommerceBookings\WCHelpers;

/**
 * Class HeaderSwitches
 */
class HeaderSwitches extends Shortcode {



	/**
	 * Install the shortcode
	 */
	public function install() {
		$this->add_shortcode( 'storeheader', array( $this, 'store_header_monitor_shortcode' ) );
	}

	/**
	 * Renders the store header room monitors for all roles.
	 *
	 * @return string
	 */
	public function store_header_monitor_shortcode(): string {
		// getting base parameters.
		$user_roles   = $this->get_instance( UserRoles::class );
		$user         = \wp_get_current_user();
		$user_id      = $user->ID;
		$output_array = array();

		$site_defaults = $this->get_instance( SiteDefaults::class );

		// Adding basics to Table.
		$output_array[] = \do_shortcode( '[wcfm_notifications]' );

		// Administrators Branch.
		if ( $user_roles->is_wordpress_administrator() ) {
			// Management Boardroom.
			$output_array[] = $this->generate_watcher_shortcode(
				$site_defaults->room_map( 'managementbr' ),
				'/communications/management-meeting/',
				'Management Boardroom {{count}} Visitor Waiting',
				'Management Boardroom {{count}} Visitors Waiting',
				'reception',
				'.'
			);
		}

		// Shop Owners.
		if ( $user_roles->is_wcfm_vendor()
			&& ! $user_roles->is_wcfm_shop_staff()
		) {
			$output_array[] = $this->generate_watcher_shortcode(
				$site_defaults->room_map( 'store', $user_id ),
				\get_site_url() . '/myarea/video/',
				$this->get_instance( WCHelpers::class )->get_my_store() . ' {{count}} Visitor Waiting',
				$this->get_instance( WCHelpers::class )->get_my_store() . ' {{count}} Visitors Waiting',
				'seated'
			);
		}

		// Staff - has to be different as owners UserID must be used to get store.
		if ( $user_roles->is_wcfm_shop_staff() ) {
			$output_array[] = $this->generate_watcher_shortcode(
				$site_defaults->room_map(
					'store',
					$this->get_instance( WCHelpers::class )->get_my_store( 'id' )
				),
				\get_site_url() . '/myarea/video/',
				$this->get_instance( WCHelpers::class )->get_my_store() . ' {{count}} Visitor Waiting',
				$this->get_instance( WCHelpers::class )->get_my_store() . ' {{count}} Visitors Waiting',
				'seated'
			);

			$bookings = $this->get_instance( WCHelpers::class )->get_vendor_upcoming_bookings();

			foreach ( $bookings as $booking_id ) {
				$output_array[] = $this->generate_watcher_shortcode(
					$site_defaults->room_map( 'bookings', $booking_id ),
					\get_site_url() . '/go?booking=' . $booking_id,
					'Booking ' . $booking_id . ' {{count}} Guest waiting',
					'Booking ' . $booking_id . ' {{count}} Guests waiting',
					'seated'
				);
			}
		}

		// Section for Everyone.
		$output_array[] = $this->generate_watcher_shortcode(
			$site_defaults->room_map( 'userbr', $user_id ),
			\bp_core_get_user_domain( $user_id ) . 'personal-video/',
			'{{count}} Visitor Waiting- Personal Meeting Space',
			'{{count}} Visitors Waiting- Personal Meeting Space'
		);

		return implode( '', $output_array );
	}

	/**
	 * Generate the watcher shortcode
	 *
	 * @param string $name         The name of the room to watch.
	 * @param string $href         The link to navigate to when clicking on the message.
	 * @param string $single_text  The text if a single person is waiting.
	 * @param string $plural_text  The text when multiple people are waiting.
	 * @param string $type         The type of monitor.
	 * @param string $loading_text The text to show while the monitor is loading.
	 *
	 * @return string
	 */
	private function generate_watcher_shortcode(
		string $name,
		string $href,
		string $single_text,
		string $plural_text,
		string $type = 'reception',
		string $loading_text = ' '
	) : string {
		return MyVideoRoomMonitor::create_instance(
			$type,
			$name,
			$loading_text,
			' ',
			$this->generate_watcher_text( $href, $single_text ),
			$single_text,
			$this->generate_watcher_text( $href, $plural_text ),
			$plural_text
		)->output_shortcode();
	}

	/**
	 * Generate text for watcher
	 *
	 * @param string $href  The href to navigate to when clicking on the notification.
	 * @param string $title The title text of the notification.
	 *
	 * @return string
	 */
	private function generate_watcher_text( string $href, string $title ) : string {
		return <<<HTML
			<a
				href="${href}"
				title="${title}"
				class="wcfm_header_panel_messages text_tip"
			>
				<i class="wcfmfa fa-bell"></i>
				<span class="unread_notification_count message_count">{{count}}</span>
				<div class="notification-ring"></div>
			</a>
HTML;
	}
}
