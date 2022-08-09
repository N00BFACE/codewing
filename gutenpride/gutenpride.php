<?php
/**
 * Plugin Name:       Gutenpride 
 * Description:       A Gutenberg block to show your pride! This block enables you to type text and style it with the color font Gilbert from Type with Pride.
 * Version:           0.1.0
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gutenpride
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/referwence/functions/register_block_type/
 */
function create_block_gutenpride_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_gutenpride_block_init' );

add_action( 'init', 'custom_post_type' );
function custom_post_type(){
    register_post_type( 'contactform',
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
                'slug' => 'contactform'
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

function myguten_register_post_meta() {
	register_post_meta(
		'contactform',
		'myguten_meta_block_field',
		array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		)
	);
}
add_action('init', 'myguten_register_post_meta');
function myguten_content_filter( $content ) {
    $value = get_post_meta( get_the_ID(), 'myguten_meta_block_field', true );
    if ( $value ) {
        return sprintf( "%s <h4> %s </h4>", $content, esc_html( $value ) );
    } else {
        return $content;
    }
}
add_filter( 'the_content', 'myguten_content_filter' );
