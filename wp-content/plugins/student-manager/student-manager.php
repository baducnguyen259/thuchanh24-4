<?php
/**
 * Plugin Name:       Student Manager
 * Plugin URI:        https://github.com/your-username/student-manager
 * Description:       Quản lý sinh viên với Custom Post Type, Meta Boxes và Shortcode hiển thị danh sách.
 * Version:           1.0.0
 * Author:            Student Developer
 * Author URI:        #
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       student-manager
 * Domain Path:       /languages
 */

// Ngăn truy cập trực tiếp vào file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Định nghĩa hằng số plugin
define( 'SM_PLUGIN_VERSION', '1.0.0' );
define( 'SM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load các file xử lý logic
require_once SM_PLUGIN_PATH . 'includes/class-student-cpt.php';
require_once SM_PLUGIN_PATH . 'includes/class-student-metabox.php';
require_once SM_PLUGIN_PATH . 'includes/class-student-shortcode.php';

/**
 * Khởi tạo plugin
 */
function sm_init_plugin() {
    $cpt       = new Student_CPT();
    $metabox   = new Student_MetaBox();
    $shortcode = new Student_Shortcode();
}
add_action( 'plugins_loaded', 'sm_init_plugin' );

/**
 * Enqueue CSS cho frontend
 */
function sm_enqueue_assets() {
    wp_enqueue_style(
        'student-manager-style',
        SM_PLUGIN_URL . 'assets/style.css',
        array(),
        SM_PLUGIN_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'sm_enqueue_assets' );
