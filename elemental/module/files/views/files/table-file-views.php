<?php

/**
 * Outputs File View Table.
 *
 * @package ElementalPlugin/Module/membership/views/files/table-file-views.php
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\TemplateIcons;

/**
 * Render the Membership User Table.
 *
 * @param array   $user_accounts the array of objects with user accounts.
 *
 * @return string
 */
return function (
	array $dir_list,
	string $encrypted_user_id,
	string $nonce,
	object $user
): string {
	ob_start();
	?>
<div id="elemental-welcome-page" class=" " data-checksum="<?php echo esc_attr( $encrypted_user_id ); ?>">
	<div>
		<H1 style=" text-align:centre;">
			<?php echo esc_html__( 'File Vault for : ', 'elementalplugin' ) . esc_attr( $user->display_name ); ?></H1>
	</div>

	<div style="background : #d8d8d8; border: 4px solid black; border-bottom:0px;">
		<div class="elemental-left">
			<strong><?php esc_html_e( ' File Uploads: ', 'elementalplugin' ); ?></strong>
			<input type="file" accept=".gif,.jpg,.jpeg,.png,.pdf,.txt,.xls,.xlsx,.doc,.docx"
				id="elemental-file-input" />
		</div>
		<div class="elemental-right-nofloat">
		<?php
			//phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - already escaped.
			echo Factory::get_instance( TemplateIcons::class )->format_button_icon( 'close_window' );
		?>
		</div>
	</div>

	<div id="elemental-top-notification" class=""></div>
	<table class="collapse wp-list-table widefat plugins elemental-table-adjust"
		style="background : #d8d8d8; border: 4px solid black;">
		<thead>
			<tr style="border-bottom: 2px solid black;">
				<th></th>
				<th>
					<h1>Name</h1>
				</th>
				<th>
					<h1>Type</h1>
				</th>
				<th>
					<h1>Size</h1>
				</th>
				<th>
					<h1>Last Modified</h1>
				</th>
			</tr>

		</thead>
		<tbody>
			<?PHP
			if ( $dir_list ) {
				foreach ( $dir_list as $file ) {
					echo '<td style="max-width=25%;"><a href="' . esc_url( $file['url'] ) . '"><img src="' . esc_url( $file['url'] ) . '" width="90" alt=""></a></td>';
					echo '<td><a href="' . esc_url( $file['url'] ) . '">' . esc_attr( $file['name'] ) . '</a> </td>';
					echo '<td> ' . esc_attr( $file['type'] ) . '</td>';
					echo '<td>' . esc_attr( $file['size'] ) . '</td>';
					echo '<td>', date( 'M d Y H:i', $file['lastmod'] ),'</td>';
					echo '<td>' .
					// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - function already escaped.
					Factory::get_instance( TemplateIcons::class )->format_button_icon( 'deletefile',
						'data-filecheck="' . esc_attr( $file['path'] ) . '" data-usrcheck ="' . esc_attr( $file['user_id_encrypted'] ) . '" data-nonce="' . esc_attr( $nonce ) . '"'
					) . '</td>';
					echo '</tr>';

				}
			} else {
				echo '<tr><td colspan="4"><h2>' . esc_html__( 'There are no files in your digital vault', 'elementalplugin' ) . '</h2></td></tr>';
			}

			?>
		</tbody>
	</table>
</div>
	<?php

		return ob_get_clean();
};
