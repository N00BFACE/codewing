<?php
/**
 * Author: Bishal Shrestha
 * Plugin Name: Counter
 * Version: 1.0
 * Description: A plugin that counts.
 * Category: InternPluginTask
 */

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
            $html = 'This post has ' . $wordCount . ' words.<br>';
        }

        if(get_option( 'location', '0' ) == 0) {
            return $html . $content;
        }
        return $content . $html;
    }

    function pluginSettings() {
        // add_settings_section(1,2,3,4); -> wp-query short code widget
        // 1=> Name of the section
        // 2=> Title for the section -> null if sub titles not needed
        // 3=> Content
        // 4=> Page slug
        add_settings_section('first_section',null, null, 'counter-plugins-setting');

        add_settings_section('second_section',null, null, 'counter-plugins-setting');

        // add_settings_field(1,2,3,4,5)
        // 1=> name of the setting
        // 2=> HTML label text
        // 3=> function responsible for html custom output
        // 4=> Page slug
        // 5=> name of the section
        add_settings_field('location','Display Location', array($this, 'locationHTML'), 'counter-plugins-setting', 'first_section');

        // register_setting(1,2,3); -> use as times neede
        // 1=> name of the group
        // 2=> actual name for the setting
        // 3=> array(sanitize/validate , default)
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