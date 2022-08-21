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
		$message = esc_html__( 'do this ?', 'myvideoroom' );
	}
	ob_start();
	?>

<div id="mvr-basket-section-confirmation" class=" mvr-woocommerce-basket mvr-nav-settingstabs-outer-wrap mvr-table-row elemental-welcome-page">
	<?php
	echo sprintf(
	/* translators: %s is the message variant translated above */
		\esc_html__(
			'Are you sure you want to %s',
			'myvideoroom'
		),
		esc_html( $message )
	);

	?>

	<table id="mvr-confirmation-table" class="wp-list-table widefat plugins mvr-shopping-basket-frame">
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
