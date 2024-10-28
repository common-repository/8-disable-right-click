<?php
/**
 * Plugin Name: 8 Disable Right Click
 * Description: Disable right click in selected pages of your website.
 * Version: 1.0.1
 * Author: 8 Web Design, Hosting & Domains
 * Author URI: https://www.8webdesign.com.au
 */

 defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if (!function_exists('ewdhddrc_get_postdata_value')):
    function ewdhddrc_get_postdata_value($postId) 
    {
        $value = get_post_meta($postId, 'ewdhddrc_enabled', true);
        return ($value === 'yes') ? 'yes' : 'no';
    }
endif;

if (!function_exists('ewdhddrc_box')):
    function ewdhddrc_box() 
    {
        add_meta_box('ewdhddrc_box', 'Disable Right Click', 'ewdhddrc_box_html', array('post', 'page'), 'side');
    }
endif;

if (!function_exists('ewdhddrc_box_html')):
    function ewdhddrc_box_html($post) {
        $enabled = (ewdhddrc_get_postdata_value($post->ID) === 'yes');
        ?>
        <div class="components-base-control__field">
            <label class="components-base-control__label" for="ewdhddrc_enabled">Disable right click?</label>
            <select id="ewdhddrc_enabled" name="ewdhddrc_enabled" style="margin-left: 5px;">
                <option value="no"<?php echo (!$enabled) ? ' selected=""' : ''?>>No</option>
                <option value="yes"<?php echo ($enabled) ? ' selected=""' : ''?>>Yes</option>
            </select>
        </div>
        <?php 
    }
endif;

if (!function_exists('ewdhddrc_save_postdata')):
    function ewdhddrc_save_postdata($post_id) 
    {
        if (array_key_exists('ewdhddrc_enabled', $_POST)) {
            $value = ($_POST['ewdhddrc_enabled'] === 'yes') ? 'yes' : 'no';
            update_post_meta($post_id, 'ewdhddrc_enabled', $value);
        }
    }
endif;

if (!function_exists('ewdhddrc_enqueue_scripts')):
    function ewdhddrc_enqueue_scripts() {
        global $post;
        $value = ewdhddrc_get_postdata_value($post->ID);
        if($value === 'yes') {
            wp_enqueue_script('ewdhddrc-js', plugin_dir_url(__FILE__) . '/assets/js/disable-right-click.js', array('jquery'));
        }
    }
endif;

add_action('save_post', 'ewdhddrc_save_postdata');
add_action('add_meta_boxes', 'ewdhddrc_box');
add_action('wp_enqueue_scripts', 'ewdhddrc_enqueue_scripts');
