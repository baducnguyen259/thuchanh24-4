<?php
/**
 * File: includes/class-student-shortcode.php
 * Đăng ký shortcode [danh_sach_sinh_vien] để hiển thị bảng sinh viên.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Student_Shortcode {

    /**
     * Mapping chuyên ngành (key => label)
     * Đồng bộ với Student_MetaBox::$departments
     */
    private $departments = array(
        'cntt'      => 'Công nghệ Thông tin (CNTT)',
        'kinh_te'   => 'Kinh tế',
        'marketing' => 'Marketing',
        'ke_toan'   => 'Kế toán',
        'quan_tri'  => 'Quản trị Kinh doanh',
        'luat'      => 'Luật',
        'ngon_ngu'  => 'Ngôn ngữ Anh',
    );

    /**
     * Constructor – đăng ký shortcode
     */
    public function __construct() {
        add_shortcode( 'danh_sach_sinh_vien', array( $this, 'render_shortcode' ) );
    }

    /**
     * Render shortcode [danh_sach_sinh_vien]
     *
     * Thuộc tính shortcode hỗ trợ:
     *   - so_luong   : số lượng sinh viên hiển thị (mặc định -1 = tất cả)
     *   - sap_xep    : trường sắp xếp: title | mssv | ngay_sinh (mặc định: title)
     *   - thu_tu     : ASC | DESC (mặc định: ASC)
     *   - lop        : lọc theo chuyên ngành (mặc định: tất cả)
     *
     * Ví dụ: [danh_sach_sinh_vien so_luong="10" sap_xep="mssv"]
     *
     * @param array $atts Thuộc tính shortcode
     * @return string     HTML output
     */
    public function render_shortcode( $atts ) {

        // Xử lý thuộc tính shortcode, đặt giá trị mặc định
        $atts = shortcode_atts(
            array(
                'so_luong' => -1,
                'sap_xep'  => 'title',
                'thu_tu'   => 'ASC',
                'lop'      => '',
            ),
            $atts,
            'danh_sach_sinh_vien'
        );

        // Sanitize thuộc tính
        $so_luong = intval( $atts['so_luong'] );
        $thu_tu   = in_array( strtoupper( $atts['thu_tu'] ), array( 'ASC', 'DESC' ), true )
                    ? strtoupper( $atts['thu_tu'] ) : 'ASC';
        $lop      = sanitize_key( $atts['lop'] );

        // Xác định trường sắp xếp
        $orderby_map = array(
            'title'     => 'title',
            'mssv'      => 'meta_value',
            'ngay_sinh' => 'meta_value',
        );
        $sap_xep = isset( $orderby_map[ $atts['sap_xep'] ] ) ? $atts['sap_xep'] : 'title';
        $orderby = $orderby_map[ $sap_xep ];

        // Xây dựng WP_Query arguments
        $query_args = array(
            'post_type'      => 'sinhvien',
            'post_status'    => 'publish',
            'posts_per_page' => $so_luong,
            'orderby'        => $orderby,
            'order'          => $thu_tu,
            'no_found_rows'  => true, // Tối ưu hiệu suất
        );

        // Nếu sắp xếp theo meta_value, thêm meta_key
        if ( 'mssv' === $sap_xep ) {
            $query_args['meta_key'] = '_sm_mssv';
        } elseif ( 'ngay_sinh' === $sap_xep ) {
            $query_args['meta_key'] = '_sm_ngay_sinh';
        }

        // Lọc theo chuyên ngành (nếu có)
        if ( ! empty( $lop ) && array_key_exists( $lop, $this->departments ) ) {
            $query_args['meta_query'] = array(
                array(
                    'key'     => '_sm_lop',
                    'value'   => $lop,
                    'compare' => '=',
                ),
            );
        }

        $students_query = new WP_Query( $query_args );

        // Bắt đầu output buffering
        ob_start();

        if ( $students_query->have_posts() ) {
            ?>

            <div class="sm-table-wrapper">
                <?php if ( ! empty( $lop ) && isset( $this->departments[ $lop ] ) ) : ?>
                    <h3 class="sm-table-title">
                        <?php
                        echo esc_html(
                            sprintf(
                                __( 'Danh sách Sinh viên – Chuyên ngành: %s', 'student-manager' ),
                                $this->departments[ $lop ]
                            )
                        );
                        ?>
                    </h3>
                <?php else : ?>
                    <h3 class="sm-table-title">
                        <?php esc_html_e( 'Danh sách Sinh viên', 'student-manager' ); ?>
                    </h3>
                <?php endif; ?>

                <p class="sm-table-count">
                    <?php
                    printf(
                        esc_html(
                            _n(
                                'Tổng cộng: %d sinh viên',
                                'Tổng cộng: %d sinh viên',
                                $students_query->post_count,
                                'student-manager'
                            )
                        ),
                        esc_html( $students_query->post_count )
                    );
                    ?>
                </p>

                <div class="sm-table-responsive">
                    <table class="sm-student-table">
                        <thead>
                            <tr>
                                <th class="sm-col-stt">
                                    <?php esc_html_e( 'STT', 'student-manager' ); ?>
                                </th>
                                <th class="sm-col-mssv">
                                    <?php esc_html_e( 'MSSV', 'student-manager' ); ?>
                                </th>
                                <th class="sm-col-hoten">
                                    <?php esc_html_e( 'Họ tên', 'student-manager' ); ?>
                                </th>
                                <th class="sm-col-lop">
                                    <?php esc_html_e( 'Lớp / Chuyên ngành', 'student-manager' ); ?>
                                </th>
                                <th class="sm-col-ngaysinh">
                                    <?php esc_html_e( 'Ngày sinh', 'student-manager' ); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stt = 1;
                            while ( $students_query->have_posts() ) :
                                $students_query->the_post();

                                $post_id   = get_the_ID();
                                $ho_ten    = get_the_title();
                                $mssv      = get_post_meta( $post_id, '_sm_mssv', true );
                                $lop_key   = get_post_meta( $post_id, '_sm_lop', true );
                                $ngay_sinh = get_post_meta( $post_id, '_sm_ngay_sinh', true );

                                // Chuyển đổi key chuyên ngành thành tên hiển thị
                                $lop_label = isset( $this->departments[ $lop_key ] )
                                             ? $this->departments[ $lop_key ]
                                             : ( $lop_key ? $lop_key : '—' );

                                // Format ngày sinh sang dd/mm/yyyy
                                $ngay_sinh_display = '';
                                if ( ! empty( $ngay_sinh ) ) {
                                    $timestamp = strtotime( $ngay_sinh );
                                    if ( $timestamp ) {
                                        $ngay_sinh_display = date_i18n( 'd/m/Y', $timestamp );
                                    }
                                }

                                $row_class = ( $stt % 2 === 0 ) ? 'sm-row-even' : 'sm-row-odd';
                                ?>
                                <tr class="<?php echo esc_attr( $row_class ); ?>">
                                    <td class="sm-col-stt"><?php echo esc_html( $stt ); ?></td>
                                    <td class="sm-col-mssv">
                                        <?php echo esc_html( $mssv ? $mssv : '—' ); ?>
                                    </td>
                                    <td class="sm-col-hoten">
                                        <?php echo esc_html( $ho_ten ); ?>
                                    </td>
                                    <td class="sm-col-lop">
                                        <?php echo esc_html( $lop_label ); ?>
                                    </td>
                                    <td class="sm-col-ngaysinh">
                                        <?php echo esc_html( $ngay_sinh_display ? $ngay_sinh_display : '—' ); ?>
                                    </td>
                                </tr>
                                <?php
                                $stt++;
                            endwhile;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?php esc_html_e( 'STT', 'student-manager' ); ?></th>
                                <th><?php esc_html_e( 'MSSV', 'student-manager' ); ?></th>
                                <th><?php esc_html_e( 'Họ tên', 'student-manager' ); ?></th>
                                <th><?php esc_html_e( 'Lớp / Chuyên ngành', 'student-manager' ); ?></th>
                                <th><?php esc_html_e( 'Ngày sinh', 'student-manager' ); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div><!-- .sm-table-responsive -->
            </div><!-- .sm-table-wrapper -->

            <?php
            wp_reset_postdata();

        } else {
            // Thông báo khi không có sinh viên nào
            ?>
            <div class="sm-empty-notice">
                <p><?php esc_html_e( 'Hiện chưa có thông tin sinh viên nào.', 'student-manager' ); ?></p>
            </div>
            <?php
        }

        return ob_get_clean();
    }
}
