<?php
/**
* Plugin Name: Contact Form
* Author: bishalcodewing
* Version: 1.0.0
* License: GPLv2 or later
* Description: A plugin to create custom contact forms and display them using either shortcodes or blocks
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
add_action('wp_enqueue_scripts', 'post_style');
function post_style() {
  wp_register_style('new_style', plugins_url( 'contact-form/style.css' ));
  wp_enqueue_style( 'new_style');
  wp_enqueue_script('new_script', plugins_url( 'contact-form/script.js' ));
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


// Add Meta Box to post
add_action('add_meta_boxes', 'single_rapater_meta_boxes');

function single_rapater_meta_boxes() {
	add_meta_box(
        'wporg_box_id',
        'Contact Form',
        'single_repeatable_meta_box_callback',
        'contact-form'
    );
}

function single_repeatable_meta_box_callback($post) {
	$single_repeter_group = get_post_meta($post->ID, 'single_repeter_group', true);
	$banner_img = get_post_meta($post->ID,'post_banner_img',true);
	wp_nonce_field( 'repeterBox', 'formType' );
	?>
	<script type="text/javascript">
		jQuery(document).ready(function( $ ){
			$( '#add-row' ).on('click', function() {
				var row = $( '.empty-row.custom-repeter-text' ).clone(true);
				row.removeClass( 'empty-row custom-repeter-text' ).css('display','table-row');
				row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
				return false;
			});

			$( '.remove-row' ).on('click', function() {
				$(this).parents('tr').remove();
				return false;
			});
		});

	</script>

	<table id="repeatable-fieldset-one" class="container">
    <tbody>
			<?php
			if ( $single_repeter_group ) :
				foreach ( $single_repeter_group as $field ) {
					?>
					<tr>
						<td><input type="text"  style="width:98%;" name="title[]" value="<?php if($field['title'] != '') echo esc_attr( $field['title'] ); ?>" placeholder="Heading" /></td>
						<td><input type="text"  style="width:98%;" name="tdesc[]" value="<?php if ($field['tdesc'] != '') echo esc_attr( $field['tdesc'] ); ?>" placeholder="Description" /></td>
						<td><a class="button remove-row" href="#1">Remove</a></td>
					</tr>
					<?php
				}
			else :
				?>
				<tr>
					<td><input type="text"   style="width:98%;" name="title[]" placeholder="Heading"/></td>
					<td><input type="text"  style="width:98%;" name="tdesc[]" value="" placeholder="Description" /></td>
					<td><a class="button  cmb-remove-row-button button-disabled" href="#">Remove</a></td>
				</tr>
			<?php endif; ?>
			<tr class="empty-row custom-repeter-text" style="display: none">
				<td><input type="text" style="width:98%;" name="title[]" placeholder="Heading"/></td>
				<td><input type="text" style="width:98%;" name="tdesc[]" value="" placeholder="Description"/></td>
				<td><a class="button remove-row" href="#">Remove</a></td>
			</tr>

		</tbody>
	</table>
	<p><a id="add-row" class="button" href="#">Add another</a></p>
	<?php
}

// Save Meta Box values.
add_action('save_post', 'single_repeatable_meta_box_save');

function single_repeatable_meta_box_save($post_id) {

	if (!isset($_POST['formType']) && !wp_verify_nonce($_POST['formType'], 'repeterBox'))
		return;
	if (!current_user_can('edit_post', $post_id))
		return;
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
      $html .= "<form id='form' class='form'>";
      $html .= "<h2>Contact With Us</h2>";
      $html .= "<div class='form-control'>";
      foreach ($feture_template as $item) {
        $html .= "<label>" . $item['title'] . "</label>";
        if( ($item['title'] == 'Message') || ($item['title'] == 'Content') || ($item['title'] == 'Body')) {
          $html .= "<textarea name='tdesc' id='content' placeholder='" . $item['tdesc'] . "'></textarea>";
        }
        elseif( ($item['title'] == 'Username') || ($item['title'] == 'Full Name') || ($item['title'] == 'Fullname') || ($item['title'] == 'Name')) {
          $html .= "<input type='text' id='username' name='tdesc' placeholder='" . $item['tdesc'] . "'/>";
        }
        elseif( ($item['title'] == 'Email') || ($item['title'] == 'Email Address')) {
          $html .= "<input type='text' id='email' name='tdesc' placeholder='" . $item['tdesc'] . "'/>";
        }
        elseif( ($item['title'] == 'Address') || ($item['title'] == 'Location') || ($item['title'] == 'Residence')) {
          $html .= "<input type='text' id='address' name='tdesc' placeholder='" . $item['tdesc'] . "'/>";
        }
        elseif( ($item['title'] == 'Contact') || ($item['title'] == 'Contact Number') || ($item['title'] == 'Number') || ($item['title'] == 'Phone Number')) {
          $html .= "<input type='text' id='contact' name='tdesc' placeholder='" . $item['tdesc'] . "'/>";
        }
        else {
          $html .= "<input type='text' name='tdesc' placeholder='" . $item['tdesc'] . "'/>";
        }
      }
      $html .= "<input type='button' class='button' id='submit' value='Send'/>";
      $html .= "<span id='err'></span></div>";
      $html .= "</form>";
      $html .= "</div>";
      return $html;
    }
}
require plugin_dir_path( __FILE__ ) . 'contact-form-shortcode.php';