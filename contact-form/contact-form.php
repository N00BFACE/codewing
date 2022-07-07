<?php
/**
* Plugin Name: Contact Form
* Author: bishal@codewing
* Version: 1.0.0
* License: GPLv2 or later
* Description: A plugin to create custom contact forms and display them using either shortcodes or blocks
* Text Domain: contact-form
* 
* @package contact-form
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action('wp_enqueue_scripts', 'post_style');
function post_style() {
	wp_register_style('new_style', plugins_url( 'contact_form_block_plugin/style.css' ));
	wp_enqueue_style( 'new_style');
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js');

	wp_enqueue_script('new_script', plugins_url( 'contact_form_block_plugin/script.js' )); 	
}

add_action( 'init', 'custom_post_type' );
function custom_post_type(){
    register_post_type( 'contact-form',
        array(
            'labels' => array(
                'name' => __( 'Contact Forms' ),
                'singular_name' => __( 'Contact Form' )
            ),
            'add_new' => 'Add New Form',
            'public' => true,
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'contact-form'
            ),
            'menu_icon' => 'dashicons-email-alt',
            'supports' => array(
                'title',
                'editor'
            )
        )
    );
}

add_action('add_meta_boxes', 'single_rapater_meta_boxes');

function single_rapater_meta_boxes() {
	add_meta_box(
        'wporg_box_id',
        'Contact Form Fields',
        'single_repeatable_meta_box_callback',
        'contact-form'
    );
}

add_action('save_post', 'single_repeatable_meta_box_save');

function single_repeatable_meta_box_save($post_id) {

	if (!isset($_POST['formType']))
		return null;
	if (!current_user_can('edit_post', $post_id))
		return null;
	$old = get_post_meta($post_id, 'single_repeter_group', true);

	$new = array();
	$titles = $_POST['title'];
	$tdescs = $_POST['tdesc'];
	$count = count( $titles );
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $titles[$i] != '' ) {
			$new[$i]['title'] = stripslashes( strip_tags( $titles[$i] ) );
			$new[$i]['tdesc'] = stripslashes( $tdescs[$i] );
		}
	}

	if ( !empty( $new ) && $new != $old ){
		update_post_meta( $post_id, 'single_repeter_group', $new );
	} elseif ( empty($new) && $old ) {
		delete_post_meta( $post_id, 'single_repeter_group', $old );
	}
	$repeter_status= $_REQUEST['repeter_status'];
	update_post_meta( $post_id, 'repeter_status', $repeter_status );
}

function display_content($post_id) {
    $id = $post_id;
    $feture_template = get_post_meta($id, 'single_repeter_group', true);
    if(!empty($feture_template)) {
      $html = "<div class='container'>";
      $html .= "<form id='myform' class='form' method='POST'>";
      $html .= "<h2>Contact With Us</h2>";
	  $html .= "<div id='data'></div>";
      $html .= "<div class='form-control'>";
      foreach ($feture_template as $item) {
        $html .= "<label>" . $item['title'] . "</label>";
		if( ($item['title'] == 'Name')) {
			$html .= "<div class='form-group'>
						<input type='text' id='fullname' name='name' placeholder='" . $item['tdesc'] . "' required minlength='10' maxlength='100'/>
						<span class='error' id='name_err'></span>
					<div>";
		}
        else if( ($item['title'] == 'Email')) {
			$html .= "<div class='form-group'>
						<input type='email' id='email' name='email' placeholder='" . $item['tdesc'] . "' required minlength='10' maxlength='100'/>
						<span class='error' id='email_err'></span>
					<div>";
        }
		else if( ($item['title'] == 'Contact')) {
			$html .= "<div class='form-group'>
						<input type='text' id='contact' name='contact' placeholder='" . $item['tdesc'] . "' required minlength='10' maxlength='12'/>
						<span class='error' id='con_err'></span>
					</div>";
		}
		else if( ($item['title'] == 'Subject')) {
			$html .= "<div class='form-group'>
						<input type='text' id='subject' name='subject' placeholder='" . $item['tdesc'] . "' required minlength='10' maxlength='12'/>
						<span class='error' id='subject_err'></span>
					</div>";
		}
		else if( ($item['title'] == 'Message')) {
			$html .= "<div class='form-group'>
						<textarea class='form-control' name='message' id='message' placeholder='" . $item['tdesc'] . "' required  maxlength='500' ></textarea>
						<span class='error' id='msg_err'></span>
					<div>";
		}
        else {
          	$html .= "<div class='form-group'>
						<input type='text' id ='tdesc' name='tdesc' placeholder='" . $item['tdesc'] . "' required maxlength='100'/>
						<span class='error' id='tdesc_err'></span>
					</div>";
        }
      }
      $html .= "<input type='submit' class='button' id='submitbtn' value='Submit' name='submit_form'/>";
	  $html .= "<input type='reset' class='button' id='reset' value='Reset'/>";
      $html .= "</div>";
      $html .= "</form>";
      $html .= "</div>";
      return $html;
    }
}
require plugin_dir_path( __FILE__ ) . 'contact-form-shortcode.php';
require plugin_dir_path( __FILE__ ) . 'mail-function.php';
require plugin_dir_path( __FILE__ ) . 'admin-mail.php';