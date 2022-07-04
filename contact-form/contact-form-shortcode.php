<?php
//shortcode for the custom post type
add_action('init', 'register_shortcode');
function register_shortcode() {
  add_shortcode('contact-form', 'shortcode_contact_form');
}
function shortcode_contact_form($atts=[], $content = null, $tag='') {
  if(!current_user_can( 'manage_options' )) {
    return ' ';
  }
  else {
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
    $head_atts = shortcode_atts( array(
      'id' => '5',
    ), $atts, $tag );
    $args = array(
      'post_type' => 'contact-form',
    );
    $query = new WP_Query($args);
    if($query->have_posts()){
      while($query->have_posts()){
        $query->the_post();
      }
      $content = display_content($head_atts['id']);
      return $content;
    }
  }
}