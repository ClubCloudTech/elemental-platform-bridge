<?php
/**
 * Outputs the configuration settings for the video plugin
 *
 * @package ElementalPlugin\Views\Admin
 */

/**
 * Render the admin page
 *
 * @param string $active_tab
 * @param array $tabs
 * @param array $messages
 *
 * @return string
 */


use ElementalPlugin\Library\AdminTemplates;
use ElementalPlugin\Shortcode\ShortcodeVisualiser;
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Factory;
use ElementalPlugin\Setup\RoomAdmin;
use ElementalPlugin\Library\ShortcodeDocuments;

return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {

	$render = include __DIR__ . '/header.php';
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped. 
	echo $render( $active_tab, $tabs, $messages );
	ob_start();

	?>
	<script type="text/javascript">
	function activateTab(pageId) {
		var tabCtrl = document.getElementById( 'tabCtrl' );
		var pageToActivate = document.getElementById(pageId);
	for (var i = 0; i < tabCtrl.childNodes.length; i++) {
			var node = tabCtrl.childNodes[i];
			if (node.nodeType == 1) { /* Element */
				node.style.display = (node == pageToActivate) ? 'block' : 'none';
			}
		}
	}

	</script>

<hr>
		<ul class="menu" >

				<a class="cc-menu-header" href="javascript:activateTab( 'page1' )" style=" 
					display: block; 
					float: left;
					border: 1px solid #ccc;
					border-bottom: none;
					margin-left: .5em;
					padding: 5px 10px;
					font-size: 14px;
					line-height: 1.71428571;
					font-weight: 600;
					background: #e5e5e5;
					color: #555;
					text-decoration: none;
					">Visual Room Builder</a>


		<a class="cc-menu-header" href="javascript:activateTab( 'page4' )" style="
					display: block; 
					float: left;
					border: 1px solid #ccc;
					border-bottom: none;
					margin-left: .5em;
					padding: 5px 10px;
					font-size: 14px;
					line-height: 1.71428571;
					font-weight: 600;
					background: #e5e5e5;
					color: #555;
					text-decoration: none;
					">Available Templates</a>

				<a class="cc-menu-header" href="javascript:activateTab( 'page2' )" style="
					display: block; 
					float: left;
					border: 1px solid #ccc;
					border-bottom: none;
					margin-left: .5em;
					padding: 5px 10px;
					font-size: 14px;
					line-height: 1.71428571;
					font-weight: 600;
					background: #e5e5e5;
					color: #555;
					text-decoration: none;

					">Installed Shortcodes</a>

				<a class="cc-menu-header" href="javascript:activateTab( 'page3' )" style="
					display: block; 
					float: left;
					border: 1px solid #ccc;
					border-bottom: none;
					margin-left: .5em;
					padding: 5px 10px;
					font-size: 14px;
					line-height: 1.71428571;
					font-weight: 600;
					background: #e5e5e5;
					color: #555;
					text-decoration: none;
					">All Shortcodes</a>
		</ul>
<br>
			<div id="tabCtrl"
				style="
				background: #e0e0e0;  
				display: block;
				float: left;
				width: 95%;
				border: 1px solid #ccc;
				padding: 5px 10px;
				font-size: 14px;
				line-height: 1.71428571;
				font-weight: 600;
				background: #e5e5e5;
				color: #555;
				text-decoration: none;
			">
				<div id="page1" style="
					display: block; 
					float: left;
					border: 1px solid #ccc;
					width: 90%;
					margin: 20px 20px 20px 20px;
					padding: 5px 10px;
					font-size: 14px;
					line-height: 1.71428571;
					font-weight: 600;
					background: #e5e5e5;
					color: #555;
					text-decoration: none;">
					<h2>Room Builder Information</h2>
		<table style="width:99%; 
				border: 1px solid black;
				border: 3px solid #969696;
				margin: 20px 20px 20px 20px;
				padding: 5px 10px;
				background: #ebedf1;
				padding: 12px;
				margin: 5px;">
				<tr>
					<th style="width:25%; text-align: left; " >Page Name</th>
					<th style="width:25%; text-align: left;" >Page URL</th>
					<th style="width:25%; text-align: left;" >Manage</th>
				</tr>
				<tr>
					<td style="width:25%; text-align: left;">
					<?php
					$title = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'meet-center', 'title' );
					if ( $title ) {
						echo esc_url_raw( $title );
					}
					?>
					</td>
					<td style="width:25%; text-align: left;">
					<?php
					$url = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'meet-center', 'url' );
					if ( $url ) {
						echo '<a href="' . esc_url_raw( $url ) . '" target="_blank">' . esc_url_raw( $url ) . '</a>';
					}
					?>
					</td>

					<td style="width:25%; text-align: left;">
						<?php
						if ( Factory::get_instance( SiteDefaults::class )->is_elementor_active() ) {
							if ( $post_id ) {
								echo esc_url_raw( '<a href="' . get_site_url() . '/wp-admin/post.php?post=' . $post_id . '&action=elementor" class="button button-primary" target="_blank">Edit in Elementor</a>' );
								echo ' - ';
							}
						}
						if ( $post_id ) {
							echo esc_url_raw( '<a href="' . get_site_url() . '/wp-admin/post.php?post=' . $post_id . '&action=edit"class="button button-primary" target="_blank">Edit in WordPress</a>' );
						} else {
							$url_base = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
							echo esc_url_raw( '<a href="' . $url_base . '&regenerate=personalmeeting" class="button button-primary" class="button button-primary">Page Deleted - Click Here to Regenerate</a>' );

						}
						?>
					</td>
				</tr>

			</table>

					<?php
					// phpcs:ignore -- Visualiser worker generates content and is output safely at its level. 
					echo Factory::get_instance( ShortcodeVisualiser::class )->visualiser_worker( SiteDefaults::USER_ID_SITE_DEFAULTS, 'Your Room' );
					?>

				</div>

				<div id="page2" style="
					display: none; 
					float: left;
					border: 1px solid #ccc;
					padding: 5px 10px;
					font-size: 14px;
					line-height: 1.71428571;
					font-weight: 600;
					background: #e5e5e5;
					color: #555;
					text-decoration: none;
					">

					<h2>Installed Shortcodes</h2>
					<p>This section shows only available shortcodes that are installed in active modules. To view all shortcodes please click on the View All Tab</p>
					<?php
					Factory::get_instance( ShortcodeDocuments::class )->render_filtered_shortcode_docs();
					?>

				</div>

				<div id="page3" style="
					display: none; 
					float: left;
					border: 1px solid #ccc;
					padding: 5px 10px;
					font-size: 14px;
					line-height: 1.71428571;
					font-weight: 600;
					background: #e5e5e5;
					color: #555;
					text-decoration: none;
					">

				<h2>All Shortcodes</h2>
				<p>This section shows all available shortcodes that are possible with the plugin in all modules. To view just installed shortcodes please click on the Installed Tab</p>
	<?php
	Factory::get_instance( \ElementalPlugin\Library\ShortcodeDocuments::class )->render_all_shortcode_docs();
	?>
			</div>

			<div id="page4" style="
					display: none; 
					float: left;
					width: inherit;
					border: 1px solid #ccc;
					padding: 5px 10px;
					font-size: 14px;
					line-height: 1.71428571;
					font-weight: 600;
					background: #e5e5e5;
					color: #555;
					text-decoration: none;
					">


				<?php
				// phpcs:ignore -- Template Browser generates content and is output safely at its level. 
				echo Factory::get_instance( AdminTemplates::class )->display_room_template_browser();
				?>
			</div>


	</div> 
	<?php

	return ob_get_clean();
};

