<?php
/**
 * Outputs New Document File Update Mail.
 *
 * @package ElementalPlugin\Module\Files\Views\email#email-template.php
 */

/**
 * Render the Membership Welcome Email.
 *
 * @param string $email      -The user Email.
 * @param string $first_name -User First name.
 *
 * @return string
 */
return function (
	string $email,
	string $first_name,
): string {
	ob_start();
	?>
<!doctype html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width">
	<title>
	<?php
	\esc_html__( ' Welcome to ', 'elemental' ) . get_bloginfo( 'name' );
	?>
	</title>
	<style type="text/css">
	@media only screen and (max-width: 599px) {
		table.body .container {
			width: 95% !important;
		}

		.header {
			padding: 15px 15px 12px 15px !important;
		}

		.header img {
			width: 200px !important;
			height: auto !important;
		}

		.content,
		.aside {
			padding: 30px 40px 20px 40px !important;
		}
	}
	</style>
</head>

<body
	style="height: 100% !important; width: 100% !important; min-width: 100%; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; -webkit-font-smoothing: antialiased !important; -moz-osx-font-smoothing: grayscale !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; margin: 0; Margin: 0; font-size: 14px; line-height: 140%; background-color: #f1f1f1; text-align: center;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" class="body"
		style="border-collapse: collapse; border-spacing: 0; vertical-align: top;  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; height: 100% !important; width: 100% !important; min-width: 100%; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; -webkit-font-smoothing: antialiased !important; -moz-osx-font-smoothing: grayscale !important; background-color: #f1f1f1; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; margin: 0; Margin: 0; text-align: left; font-size: 14px; line-height: 140%;">
		<tr style="padding: 0; vertical-align: top; text-align: left;">
			<td align="center" valign="top"
				style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top;  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; margin: 0; Margin: 0; font-size: 14px; line-height: 140%; text-align: center;">
				<!-- Container -->
				<table border="0" cellpadding="0" cellspacing="0" class="container"
					style="border-collapse: collapse; border-spacing: 0; padding: 0; vertical-align: top;  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; width: 600px; margin: 0 auto 30px auto; Margin: 0 auto 30px auto; text-align: inherit;">
					<!-- Header -->
					<tr style="padding: 0; vertical-align: top; text-align: left;">
						<td align="center" valign="middle" class="header"
						style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top;  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; margin: 0; Margin: 0; text-align: left; font-size: 14px; line-height: 140%; background-color: #ffffff; padding: 60px 75px 45px 75px; border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; border-left: 1px solid #ddd; border-top: 3px solid #809eb0;">
						<?php
						$image_header = wp_get_attachment_url( get_theme_mod( 'custom_logo' ) );
						if ( $image_header ) {
							?>
								<img src="<?php echo esc_url( $image_header ); ?>"
								width="100" alt="<?php echo esc_html__( 'Welcome to ', 'elemental' ) . esc_textarea( get_bloginfo( 'name' ) ); ?>"
								style="outline: none; text-decoration: none; max-width: 100px; clear: both; -ms-interpolation-mode: bicubic; display: inline-block !important; width: 250px;">
							<?php
						}

						?>
						</td>
					</tr>
					<!-- Content -->
					<tr style="padding: 0; vertical-align: top; text-align: left;">
						<td align="left" valign="top" class="content"
							style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top;  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; margin: 0; Margin: 0; text-align: left; font-size: 14px; line-height: 140%; background-color: #ffffff; padding: 60px 75px 45px 75px; border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; border-left: 1px solid #ddd; border-top: 3px solid #809eb0;">
							<div class="success" style="text-align: center;">
								<p class="text-extra-large text-center congrats"
									style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; line-height: 140%; font-size: 20px; text-align: center; margin: 0 0 20px 0; Margin: 0 0 20px 0;">
									<?php echo esc_html__( 'Document for ', 'elemental' ) . esc_textarea( $first_name ) . esc_html__( ' from ', 'elemental ' ) . esc_textarea( get_bloginfo( 'name' ) ); ?>
								</p>
								<p class="text-large"
									style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; text-align: left; line-height: 140%; margin: 0 0 15px 0; Margin: 0 0 15px 0; font-size: 16px;">
									<?php echo esc_html__( 'There is a new document waiting for you in your user vault on the platform. Please review at your convenience ', 'elemental' ); ?>
								</p>
								<p class="text-large"
									style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; text-align: left; line-height: 140%; margin: 0 0 15px 0; Margin: 0 0 15px 0; font-size: 16px;">
									<?php
									echo esc_html__( 'To begin, you can access your account ', 'elemental' ) .
									'<a href="' . esc_url( \get_site_url() ) . '">' . \esc_html__( ' Here ', 'elemental' ) . '<a>';
									?>
									<div>
										<table id="elemental-table-basket-frame"
											class="wp-list-table widefat plugins elemental-table-adjust">
											<tbody>
												<tr>
													<td align="left" scope="col" style="width:40%">
														<strong>
															<?php esc_html_e( 'Username', 'elemental' ); ?>
														</strong>
													</td>
												</tr>
												<tr>
													<td align="left" scope="col" style="width:40%">
														<?php echo esc_textarea( $email ); ?>
													</td>
												</tr>
												<tr>
													<td align="left" scope="col" style="width:40%">
														<strong>
															<?php esc_html_e( 'Password', 'elemental' ); ?>
														</strong>
													</td>
												</tr>


											</tbody>
										</table>
									</div>
								</p>
								<p
									style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; text-align: left; font-size: 14px; line-height: 140%; margin: 0 0 15px 0; Margin: 0 0 15px 0;">

								</p>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>

</html>
	<?php

	return ob_get_clean();
};
