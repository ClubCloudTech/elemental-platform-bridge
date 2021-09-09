<?

 /**
     * Display Videoroom for a merchant storefront based on stored Buddypress XProfile Parameters for Merchants
     * Usage: In all front end storefront locations where seamless permissions video is needed.
     * Requires - WCFM - BuddyPress
     * Private function for MVR - not ready for broad plugin
     *
     * @return string
     */
    public function call_storefront_shortcode()
    {
        global $WCFM;
        $post                 = \get_post();    // getting current page information to compare parent owners
        $currentpost_store_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $post->ID);

        // Get Room Template from Vendor and handle blank returns
        $field_name_or_id = $this->get_instance(SiteDefaults::class)->defaults( 'xprofile_storefront_field', $currentpost_store_id);

        $layout_id = \xprofile_get_field_data( $field_name_or_id, $currentpost_store_id);
        if (! $layout_id) {
            $layout_id = $this->get_instance(SiteDefaults::class)->defaults( 'xprofile_storefront_sitedefault' );// get site details from user1's backup site field
        }

        // deal with logged in users who arent store owners, staff or vendors.
        $user       = \wp_get_current_user();
        $user_roles = $this->get_instance(UserRoles::class);

        $myvideoroom_app = MyVideoRoomApp::create_instance(
            $this->get_instance(SiteDefaults::class)->room_map( 'store', $currentpost_store_id),
            $layout_id,
        );

        // Get Reception Setting - for Guests.
        
        $reception_setting       = $this->get_instance(VideoHelpers::class)->get_enable_reception_state( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);
        $reception_template      = $this->get_instance(VideoHelpers::class)->get_reception_template( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);
        $video_template          = $this->get_instance(VideoHelpers::class)->get_videoroom_template( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);
        $video_reception_state   = $this->get_instance(VideoHelpers::class)->get_video_reception_state( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);
        $video_reception_url     = $this->get_instance(VideoHelpers::class)->get_video_reception_url( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);
        $show_floorplan          = $this->get_instance(VideoHelpers::class)->get_show_floorplan( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);



        $reception_setting  = $this->get_instance(SiteDefaults::class)->get_layout_id( 'store_privacy', $currentpost_store_id);
        $reception_template = $this->get_instance(SiteDefaults::class)->get_layout_id( 'store_reception_template', $currentpost_store_id);
        if ( $reception_setting) {
            $myvideoroom_app->enable_reception()
                                ->set_reception_id( $reception_template);
        }

        if (
            ! \is_user_logged_in() || (
                ! $user_roles->is_wordpress_administrator() &&
                ! $user_roles->is_wcfm_shop_staff() &&
                ! $user_roles->is_wcfm_vendor()
            )
        ) {
            return $myvideoroom_app->output_shortcode();
        }

        // get meta data from currently logged in user and return the parent vendor id We use this to know if a user is a child merchant/staff etc.
        $my_vendor_id = \get_user_meta( $user->id, '_wcfm_vendor', true);
        $my_owner_id  = \get_current_user_id();

        // Give Administrators or Store Manager Rights.
        if ( $user_roles->is_wordpress_administrator() || $user_roles->is_wcfm_store_manager() ) {
            if ( $my_owner_id === $currentpost_store_id) {
                // in case Admin has their own store.
                $myvideoroom_app->enable_admin();
            } else {
                // administrators and store managers do not see reception so they emulate the store owners.
                $myvideoroom_app->disable_reception();
            }
            //Not yet implemented

            $permissions_page = \ElementalPlugin\Factory::get_instance( \ElementalPlugin\Shortcode\SecurityVideoPreference::class )->choose_settings(
                $user_id,
                SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);


            return $myvideoroom_app->output_shortcode();
        }

        // Switch Store Owner from Staff and Other.
        // echo Check is a WCFM vendor, or store staff.

        if (
            ( $user_roles->is_wcfm_vendor() && $my_owner_id === $currentpost_store_id) || // case of an Owner in their own store.
            ( $user_roles->is_wcfm_shop_staff() && $my_vendor_id === $currentpost_store_id) // case of a Staff Member in their own store.
        ) {
            $myvideoroom_app->enable_admin();
        }

        return $myvideoroom_app->output_shortcode();
    }