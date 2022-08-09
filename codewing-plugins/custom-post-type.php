<?php
/**
 * Plugin Name: Services Portfolio
 * Description: A custom post type to create services portfolio.
 * Author: Bishal Shrestha
 * Version: 1.0.0
 * License: GPLv2 or later
 */

if(!defined('ABSPATH')) {
    die;
}

//custom post type
function wporg_custom_post_type() {
    register_post_type('wporg_service',
        array(
            'labels'      => array(
                'name'          => __('Portfolio', 'textdomain'),
                'singular_name' => __('Portfolio', 'textdomain'),
            ),
                'public'      => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-portfolio',
        )
    );
}
add_action('init', 'wporg_custom_post_type');

//meta box
function wporg_add_custom_box() {
    add_meta_box(
        'wporg_box_id',
        'Portfolio Details',
        'wporg_custom_box_html',
        'wporg_service',
        );
}
add_action('add_meta_boxes', 'wporg_add_custom_box');

//meta box html
function wporg_custom_box_html($post) {
    wp_nonce_field( 'wporg_custom_box_html_nonce', 'wporg_custom_box_nonce');
    ?>

    <!-- callback function -->
    <?php
        $wporg_name = get_post_meta( $post->ID, 'portfolio_name', true );
        $wporg_wdyso = get_post_meta( $post->ID, 'portfolio_wdyso', true );
        $wporg_first_service = get_post_meta( $post->ID, 'portfolio_first_service', true );
        $wporg_second_service = get_post_meta( $post->ID, 'portfolio_second_service', true );
        $wporg_third_service = get_post_meta( $post->ID, 'portfolio_third_service', true );
        $wporg_fourth_service = get_post_meta( $post->ID, 'portfolio_fourth_service', true );
        $wporg_yourself = get_post_meta( $post->ID, 'portfolio_yourself', true );
        $wporg_first_project = get_post_meta( $post->ID, 'portfolio_first_project', true );
        $wporg_second_project = get_post_meta( $post->ID, 'portfolio_second_project', true );
        $wporg_third_project = get_post_meta( $post->ID, 'portfolio_third_project', true );
        $wporg_fourth_project = get_post_meta( $post->ID, 'portfolio_fourth_project', true );
        $wporg_expertise = get_post_meta( $post->ID, 'portfolio_expertise', true );
        $wporg_experience = get_post_meta( $post->ID, 'portfolio_experience', true );
    ?>

    <!-- Meta Data Form -->
    <div class="wrap">
        <table>
                <tr>
                    <td>
                        <label for="your_name">Your Name</label>
                    </td>
                    <td>
                        <input type="text" placeholder="Your Name" name="name" value="<?php echo esc_attr( $wporg_name ); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="specialization">What do you specialize on?</label>
                    </td>
                    <td>
                        <input type="text" placeholder="Specialization" name="wdyso" value="<?php echo esc_attr( $wporg_wdyso); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="services">Services you provide</label>
                    </td>
                    <td>
                        <input type="text" name="first_service" placeholder="First Service" value="<?php echo esc_attr($wporg_first_service); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td><label> ** Any four services. ** </label></td>
                    <td>
                        <input type="text" placeholder="Second Service" name="second_service" value="<?php echo esc_attr($wporg_second_service); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="text" placeholder="Third Service" name="third_service" value="<?php echo esc_attr($wporg_third_service); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="text" placeholder="Fourth Service" name="fourth_service" value="<?php echo esc_attr($wporg_fourth_service); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="yourself">Write About Yourself</label>
                    </td>
                    <td>
                        <textarea name="yourself" placeholder="Write Here"><?php echo esc_attr($wporg_yourself); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="recent_projects">Recent Project</label>
                    </td>
                    <td>
                        <input type="text" placeholder="Projects" name="first_project" value="<?php echo esc_attr($wporg_first_project); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label> ** At most four projects. ** </label>
                    </td>
                    <td>
                        <input type="text" placeholder="Projects" name="second_project" value="<?php echo esc_attr($wporg_second_project); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="text" placeholder="Projects" name="third_project" value="<?php echo esc_attr($wporg_third_project); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="text" placeholder="Projects" name="fourth_project" value="<?php echo esc_attr($wporg_fourth_project); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="area_of_expertise">Area of Expertise</label>
                    </td>
                    <td>
                        <input type="text" placeholder="Area of Expertise" name="expertise" value="<?php echo esc_attr($wporg_expertise); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="years_of_experience">Years of Experience</label>
                    </td>
                    <td>
                        <input type="text" placeholder="Experience" name="experience" value="<?php echo esc_attr($wporg_experience); ?>"/>
                    </td>
                </tr>
        </table>
    </div>
    <?php
}

//Save Postdata
function wporg_save_postdata($post_id) {
    if( !isset($_POST['wporg_custom_box_nonce']) || !wp_verify_nonce( $_POST['wporg_custom_box_nonce'], 'wporg_custom_box_html_nonce' )){
        return;
    }
    if( !current_user_can( 'edit_post', $post_id )){
        return;
    }
    if( isset($_POST['name'])) {
        update_post_meta( $post_id, 'portfolio_name', sanitize_text_field( $_POST['name'] ));
    }
    if( isset($_POST['wdyso'])) {
        update_post_meta( $post_id, 'portfolio_wdyso', sanitize_text_field( $_POST['wdyso'] ));
    }
    if( isset($_POST['first_service'])) {
        update_post_meta( $post_id, 'portfolio_first_service', sanitize_text_field( $_POST['first_service'] ));
    }
    if( isset($_POST['second_service'])) {
        update_post_meta( $post_id, 'portfolio_second_service', sanitize_text_field( $_POST['second_service'] ));
    }
    if( isset($_POST['third_service'])) {
        update_post_meta( $post_id, 'portfolio_third_service', sanitize_text_field( $_POST['third_service'] ));
    }
    if( isset($_POST['fourth_service'])) {
        update_post_meta( $post_id, 'portfolio_fourth_service', sanitize_text_field( $_POST['fourth_service'] ));
    }
    if( isset($_POST['yourself'])) {
        update_post_meta( $post_id, 'portfolio_yourself', sanitize_text_field( $_POST['yourself'] ));
    }
    if( isset($_POST['first_project'])) {
        update_post_meta( $post_id, 'portfolio_first_project', sanitize_text_field( $_POST['first_project'] ));
    }
    if( isset($_POST['second_project'])) {
        update_post_meta( $post_id, 'portfolio_second_project', sanitize_text_field( $_POST['second_project'] ));
    }
    if( isset($_POST['third_project'])) {
        update_post_meta( $post_id, 'portfolio_third_project', sanitize_text_field( $_POST['third_project'] ));
    }
    if( isset($_POST['fourth_project'])) {
        update_post_meta( $post_id, 'portfolio_fourth_project', sanitize_text_field( $_POST['fourth_project'] ));
    }
    if( isset($_POST['expertise'])) {
        update_post_meta( $post_id, 'portfolio_expertise', sanitize_text_field( $_POST['expertise'] ));
    }
    if( isset($_POST['experience'])) {
        update_post_meta( $post_id, 'portfolio_experience', sanitize_text_field( $_POST['experience'] ));
    }
}
add_action('save_post', 'wporg_save_postdata');

require plugin_dir_path( __FILE__ ) . 'single-wporg_service.php';