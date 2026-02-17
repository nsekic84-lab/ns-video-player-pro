<?php
/*
Plugin Name: NS Video Player PRO
Plugin URI: https://nsdizajn.in.rs/ns-video-player-pro
Description: Fully functional WordPress video player with admin panel, lazy load, watermark, and shortcode generator.
Version: 1.0.0
Author: Nikola Sekić
Author URI: https://nsdizajn.in.rs
Text Domain: ns-video-player-pro
Domain Path: /languages
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit;

class NS_Video_Player_PRO {

    function __construct() {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
        add_shortcode('ns_video', array($this, 'render_video'));
    }

    // Admin menu
    function admin_menu() {
        add_menu_page(
            'NS Video Player', 
            'NS Video Player', 
            'manage_options', 
            'ns-video-player-pro', 
            array($this, 'admin_page'), 
            'dashicons-video-alt3', 
            30
        );
    }

    // Admin scripts & WP Media
    function admin_scripts($hook) {
        if ($hook != 'toplevel_page_ns-video-player-pro') return;

        // WP Media Library
        if (!function_exists('wp_enqueue_media')) wp_enqueue_media();

        wp_enqueue_script('ns-video-admin-js', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery'), null, true);
        wp_enqueue_style('ns-video-admin-css', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    }

    // Frontend scripts & CSS
    function frontend_scripts() {
        wp_enqueue_script('ns-video-player-js', plugin_dir_url(__FILE__) . 'assets/js/player.js', array(), null, true);
        wp_enqueue_style('ns-video-player-css', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    }

    // Admin page
    function admin_page() {
        ?>
        <div class="wrap">
            <h1>NS Video Player PRO</h1>
            <form id="ns-video-form" method="post">
                <table class="form-table">
                    <tr>
                        <th>Video</th>
                        <td>
                            <input type="text" id="ns_video_src" name="ns_video_src" readonly />
                            <button class="button" id="ns_video_upload">Select Video</button>
                        </td>
                    </tr>
                    <tr>
                        <th>Poster Image</th>
                        <td>
                            <input type="text" id="ns_video_poster" name="ns_video_poster" readonly />
                            <button class="button" id="ns_poster_upload">Select Poster</button>
                        </td>
                    </tr>
                    <tr>
                        <th>Autoplay</th>
                        <td><input type="checkbox" id="ns_video_autoplay" name="ns_video_autoplay" /></td>
                    </tr>
                    <tr>
                        <th>Watermark</th>
                        <td><input type="checkbox" id="ns_video_watermark" name="ns_video_watermark" /></td>
                    </tr>
                    <tr>
                        <th>Primary Color</th>
                        <td><input type="color" id="ns_video_color" name="ns_video_color" value="#ff0000" /></td>
                    </tr>
                </table>
                <p>
                    <button class="button button-primary" id="ns_generate_shortcode">Generate Shortcode</button>
                </p>
            </form>
            <h2>Generated Shortcode</h2>
            <input type="text" id="ns_generated_shortcode" style="width:100%" readonly />
        </div>
        <?php
    }

    // Shortcode render
    function render_video($atts) {
        $atts = shortcode_atts(array(
            'src' => '',
            'poster' => '',
            'autoplay' => '0',
            'watermark' => '0',
            'color' => '#ff0000'
        ), $atts, 'ns_video');

        if(!$atts['src']) return '';

        $autoplay = $atts['autoplay'] == '1' ? ' autoplay muted' : '';
        $watermark_html = $atts['watermark'] == '1' ? '<div class="ns-watermark">NS Video</div>' : '';

        return '<div class="ns-video-container" data-color="'.$atts['color'].'">
                    <video controls preload="none" data-src="'.$atts['src'].'" poster="'.$atts['poster'].'"'.$autoplay.'></video>
                    '.$watermark_html.'
                </div>';
    }
}

new NS_Video_Player_PRO();
