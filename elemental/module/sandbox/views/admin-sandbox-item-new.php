<?php
/**
 * Shows Membership Room Items
 *
 * @package module/sandbox/views/membership-item-new.php
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\MeetingIdGenerator;

/**
 * Render the Membership Items.
 *
 * @param array $sandbox_item data room
 *
 * @return string
 */
return function (
): string {
	ob_start();
	?>
	<tr class="active mvr-table-mobile" data-room-id="<?php echo esc_attr( $sandbox_item['level'] ); ?>">
	<td>

	<input type="checkbox" 
		class="elemental-membership-template"	
		name="status_enabled"
		id = "status_enabled_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>"
		data-field="<?php echo esc_attr( Factory::get_instance( MeetingIdGenerator::class )->encrypt_string( 'enabled' ) ); ?>"
		data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		<?php echo $sandbox_item && $sandbox_item['enabled'] ? 'checked' : ''; ?>
	/></input>
		</td>

	<td class="plugin-title column-primary">
	<label for="tab_name">
		<input type="text" max_length="255"
		size="25" rows="2" name="tab_name" 
		data-field="<?php echo esc_attr( Factory::get_instance( MeetingIdGenerator::class )->encrypt_string( 'tab_name' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['tab_name'] ); ?>" 
		placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="tab_name_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>

	<td class="plugin-title column-primary">
	<label for="user_name_prepend">
		<input type="text" max_length="255"
		size="25" rows="2" name="user_name_prepend" 
		data-field="<?php echo esc_attr( Factory::get_instance( MeetingIdGenerator::class )->encrypt_string( 'user_name_prepend' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['user_name_prepend'] ); ?>" 
		placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="user_name_prepend_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>

	<td class="plugin-title column-primary">
	<label for="destination_url">
		<input type="text" max_length="255"
		size="25" rows="2" name="destination_url" 
		data-field="<?php echo esc_attr( Factory::get_instance( MeetingIdGenerator::class )->encrypt_string( 'destination_url' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['destination_url'] ); ?>" 
		placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="destination_url<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>

	<td class="plugin-title column-primary">
	<label for="customfield1">
		<input type="text" max_length="255"
		size="25" rows="2" name="customfield1" 
		data-field="<?php echo esc_attr( Factory::get_instance( MeetingIdGenerator::class )->encrypt_string( 'customfield1' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['customfield1'] ); ?>" 
		placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="customfield1<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>

	<td class="plugin-title column-primary">
	<label for="customfield2">
		<input type="text" max_length="255"
		size="25" rows="2" name="customfield1" 
		data-field="<?php echo esc_attr( Factory::get_instance( MeetingIdGenerator::class )->encrypt_string( 'customfield2' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['customfield2'] ); ?>" 
		placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="customfield2<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>

	<td class="plugin-title column-primary">
	<label for="private_key">
		<input type="text" max_length="255"
		size="25" rows="2" name="private_key"
		data-field="<?php echo esc_attr( Factory::get_instance( MeetingIdGenerator::class )->encrypt_string( 'private_key' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['private_key'] ); ?>" 
		placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="private_key<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>




	</tr>


	<?php

	return ob_get_clean();
};
