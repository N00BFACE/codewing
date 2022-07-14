<?php
add_action( 'admin_init', 'add_contact_field' );
function add_contact_field() {
	add_meta_box( 
		'contact_field', 
		'Contact Email', 
		'contact_field_callback', 
		'contactform');
}
function contact_field_callback( ) {
	wp_nonce_field( 'save_contact_field', 'contact_field_nonce' );
	$email = get_option('admin_email');
	echo "<div class='container'>";
	echo "<form id='myform' class='form' method='POST'>";
	echo "<h2>Send Contact Us Mail To:</h2>";
	echo "<div class='form-control'>";
	echo "<div class='form-group'>
			<input type='email' id='email' name='email' placeholder='Enter your email' value='$email' minlength='10' maxlength='100' style='width:250px; padding:5px; margin-right:15px;'/>";
	echo "<input type='submit' class='button' value='Change' name='submit_form' style='padding:5px;'/>";
	echo "</div></div>";
	echo "</form>";
	echo "</div>";
}
add_action('admin_init', 'change_admin_email');
function change_admin_email() {
	if(array_key_exists('submit_form', $_POST)) {
		$email = $_POST['email'];
		update_option('admin_email', $email);
	}
}