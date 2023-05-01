<?php
/**
 * Outputs a Membership Confirmation
 *
 * @package ElementalPlugin\Module\Membership\Views\confirmation-page.php
 */

/**
 * Renders a Membership Screen operation confirmation
 *
 * @param string $message - Message to Display.
 * @param string $confirmation_button_approved - Button to Display for Approved.
 * @param string $confirmation_button_cancel - Button to Display for rejected.
 */
return function (
	string $message = null,
	string $confirmation_button_approved,
	string $confirmation_button_cancel
): string {
	// Check Nonce for Operation.

	if ( ! $message ) {
		$message = esc_html__( 'do this ?', 'elemental' );
	}
	ob_start();
	?>

<div id="elemental-basket-section-confirmation" class=" elemental-woocommerce-basket elemental-nav-settingstabs-outer-wrap elemental-table-row elemental-welcome-page">
	<?php
	echo sprintf(
	/* translators: %s is the message variant translated above */
		\esc_html__(
			'Are you sure you want to %s',
			'elemental'
		),
		esc_html( $message )
	);

	?>

	<table id="elemental-confirmation-table" class="wp-list-table widefat plugins elemental-shopping-basket-frame">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-name column-primary">
					<?php
				//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Function is Icon only, and already escaped within it.
					echo $confirmation_button_approved;
					?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
					<?php
				//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Function is Icon only, and already escaped within it.
					echo $confirmation_button_cancel;
					?>
				</th>
			</tr>
		</thead>
</div>

	<?php
	return ob_get_clean();
};
