<?php

function jwplayer_login_init() {
	add_action( 'admin_menu', 'jwplayer_login_create_pages' );
}

function jwplayer_login_create_pages(){
	//adds the login page
	add_submenu_page( null, 'JW Player Authorization', 'JW Player Authorization', 'manage_options', 'jwplayer_login_page', 'jwplayer_login_page' );
	//adds the logout page
	add_submenu_page( null, 'JW Player Deauthorization', 'JW Player Deauthorization', 'manage_options', 'jwplayer_logout_page', 'jwplayer_login_logout' );
}

function jwplayer_login_print_error( $message ) {
	?>
	<div class='error fade'>
		<p>
			<strong><?php echo esc_html( $message ); ?></strong>
		</p>
	</div>
	<?php
}

// The login page
function jwplayer_login_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		jwplayer_login_print_error( 'You do not have sufficient privileges to access this page.' );
		return;
	}

	if ( ! isset( $_POST['apikey'], $_POST['apisecret'] ) ) {//input var okay
		jwplayer_login_form();
		return;
	}

	// Check the nonce (counter XSRF)
	if ( isset( $_POST['_wpnonce'] ) ){
		$nonce = sanitize_text_field( $_POST['_wpnonce'] );//input var okay
		if ( ! wp_verify_nonce( $nonce, 'jwplayer-login-nonce' ) ) {
			jwplayer_login_print_error( 'Could not verify the form data.' );
			jwplayer_login_form();
			return;
		}
	}

	if ( isset($_POST['apikey']) ){
		$api_key = sanitize_text_field( $_POST['apikey'] );//input var okay
	}

	if ( isset($_POST['apisecret']) ){
		$api_secret = sanitize_text_field( $_POST['apisecret'] );//input var okay
	}

	$api_verified = jwplayer_login_verify_api_key_secret( $api_key, $api_secret );

	if ( null === $api_verified ) {
		jwplayer_login_print_error( 'Communications with the JW Player API failed. Please try again later.' );
		jwplayer_login_form();
	}
	elseif ( false === $api_verified ) {
		jwplayer_login_print_error( 'Your API credentials were not accepted. Please try again.' );
		jwplayer_login_form();
	}
	else {
		// Perform the login.
		update_option( 'jwplayer_api_key', $api_key );
		update_option( 'jwplayer_api_secret', $api_secret );
		echo '<h2>Authorization succesful</h2><p>You have successfully authorized the plugin to access your JW Player account. Returning you to the <a href="options-media.php">media settings</a> page...</p>';
		// Perform a manual JavaScript redirect
		echo '<script type="application/x-javascript">document.location.href = "options-general.php?page=jwplayer_settings"</script>';
	}
}

// Print the login page
function jwplayer_login_form() {
	?>
	<div class="wrap">
		<h2>Plugin Authorization</h2>

		<form method="post" action="">
			<p>
				In order to use the JW Player plugin, you need to authorize the plugin
				to access the data in your JW Player account. (Don't have a JW Player
				account yet? <a href="https://www.jwplayer.com/pricing/">Sign up
				here</a>).
			</p>
			<p>
				Insert your JW Player API Credentials below. These are located in the
				<strong>Account > API Keys</strong> section of your dashboard.
			</p>
			<table class="form-table">

				<tr valign="top">
					<th scope="row">API Key</th>
					<td><input type="text" name="apikey"></td>
				</tr>

				<tr valign="top">
					<th scope="row">API Secret</th>
					<td><input type="password" name="apisecret">
				</tr>

			</table>

			<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'jwplayer-login-nonce' ) ); ?>">

			<p class="submit"><input type="submit" class="button-primary" value="Authorize plugin"></p>

		</form>
	</div>
	<?php
}

/**
 * Verify the API key and secret that the user has given, by making a call to
 * the API.
 *
 * If the credentials are invalid, return false.
 *
 * If the API call failed, return NULL.
 */
function jwplayer_login_verify_api_key_secret( $key, $secret ) {
	// require_once 'include/jwplayer-api.class.php';

	// Create an API object with the provided key and secret.
	$api = new JWPlayer_api( $key, $secret );
	$response = $api->call( '/accounts/show', $params );
	return jwplayer_api_response_ok( $response );
}

// The logout page
function jwplayer_login_logout() {
	if ( ! current_user_can( 'manage_options' ) ) {
		jwplayer_login_print_error( 'You do not have sufficient privileges to access this page.' );
		return;
	}

	if ( ! isset( $_POST['logout'] ) ) {//input var okay
		jwplayer_login_logout_form();
		return;
	}

	// Check the nonce (counter XSRF)
	if ( isset( $_POST['_wpnonce'] ) ) {
		$nonce = sanitize_text_field( $_POST['_wpnonce'] );//input var okay
		if ( ! wp_verify_nonce( $nonce, 'jwplayer-logout-nonce' ) ) {
			jwplayer_login_print_error( 'Could not verify the form data.' );
			jwplayer_login_logout_form();
			return;
		}
	}

	// Perform the logout.
	update_option( 'jwplayer_login', null );
	update_option( 'jwplayer_api_key', '' );
	update_option( 'jwplayer_api_secret', '' );
	echo '<h2>Deauthorized</h2><p>Deauthorization successful. Returning you to the <a href="' . esc_url( 'options-media.php' ) . '">media settings</a> page...</p>';
	// Perform a manual JavaScript redirect
	echo '<script type="application/x-javascript">document.location.href = "options-media.php"</script>';
}

// Print the logout page
function jwplayer_login_logout_form() {
	?>
	<div class="wrap">
		<h2>JW Player deauthorization</h2>

		<form method="post" action="">
			<p>You can use this page to deauthorize access to your JW Player account.<br>
				Note that, while deauthorized, videos will not be embedded.</p>

			<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'jwplayer-logout-nonce' ) ); ?>">

			<p class="submit"><input type="submit" class="button-primary" value="Deauthorize" name="logout"></p>

		</form>
	</div>
	<?php
}
