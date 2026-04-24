<?php
/**
 * File: includes/class-student-metabox.php
 * Tạo và xử lý Custom Meta Boxes cho CPT "sinhvien"
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Student_MetaBox {

    /**
     * Tên nonce field
     */
    const NONCE_ACTION = 'sm_save_student_meta';
    const NONCE_NAME   = 'sm_student_nonce';

    /**
     * Danh sách chuyên ngành / lớp
     */
    private $departments = array(
        ''           => '-- Chọn chuyên ngành --',
        'cntt'       => 'Công nghệ Thông tin (CNTT)',
        'kinh_te'    => 'Kinh tế',
        'marketing'  => 'Marketing',
        'ke_toan'    => 'Kế toán',
        'quan_tri'   => 'Quản trị Kinh doanh',
        'luat'       => 'Luật',
        'ngon_ngu'   => 'Ngôn ngữ Anh',
    );

    /**
     * Constructor – đăng ký hook
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post_sinhvien', array( $this, 'save_meta_data' ) );

        // Thêm cột tùy chỉnh trong danh sách Admin
        add_filter( 'manage_sinhvien_posts_columns', array( $this, 'add_admin_columns' ) );
        add_action( 'manage_sinhvien_posts_custom_column', array( $this, 'render_admin_columns' ), 10, 2 );
    }

    /**
     * Đăng ký Meta Box
     */
    public function add_meta_boxes() {
        add_meta_box(
            'sm_student_info',                       // ID
            __( 'Thông tin Sinh Viên', 'student-manager' ), // Tiêu đề
            array( $this, 'render_meta_box' ),       // Callback
            'sinhvien',                              // Post type
            'normal',                                // Vị trí
            'high'                                   // Độ ưu tiên
        );
    }

    /**
     * Render nội dung Meta Box
     *
     * @param WP_Post $post
     */
    public function render_meta_box( $post ) {
        // Tạo Nonce field để bảo mật
        wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

        // Lấy dữ liệu đã lưu (nếu có)
        $mssv      = get_post_meta( $post->ID, '_sm_mssv', true );
        $lop       = get_post_meta( $post->ID, '_sm_lop', true );
        $ngay_sinh = get_post_meta( $post->ID, '_sm_ngay_sinh', true );
        ?>

        <div class="sm-metabox-wrap">

            <!-- ===== Mã số sinh viên (MSSV) ===== -->
            <div class="sm-field-row">
                <label for="sm_mssv">
                    <strong><?php esc_html_e( 'Mã số sinh viên (MSSV)', 'student-manager' ); ?></strong>
                    <span class="sm-required">*</span>
                </label>
                <input
                    type="text"
                    id="sm_mssv"
                    name="sm_mssv"
                    value="<?php echo esc_attr( $mssv ); ?>"
                    placeholder="VD: SV001234"
                    class="sm-input"
                />
                <p class="sm-description"><?php esc_html_e( 'Nhập mã số sinh viên (không trùng lặp).', 'student-manager' ); ?></p>
            </div>

            <!-- ===== Lớp / Chuyên ngành ===== -->
            <div class="sm-field-row">
                <label for="sm_lop">
                    <strong><?php esc_html_e( 'Lớp / Chuyên ngành', 'student-manager' ); ?></strong>
                    <span class="sm-required">*</span>
                </label>
                <select id="sm_lop" name="sm_lop" class="sm-select">
                    <?php foreach ( $this->departments as $value => $label ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>"
                            <?php selected( $lop, $value ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- ===== Ngày sinh ===== -->
            <div class="sm-field-row">
                <label for="sm_ngay_sinh">
                    <strong><?php esc_html_e( 'Ngày sinh', 'student-manager' ); ?></strong>
                </label>
                <input
                    type="date"
                    id="sm_ngay_sinh"
                    name="sm_ngay_sinh"
                    value="<?php echo esc_attr( $ngay_sinh ); ?>"
                    class="sm-input sm-date"
                    max="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>"
                />
            </div>

        </div><!-- .sm-metabox-wrap -->

        <style>
            .sm-metabox-wrap { padding: 10px 0; }
            .sm-field-row { margin-bottom: 18px; }
            .sm-field-row label { display: block; margin-bottom: 5px; font-size: 14px; }
            .sm-required { color: #d63638; margin-left: 2px; }
            .sm-input, .sm-select {
                width: 100%;
                max-width: 400px;
                padding: 7px 10px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 14px;
                background: #fff;
            }
            .sm-input:focus, .sm-select:focus {
                border-color: #2271b1;
                outline: none;
                box-shadow: 0 0 0 1px #2271b1;
            }
            .sm-description { margin: 4px 0 0; color: #646970; font-size: 12px; }
        </style>

        <?php
    }

    /**
     * Lưu Meta Data khi save post
     *
     * @param int $post_id
     */
    public function save_meta_data( $post_id ) {

        // 1. Kiểm tra Nonce (bảo mật)
        if (
            ! isset( $_POST[ self::NONCE_NAME ] ) ||
            ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION )
        ) {
            return;
        }

        // 2. Kiểm tra autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // 3. Kiểm tra quyền hạn
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // 4. Sanitize và lưu MSSV
        if ( isset( $_POST['sm_mssv'] ) ) {
            $mssv = sanitize_text_field( wp_unslash( $_POST['sm_mssv'] ) );
            update_post_meta( $post_id, '_sm_mssv', $mssv );
        }

        // 5. Sanitize và lưu Lớp/Chuyên ngành
        if ( isset( $_POST['sm_lop'] ) ) {
            $lop_valid = array_keys( $this->departments );
            $lop       = sanitize_key( wp_unslash( $_POST['sm_lop'] ) );

            // Chỉ lưu nếu giá trị hợp lệ (whitelist)
            if ( in_array( $lop, $lop_valid, true ) ) {
                update_post_meta( $post_id, '_sm_lop', $lop );
            }
        }

        // 6. Sanitize và lưu Ngày sinh
        if ( isset( $_POST['sm_ngay_sinh'] ) ) {
            $ngay_sinh = sanitize_text_field( wp_unslash( $_POST['sm_ngay_sinh'] ) );

            // Validate định dạng ngày Y-m-d
            if ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $ngay_sinh ) ) {
                update_post_meta( $post_id, '_sm_ngay_sinh', $ngay_sinh );
            } else {
                update_post_meta( $post_id, '_sm_ngay_sinh', '' );
            }
        }
    }

    /**
     * Thêm cột tùy chỉnh trong bảng Admin
     *
     * @param array $columns
     * @return array
     */
    public function add_admin_columns( $columns ) {
        $new_columns = array();
        foreach ( $columns as $key => $value ) {
            $new_columns[ $key ] = $value;
            if ( 'title' === $key ) {
                $new_columns['sm_mssv']      = __( 'MSSV', 'student-manager' );
                $new_columns['sm_lop']       = __( 'Chuyên ngành', 'student-manager' );
                $new_columns['sm_ngay_sinh'] = __( 'Ngày sinh', 'student-manager' );
            }
        }
        return $new_columns;
    }

    /**
     * Render dữ liệu cho cột tùy chỉnh
     *
     * @param string $column
     * @param int    $post_id
     */
    public function render_admin_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'sm_mssv':
                $mssv = get_post_meta( $post_id, '_sm_mssv', true );
                echo esc_html( $mssv ? $mssv : '—' );
                break;

            case 'sm_lop':
                $lop = get_post_meta( $post_id, '_sm_lop', true );
                echo esc_html( $lop ? $this->get_department_label( $lop ) : '—' );
                break;

            case 'sm_ngay_sinh':
                $ngay_sinh = get_post_meta( $post_id, '_sm_ngay_sinh', true );
                echo esc_html( $ngay_sinh ? date_i18n( 'd/m/Y', strtotime( $ngay_sinh ) ) : '—' );
                break;
        }
    }

    /**
     * Lấy tên hiển thị của chuyên ngành từ key
     *
     * @param string $key
     * @return string
     */
    public function get_department_label( $key ) {
        return isset( $this->departments[ $key ] ) ? $this->departments[ $key ] : $key;
    }

    /**
     * Getter cho departments (dùng ở Shortcode)
     *
     * @return array
     */
    public function get_departments() {
        return $this->departments;
    }
}
