<?php
/**
 * File: includes/class-student-cpt.php
 * Đăng ký Custom Post Type "Sinh Viên"
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Student_CPT {

    /**
     * Constructor – đăng ký hook
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ) );
    }

    /**
     * Đăng ký CPT "sinhvien"
     */
    public function register_post_type() {

        $labels = array(
            'name'                  => _x( 'Sinh Viên', 'Post Type General Name', 'student-manager' ),
            'singular_name'         => _x( 'Sinh Viên', 'Post Type Singular Name', 'student-manager' ),
            'menu_name'             => __( 'Sinh Viên', 'student-manager' ),
            'name_admin_bar'        => __( 'Sinh Viên', 'student-manager' ),
            'archives'              => __( 'Danh sách Sinh Viên', 'student-manager' ),
            'attributes'            => __( 'Thuộc tính Sinh Viên', 'student-manager' ),
            'parent_item_colon'     => __( 'Sinh viên cha:', 'student-manager' ),
            'all_items'             => __( 'Tất cả Sinh Viên', 'student-manager' ),
            'add_new_item'          => __( 'Thêm Sinh Viên Mới', 'student-manager' ),
            'add_new'               => __( 'Thêm Mới', 'student-manager' ),
            'new_item'              => __( 'Sinh Viên Mới', 'student-manager' ),
            'edit_item'             => __( 'Chỉnh Sửa Sinh Viên', 'student-manager' ),
            'update_item'           => __( 'Cập Nhật Sinh Viên', 'student-manager' ),
            'view_item'             => __( 'Xem Sinh Viên', 'student-manager' ),
            'view_items'            => __( 'Xem Danh Sách', 'student-manager' ),
            'search_items'          => __( 'Tìm kiếm Sinh Viên', 'student-manager' ),
            'not_found'             => __( 'Không tìm thấy sinh viên.', 'student-manager' ),
            'not_found_in_trash'    => __( 'Không có sinh viên trong thùng rác.', 'student-manager' ),
            'featured_image'        => __( 'Ảnh đại diện', 'student-manager' ),
            'set_featured_image'    => __( 'Đặt ảnh đại diện', 'student-manager' ),
            'remove_featured_image' => __( 'Xóa ảnh đại diện', 'student-manager' ),
            'use_featured_image'    => __( 'Dùng làm ảnh đại diện', 'student-manager' ),
            'insert_into_item'      => __( 'Chèn vào sinh viên', 'student-manager' ),
            'uploaded_to_this_item' => __( 'Tải lên cho sinh viên này', 'student-manager' ),
            'items_list'            => __( 'Danh sách sinh viên', 'student-manager' ),
            'items_list_navigation' => __( 'Điều hướng danh sách', 'student-manager' ),
            'filter_items_list'     => __( 'Lọc danh sách sinh viên', 'student-manager' ),
        );

        $args = array(
            'label'               => __( 'Sinh Viên', 'student-manager' ),
            'description'         => __( 'Quản lý thông tin sinh viên', 'student-manager' ),
            'labels'              => $labels,

            // Hỗ trợ title (Họ tên) và editor (Tiểu sử/Ghi chú)
            'supports'            => array( 'title', 'editor' ),

            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-groups',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => false, // Tắt Gutenberg, dùng Classic Editor
        );

        register_post_type( 'sinhvien', $args );
    }
}
