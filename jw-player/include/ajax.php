<?php

// ajax calls for the jwplayer plugin
// utlizes proxy.php
add_action( 'wp_ajax_jwplayer', function() {
	if ( isset( $_GET['method'] ) ) {//input var okay
		if ( 'upload_ready' === sanitize_text_field( wp_unslash( $_GET['method'] ) ) ) {//input var okay
			echo '{"status" : "ok"}';
		} else {
			jwplayer_proxy();
		}
	}
	die();
} );
