<?php
function single_repeatable_meta_box_callback($post) {
	$single_repeter_group = get_post_meta($post->ID, 'single_repeter_group', true);
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
	<p><a id="add-row" class="button" href="#">Add Text Field</a></p>
	<?php
}