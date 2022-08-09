<?php
/**
 * Plugin Name: AutoPage
 * Author:Bishal Shrestha
 * Description: Plugin to auto create regular pages and add custom style and scripts directly to your page. The style needs to be added in the header scripts section inside the style tag and the scripts in the footer scripts section.
 * Version: 1.0.0
 * License: GLV2 or later
 */

if(!defined('ABSPATH')) {
    die;
}

class AutoPage{
    function __construct()
    {
        add_action( 'admin_menu', array($this, 'pluginPage'));
        add_action('init', array($this, 'pages'));
        add_action('wp_head', array($this, 'display_header_scripts'));
        add_action('wp_footer', array($this, 'display_footer_scripts'));
    }
    function pluginPage() {
        add_menu_page( 'Auto Page Settings', 'Auto-Page', 'manage_options', 'auto-page-settings', array($this, 'pluginHTML'));
    }
    function pluginHTML() {
        if(array_key_exists('submit_scripts_update',$_POST)) {
            update_option( 'auto_page_header_scripts', $_POST['headerScript'] );
            update_option( 'auto_page_footer_scripts', $_POST['footerScript'] );
            ?>
            <div id="setting-error-settings_updates" class="updated settings-error notice is-dismissible">
                <strong>Settings have been saved.</strong>
            </div>
            <?php
        }
        $header_scripts = get_option('auto_page_header_scripts','none');
        $footer_scripts = get_option( 'auto_page_footer_scripts', 'none');
        
        ?>
        <div class="wrap">
            <h2>Update Scripts and Pages</h2>
            <form method="POST" action="">
                <table>
                    <tr>
                        <td>
                            <label for="header_scripts">Header Scripts</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <textarea class="large-text" name="headerScript"><?php echo $header_scripts; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="footer_scripts">Footer Scripts</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <textarea class="large-text" name="footerScript"><?php echo $footer_scripts; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>About Us Page</label>
                        </td>
                        <td>
                            <input type='checkbox' name='about_us' value='1' <?php checked( get_option('about_us'), '1') ?>/>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <label>Contact Page</label>
                        </td>
                        <td>
                            <input type='checkbox' name='contact' value='1' <?php checked( get_option('contact'), '1') ?>/>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" name="submit_scripts_update" value="UPDATE SCRIPTS" class="button button-primary"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    <?php }
    //pages
    function pages($parent_id=NULL) { 
        //about us
        $check_page_exist = get_page_by_title( 'About Us', 'OBJECT', 'page' );
        if (empty($check_page_exist) AND get_option( 'about_us', '1' )) {
            $page_id = wp_insert_post( array(
                    'post_title' => 'About Us',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => 'About Us',
                    'comment_status' => 'close',
                    'post_parent' => $parent_id,
                )
            );
        }
        //contact
        $check_page_exist = get_page_by_title( 'Contact', 'OBJECT', 'page' );
        if (empty($check_page_exist) AND get_option( 'contact', '1' )) {
            $page_id = wp_insert_post( array(
                    'post_title' => 'Contact',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => 'Contact',
                    'comment_status' => 'close',
                    'post_parent' => $parent_id,
                )
            );
        }
    }
    function display_header_scripts() {
        $header_scripts = get_option('auto_page_header_scripts','none');
        print $header_scripts;
    }
    function display_footer_scripts() {
        $footer_scripts = get_option('auto_page_footer_scripts','none');
        print $footer_scripts;
    }
}

$autoPage = new AutoPage();