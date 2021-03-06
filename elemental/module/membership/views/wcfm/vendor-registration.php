<?php
/**
 * WCFM plugin view
 *
 * WCFMgs Memberships Template
 *
 * @author      WC Lovers modified by ClubCloud for Elemental
 * @package     wcfmvm/templates
 * @version   1.0.0cc
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.

/**
 * Render the admin page
 *
 * @return string
 */
return function ( $user_id_inbound ): string {
	ob_start();
	global $WCFM, $WCFMvm;

	$user_id             = 0;
	$user_name           = '';
	$user_email          = '';
	$first_name          = '';
	$last_name           = '';
	$store_name          = '';
	$wcfmvm_static_infos = array();
	$wcfmvm_custom_infos = array();

	$email_verification = '';
	$sms_verification   = '';

		$user_id      = $user_id_inbound;
		$current_user = get_userdata( $user_id );
		// Fetching User Data.
	if ( $current_user && ! empty( $current_user ) ) {
		$user_name           = $current_user->user_login;
		$user_email          = $current_user->user_email;
		$first_name          = $current_user->first_name;
		$last_name           = '';
		$store_name          = get_user_meta( $user_id, 'store_name', true );
		$wcfmvm_static_infos = get_user_meta( $user_id, 'wcfmvm_static_infos', true );
		$wcfmvm_custom_infos = (array) get_user_meta( $user_id, 'wcfmvm_custom_infos', true );
	}

	$wcfm_membership_options  = get_option( 'wcfm_membership_options', array() );
	$membership_type_settings = array();
	if ( isset( $wcfm_membership_options['membership_type_settings'] ) ) {
		$membership_type_settings = $wcfm_membership_options['membership_type_settings'];
	}

	if ( apply_filters( 'wcfm_is_allow_email_verification', true ) ) {
		$email_verification = isset( $membership_type_settings['email_verification'] ) ? 'yes' : '';
	}

	if ( apply_filters( 'wcfm_is_allow_sms_verification', true ) && function_exists( 'wcfm_is_store_page' ) ) {
		$sms_verification = isset( $membership_type_settings['sms_verification'] ) ? 'yes' : '';
	}

	$wcfmvm_registration_static_fields = wcfm_get_option( 'wcfmvm_registration_static_fields', array() );

	$wcfmvm_registration_custom_fields = wcfm_get_option( 'wcfmvm_registration_custom_fields', array() );

	?>

	<div id="wcfm_membership_container">
	<form id="wcfm_membership_registration_form" class="wcfm">
			<div class="wcfm-container">
			<div id="wcfm_membership_registration_form_expander" class="wcfm-content">
					<?php
					do_action( 'begin_wcfm_membership_registration_form' );
					if ( $user_id ) {
						$registratio_user_fields = array(
							'user_name'  => array(
								'label'             => esc_html__( 'Username', 'wc-multivendor-membership' ),
								'type'              => 'text',
								'custom_attributes' => array( 'required' => 1 ),
								'attributes'        => array( 'readonly' => true ),
								'class'             => 'wcfm-text wcfm_ele ',
								'label_class'       => 'wcfm_ele wcfm_title',
								'value'             => $user_name,
							),
							'user_email' => array(
								'label'             => esc_html__( 'Email', 'wc-multivendor-membership' ),
								'type'              => 'text',
								'custom_attributes' => array( 'required' => 1 ),
								'attributes'        => array( 'readonly' => true ),
								'class'             => 'wcfm-text wcfm_ele ',
								'label_class'       => 'wcfm_ele wcfm_title',
								'value'             => $user_email,
							),
							'member_id'  => array(
								'type'  => 'hidden',
								'value' => $user_id,
							),
						);
					} else {
						$registratio_user_fields = array(
							'user_name'  => array(
								'label'             => esc_html__( 'Username', 'wc-multivendor-membership' ),
								'type'              => 'text',
								'custom_attributes' => array( 'required' => 1 ),
								'class'             => 'wcfm-text wcfm_ele ',
								'label_class'       => 'wcfm_ele wcfm_title',
								'value'             => $user_name,
							),
							'user_email' => array(
								'label'             => esc_html__( 'Email', 'wc-multivendor-membership' ),
								'type'              => 'text',
								'custom_attributes' => array( 'required' => 1 ),
								'class'             => 'wcfm-text wcfm_ele ',
								'label_class'       => 'wcfm_ele wcfm_title',
								'value'             => $user_email,
							),
						);
					}

						$is_user_name = isset( $wcfmvm_registration_static_fields['user_name'] ) ? 'yes' : '';
					if ( ! $is_user_name ) {
						unset( $registratio_user_fields['user_name'] );
					}

						$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_membership_registration_fields_username', $registratio_user_fields ) );

					if ( apply_filters( 'wcfm_is_allow_email_verification', true ) ) {
						if ( $email_verification ) {
							$email_verified = false;
							if ( $user_id ) {
								$email_verified          = get_user_meta( $user_id, '_wcfm_email_verified', true );
								$wcfm_email_verified_for = get_user_meta( $user_id, '_wcfm_email_verified_for', true );
								if ( $user_email !== $wcfm_email_verified_for ) {
									$email_verified = false;
								}
							}

							if ( $user_id && $email_verified ) {
								?>
									<div class="wcfm_email_verified">
										<span class="wcfmfa fa-envelope wcfm_email_verified_icon">
											<span class="wcfm_email_verified_text"><?php esc_html_e( 'Email already verified', 'wc-multivendor-membership' ); ?></span>
											<input type="hidden" name="email_verified" value="true" />
										</span>
									</div>
									<div class="wcfm_clearfix"></div>
									<?php
							} else {
								?>
									<div class="wcfm_email_verified">
										<input type="text" name="wcfm_email_verified_input" data-required="1" data-required_message="<?php esc_html_e( 'Email Verification Code: ', 'wc-multivendor-membership' ) . esc_html_e( 'This field is required.', 'wc-frontend-manager' ); ?>" class="wcfm-text wcfm_email_verified_input" placeholder="<?php esc_html_e( 'Verification Code', 'wc-multivendor-membership' ); ?>" value="" />
										<input type="button" name="wcfm_email_verified_button" class="wcfm-text wcfm_submit_button wcfm_email_verified_button" value="<?php esc_html_e( 'Re-send Code', 'wc-multivendor-membership' ); ?>" />
										<div class="wcfm_clearfix"></div>
									</div>
									<div class="wcfm-message email_verification_message" tabindex="-1"></div>
									<div class="wcfm_clearfix"></div>
									<?php
							}
						}
					}

					if ( apply_filters( 'wcfm_is_allow_sms_verification', true ) ) {
						if ( $sms_verification ) {
							$sms_verified = false;
							$user_phone   = '';
							if ( $user_id ) {
								$vendor_data = get_user_meta( $user_id, 'wcfmmp_profile_settings', true );
								$user_phone  = isset( $vendor_data['phone'] ) ? esc_attr( $vendor_data['phone'] ) : '';
								if ( ! $user_phone ) {
									$user_phone = get_user_meta( $user_id, 'billing_phone', true ); }
								$sms_verified          = get_user_meta( $user_id, '_wcfm_sms_verified', true );
								$wcfm_sms_verified_for = get_user_meta( $user_id, '_wcfm_sms_verified_for', true );
								if ( $user_phone !== $wcfm_sms_verified_for ) {
									$sms_verified = false;
								}
							}

							$WCFM->wcfm_fields->wcfm_generate_form_field(
								apply_filters(
									'wcfm_membership_registration_fields_phone',
									array(
										'user_phone' => array(
											'label'       => esc_html__( 'Store Phone', 'wc-frontend-manager' ),
											'type'        => 'text',
											'name'        => 'wcfmvm_static_infos[phone]',
											'custom_attributes' => array( 'required' => 1 ),
											'class'       => 'wcfm-text wcfm_ele',
											'label_class' => 'wcfm_title wcfm_ele',
											'value'       => $user_phone,
										),
									)
								)
							);

							if ( $user_id && $sms_verified ) {
								?>
									<div class="wcfm_sms_verified">
										<span class="wcfmfa fa-phone wcfm_sms_verified_icon">
											<span class="wcfm_sms_verified_text"><?php esc_html_e( 'Phone already verified', 'wc-multivendor-membership' ); ?></span>
											<input type="hidden" name="sms_verified" value="true" />
										</span>
									</div>
									<div class="wcfm_clearfix"></div>
									<?php
							} else {
								?>
									<div class="wcfm_sms_verified">
										<input type="text" name="wcfm_sms_verified_input" data-required="1" data-required_message="<?php esc_html_e( 'Phone Verification Code: ', 'wc-multivendor-membership' ) . esc_html_e( 'This field is required.', 'wc-frontend-manager' ); ?>" class="wcfm-text wcfm_sms_verified_input" placeholder="<?php esc_html_e( 'OTP', 'wc-multivendor-membership' ); ?>" value="" />
										<input type="button" name="wcfm_sms_verified_button" class="wcfm-text wcfm_submit_button wcfm_sms_verified_button" value="<?php esc_html_e( 'Re-send Code', 'wc-multivendor-membership' ); ?>" />
										<div class="wcfm_clearfix"></div>
									</div>
									<div class="wcfm-message sms_verification_message" tabindex="-1"></div>
									<div class="wcfm_clearfix"></div>
									<?php
							}
						}
					}

						$is_first_name = isset( $wcfmvm_registration_static_fields['first_name'] ) ? 'yes' : '';
						$is_last_name  = isset( $wcfmvm_registration_static_fields['last_name'] ) ? 'yes' : '';

						$registration_name_fields = apply_filters(
							'wcfm_membership_registration_fields',
							array(
								'first_name' => array(
									'label'       => esc_html__( 'First Name', 'wc-multivendor-membership' ),
									'type'        => 'text',
									'class'       => 'wcfm-text wcfm_ele ',
									'label_class' => 'wcfm_ele wcfm_title',
									'value'       => $first_name,
								),
								'last_name'  => array(
									'label'       => esc_html__( 'Last Name', 'wc-multivendor-membership' ),
									'type'        => 'text',
									'class'       => 'wcfm-text wcfm_ele ',
									'label_class' => 'wcfm_ele wcfm_title',
									'value'       => $last_name,
								),
								'store_name' => array(
									'label'             => esc_html__( 'Store Name', 'wc-multivendor-membership' ),
									'type'              => 'text',
									'custom_attributes' => array( 'required' => 1 ),
									'class'             => 'wcfm-text wcfm_ele wcfm_name_input',
									'label_class'       => 'wcfm_ele wcfm_title',
									'value'             => $store_name,
								),
							)
						);

	if ( ! $is_first_name ) {
		unset( $registration_name_fields['first_name'] );
	}
	if ( ! $is_last_name ) {
		unset( $registration_name_fields['last_name'] );
	}

	if ( $WCFM->is_marketplace && ( 'wcfmmarketplace' === $WCFM->is_marketplace ) ) {
		global $WCFMmp;
		if ( ! apply_filters( 'wcfm_is_allow_store_name', true ) || ! $WCFMmp->wcfmmp_vendor->is_vendor_sold_by() || wcfm_is_vendor() ) {
			unset( $registration_name_fields['store_name'] );
		}
	}

						$WCFM->wcfm_fields->wcfm_generate_form_field( $registration_name_fields );

	if ( $WCFM->is_marketplace && ( 'wcfmmarketplace' === $WCFM->is_marketplace ) ) {
		global $WCFMmp;
		if ( apply_filters( 'wcfm_is_allow_store_name', true ) && $WCFMmp->wcfmmp_vendor->is_vendor_sold_by() && ! wcfm_is_vendor() ) {
			$wcfm_store_url = get_option( 'wcfm_store_url', 'store' );
			$store_slug     = '[' . esc_html__( 'your_store', 'wc-multivendor-membership' ) . ']';
			if ( $store_name ) {
				$store_slug = sanitize_title( $store_name );
			}
			echo '<p class="description wcfm_store_slug_verified wcfm_page_options_desc" data-avail="' . esc_html__( 'Available', 'wc-multivendor-membership' ) . '" data-unavail="' . esc_html__( 'Un-available', 'wc-multivendor-membership' ) . '">' . esc_url( trailingslashit( get_site_url() ) ) . esc_url( $wcfm_store_url ) . '/<span class="wcfm_store_slug">' . esc_url( $store_slug ) . '</span></p>';
		}
	}

						do_action( 'wcfm_membership_registration_form_before_static_custom_fields' );

						// Registration Static Field Support - 1.0.6.
						$terms      = '';
						$terms_page = '';
	if ( ! empty( $wcfmvm_registration_static_fields ) ) {
		foreach ( $wcfmvm_registration_static_fields as $wcfmvm_registration_static_field => $wcfmvm_registration_static_field_val ) {
			$field_value = array();
			$field_name  = 'wcfmvm_static_infos[' . $wcfmvm_registration_static_field . ']';

			if ( $wcfmvm_static_infos && is_array( $wcfmvm_static_infos ) && ! empty( $wcfmvm_static_infos ) && ! array_filter( $wcfmvm_static_infos ) ) {
				$field_value = isset( $wcfmvm_static_infos[ $wcfmvm_registration_static_field ] ) ? $wcfmvm_static_infos[ $wcfmvm_registration_static_field ] : array();
			} elseif ( $user_id ) {
				$vendor_data = get_user_meta( $user_id, 'wcfmmp_profile_settings', true );

				$field_value['addr_1']  = isset( $vendor_data['address']['street_1'] ) ? $vendor_data['address']['street_1'] : get_user_meta( $user_id, 'billing_address_1', true );
				$field_value['addr_2']  = isset( $vendor_data['address']['street_2'] ) ? $vendor_data['address']['street_2'] : get_user_meta( $user_id, 'billing_address_2', true );
				$field_value['city']    = isset( $vendor_data['address']['city'] ) ? $vendor_data['address']['city'] : get_user_meta( $user_id, 'billing_city', true );
				$field_value['zip']     = isset( $vendor_data['address']['zip'] ) ? $vendor_data['address']['zip'] : get_user_meta( $user_id, 'billing_postcode', true );
				$field_value['country'] = isset( $vendor_data['address']['country'] ) ? $vendor_data['address']['country'] : get_user_meta( $user_id, 'billing_country', true );
				$field_value['state']   = isset( $vendor_data['address']['state'] ) ? $vendor_data['address']['state'] : get_user_meta( $user_id, 'billing_state', true );

			}

			switch ( $wcfmvm_registration_static_field ) {
				case 'address':
									// GEO Locate Support.
					if ( is_user_logged_in() && ( ! isset( $field_value['country'] ) || ( isset( $field_value['country'] ) && empty( $field_value['country'] ) ) ) ) {
							$user_location = get_user_meta( $user_id, 'wcfm_user_location', true );
						if ( $user_location ) {
										$field_value['country'] = $user_location['country'];
										$field_value['state']   = $user_location['state'];
										$field_value['city']    = $user_location['city'];
						}
					}

					if ( apply_filters( 'wcfm_is_allow_wc_geolocate', true ) && class_exists( 'WC_Geolocation' ) && ( ! isset( $field_value['country'] ) || ( isset( $field_value['country'] ) && empty( $field_value['country'] ) ) ) ) {
						$user_location          = \WC_Geolocation::geolocate_ip();
						$field_value['country'] = $user_location['country'];
						$field_value['state']   = $user_location['state'];
					}

									$WCFM->wcfm_fields->wcfm_generate_form_field(
										apply_filters(
											'wcfm_membership_registration_fields_address',
											array(
												'addr_1'  => array(
													'label' => esc_html__( 'Address 1', 'wc-frontend-manager' ),
													'type' => 'text',
													'name' => $field_name . '[addr_1]',
													'custom_attributes' => array( 'required' => 1 ),
													'class' => 'wcfm-text wcfm_ele',
													'label_class' => 'wcfm_title wcfm_ele',
													'value' => isset( $field_value['addr_1'] ) ? $field_value['addr_1'] : '',
												),
												'addr_2'  => array(
													'label' => esc_html__( 'Address 2', 'wc-frontend-manager' ),
													'type' => 'text',
													'name' => $field_name . '[addr_2]',
													'class' => 'wcfm-text wcfm_ele',
													'label_class' => 'wcfm_title wcfm_ele',
													'value' => isset( $field_value['addr_2'] ) ? $field_value['addr_2'] : '',
												),
												'country' => array(
													'label' => esc_html__( 'Country', 'wc-frontend-manager' ),
													'type' => 'country',
													'name' => $field_name . '[country]',
													'custom_attributes' => array( 'required' => 1 ),
													'class' => 'wcfm-select wcfm_ele wcfmvm_country_to_select',
													'label_class' => 'wcfm_title wcfm_ele',
													'attributes' => array( 'style' => 'width: 60%;' ),
													'value' => isset( $field_value['country'] ) ? $field_value['country'] : '',
												),
												'city'    => array(
													'label' => esc_html__( 'City/Town', 'wc-frontend-manager' ),
													'type' => 'text',
													'name' => $field_name . '[city]',
													'class' => 'wcfm-text wcfm_ele',
													'label_class' => 'wcfm_title wcfm_ele',
													'value' => isset( $field_value['city'] ) ? $field_value['city'] : '',
												),
												'state'   => array(
													'label' => esc_html__( 'State/County/Province', 'wc-frontend-manager' ),
													'type' => 'select',
													'name' => $field_name . '[state]',
													'class' => 'wcfm-select wcfm_ele wcfmvm_state_to_select',
													'label_class' => 'wcfm_title wcfm_ele',
													'options' => isset( $field_value['state'] ) ? array( $field_value['state'] => $field_value['state'] ) : array(),
													'value' => isset( $field_value['state'] ) ? $field_value['state'] : '',
												),
												'zip'     => array(
													'label' => esc_html__( 'Postcode/Zip', 'wc-frontend-manager' ),
													'type' => 'text',
													'name' => $field_name . '[zip]',
													'custom_attributes' => array( 'required' => 1 ),
													'class' => 'wcfm-text wcfm_ele',
													'label_class' => 'wcfm_title wcfm_ele',
													'value' => isset( $field_value['zip'] ) ? $field_value['zip'] : '',
												),
											)
										)
									);
					break;

				case 'phone':
					if ( ! apply_filters( 'wcfm_is_allow_sms_verification', true ) || ! $sms_verification ) {
						if ( is_array( $field_value ) ) {
							$field_value = '';
						}
						if ( ! $field_value && $user_id ) {
							$field_value = get_user_meta( $user_id, 'billing_phone', true );
						}
						$WCFM->wcfm_fields->wcfm_generate_form_field(
							apply_filters(
								'wcfm_membership_registration_fields_phone',
								array(
									'phone' => array(
										'label'       => esc_html__( 'Store Phone', 'wc-frontend-manager' ),
										'type'        => 'text',
										'name'        => $field_name,
										'custom_attributes' => array( 'required' => 1 ),
										'class'       => 'wcfm-text wcfm_ele',
										'label_class' => 'wcfm_title wcfm_ele',
										'value'       => $field_value,
									),
								)
							)
						);
					}
					break;

				case 'terms':
					$terms = 'active';
					break;

				case 'terms_page':
					$terms_page = $wcfmvm_registration_static_field_val;
					break;

				default:
					do_action( 'wcfmvm_registration_static_field_show', $wcfmvm_registration_static_field, $field_name, $field_value );
					break;
			}
		}
	}

						do_action( 'wcfm_membership_registration_form_after_static_custom_fields' );

						do_action( 'wcfm_membership_registration_form_before_dynamic_custom_fields' );

						// Registration Custom Field Support - 1.0.5.
	if ( ! empty( $wcfmvm_registration_custom_fields ) && ! wcfm_is_vendor() ) {
		foreach ( $wcfmvm_registration_custom_fields as $wcfmvm_registration_custom_field ) {
			if ( ! isset( $wcfmvm_registration_custom_field['enable'] ) ) {
				continue;
			}
			if ( ! $wcfmvm_registration_custom_field['label'] ) {
				continue;
			}

			$field_class = '';
			$field_value = '';

			$wcfmvm_registration_custom_field['name'] = sanitize_title( $wcfmvm_registration_custom_field['label'] );
			$field_name                               = 'wcfmvm_custom_infos[' . $wcfmvm_registration_custom_field['name'] . ']';
			$field_id                                 = md5( $field_name );

			if ( ! empty( $wcfmvm_custom_infos ) ) {
				if ( 'checkbox' === $wcfmvm_registration_custom_field['type'] ) {
					$field_value = isset( $wcfmvm_custom_infos[ $wcfmvm_registration_custom_field['name'] ] ) ? $wcfmvm_custom_infos[ $wcfmvm_registration_custom_field['name'] ] : 'no';
				} else {
					$field_value = isset( $wcfmvm_custom_infos[ $wcfmvm_registration_custom_field['name'] ] ) ? $wcfmvm_custom_infos[ $wcfmvm_registration_custom_field['name'] ] : '';
				}
			}

			// Is Required.
			$custom_attributes = array();
			if ( isset( $wcfmvm_registration_custom_field['required'] ) && $wcfmvm_registration_custom_field['required'] ) {
				$custom_attributes = array( 'required' => 1 );
			}

			$attributes = array();
			if ( 'mselect' === $wcfmvm_registration_custom_field['type'] ) {
				$field_class = 'wcfm_multi_select';
				$attributes  = array(
					'multiple' => 'multiple',
					'style'    => 'width: 60%;',
				);
			}

			switch ( $wcfmvm_registration_custom_field['type'] ) {
				case 'text':
					$WCFM->wcfm_fields->wcfm_generate_form_field(
						array(
							$field_id => array(
								'label'             => esc_html__( $wcfmvm_registration_custom_field['label'], 'WCfM' ),
								'name'              => $field_name,
								'custom_attributes' => $custom_attributes,
								'type'              => 'text',
								'class'             => 'wcfm-text',
								'label_class'       => 'wcfm_title',
								'value'             => $field_value,
								'hints'             => esc_html__(
									$wcfmvm_registration_custom_field['help_text'],
									'WCfM'
								),
							),
						)
					);
					break;

				case 'number':
					$WCFM->wcfm_fields->wcfm_generate_form_field(
						array(
							$field_id => array(
								'label'             => esc_html__( $wcfmvm_registration_custom_field['label'], 'WCfM' ),
								'name'              => $field_name,
								'custom_attributes' => $custom_attributes,
								'type'              => 'number',
								'class'             => 'wcfm-text',
								'label_class'       => 'wcfm_title',
								'value'             => $field_value,
								'hints'             => esc_html__(
									$wcfmvm_registration_custom_field['help_text'],
									'WCfM'
								),
							),
						)
					);
					break;

				case 'textarea':
					$WCFM->wcfm_fields->wcfm_generate_form_field(
						array(
							$field_id => array(
								'label'             => esc_html__( $wcfmvm_registration_custom_field['label'], 'WCfM' ),
								'name'              => $field_name,
								'custom_attributes' => $custom_attributes,
								'type'              => 'textarea',
								'class'             => 'wcfm-textarea',
								'label_class'       => 'wcfm_title',
								'value'             => $field_value,
								'hints'             => esc_html__(
									$wcfmvm_registration_custom_field['help_text'],
									'WCfM'
								),
							),
						)
					);
					break;

				case 'datepicker':
					$WCFM->wcfm_fields->wcfm_generate_form_field(
						array(
							$field_id => array(
								'label'             => esc_html__( $wcfmvm_registration_custom_field['label'], 'WCfM' ),
								'name'              => $field_name,
								'custom_attributes' => $custom_attributes,
								'type'              => 'text',
								'placeholder'       => get_option( 'date_format' ),
								'class'             => 'wcfm-text wcfm_datepicker',
								'label_class'       => 'wcfm_title',
								'value'             => $field_value,
								'hints'             => esc_html__(
									$wcfmvm_registration_custom_field['help_text'],
									'WCfM'
								),
							),
						)
					);
					break;

				case 'timepicker':
					$WCFM->wcfm_fields->wcfm_generate_form_field(
						array(
							$field_id => array(
								'label'             => esc_html__( $wcfmvm_registration_custom_field['label'], 'WCfM' ),
								'name'              => $field_name,
								'custom_attributes' => $custom_attributes,
								'type'              => 'time',
								'class'             => 'wcfm-text',
								'label_class'       => 'wcfm_title',
								'value'             => $field_value,
								'hints'             => esc_html__(
									$wcfmvm_registration_custom_field['help_text'],
									'WCfM'
								),
							),
						)
					);
					break;

				case 'checkbox':
					$WCFM->wcfm_fields->wcfm_generate_form_field(
						array(
							$field_id => array(
								'label'             => esc_html__( $wcfmvm_registration_custom_field['label'], 'WCfM' ),
								'name'              => $field_name,
								'custom_attributes' => $custom_attributes,
								'type'              => 'checkbox',
								'class'             => 'wcfm-checkbox',
								'label_class'       => 'wcfm_title checkbox-title',
								'value'             => 'yes',
								'dfvalue'           => $field_value,
								'hints'             => esc_html__(
									$wcfmvm_registration_custom_field['help_text'],
									'WCfM'
								),
							),
						)
					);
					break;

				case 'upload':
					$WCFM->wcfm_fields->wcfm_generate_form_field(
						array(
							$field_id => array(
								'label'             => esc_html__( $wcfmvm_registration_custom_field['label'], 'WCfM' ),
								'custom_attributes' => $custom_attributes,
								'type'              => 'file',
								'class'             => 'wcfm_ele',
								'label_class'       => 'wcfm_title',
								'hints'             => esc_html__(
									$wcfmvm_registration_custom_field['help_text'],
									'WCfM'
								),
							),
						)
					);
					break;

				case 'select':
				case 'mselect':
				case 'dropdown':
					$select_opt_vals = array();
					$select_options  = explode( '|', $wcfmvm_registration_custom_field['options'] );
					$is_first        = true;
					if ( ! empty( $select_options ) ) {
						foreach ( $select_options as $select_option ) {
							if ( $select_option ) {
								$select_opt_label                  = esc_html__( ucfirst( str_replace( '-', ' ', $select_option ) ), 'wc-multivendor-membership' );
								$select_opt_label                  = apply_filters( 'wcfm_registration_custom_field_select_label', $select_opt_label, $select_option );
								$select_opt_vals[ $select_option ] = $select_opt_label;
							} elseif ( $is_first ) {
								$select_opt_vals[''] = esc_html__( '-Select-', 'wc-frontend-manager' );
							}
							$is_first = false;
						}
					}
					$WCFM->wcfm_fields->wcfm_generate_form_field(
						array(
							$field_id => array(
								'label'             => esc_html__( $wcfmvm_registration_custom_field['label'], 'WCfM' ),
								'name'              => $field_name,
								'custom_attributes' => $custom_attributes,
								'attributes'        => $attributes,
								'type'              => 'select',
								'class'             => 'wcfm-select ' . $field_class,
								'label_class'       => 'wcfm_title',
								'options'           => $select_opt_vals,
								'value'             => $field_value,
								'hints'             => esc_html__(
									$wcfmvm_registration_custom_field['help_text'],
									'WCfM'
								),
							),
						)
					);
					break;

				case 'html':
					$content = nl2br( wcfm_removeslashes( $wcfmvm_registration_custom_field['content'] ) );
					if ( isset( $custom_attributes['required'] ) ) {
						unset( $custom_attributes['required'] );
					}
					$WCFM->wcfm_fields->wcfm_generate_form_field(
						array(
							$field_id => array(
								'label'             => esc_html__( $wcfmvm_registration_custom_field['label'], 'WCfM' ),
								'custom_attributes' => $custom_attributes,
								'attributes'        => array( 'style' => 'margin-bottom:25px;' ),
								'type'              => 'html',
								'class'             => 'wcfm_ele wcfm-html-content',
								'label_class'       => 'wcfm_title wcfm_html_content_title',
								'hints'             => esc_html__( $wcfmvm_registration_custom_field['help_text'], 'WCfM' ),
								'value'             => $content . '<div class="wcfm-clearfix"></div>',
							),
						)
					);
					break;
			}
		}
	}
						do_action( 'wcfm_membership_registration_form_after_dynamic_custom_fields' );

	if ( ! $user_id ) {
		$WCFM->wcfm_fields->wcfm_generate_form_field(
			apply_filters(
				'wcfm_membership_registration_fields_password',
				array(
					'passoword'         => array(
						'label'             => esc_html__( 'Password', 'wc-multivendor-membership' ),
						'type'              => 'password',
						'custom_attributes' => array(
							'required'         => 1,
							'mismatch_message' => esc_html__( 'Password and Confirm-password are not same.', 'wc-multivendor-membership' ),
						),
						'class'             => 'wcfm-text wcfm_ele ',
						'label_class'       => 'wcfm_ele wcfm_title',
						'value'             => '',
					),
					'password_strength' => array(
						'type'  => 'html',
						'value' => '<div id="password-strength-status"></div>',
					),
					'confirm_pwd'       => array(
						'label'             => esc_html__( 'Confirm Password', 'wc-multivendor-membership' ),
						'type'              => 'password',
						'custom_attributes' => array( 'required' => 1 ),
						'class'             => 'wcfm-text wcfm_ele ',
						'label_class'       => 'wcfm_ele wcfm_title',
						'value'             => '',
					),
				)
			)
		);
	}

						do_action( 'end_wcfm_membership_registration_form' );

						// Terms & Conditions support add - 1.1.9.
	if ( 'active' === $terms ) {
		?>
							<input type="checkbox" id="terms" name="wcfmvm_static_infos[terms]" class="wcfm-checkbox" value="<?php esc_html_e( 'Agree', 'wc-multivendor-membership' ); ?>" data-required="1" data-required_message="<?php esc_html_e( 'Terms & Conditions', 'wc-multivendor-membership' ); ?>: <?php esc_html_e( 'This field is required.', 'wc-frontend-manager' ); ?>">
							<p class="terms_title wcfm_title">
								<strong>
									<span class="required">*</span>
				<?php
				esc_html_e( 'Agree', 'wc-multivendor-membership' );
				echo '&nbsp;&nbsp;';
				if ( $terms_page ) {
					?>
										<a target="_blank" href="<?php echo esc_url( get_permalink( $terms_page ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'wc-multivendor-membership' ); ?></a>
																			<?php
				} else {
					esc_html_e( 'Terms & Conditions', 'wc-multivendor-membership' );
				}
				?>
								</strong>
							</p>
		<?php
	}
	?>
				</div>
				<div class="wcfm-clearfix"></div>
			</div>

			<?php if ( apply_filters( 'wcfm_is_allow_registration_recaptcha', true ) ) { ?>
				<?php if ( function_exists( 'gglcptch_init' ) ) { ?>
					<div class="wcfm_clearfix"></div>
					<div class="wcfm_gglcptch_wrapper" style="float:right;">
						<?php echo apply_filters( 'gglcptch_display_recaptcha', '', 'wcfm_registration_form' ); ?>
					</div>
				<?php } elseif ( class_exists( 'anr_captcha_class' ) && function_exists( 'anr_captcha_form_field' ) ) { ?>
					<div class="wcfm_clearfix"></div>
					<div class="wcfm_gglcptch_wrapper" style="float:right;">
						<div class="anr_captcha_field"><div id="anr_captcha_field_99"></div></div>

						<?php
						$language = trim( anr_get_option( 'language' ) );
						$lang     = '';
						if ( $language ) {
							$lang = "&hl=$language";
						}
						?>
						<script src="https://www.google.com/recaptcha/api.js?render=explicit<?php echo esc_js( $lang ); ?>"
							async defer>
						</script>
					</div>
				<?php } ?>
			<?php } ?>
			<div class="wcfm-clearfix"></div>
			<div class="wcfm-message" tabindex="-1"></div>

			<div id="wcfm_membership_registration_submit" class="wcfm_form_simple_submit_wrapper">
			<?php if ( wcfm_is_allowed_membership() ) { ?>
				<input type="submit" name="save-data" value="
				<?php
				if ( $user_id ) {
					_e( 'Confirm', 'wc-multivendor-membership' );
				} else {
					_e( 'Register', 'wc-multivendor-membership' ); }
				?>
				" id="wcfm_membership_register_button" class="wcfm_submit_button" />
				<?php if ( ! apply_filters( 'wcfmvm_is_allow_registration_first', false ) && is_wcfm_membership_page() ) { ?>
				<a href="<?php echo apply_filters( 'wcfm_change_membership_url', get_wcfm_membership_url() ); ?>" class="wcfm_submit_button"><<&nbsp;<?php esc_html_e( 'Plans', 'wc-multivendor-membership' ); ?></a>
				<?php } ?>
				<?php } else { ?>
					<?php esc_html_e( 'Your user role is not allowed to subscribe', 'wc-multivendor-membership' ); ?>
				<?php } ?>
			</div>
			<div class="wcfm-clearfix"></div>
		</form>
	</div>
	<?php
	return ob_get_clean();

};
