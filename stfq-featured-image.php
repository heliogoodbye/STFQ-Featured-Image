<?php
/*
Plugin Name: STFQ Featured Image
Plugin URI: #
Description: Allows users to set a default featured image from the media library.
Plugin URI: https://strangefrequency.com/wp-plugins/stfq-login-logo/
Version: 1.0
Author: Strangefrequency LLC
Author URI: https://strangefrequency.com/
License: GPL-3.0
License URI: https://www.gnu.org/licenses/old-licenses/gpl-3.0.html
*/


// Add a menu item to the admin menu
function stfq_featured_image_add_admin_menu() {
    add_options_page('STFQ Featured Image Settings', 'STFQ Featured Image', 'manage_options', 'stfq-featured-image-settings', 'stfq_featured_image_render_settings_page');
}
add_action('admin_menu', 'stfq_featured_image_add_admin_menu');

// Render the settings page
function stfq_featured_image_render_settings_page() {
    ?>
    <div class="wrap">
        <h2>STFQ Featured Image Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('stfq_featured_image_settings_group'); ?>
            <?php do_settings_sections('stfq-featured-image-settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register and initialize plugin settings
function stfq_featured_image_initialize_settings() {
    register_setting('stfq_featured_image_settings_group', 'stfq_featured_image_default');
    add_settings_section('stfq_featured_image_settings_section', 'Default Featured Image', 'stfq_featured_image_settings_section_callback', 'stfq-featured-image-settings');
    add_settings_field('stfq_featured_image_default', 'Select Default Featured Image', 'stfq_featured_image_default_callback', 'stfq-featured-image-settings', 'stfq_featured_image_settings_section');
}
add_action('admin_init', 'stfq_featured_image_initialize_settings');

// Callback for settings section
function stfq_featured_image_settings_section_callback() {
    echo 'Select a default featured image from the media library.';
}

// Callback for default image field
function stfq_featured_image_default_callback() {
    $default_image_id = get_option('stfq_featured_image_default');
    $image_html = '';

    if (!empty($default_image_id)) {
        $image_html .= wp_get_attachment_image($default_image_id, 'thumbnail');
        $image_html .= '<br/><a href="#" id="stfq-remove-default-image">Remove Default Image</a>';
    }

    $image_html .= '<div id="stfq-featured-image-preview">';
    if (empty($default_image_id)) {
        $image_html .= 'No default image selected.';
    }
    $image_html .= '</div>';

    $image_html .= '<input type="hidden" name="stfq_featured_image_default" id="stfq-featured-image-id" value="' . esc_attr($default_image_id) . '" />';
    $image_html .= '<button id="stfq-select-featured-image" class="button">Select Image</button>';

    echo $image_html;
}

// Enqueue media library scripts and custom JavaScript
function stfq_featured_image_enqueue_scripts($hook) {
    if ($hook == 'settings_page_stfq-featured-image-settings') {
        wp_enqueue_media();
        wp_enqueue_script('stfq-featured-image-media-library', plugin_dir_url(__FILE__) . 'js/media-library.js', array('jquery'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'stfq_featured_image_enqueue_scripts');

// Set default featured image if post doesn't have one
function stfq_featured_image_set_default_featured_image($post_id) {
    if (!has_post_thumbnail($post_id)) {
        $default_image = get_option('stfq_featured_image_default');
        if (!empty($default_image)) {
            set_post_thumbnail($post_id, $default_image);
        }
    }
}
add_action('save_post', 'stfq_featured_image_set_default_featured_image');
