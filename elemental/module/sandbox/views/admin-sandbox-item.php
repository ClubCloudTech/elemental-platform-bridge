<?php
/**
 * Shows Membership Room Items
 *
 * @package module/sandbox/views/membership-item.php
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\Encryption;

/**
 * Render the Membership Items.
 *
 * @param array $sandbox_item data room
 *
 * @return string
 */
return function (
	array $sandbox_item
): string {
	ob_start();
	?>
	<tr class="active elemental-table-mobile" data-room-id="<?php echo esc_attr( $sandbox_item['level'] ); ?>">
	<td>

	<input type="checkbox" 
		class="elemental-sandbox-control"	
		name="status_enabled"
		id = "status_enabled_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>"
		data-field="<?php echo esc_attr( Factory::get_instance( Encryption::class )->encrypt_string( 'enabled' ) ); ?>"
		data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		<?php echo $sandbox_item && $sandbox_item['enabled'] ? 'checked' : ''; ?>
	/></input>
		</td>

	<td class="plugin-title column-primary">
	<label for="tab_name">
		<input type="text" max_length="255"
		size="25" rows="2" name="tab_name" 
		data-field="<?php echo esc_attr( Factory::get_instance( Encryption::class )->encrypt_string( 'tab_name' ) ); ?>"
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
		data-field="<?php echo esc_attr( Factory::get_instance( Encryption::class )->encrypt_string( 'user_name_prepend' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['user_name_prepend'] ); ?>" 
		placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="user_name_prepend_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>

	<td class="plugin-title column-primary">
	<label for="destination_url">
		<input type="text" max_length="2096"
		size="25" rows="2" name="destination_url" 
		data-field="<?php echo esc_attr( Factory::get_instance( Encryption::class )->encrypt_string( 'destination_url' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['destination_url'] ); ?>" 
		placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="destination_url<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>

	<td class="plugin-title column-primary">
	<label for="customfield1">
		<input type="text" max_length="4096"
		size="25" rows="2" name="customfield1" 
		data-field="<?php echo esc_attr( Factory::get_instance( Encryption::class )->encrypt_string( 'customfield1' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['customfield1'] ); ?>" 
		placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="customfield1<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>

	<td class="plugin-title column-primary">
	<label for="customfield2">
		<input type="text" max_length="4096"
		size="25" rows="2" name="customfield1" 
		data-field="<?php echo esc_attr( Factory::get_instance( Encryption::class )->encrypt_string( 'customfield2' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['customfield2'] ); ?>" 
		placeholder=""
		data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="customfield2<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>

	<td class="plugin-title column-primary">
	<label for="owner_user_id">
		<input type="number" max_length="6"
		size="6" name="owner_user_id"
		data-field="<?php echo esc_attr( Factory::get_instance( Encryption::class )->encrypt_string( 'owner_user_id' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['owner_user_id'] ); ?>" 
		placeholder=""
		data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="owner_user_id<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label><br>
	<?php echo esc_textarea( $sandbox_item['owner_user_name'] ); ?>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>

	<td class="plugin-title column-primary">
	<label for="column_priority">
		<input type="number" max_length="3"
		size="5" name="column_priority"
		data-field="<?php echo esc_attr( Factory::get_instance( Encryption::class )->encrypt_string( 'column_priority' ) ); ?>"
		value="<?php echo esc_textarea( $sandbox_item['column_priority'] ); ?>" 
		placeholder=""
		data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		id="column_priority<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		class="elemental-sandbox-control" />
	</label>
	<div id="confirmation_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" class = "elemental-membership-displayconf"></div>
	</td>

	<td>

	<input type="checkbox" 
		class="elemental-sandbox-control"	
		name="admin_enforced"
		id = "admin_enforced_<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>"
		data-field="<?php echo esc_attr( Factory::get_instance( Encryption::class )->encrypt_string( 'admin_enforced' ) ); ?>"
		data-level = "<?php echo esc_textarea( $sandbox_item['record_id'] ); ?>" 
		<?php echo $sandbox_item && $sandbox_item['admin_enforced'] ? 'checked' : ''; ?>
	/></input>
		</td>


	</tr>


	<?php

	return ob_get_clean();
};
