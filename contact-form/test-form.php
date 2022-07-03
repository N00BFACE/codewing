<?php
/**
 * Plugin Name: Test Form
 * Author: bishal
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
// Add Meta Box to post
add_action('admin_init', 'single_rapater_meta_boxes', 2);

function single_rapater_meta_boxes() {
	add_meta_box( 
        'wporg_box_id',
        'Contact Form',
        'single_repeatable_meta_box_callback',
        'contact_form'
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

	<table id="repeatable-fieldset-one" width="100%">
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
add_action('the_content', 'show_post_meta_data');
function show_post_meta_data() {
    $id = get_the_ID();
    $feture_template = get_post_meta($id, 'single_repeter_group', true);
    if(!empty($feture_template)) {
        ?>
        <form method="POST" action="">
            <table width="100%" cellspacing="10" cellpadding="10">
                <tbody>
                    <?php  foreach ($feture_template as $item) { ?>
                        <tr>
                            <td style="width: 30%;"><?php echo $item['title']; ?></td>
                            <?php if( $item['title'] != 'Message' ) { ?>
                                <td><input type="text" name="tdesc" placeholder="<?php echo $item['tdesc']; ?>" /></td>
                            <?php } else { ?>
                                <td><textarea name="tdesc" placeholder="<?php echo $item['tdesc']; ?>"></textarea></td>
                            <?php } ?>
                        </tr> 
                    <?php } ?>
                    <tr>
                        <td><input type="submit" value="Send" name="cf-submitted" /></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <?php
    }
}