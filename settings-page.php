<?php
/**
 * Add the settings menu link to the Settings menu in admin interface
 */
function mm2_add_settings_menu(){
	//Create a new link to the settings menu
	//Returns the suffix of the page that can later be used in the actions etc
	$page = add_options_page(
			'Add or Remove Www', //name of the settings page
			'Add or Remove Www',
			'manage_options',
			'add-or-remove-www',
			'mm2_display_settings_page' //the function that is going to be called if the created page is loaded
	);
	//If the form was submitted
	if( isset($_POST['mm2_settings_save']) ){
		//Add the action to the plugin head to call mm2_save_settings
		add_action("admin_head-$page", 'mm2_save_settings');
	}
}
add_action( 'admin_menu', 'mm2_add_settings_menu' );

/**
 * Display the settings page in the admin interface
 */
function mm2_display_settings_page(){
	$domain_name =  preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);
	?>
		<div class="wrap">
			<h2>Add or Remove Www</h2>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				<?php if( isset( $_POST['mm2_settings_save'] ) ):?>
					<div class="updated"><p><strong><?php _e( 'Options saved.' ); ?></strong></p></div>
				<?php endif; ?>
				<?php wp_nonce_field( 'mm2-save-settings' ); ?>
				<table class="form-table">
					<tr>
						<th>
							<label>
								<input type="radio" name="www-url" value="1" <?php echo get_option( 'mm2_url_with_www' ) == 1 ? 'checked="checked"' : ''; ?>/>Use URLs with www
							</label>
						</th>
						<td>
							<code><?php echo 'http://www.' . $domain_name; ?></code>
						</td>
					</tr>	
					<tr>
						<th>
							<label>
								<input type="radio" name="www-url" value="0" <?php echo get_option( 'mm2_url_with_www' ) == 0 ? 'checked="checked"' : ''; ?> />Use URLs without www
							</label>
						</th>
						<td>
							<code><?php echo 'http://' . $domain_name; ?></code>
						</td>
					</tr>
				</table>
				<p>
					<input type="submit" value="Save options" name="mm2_settings_save">
				</p>
			</form>
		</div>
	<?php
}
?>