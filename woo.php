<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://#
 * @since             1.0.0
 * @package           Woo
 *
 * @wordpress-plugin
 * Plugin Name:       Woo
 * Plugin URI:        https://#
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            a
 * Author URI:        https://#/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOO_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-activator.php
 */
function activate_woo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-activator.php';
	Woo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-deactivator.php
 */
function deactivate_woo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-deactivator.php';
	Woo_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo' );
register_deactivation_hook( __FILE__, 'deactivate_woo' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo() {

	$plugin = new Woo();
	$plugin->run();

}
run_woo();


 /*Adds the Featured Badge meta box to WooCommerce products.*/
 
 
function add_featured_badge_meta_box() {
    add_meta_box(
        'featured_badge_meta_box',
        __( 'Featured Badge', 'your-plugin-textdomain' ),
        'render_featured_badge_meta_box',
        'product',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'add_featured_badge_meta_box' );


function render_featured_badge_meta_box( $post ) {

	
    wp_nonce_field( 'your_plugin_nonce', 'your_plugin_nonce_field' );


	$is_featured = get_post_meta( $post->ID, '_is_featured_custom', true );
    ?>
    <label for="is_featured_custom">
        <input type="checkbox"
               name="is_featured_custom"
               id="is_featured_custom"
               value="1"
               <?php checked( $is_featured, '1' ); ?> />
        <?php _e( 'Enable Featured Badge', 'your-plugin-textdomain' ); ?>
    </label>
    <?php
}


function your_plugin_save_data( $post_id ) {

    
    if ( ! isset( $_POST['your_plugin_nonce'] ) || ! wp_verify_nonce( $_POST['your_plugin_nonce'], 'your_plugin_nonce_field' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    
    if ( isset( $_POST['is_featured_custom'] ) ) {
        update_post_meta( $post_id, '_is_featured_custom', '1' );
    } else {
        delete_post_meta( $post_id, '_is_featured_custom' );
    }
}
add_action( 'save_post_product', 'your_plugin_save_data' );