<?php
/**
 * Display section templates ..
 *
 * @package MyVideoRoomExtrasPlugin\Library
 */

namespace MyVideoRoomExtrasPlugin\Library;

/**
 * Class SectionTemplate
 */
class AdminTemplates {


	public function display_room_template_browser() {

		?>

	<div class="wrap">


		<h1>Room Template Browser</h1>
		<p> Use the Template Browser tab to view room selection templates<br>    </p>

	<ul class="menu" style="display: flex;    justify-content: space-between;    width: 50%;">
		<a class="cc-menu-header" href="javascript:activateTab2( 'page1' )" style="text-align: justify ;color: #000000;    font-family: Montserrat, Sans-serif; font-size: 20px;     font-weight: 200;    text-transform: capitalize;">Video Room Templates</a>
		<a class="cc-menu-header" href="javascript:activateTab2( 'page2' )" style="text-align: justify ;color: #000000;    font-family: Montserrat, Sans-serif; font-size: 20px;     font-weight: 200;    text-transform: capitalize;">Reception Templates</a>
		<a class="cc-menu-header" href="javascript:activateTab2( 'page3' )" style="text-align: justify ;color: #000000;    font-family: Montserrat, Sans-serif; font-size: 20px;     font-weight: 200;    text-transform: capitalize;">Using Templates</a>
	</ul>
		<div id="tabCtrl">
			<div id="page1" style="display: block; "><iframe src="https://rooms.clubcloud.tech/views/layout?tag[]=basic&tag[]=premium&embed=tru" width="100%" height="1600px" frameborder="0" scrolling="yes" align="left"> </iframe>
			</div>
			<div id="page2" style="display: none;"><iframe src="https://rooms.clubcloud.tech/views/reception?tag[]=basic&tag[]=premium&embed=true" width="100%" height="1600px" frameborder="0" scrolling="yes" align="left"> </iframe>
			</div>
			<div id="page3" style="display: none;">
				<h1>How to Use Templates</h1>
				<p> Templates can be used as arguments into any shortcode you build manually with [clubvideo], or in drop down boxes of Menus of Club Cloud Video Extras</p>
			</div>
		</div>
	</div>

	</div>
		<?php

	}



	public function display_shortcode_explorer() {
		?>

	<div class="wrap">


		<h1>Shortcode Explorer</h1>
		<p> Use this page to explore all shortcodes available for your enabled modules<br>    </p>

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
	<ul class="menu" style="display: flex;    justify-content: space-between;    width: 50%;">
		<a class="cc-menu-header" href="javascript:activateTab( 'page1' )" style="text-align: justify ;color: #000000;    font-family: Montserrat, Sans-serif; font-size: 20px;     font-weight: 200;    text-transform: capitalize;"> Installed Shortcodes  </a>
		<a class="cc-menu-header" href="javascript:activateTab( 'page2' )" style="text-align: justify ;color: #000000;    font-family: Montserrat, Sans-serif; font-size: 20px;     font-weight: 200;    text-transform: capitalize;"> All Shortcodes  </a>


	</ul>
	<div id="tabCtrl">
		<div id="page1" style="display: block; ">
				<h2>Installed Shortcodes</h2>
				<p>This section shows only available shortcodes that are installed in active modules. To view all shortcodes please click on the View All Tab</p>
			<?php
			\MyVideoRoomExtrasPlugin\Factory::get_instance( \MyVideoRoomExtrasPlugin\Library\ShortcodeDocuments::class )->render_filtered_shortcode_docs();

			?>

		</div>

		<div id="page2" style="display: none;">
				<h2>All Shortcodes</h2>
				<p>This section shows all available shortcodes that are possible with the plugin in all modules. To view just installed shortcodes please click on the Installed Tab</p>
			<?php
			\MyVideoRoomExtrasPlugin\Factory::get_instance( \MyVideoRoomExtrasPlugin\Library\ShortcodeDocuments::class )->render_all_shortcode_docs();

			?>

		</div>

		</div>
	</div>


	</div>
		<?php

	}

}//end class
