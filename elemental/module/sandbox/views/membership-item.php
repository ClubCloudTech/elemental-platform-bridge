<?php
/**
 * Shows Membership Room Items
 *
 * @package module/sandbox/views/membership-item.php
 */

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
	<tr class="active mvr-table-mobile" data-room-id="<?php echo esc_attr( $sandbox_item['level'] ); ?>">
		<td class="plugin-title column-primary myvideoroom-mobile-table-row-adjust">
	<?php
	if ( $sandbox_item['badge_url'] ) {
		?>
			<img src="<?php echo esc_url( $sandbox_item['badge_url'] ); ?>" alt="Level">
		<?php
	}
	?>
		</td>
		<td class="column-description myvideoroom-mobile-table-row-adjust">
	<?php
	if ( $sandbox_item['wcfm_level'] ) {
		$output = ' WCFM (' . esc_textarea( $sandbox_item['wcfm_level'] ) . ')';
	}
		// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped - escaped two lines above.
		echo '<h2>' . esc_textarea( $sandbox_item['label'] ) . ' UMP(' . esc_textarea( $sandbox_item['level'] ) . ')'. $output . '</h2>';
	?>
		</td>
		<td>
	<?php
				echo esc_textarea( $sandbox_item['price_text'] );
	?>
		</td>
		<td class="plugin-title column-primary">
		<label for="number_child">
			<input type="number" size="12" name="number_child" value="<?php echo esc_textarea( $sandbox_item['limit'] ); ?>" placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['level'] ); ?>" id="number_child_<?php echo esc_textarea( $sandbox_item['level'] ); ?>" class="elemental-membership-control" />
		</label>
		<div id="confirmation_<?php echo esc_textarea( $sandbox_item['level'] ); ?>" class = "elemental-membership-displayconf"></div>
		</td>
		<td>
		<input type="number" min="0" max="10000" name="number_template" value="<?php echo esc_textarea( $sandbox_item['template'] ); ?>" placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['level'] ); ?>" id="number_template_<?php echo esc_textarea( $sandbox_item['level'] ); ?>" class="elemental-membership-template" />
		<div id="confirmation_template_<?php echo esc_textarea( $sandbox_item['level'] ); ?>" class = "elemental-membership-displayconf"></div>
		</td>
		<td>
		<input type="number" min="0" max="10000" name="number_template" value="<?php echo esc_textarea( $sandbox_item['landing_template'] ); ?>" placeholder="" data-level = "<?php echo esc_textarea( $sandbox_item['level'] ); ?>" id="number_landing_template_<?php echo esc_textarea( $sandbox_item['level'] ); ?>" class="elemental-membership-landing-template" />
		<div id="confirmation_template_<?php echo esc_textarea( $sandbox_item['level'] ); ?>" class = "elemental-membership-displayconf"></div>
		</td>
	</tr>


	<?php

	return ob_get_clean();
};
