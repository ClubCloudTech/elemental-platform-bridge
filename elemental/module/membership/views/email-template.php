<?php
/**
 * Outputs User Membership Levels Table.
 *
 * @package ElementalPlugin\Module\Membership\Views\email-template.php
 */

/**
 * Render the Membership Welcome Email.
 *
 * @param string $password   The user password.
 * @param string $email      The user Email.
 *
 * @return string
 */
return function (
	string $password,
	string $email,
	string $first_name,
	array $data
): string {
	ob_start();
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html data-editor-version="2" class="sg-campaigns" xmlns="http://www.w3.org/1999/xhtml"
	  xmlns:th="http://www.thymeleaf.org">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<style type="text/css">
	body, p, div {
   font-family: "Montserrat", Sans-serif!important;
	  font-size: 14px;
	}
	body {
	  color: #000000;
	}
	body a {
	  color: #1188E6;
	  text-decoration: none;
	}
	p { margin: 0; padding: 0; }
	table.wrapper {
	  width:100% !important;
	  table-layout: fixed;
	  -webkit-font-smoothing: antialiased;
	  -webkit-text-size-adjust: 100%;
	  -moz-text-size-adjust: 100%;
	  -ms-text-size-adjust: 100%;
	}
	img.max-width {
	  max-width: 100% !important;
	}
	.column.of-2 {
	  width: 50%;
	}
	.column.of-3 {
	  width: 33.333%;
	}
	.column.of-4 {
	  width: 25%;
	}
	ul ul ul ul  {
	  list-style-type: disc !important;
	}
	ol ol {
	  list-style-type: lower-roman !important;
	}
	ol ol ol {
	  list-style-type: lower-latin !important;
	}
	ol ol ol ol {
	  list-style-type: decimal !important;
	}
	@media screen and (max-width:480px) {
	  .preheader .rightColumnContent,
	  .footer .rightColumnContent {
		text-align: left !important;
	  }
	  .preheader .rightColumnContent div,
	  .preheader .rightColumnContent span,
	  .footer .rightColumnContent div,
	  .footer .rightColumnContent span {
		text-align: left !important;
	  }
	  .preheader .rightColumnContent,
	  .preheader .leftColumnContent {
		font-size: 80% !important;
		padding: 5px 0;
	  }
	  table.wrapper-mobile {
		width: 100% !important;
		table-layout: fixed;
	  }
	  img.max-width {
		height: auto !important;
		max-width: 100% !important;
	  }
	  a.bulletproof-button {
		display: block !important;
		width: auto !important;
		font-size: 80%;
		padding-left: 0 !important;
		padding-right: 0 !important;
	  }
	  .columns {
		width: 100% !important;
	  }
	  .column {
		display: block !important;
		width: 100% !important;
		padding-left: 0 !important;
		padding-right: 0 !important;
		margin-left: 0 !important;
		margin-right: 0 !important;
	  }
	  .social-icon-column {
		display: inline-block !important;
	  }
	}
	</style>
</head>
<body>
<center class="wrapper" data-link-color="#1188E6"
		data-body-style="font-size:14px; font-family:arial,helvetica,sans-serif; color:#000000; background-color:#FFFFFF;">
	<div class="webkit">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="wrapper" bgcolor="#FFFFFF">
			<tr>
				<td valign="top" bgcolor="#FFFFFF" width="100%">
					<table width="100%" role="content-container" class="outer" align="center" cellpadding="0"
						   cellspacing="0" border="0">
						<tr>
							<td width="100%">
								<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td>
									 
											<table width="100%" cellpadding="0" cellspacing="0" border="0"
												   style="width:100%; max-width:700px;" align="center">
												<tr>
													<td role="modules-container"
														style="padding:10px 10px 10px 10px; color:#000000; text-align:left;"
														bgcolor="#FFFFFF" width="100%" align="left">
														<table class="module preheader preheader-hide" role="module"
															   data-type="preheader" border="0" cellpadding="0"
															   cellspacing="0" width="100%"
															   style="display: none !important; mso-hide: all; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;">
															<tr>
																<td role="module-content">
																	<span th:text="${previewText}"></span>
																</td>
															</tr>
														</table>
														<table border="0" cellpadding="0" cellspacing="0" align="center"
															   width="100%" role="module" data-type="columns"
															   style="padding:0px 0px 0px 0px;" bgcolor="#f1f1f6"
															   data-distribution="1">
															<tbody>
															<tr role="module-content">
																<td height="100%" valign="top">
																	<table width="660"
																		   style="width:660px; border-spacing:0; border-collapse:collapse; margin:0px 10px 0px 10px;"
																		   cellpadding="0" cellspacing="0" align="left"
																		   border="0" bgcolor=""
																		   class="column column-0">
																		<tbody>
																		<tr>
																			<td style="padding:0px;margin:0px;border-spacing:0;">
																				<table class="wrapper" role="module"
																					   data-type="image" border="0"
																					   cellpadding="0" cellspacing="0"
																					   width="100%"
																					   style="table-layout: fixed;"
																					   data-muid="roS4A48of9YUHLguDSh1xv">
																					<tbody>
																					<tr>
																						<td style="font-size:6px; line-height:10px; padding:0px 0px 0px 0px;"
																							valign="top" align="left">
																							<img class="max-width"
																								 border="0"
																								 style="display:block; color:#000000; text-decoration:none; font-family:Helvetica, arial, sans-serif; font-size:16px; max-width:100% !important; width:100%; height:auto !important;"
																								 width="660" alt=""
																								 data-proportionally-constrained="true"
																								 data-responsive="true"
																								 src="http://cdn.mcauto-images-production.sendgrid.net/4c09c012201affc9/108b0e27-83ef-4764-8f40-995c7417bae7/600x132.png">
																						</td>
																					</tr>
																					</tbody>
																				</table>
																				<table class="module" role="module"
																					   data-type="spacer" border="0"
																					   cellpadding="0" cellspacing="0"
																					   width="100%"
																					   style="table-layout: fixed;"
																					   data-muid="7deb70e1-46ea-4cd8-8ce5-66cc02ad92fa">
																					<tbody>
																					<tr>
																						<td style="padding:0px 0px 5px 0px;"
																							role="module-content"
																							bgcolor="">
																						</td>
																					</tr>
																					</tbody>
																				</table>
																				<table class="module" role="module"
																					   data-type="text" border="0"
																					   cellpadding="0" cellspacing="0"
																					   width="100%"
																					   style="table-layout: fixed;"
																					   data-muid="efb6be68-1b18-4171-9945-19178cf60763"
																					   data-mc-module-version="2019-10-22">
																					<tbody>
																					<tr>
																						<td style="padding:0px 0px 0px 0px; line-height:23px; text-align:inherit;"
																							height="100%" valign="top"
																							bgcolor=""
																							role="module-content">
																							<div><h3
																									style="text-align: center">
																								<span style="color: #0f0f0f;"
																									  th:text="${header}"></span>
																							</h3>
																								<div></div>
																							</div>
																						</td>
																					</tr>
																					</tbody>
																				</table>
																				<table class="module" role="module"
																					   data-type="text" border="0"
																					   cellpadding="0" cellspacing="0"
																					   width="100%"
																					   style="table-layout: fixed;"
																					   data-muid="96001edd-3521-42d0-8a1b-3e1e76121d85"
																					   data-mc-module-version="2019-10-22">
																					<tbody>
																					<tr>
																						<td style="padding:18px 10px 18px 20px; line-height:22px; text-align:inherit;"
																							height="100%" valign="top"
																							bgcolor=""
																							role="module-content">
																							<div>
																								<div style="font-family: inherit; text-align: inherit">
																								   
																								</div>
																								<div style="font-family: inherit; text-align: inherit">
																										<tr>
									<td style="padding:0 35px;">
										<h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:"Rubik,sans-serif;"><?php echo esc_textarea( $first_name ) . ', ' . esc_html__( 'welcome ', 'elementalplugin' ) . esc_html__( ' to ', 'elementalplugin' ) . esc_textarea( get_bloginfo( 'name' ) ); ?></h1>
										<span
											style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
										<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:15px;">
										  <p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:5px;">You can log in with your details.</p>
										  <p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:5px;"><strong><?php echo esc_html__( 'Username', 'elementalplugin' ); ?>:</strong> <?php echo esc_textarea( $email ); ?></p>
										  <p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:5px;"><strong><?php echo esc_html__( 'Password ', 'elementalplugin' ); ?>:</strong> <?php echo esc_textarea( $password ); ?></p>
										</p>
										<a href="<?php echo esc_url( \get_site_url() ) . '/login'; ?>"
											style="background:#FB5C5F;text-decoration:none !important; font-weight:500; margin:15px 0; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">Accept Invite</a>
										<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:15px;">
											<strong>Coadjute</strong> makes property transactions better for everyone – from estate agents to conveyancers, brokers to lenders and all other parties in between.
										</p>
										<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:15px;">
											It Provides one view of the property transaction process, end-to-end.
										</p>
										<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:15px;">
										</p>
										<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:15px;">
											Coadjute <strong>Sandbox</strong> is your first opportunity to play and interact with the Coadjute applet in a contained and safe way.
										</p>
										<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:15px;">
											We have provided you with some profiles that will allow you to act as other participants on the Sandbox. Please meet…
										</p>
										<?php if ( isset( $data['estateagent'] ) ) { ?>
												<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:5px;"><strong><?php echo esc_textarea( $data['estateagent']['company_name'] ); ?></strong></p>
												<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:5px;"><?php echo esc_textarea( $data['estateagent']['first_name'] . ' ' . $data['estateagent']['last_name'] ); ?></p> 
												<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:15px;"><?php echo esc_textarea( $data['estateagent']['email'] ); ?></p> 
										<?php } ?>
										<?php if ( isset( $data['buyer_conveyancer'] ) ) { ?>
												<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:5px;"><strong><?php echo esc_textarea( $data['buyer_conveyancer']['company_name'] ); ?></strong></p>
												<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:5px;"><?php echo esc_textarea( $data['buyer_conveyancer']['first_name'] . ' ' . $data['buyer_conveyancer']['last_name'] ); ?></p> 
												<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:15px;"><?php echo esc_textarea( $data['buyer_conveyancer']['email'] ); ?></p> 
										<?php } ?>
										<?php if ( isset( $data['seller_conveyancer'] ) ) { ?>
												<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:5px;"><strong><?php echo esc_textarea( $data['seller_conveyancer']['company_name'] ); ?></strong></p>
												<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:5px;"><?php echo esc_textarea( $data['seller_conveyancer']['first_name'] . ' ' . $data['seller_conveyancer']['last_name'] ); ?></p> 
												<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:15px;"><?php echo esc_textarea( $data['seller_conveyancer']['email'] ); ?></p> 
										<?php } ?>
										<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:5px;">Thanks,</p>
										<p style="color:#455056; font-size:15px;line-height:24px; margin:0; margin-bottom:15px;">The Coadjute Team</p>
									</td>
								</tr>
																								</div>
																								<div></div>
																							</div>
																						</td>
																					</tr>
																					</tbody>
																				</table>
																				<table class="module" role="module" data-type="divider"
																					   border="0" cellpadding="0"
																					   cellspacing="0" width="100%" style="table-layout: fixed;"
																					   data-muid="ff62341d-97fe-4634-acaa-1ecc0179d9d3">
																					<tbody>
																					<tr>
																						<td style="padding:0px 0px 0px 0px;"
																							role="module-content" height="100%"
																							valign="top" bgcolor="">
																							<table border="0" cellpadding="0" cellspacing="0"
																								   align="center" width="100%"
																								   height="3px"
																								   style="line-height:3px; font-size:3px;">
																								<tbody>
																								<tr>
																									<td style="padding:0px 0px 3px 0px;"
																										bgcolor="#5335ca"></td>
																								</tr>
																								</tbody>
																							</table>
																						</td>
																					</tr>
																					</tbody>
																				</table>
																				<table class="module" role="module" data-type="text" border="0"
																					   cellpadding="0"
																					   cellspacing="0" width="100%" style="table-layout: fixed;"
																					   data-muid="90c6ff4e-6693-489d-b4f7-938d94e93a59"
																					   data-mc-module-version="2019-10-22">
																					<tbody>
																					<tr>
																						<td
																								style="padding:7px 0px 5px 18px; line-height:10px; text-align:inherit; background-color:#F1F1F6;"
																								height="100%" valign="top" bgcolor="#F1F1F6"
																								role="module-content">
																							<div>
																								<div style="font-family: inherit; text-align: center"><span
																										style="margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; color: #444444; font-family: Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; text-align: center; font-size: 11px; line-height: 19.25px; font-weight: bold; background-color: rgb(241, 241, 246)">(Sent
											through automation)&nbsp;</span></div>
																								<div style="font-family: inherit; text-align: center"><span
																										style="background-color: rgb(241, 241, 246)">&nbsp;</span>
																								</div>
																								<div style="font-family: inherit; text-align: center"><span
																										style="margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; color: #444444; font-family: Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; font-size: 11px; line-height: 13.75px; background-color: rgb(241, 241, 246)">
																			Coadjute,
											12th Floor, 2 London Wall Place, London, London EC2Y 5AU, United Kingdom,
											+44 2033270438</span><span
																										style="background-color: rgb(241, 241, 246)">&nbsp;</span>
																								</div>
																								<div></div>
																							</div>
																						</td>
																					</tr>
																					</tbody>
																				</table>
																			</td>
																		</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</center>
</body>
</html>

	<?php

	return ob_get_clean();
};
