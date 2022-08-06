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
    exit;
}

add_action('wp_enqueue_scripts', 'post_style');
function post_style() {
	wp_register_style('new_style', plugins_url( 'contact_form_block_plugin/style.css' ));
	wp_enqueue_style( 'new_style');
    wp_register_script('main', plugins_url( 'contact_form_block_plugin/main.js' ), array('jquery'), '1.0.0', true);
    $translation_array = array(
        'ajax_url' => admin_url('admin-ajax.php'),
    );
    wp_localize_script( 'main', 'cpm_object', $translation_array );
    wp_enqueue_script('main');
}

add_action( 'init', 'custom_post_type' );
function custom_post_type(){
    register_post_type( 'metablocks',
        array(
            'labels' => array(
                'name' => __( 'Contact Forms' ),
                'singular_name' => __( 'Contact Form' )
            ),
            'add_new' => 'Add New Form',
            'public' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'metablocks'
            ),
            'menu_icon' => 'dashicons-email-alt',
            'show_in_rest' => true,
            'supports' => array(
                'title',
                'editor',
                'custom-fields',
            )
        )
    );
}

add_action( 'init', 'single_post_meta' );
function single_post_meta() {
    register_post_meta( 
        'metablocks', 
        'meta_block_field', 
        array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        )
    );
}

add_action('add_meta_boxes', 'single_rapater_meta_boxes');
function single_rapater_meta_boxes() {
    $posts = [ 'post_type' => 'metablocks' ];
    foreach($posts as $post) {
        add_meta_box(
            'wporg_box_id',
            'Contact Forms',
            'single_repeatable_meta_box_callback',
            $post
        );
    }
}

require plugin_dir_path( __FILE__ ) . 'repeater-callback.php';

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
	$repeater_status= $_REQUEST['repeater_status'];
	update_post_meta( $post_id, 'repeater_status', $repeater_status );
}

function content_filter($content) {
    $single_repeter_group = get_post_meta(get_the_ID(), 'single_repeter_group', true);
    if($single_repeter_group) {
        $fullcontent = "<style>
                            .container {
                                font-family: 'Open Sans', sans-serif;
                                background-color: rgb(202, 126, 126);
                                border-radius: 5px;
                                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
                                width: 400px;
                            }
                            .form {
                                padding: 30px 40px;
                            }
                              
                            .form-control {
                                margin-bottom: 10px;
                                padding-bottom: 20px;
                                position: relative;
                            }  
                            .form-control label {
                                color: #777;
                                display: block;
                                margin-bottom: 5px;
                            }  
                            .form-control input {
                                border: 2px solid #f0f0f0;
                                border-radius: 4px;
                                display: block;
                                width: 100%;
                                padding: 10px;
                                font-size: 14px;
                            }
                            .form-control textarea {
                                border: 2px solid #f0f0f0;
                                border-radius: 4px;
                                display: block;
                                width: 100%;
                                padding: 10px;
                                font-size: 14px;
                            }
                        </style>";
        $fullcontent .= "<div class='repeter-content' >";
        $fullcontent .= "<div class='container'>";
        $fullcontent .= "<form class='form'>";
        $fullcontent .= "<div class='form-control'>";
        foreach ($single_repeter_group as $field) {
            $fullcontent .= "<label>" . $field['title'] . "</label>";
            $fullcontent .= "<div class='form-group'>";
            if($field['title'] == 'Message') {
                $fullcontent .= "<textarea name='message' placeholder='Enter your message'></textarea>";
            } else {
                $fullcontent .= "<input type='text' name='" . $field['title'] . "' placeholder='Enter your " . $field['title'] . "'>";
            }
            $fullcontent .= "</div>";
        }
        $fullcontent .= "</div>";
        $fullcontent .= "</form>";
        $fullcontent .= "</div>";
        $fullcontent .= "</div>";
        return $fullcontent;
    } else {
        return $content;
    }
}
add_filter ('the_content', 'content_filter');

add_action( 'init', 'create_block_init' );
function create_block_init() {
    register_block_type( __DIR__ . '/build', 
	array (
		'render_callback' => 'contact_form_block_render_callback',
	) );
}

function contact_form_block_render_callback( $attributes, $content ) {
    $posts = get_posts(
        [
            'post' => $attributes['postid'],
        ]
    );
	$args = array(
		'post_type' => 'metablocks',
	  );
	  $query = new WP_Query($args);
	  if($query->have_posts()){
		while($query->have_posts()){
		  $query->the_post();
		}
		$content = display_content($attributes['postid']);
		return $content;
	}
}

function display_content($post_id) {
    $id = $post_id;
    $feature_template = get_post_meta($id, 'single_repeter_group', true);
    if(!empty($feature_template)) {
        $html = "<div class='container form-ajax'>";
        $html .= "<form id='myform' class='form' method='POST'>";
        $html .= "<h2>Contact Us</h2>";
        $html .= "<span class='error' id='error'></span>";
        $html .= "<div class='form-control'>";
        foreach ($feature_template as $item) {
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
                            <input type='text' id='subject' name='subject' placeholder='" . $item['tdesc'] . "' required maxlength='100'/>
                            <span class='error' id='subject_err'></span>
                        </div>";
            }
            else if( ($item['title'] == 'Message')) {
                $html .= "<div class='form-group'>
                            <textarea class='form-control' name='message' id='message' placeholder='" . $item['tdesc'] . "' required  maxlength='1000' ></textarea>
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
