<?php
/**
 * Shows Membership Room Items
 *
 * @package ElementalPlugin\Module\Membership\Views\membership-item.php
 */

/**
 * Render the Membership Items.
 *
 * @param array $memberships data room
 *
 * @return string
 */
return function (
	array $memberships
): string {
	ob_start();

	?>
	<tr class="active mvr-table-mobile" data-room-id="<?php echo esc_attr( $memberships['level'] ); ?>">
		<td class="plugin-title column-primary myvideoroom-mobile-table-row-adjust">
	<?php
	if ( $memberships['badge_url'] ) {
		?>
			<img src="<?php echo esc_url( $memberships['badge_url'] ); ?>" alt="Level">
		<?php
	}
	?>
		</td>
		<td class="column-description myvideoroom-mobile-table-row-adjust">
	<?php
	if ( $memberships['wcfm_level'] ) {
		$output = ' WCFM (' . esc_textarea( $memberships['wcfm_level'] ) . ')';
	}
		// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped - escaped two lines above.
		echo '<h2>' . esc_textarea( $memberships['label'] ) . ' UMP(' . esc_textarea( $memberships['level'] ) . ')'. $output . '</h2>';
	?>
		</td>
		<td>
	<?php
				echo esc_textarea( $memberships['price_text'] );
	?>
		</td>
		<td class="plugin-title column-primary">
		<label for="number_child">
			<input type="number" size="12" name="number_child" value="<?php echo esc_textarea( $memberships['limit'] ); ?>" placeholder="" data-level = "<?php echo esc_textarea( $memberships['level'] ); ?>" id="number_child_<?php echo esc_textarea( $memberships['level'] ); ?>" class="elemental-membership-control" />
		</label>
		<div id="confirmation_<?php echo esc_textarea( $memberships['level'] ); ?>" class = "elemental-membership-displayconf"></div>
		</td>
		<td>
		<input type="number" min="0" max="10000" name="number_template" value="<?php echo esc_textarea( $memberships['template'] ); ?>" placeholder="" data-level = "<?php echo esc_textarea( $memberships['level'] ); ?>" id="number_template_<?php echo esc_textarea( $memberships['level'] ); ?>" class="elemental-membership-template" />
		<div id="confirmation_template_<?php echo esc_textarea( $memberships['level'] ); ?>" class = "elemental-membership-displayconf"></div>
		</td>
		<td>
		<input type="number" min="0" max="10000" name="number_template" value="<?php echo esc_textarea( $memberships['landing_template'] ); ?>" placeholder="" data-level = "<?php echo esc_textarea( $memberships['level'] ); ?>" id="number_landing_template_<?php echo esc_textarea( $memberships['level'] ); ?>" class="elemental-membership-landing-template" />
		<div id="confirmation_template_<?php echo esc_textarea( $memberships['level'] ); ?>" class = "elemental-membership-displayconf"></div>
		</td>
	</tr>


	<?php

	return ob_get_clean();
};
