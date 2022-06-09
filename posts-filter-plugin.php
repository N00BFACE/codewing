<?php
/**
 * Author: Bishal Shrestha
 * Plugin Name: PostFilter
 * Version: 1.0
 * Description: A plugin that filters the posts.
 * Category: InternPluginTask
 */
add_action( 'init', 'wporg_shortcodes_init' );

add_action( 'wp_enqueue_scripts', 'post_style' );

function wporg_shortcode( $atts = [], $content = null, $tag = '' ) {
    if(!current_user_can( 'manage_options' )) {
        return '';
    }
    else {
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
    
        // override default attributes with user attributes
        $head_atts = shortcode_atts( array(
            'head' => ' Posts',
            'image' => '1',
            ), $atts, $tag
        );

        $query = new WP_Query( 
            shortcode_atts(
                array(
                    'author' => 'codewing',
                    'tags' => ' ',
                    'posts_per_page' => 20,
                    'order' => 'asc',
                    'post_type' => 'post',
                    'post_status' => 'publish',
                ), $atts, $tag
            )
        );
        if($query->have_posts()){ ?>
        <h1><?php echo "{$head_atts['head']}"; ?></h1>
            <div class="grid-container"> <?php
            while($query->have_posts()): $query->the_post(); ?>
                <article>
                <h1 style='height: 100px;'><?php the_title(); ?></h1>
                <?php 
                    if("{$head_atts['image']}" == 1) { ?>
                    <figure>
                        <img src="https://via.placeholder.com/850x450.png/333/fff?text=Featured+Image" alt="#">
                    </figure>
                <?php
                    } ?>
                    <p>Author: <?php the_author( ); ?></p>
                    <p>Posted: <?php the_date('F j, Y'); ?> at <?php the_time('g:i a'); ?></p>
                </article>
            <?php endwhile; ?>
            </div> <?php
        }
    }
}

function wporg_shortcodes_init() {
    add_shortcode( 'wporg', 'wporg_shortcode' );
}

function post_style() {
    wp_register_style('new_style', plugins_url( 'plugins-codewing/css/plugin.css' ));
    wp_enqueue_style( 'new_style');
}