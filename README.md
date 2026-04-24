# 🎓 Student Manager — WordPress Plugin

<div align="center">

### Ảnh 1: Menu CPT "Sinh viên" trong Admin

![Menu Sinh viên](/img/image.png)

---

### Ảnh 2: Meta Box nhập MSSV, Lớp, Ngày sinh

![Meta Box Sinh viên](/img/Screenshot%202026-04-24%20135938.png)

---

### Ảnh 3: Trang hiển thị danh sách sinh viên (Shortcode)

![Danh sách sinh viên](/img/Screenshot%202026-04-24%20140004.png)
**Plugin WordPress quản lý thông tin sinh viên với Custom Post Type, Meta Boxes bảo mật và Shortcode hiển thị bảng dữ liệu động.**

</div>

---

## 📋 Mục lục

- [Giới thiệu](#-giới-thiệu)
- [Tính năng](#-tính-năng)
- [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
- [Cấu trúc thư mục](#-cấu-trúc-thư-mục)
- [Hướng dẫn cài đặt](#-hướng-dẫn-cài-đặt)
- [Hướng dẫn sử dụng](#-hướng-dẫn-sử-dụng)
- [Shortcode Reference](#-shortcode-reference)
- [Bảo mật](#-bảo-mật)
- [Tùy biến & Mở rộng](#-tùy-biến--mở-rộng)
- [Ảnh chụp màn hình](#-ảnh-chụp-màn-hình)
- [Changelog](#-changelog)
- [Tác giả](#-tác-giả)
- [License](#-license)

---

## 🌟 Giới thiệu

**Student Manager** là một WordPress plugin được xây dựng theo chuẩn OOP (Object-Oriented Programming), cho phép quản trị viên:

- Quản lý hồ sơ sinh viên trực tiếp trong WordPress Admin
- Lưu trữ thông tin mở rộng (MSSV, Chuyên ngành, Ngày sinh) thông qua Custom Meta Boxes
- Hiển thị danh sách sinh viên dưới dạng bảng HTML responsive trên frontend bằng Shortcode

Plugin được xây dựng theo đúng chuẩn WordPress Coding Standards với đầy đủ cơ chế bảo mật: **Nonce verification**, **Capability check**, **Data sanitization** và **Output escaping**.

---

## ✨ Tính năng

### 🔧 Backend (Quản trị)

| Tính năng                | Chi tiết                                                                        |
| :----------------------- | :------------------------------------------------------------------------------ |
| **Custom Post Type**     | Đăng ký CPT `sinhvien` — xuất hiện trong Admin menu với icon `dashicons-groups` |
| **Hỗ trợ `title`**       | Trường nhập Họ và tên sinh viên                                                 |
| **Hỗ trợ `editor`**      | Khu vực soạn thảo Tiểu sử / Ghi chú                                             |
| **Meta Box — MSSV**      | Trường `text` nhập mã số sinh viên                                              |
| **Meta Box — Lớp**       | Trường `dropdown` chọn chuyên ngành (7 lựa chọn)                                |
| **Meta Box — Ngày sinh** | Trường `date` với giới hạn ngày hợp lệ                                          |
| **Cột Admin tùy chỉnh**  | Hiển thị MSSV, Chuyên ngành, Ngày sinh ngay trong danh sách                     |
| **Bảo mật Nonce**        | Xác thực request qua `wp_nonce_field()` + `wp_verify_nonce()`                   |
| **Sanitize đầu vào**     | `sanitize_text_field()`, `sanitize_key()`, whitelist dropdown, regex ngày       |

### 🖥️ Frontend (Hiển thị)

| Tính năng         | Chi tiết                                                |
| :---------------- | :------------------------------------------------------ |
| **Shortcode**     | `[danh_sach_sinh_vien]` với nhiều tham số tùy chọn      |
| **Bảng HTML**     | 5 cột: STT, MSSV, Họ tên, Lớp / Chuyên ngành, Ngày sinh |
| **Zebra stripes** | Màu xen kẽ dòng chẵn/lẻ để dễ đọc                       |
| **Responsive**    | Cuộn ngang tự động trên màn hình nhỏ                    |
| **Đếm tổng**      | Hiển thị tổng số sinh viên phía trên bảng               |
| **Lọc theo lớp**  | Tham số `lop="cntt"` lọc theo chuyên ngành              |
| **Sắp xếp**       | Tham số `sap_xep` và `thu_tu` để sắp xếp dữ liệu        |
| **Escape output** | Toàn bộ HTML output qua `esc_html()` / `esc_attr()`     |

---

## ⚙️ Yêu cầu hệ thống

| Thành phần  | Phiên bản tối thiểu                           |
| :---------- | :-------------------------------------------- |
| WordPress   | 5.8+                                          |
| PHP         | 7.4+                                          |
| MySQL       | 5.7+ / MariaDB 10.3+                          |
| Trình duyệt | Chrome 80+, Firefox 75+, Safari 13+, Edge 80+ |

---

## 📁 Cấu trúc thư mục

```
student-manager/
│
├── 📄 student-manager.php              ← File chính: Plugin Header & khởi tạo
│
├── 📂 includes/
│   ├── 📄 class-student-cpt.php        ← Đăng ký Custom Post Type "sinhvien"
│   ├── 📄 class-student-metabox.php    ← Meta Boxes + xử lý lưu dữ liệu an toàn
│   └── 📄 class-student-shortcode.php  ← Shortcode [danh_sach_sinh_vien]
│
├── 📂 assets/
│   └── 🎨 style.css                    ← CSS responsive cho bảng frontend

```

### Mô tả chi tiết từng file

#### `student-manager.php` — File chính

- Khai báo Plugin Header (tên, version, author, license…)
- Định nghĩa hằng số: `SM_PLUGIN_VERSION`, `SM_PLUGIN_PATH`, `SM_PLUGIN_URL`
- `require_once` 3 file class trong `includes/`
- Khởi tạo các class trong hook `plugins_loaded`
- Enqueue CSS frontend qua `wp_enqueue_scripts`

#### `includes/class-student-cpt.php` — Custom Post Type

- Class `Student_CPT`
- Hook: `init` → `register_post_type('sinhvien', $args)`
- Hỗ trợ: `title`, `editor`
- Cấu hình: public, show_ui, menu_icon, menu_position

#### `includes/class-student-metabox.php` — Meta Boxes

- Class `Student_MetaBox`
- Hook `add_meta_boxes`: đăng ký meta box "Thông tin Sinh Viên"
- Hook `save_post_sinhvien`: xử lý lưu với đầy đủ bảo mật
- Hook `manage_sinhvien_posts_columns`: thêm cột Admin
- Method `get_department_label()`: chuyển key → tên hiển thị

#### `includes/class-student-shortcode.php` — Shortcode

- Class `Student_Shortcode`
- `add_shortcode('danh_sach_sinh_vien', ...)`
- `shortcode_atts()` xử lý tham số với giá trị mặc định
- `WP_Query` với `no_found_rows: true` (tối ưu hiệu suất)
- `ob_start()` / `ob_get_clean()` để capture HTML output
- `wp_reset_postdata()` sau query

---

## 🚀 Hướng dẫn cài đặt

### Cách 1: Upload qua WordPress Admin _(Khuyến nghị)_

```
1. Vào WordPress Admin
2. Plugins → Thêm mới → Tải lên plugin
3. Chọn file "student-manager.zip"
4. Nhấn "Cài đặt ngay"
5. Nhấn "Kích hoạt plugin"
```

Sau khi kích hoạt, mục **Sinh Viên** sẽ xuất hiện trong sidebar Admin.

### Cách 2: Upload thủ công qua FTP/SFTP

```bash
# Giải nén file ZIP
unzip student-manager.zip

# Upload toàn bộ thư mục vào
/wp-content/plugins/student-manager/

# Vào Admin → Plugins → Tìm "Student Manager" → Kích hoạt
```

### Bước cuối: Flush Permalink _(Quan trọng)_

```
WordPress Admin → Cài đặt → Đường dẫn tĩnh → Lưu thay đổi
```

> **Lý do:** WordPress cần đăng ký lại rewrite rules cho Custom Post Type mới.

---

## 📖 Hướng dẫn sử dụng

### Bước 1 — Thêm sinh viên mới

Vào **Sinh Viên → Thêm Mới** trong Admin menu:

```
┌─────────────────────────────────────────────┐
│  Tiêu đề: [Nhập họ và tên sinh viên]        │
│                                             │
│  Tiểu sử / Ghi chú: [Editor soạn thảo]     │
│                                             │
│  ┌─ Thông tin Sinh Viên ─────────────────┐  │
│  │  MSSV:          [          ]          │  │
│  │  Lớp/Ngành:     [-- Chọn --▼]        │  │
│  │  Ngày sinh:     [dd/mm/yyyy]          │  │
│  └───────────────────────────────────────┘  │
│                              [Xuất bản]     │
└─────────────────────────────────────────────┘
```

**Lưu ý khi nhập liệu:**

- **Họ tên** (tiêu đề): Bắt buộc. Nhập đầy đủ họ và tên.
- **MSSV**: Nên dùng định dạng thống nhất, ví dụ `SV001234`. Không có ký tự đặc biệt.
- **Lớp/Chuyên ngành**: Chọn từ danh sách dropdown. Không thể nhập tự do.
- **Ngày sinh**: Dùng date picker, không thể chọn ngày tương lai.

### Bước 2 — Xem danh sách trong Admin

Vào **Sinh Viên** trong menu — danh sách hiển thị các cột:

| Tiêu đề (Họ tên) | MSSV     | Chuyên ngành | Ngày sinh  | Ngày đăng  |
| :--------------- | :------- | :----------- | :--------- | :--------- |
| Nguyễn Văn An    | SV001001 | CNTT         | 15/03/2003 | 24/04/2025 |

### Bước 3 — Hiển thị bảng trên trang web

1. Vào **Trang → Thêm mới** (hoặc mở trang đã có)
2. Thêm block **Shortcode** (Classic Editor: dán trực tiếp vào nội dung)
3. Nhập shortcode:

```
[danh_sach_sinh_vien]
```

4. **Xuất bản / Cập nhật** trang
5. Xem trang — bảng sinh viên sẽ hiển thị tự động

---

## 📌 Shortcode Reference

### Cú pháp đầy đủ

```
[danh_sach_sinh_vien so_luong="" sap_xep="" thu_tu="" lop=""]
```

### Tham số

| Tham số    | Kiểu     | Mặc định | Mô tả                                            |
| :--------- | :------- | :------- | :----------------------------------------------- |
| `so_luong` | `int`    | `-1`     | Số sinh viên tối đa. `-1` = lấy tất cả           |
| `sap_xep`  | `string` | `title`  | Trường sắp xếp: `title` \| `mssv` \| `ngay_sinh` |
| `thu_tu`   | `string` | `ASC`    | Chiều sắp xếp: `ASC` \| `DESC`                   |
| `lop`      | `string` | _(rỗng)_ | Key chuyên ngành để lọc (xem bảng dưới)          |

### Giá trị hợp lệ cho tham số `lop`

| Giá trị     | Tên chuyên ngành hiển thị  |
| :---------- | :------------------------- |
| `cntt`      | Công nghệ Thông tin (CNTT) |
| `kinh_te`   | Kinh tế                    |
| `marketing` | Marketing                  |
| `ke_toan`   | Kế toán                    |
| `quan_tri`  | Quản trị Kinh doanh        |
| `luat`      | Luật                       |
| `ngon_ngu`  | Ngôn ngữ Anh               |

### Ví dụ sử dụng

```php
// Hiển thị tất cả sinh viên, sắp xếp theo tên A→Z
[danh_sach_sinh_vien]

// Chỉ hiển thị 10 sinh viên đầu tiên
[danh_sach_sinh_vien so_luong="10"]

// Lọc riêng sinh viên ngành CNTT
[danh_sach_sinh_vien lop="cntt"]

// Sắp xếp theo MSSV tăng dần
[danh_sach_sinh_vien sap_xep="mssv" thu_tu="ASC"]

// Lọc Marketing, sắp xếp theo ngày sinh mới nhất
[danh_sach_sinh_vien lop="marketing" sap_xep="ngay_sinh" thu_tu="DESC"]

// Top 5 sinh viên Kinh tế
[danh_sach_sinh_vien lop="kinh_te" so_luong="5"]
```

---

## 🔒 Bảo mật

Plugin áp dụng đầy đủ các lớp bảo vệ theo chuẩn WordPress:

### Lớp 1 — Kiểm tra Nonce

```php
// Tạo nonce khi render form
wp_nonce_field( 'sm_save_student_meta', 'sm_student_nonce' );

// Xác thực khi lưu
if ( ! wp_verify_nonce( $_POST['sm_student_nonce'], 'sm_save_student_meta' ) ) {
    return; // Từ chối nếu nonce không hợp lệ
}
```

> Ngăn chặn **CSRF (Cross-Site Request Forgery)** — mỗi form có token ngẫu nhiên, hết hạn sau ~12 giờ.

### Lớp 2 — Kiểm tra quyền hạn

```php
if ( ! current_user_can( 'edit_post', $post_id ) ) {
    return; // Chỉ user có quyền mới được lưu
}
```

### Lớp 3 — Bỏ qua Autosave

```php
if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
}
```

### Lớp 4 — Sanitize đầu vào

```php
$mssv      = sanitize_text_field( wp_unslash( $_POST['sm_mssv'] ) );
$lop       = sanitize_key( wp_unslash( $_POST['sm_lop'] ) );
$ngay_sinh = sanitize_text_field( wp_unslash( $_POST['sm_ngay_sinh'] ) );

// Whitelist: chỉ lưu nếu giá trị nằm trong danh sách cho phép
if ( in_array( $lop, array_keys( $this->departments ), true ) ) {
    update_post_meta( $post_id, '_sm_lop', $lop );
}

// Validate định dạng ngày
if ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $ngay_sinh ) ) {
    update_post_meta( $post_id, '_sm_ngay_sinh', $ngay_sinh );
}
```

### Lớp 5 — Escape output

```php
echo esc_html( $ho_ten );      // Escape text
echo esc_attr( $mssv );        // Escape attribute HTML
```

### Lớp 6 — Ngăn truy cập trực tiếp

```php
// Đầu mỗi file PHP
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
```

---

## 🛠️ Tùy biến & Mở rộng

### Thêm chuyên ngành mới

Mở **2 file** sau và thêm vào array `$departments`:

**File 1:** `includes/class-student-metabox.php`

```php
private $departments = array(
    ''           => '-- Chọn chuyên ngành --',
    'cntt'       => 'Công nghệ Thông tin (CNTT)',
    'kinh_te'    => 'Kinh tế',
    'marketing'  => 'Marketing',
    // ↓ Thêm chuyên ngành mới vào đây
    'du_lich'    => 'Du lịch & Nhà hàng Khách sạn',
    'y_te'       => 'Y tế Công cộng',
);
```

**File 2:** `includes/class-student-shortcode.php`

```php
private $departments = array(
    'cntt'       => 'Công nghệ Thông tin (CNTT)',
    'kinh_te'    => 'Kinh tế',
    'marketing'  => 'Marketing',
    // ↓ Thêm tương tự (không có dòng rỗng '')
    'du_lich'    => 'Du lịch & Nhà hàng Khách sạn',
    'y_te'       => 'Y tế Công cộng',
);
```

### Tùy chỉnh giao diện bảng

Chỉnh sửa file `assets/style.css`:

```css
/* Đổi màu header bảng */
.sm-student-table thead tr {
  background: linear-gradient(135deg, #1e3a5f 0%, #2271b1 100%);
}

/* Tăng cỡ chữ */
.sm-student-table {
  font-size: 1em;
}

/* Đổi màu hover */
.sm-student-table tbody tr:hover {
  background-color: #fff8e1 !important;
}
```

### Thêm cột vào bảng frontend

Mở `includes/class-student-shortcode.php`, tìm phần `<thead>` và `<tbody>` để thêm cột mới:

```php
// Trong <thead>, thêm sau cột Ngày sinh:
<th class="sm-col-email">Email</th>

// Trong <tbody>, lấy meta và hiển thị:
$email = get_post_meta( $post_id, '_sm_email', true );
<td class="sm-col-email"><?php echo esc_html( $email ? $email : '—' ); ?></td>
```

---

## 📸 Ảnh chụp màn hình

### 1. Menu "Sinh Viên" trong Admin Sidebar

> _Sau khi kích hoạt plugin, mục Sinh Viên xuất hiện trong menu với icon nhóm người_

```
📷 [Chèn ảnh chụp màn hình Admin sidebar tại đây]
```

### 2. Form thêm/sửa Sinh viên với Meta Box

> _Khung "Thông tin Sinh Viên" xuất hiện bên dưới editor với 3 trường nhập liệu_

```
📷 [Chèn ảnh chụp màn hình Edit post screen tại đây]
```

### 3. Danh sách Sinh viên trong Admin với cột tùy chỉnh

> _Các cột MSSV, Chuyên ngành, Ngày sinh hiển thị ngay trong bảng danh sách_

```
📷 [Chèn ảnh chụp màn hình Admin list view tại đây]
```

### 4. Bảng hiển thị Shortcode trên Frontend

> _Bảng responsive với zebra stripes, header màu xanh, hiển thị đúng 5 cột yêu cầu_

```
📷 [Chèn ảnh chụp màn hình Frontend table tại đây]
```

---

## 📝 Changelog

### v1.0.0 — _2025_

- Phát hành phiên bản đầu tiên
- Đăng ký Custom Post Type `sinhvien` với `title` và `editor`
- Custom Meta Boxes: MSSV (text), Lớp/Chuyên ngành (dropdown 7 mục), Ngày sinh (date)
- Cơ chế bảo mật đầy đủ: Nonce, Capability check, Autosave guard, Sanitize, Whitelist
- Shortcode `[danh_sach_sinh_vien]` với 4 tham số: `so_luong`, `sap_xep`, `thu_tu`, `lop`
- Cột tùy chỉnh trong Admin list view (MSSV, Chuyên ngành, Ngày sinh)
- Responsive CSS table với zebra stripes, hover highlight, tfoot
- Tối ưu WP_Query với `no_found_rows: true`

---

## 👨‍💻 Tác giả

**Student Developer**

| Thông tin | Chi tiết                                                                                     |
| :-------- | :------------------------------------------------------------------------------------------- |
| Email     | student@example.com                                                                          |
| GitHub    | [github.com/your-username/student-manager](https://github.com/your-username/student-manager) |
| Trường    | Đại học [Tên trường]                                                                         |
| MSSV      | [MSSV của bạn]                                                                               |
| Môn học   | Lập trình Web / WordPress Development                                                        |

---

## 📄 License

Plugin này được phân phối theo giấy phép **GNU General Public License v2.0 hoặc cao hơn**.

```
Copyright (C) 2025  Student Developer

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

Chi tiết: [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)

---

<div align="center">

**Được xây dựng với ❤️ theo chuẩn WordPress Coding Standards**

[⬆ Về đầu trang](#-student-manager--wordpress-plugin)

</div>
