<?php
/**
 * Outputs New Document File Update Mail.
 *
 * @package ElementalPlugin/views/email/email-generic.php
 */

/**
 * Render a Generic Email.
 *
 * @param string $welcome_message -The user Email Header.
 * @param string $body_message -Message Body of the Mail.
 * @param string $detail -The User Detail if any.
 *
 * @return string
 */
return function (
	string $welcome_message,
	string $body_message,
	string $detail = null,
	string $image_header = null
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
					style="border-collapse: collapse; border-spacing: 0; padding: 0; vertical-align: top;  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; width: 600px; margin: 0 auto 30px auto; Margin: 0 auto 30px auto; text-align: inherit; background-color: #0d173b">
					<!-- Header -->
					<tr style="padding: 0; vertical-align: top; text-align: left; background-color: black;">
						<td align="center" valign="middle" class="header"
							style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top;  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; margin: 0; Margin: 0; text-align: left; font-size: 14px; line-height: 140%; background-color: #ffffff; padding: 60px 75px 45px 75px; border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; border-left: 1px solid #ddd; border-top: 3px solid #809eb0;">
							<?php
							if ( $image_header ) {
								?>
							<img style="background-color: #0d173b;"src="<?php echo esc_url( $image_header ); ?>" width="100" alt=""
								style="outline: none; text-decoration: none; max-width: 100px; clear: both; -ms-interpolation-mode: bicubic; display: inline-block !important; width: 250px;">
								<?php
							}
							?>
							<h4>
								<?php
							// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - already escaped.
							echo  $welcome_message;
								?>
							</h4>
						</td>
					</tr>
					<!-- Content -->
					<tr style="padding: 0; vertical-align: top; text-align: left;">
						<td align="left" valign="top" class="content"
							style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top;  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; margin: 0; Margin: 0; text-align: left; font-size: 14px; line-height: 140%; background-color: #ffffff; padding: 60px 75px 45px 75px; border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; border-left: 1px solid #ddd; border-top: 3px solid #809eb0;">
							<div class="success" style="text-align: center;">
								<p class="text-extra-large text-center congrats"
									style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; line-height: 140%; font-size: 20px; text-align: center; margin: 0 0 20px 0; Margin: 0 0 20px 0;">
									<?php
								// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - already escaped.
								echo  $body_message;
									?>
								</p>
								<p class="text-extra-large text-center congrats"
									style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; line-height: 140%; font-size: 20px; text-align: center; margin: 0 0 20px 0; Margin: 0 0 20px 0;">
									<?php
								// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - already escaped.
								echo  $detail;
									?>
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
