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
	array $dir_list
): string {
	ob_start();
	?>
<table class="collapse wp-list-table widefat plugins elemental-table-adjust" style = "background : #d8d8d8; border: 4px solid black;">
	<thead>
		<tr style= "border-bottom: 3px solid black;">
			<th></th>
			<th><h1>Name</h1></th>
			<th><h1>Type</h1></th>
			<th><h1>Size</h1></th>
			<th><h1>Last Modified</h1></th>
		</tr>
	</thead>
	<tbody>
		<?PHP
		foreach ( $dir_list as $file ) {

			echo '<td style="max-width=25%;"><a href="' . esc_url( $file['url'] ) . '">' . $file['icon'] . '<img src="' . esc_url( $file['url'] ) . '" width="90" alt=""></a></td>';
			echo '<td><a href="' . esc_url( $file['url'] ) . '">' . esc_attr( $file['name'] ) . '</a> </td>';
			echo "<td>{$file["type"]}</td>";
			echo "<td>{$file["size"]}</td>";
			echo '<td>',date( 'r', $file["lastmod"] ),"</td>";
			echo "</tr><br>";
			
		}
		?>
	</tbody>
</table>

	<?php

		return ob_get_clean();
};
