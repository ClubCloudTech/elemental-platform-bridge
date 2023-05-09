<?php

/**
 * Outputs File View Table.
 *
 * @package ElementalPlugin/Module/membership/views/files/table-file-views.php
 */

/**
 * Render the Membership User Table.
 *
 * @param array   $user_accounts the array of objects with user accounts.
 *
 * @return string
 */
return function (
	object $dir_list
): string {
	ob_start();
	?>
<h1>Display PNG images in a TABLE</h1>

<table class="collapse" border="1">
	<thead>
		<tr>
			<th></th>
			<th>Name</th>
			<th>Type</th>
			<th>Size</th>
			<th>Last Modified</th>
		</tr>
	</thead>
	<tbody>
		<?PHP

		foreach ( $dir_list as $file ) {
			if ( ! preg_match( '/\.png$/', $file['name'] ) ) {
				continue;
			}
			echo "<tr>\n";
			echo "<td><img src=\"{$file['name']}\" width=\"64\" alt=\"\"></td>\n";
			echo "<td>{$file['name']}</td>\n";
			echo "<td>{$file['type']}</td>\n";
			echo "<td>{$file['size']}</td>\n";
			echo '<td>',date( 'r', $file['lastmod'] ),"</td>\n";
			echo "</tr>\n";
		}
		?>
	</tbody>
</table>

	<?php

		return ob_get_clean();
};
