<?php
/**
 * Saves the settings
 */
function mm2_save_settings(){
	check_admin_referer( 'mm2-save-settings' );
	update_option( 'mm2_url_with_www', $_POST['www-url'] );
}

/**
 * Take string as an input, return modified string with replaced urls (if any)
 * 
 * @param String $text
 * @return String The modified text
 */
function mm2_get_text_with_modified_urls( $text ){
	
	//Get the www option from database
	$is_url_with_www = get_option( 'mm2_url_with_www' );
	//If the option for the URL is not set, return the content as it is
	if( $is_url_with_www != '0' && $is_url_with_www != '1' ){
		return $text;
	}
	
	//Get the domain name without 'www'
	$domain_name =  preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);
	
	//Replace all the www urls with without-www url
	$text = str_replace( '//www.' . $domain_name, '//' . $domain_name, $text );
	
	//If the url should be with www, replace the urls
	if( $is_url_with_www ){
		$text = str_replace( '//' . $domain_name, '//www.' . $domain_name, $text );
	}
	//Return the modified text
	return $text;
}

/**
 * Modifies the content of the post according to the plugin settings.
 * Looks for URL-s and adds/removes www subdomain
 * 
 * @param String $content
 * @return String The modified text
 */
function mm2_modify_content_urls( $content ){
	return mm2_get_text_with_modified_urls( $content );
}

//Add filters to run the functions when saving content
add_filter( 'content_save_pre', 'mm2_modify_content_urls', 10, 1 );

/**
 * Add links to WPSOS
 */
function mm2_set_plugin_meta( $links, $file ) {

	if ( strpos( $file, 'add-or-remove-www.php' ) !== false ) {

		$links = array_merge( $links, array( '<a href="http://www.wpsos.io/wordpress-plugin-add-or-remove-www/">' . __( 'Plugin details', 'simple-embed-code' ) . '</a>' ) );
		$links = array_merge( $links, array( '<a href="http://www.wpsos.io/">' . __( 'WPSOS - WordPress Security, Optimization & Speed', 'simple-embed-code' ) . '</a>' ) );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'mm2_set_plugin_meta', 10, 2 );
?>
