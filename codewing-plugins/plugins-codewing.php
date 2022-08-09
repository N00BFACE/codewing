<?php
/**
 * Author: Bishal Shrestha
 * Plugin Name: Poster
 * Version: 1.0
 * Description: A plugin that presents all the posts.
 * Category: InternPluginTask
 */


// function that runs when shortcode is called
function wpb_demo_shortcode() {
    // the query
    $query = new WP_Query( array( 'author_name' => 'codewing' ) ); ?>
    
    <?php if ( $query->have_posts() ) : ?>
    
        <!-- pagination here -->
    
        <!-- the loop -->
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <h2><?php the_title(); ?></h2>
            <i><?php the_content(); ?></i>
            <?php comment_form(  ); ?>
        <?php endwhile; ?>
        <?php echo 'Search found '.$query->post_count.' results';?>
        <br>
        <!-- end of the loop -->
    
        <!-- pagination here -->
    
        <?php wp_reset_postdata(); ?>
    
    <?php else : ?>
        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
    <?php endif; ?>
<?php
}


// register shortcode
add_shortcode('greeting', 'wpb_demo_shortcode');

class Counter {
    function __construct(){
        add_action('admin_menu', array($this, 'pluginPage'));
        add_action('admin_init', array($this, 'pluginSettings')); //pluginSettings->method/funtion
        add_filter('the_content', array($this,'ifWrap'));
    }

    function ifWrap($content) {
        if(get_option( 'count', '1')) {
            return $this->createHTML($content);
        }
        return $content;
    }

    function createHTML($content) {
        if(get_option( 'count', '1' )) {
            $wordCount = str_word_count($content);
        }
        
        if(get_option( 'count', '1' )) {
            $html = 'This post has ' . $wordCount . ' words.';
        }

        if(get_option( 'location', '0' ) == 0) {
            return $html . $content;
        }
        return $content . $html;
    }

    function pluginSettings() {
        add_settings_section('first_section',null, null, 'counter-plugins-setting');
        add_settings_section('second_section',null, null, 'counter-plugins-setting');

        add_settings_field('location','Display Location', array($this, 'locationHTML'), 'counter-plugins-setting', 'first_section');
        register_setting('counterPlugin','location', array('sanitize_callback'=> array($this, 'sanitize_location'),'default'=>'0'));

        add_settings_field('count','Word count', array($this, 'countHTML'), 'counter-plugins-setting', 'second_section');
        register_setting('counterPlugin','count', array('sanitize_callback'=>'sanitize_text_field','default'=>'1'));
    }

    function sanitize_location($input) {
        if($input != '0' && $input != '1') {
            add_settings_error( 'location', 'location_error', 'Display must be in top or bottom.' );
            return get_option( 'location');
        }
        return $input;
    }

    function locationHTML() { ?>
        <select name="location">
            <option value="0" <?php selected( get_option( 'location' ), '0' ) ?>>Top</option>
            <option value="1" <?php selected( get_option( 'location' ), '1' ) ?>>Bottom</option>
        </select>
    <?php }

    function countHTML() { ?>
        <input type="checkbox" name="count" value="1" <?php checked( get_option('count'), '1' ) ?>/>
    <?php }

    function pluginPage() {
        add_options_page('Counter Settings','Counter','manage_options','counter-plugins-setting',array($this, 'pluginHTML'));
    }
    function pluginHTML() { ?>
    <div class="wrap">
        <h1>Counter Settings</h1>
        <form action="options.php" method="POST">
            <?php
              settings_fields( 'counterPlugin' );
              do_settings_sections('counter-plugins-setting');
              submit_button();
            ?>
        </form>
    </div>
    <?php }
 }

 $counter = new Counter();