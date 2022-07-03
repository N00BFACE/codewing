<?php
/**
 * Plugin Name: Contact Form
 * Auhor: bishal
 * Version: 0.1.0
 * License: GPL2 or later
 * Description: A simple contact form generator.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
add_action( 'init', 'create_post_type' ) ;
function create_post_type()
{
    register_post_type( 'contact_form',
        array(
            'labels' => array(
                'name' => __( 'Contact Forms' ),
                'singular_name' => __( 'Contact Form' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'contact-form'
            ),
            'supports' => array(
                'title',
                'editor',
                'thumbnail'
            )
        )
    );
}
add_action('add_meta_boxes', 'wporg_add_custom_box');
function wporg_add_custom_box() {
    add_meta_box(
        'wporg_box_id',
        'Contact Form',
        'wporg_custom_box_html',
        'contact_form',
    );
}
function wporg_custom_box_html($post) {
    wp_nonce_field( 'wporg_custom_box_html_nonce', 'wporg_custom_box_nonce');
    $value = get_post_meta($post->ID);
    ?>
    <div class="wrap">
    <p>
        <label for="meta-text" ><?php _e( 'Example Text Input', 'prfx-textdomain' )?></label>
        <input type="text" name="form_fields" id="meta-text" value="<?php if ( isset ( $value['meta-text'] ) ) echo $value['meta-text'][0]; ?>" />
    </p>
    </div>
    <?php
}
add_action( 'save_post', 'wporg_save_postdata' );
function wporg_save_postdata($post_id) {
    if ( ! isset( $_POST['wporg_custom_box_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['wporg_custom_box_nonce'], 'wporg_custom_box_html_nonce' ) ) {
        return;
    }
    if( !current_user_can( 'edit_post', $post_id )) {
        return;
    }
    if ( isset( $_POST['form_fields'] ) ) {
        update_post_meta( $post_id, 'meta-text', sanitize_text_field(  $_POST['form_fields'] ) );
    }
}