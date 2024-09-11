<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Module: General Language File for common lang keys
 * Language: English
 *
 * Last edited:
 * 30th April 2015
 *
 * Package:
 * Stock Manage Advance v3.0
 *
 * You can translate this file to your language.
 * For instruction on new language setup, please visit the documentations.
 * You also can share your language files by emailing to saleem@tecdiary.com
 * Thank you
 */

/* --------------------- CUSTOM FIELDS ------------------------ */
/*
* Below are custome field labels
* Please only change the part after = and make sure you change the the words in between "";
* $lang['bcf1']                         = "Biller Custom Field 1";
* Don't change this                     = "You can change this part";
* For support email contact@tecdiary.com Thank you!
*/

$lang['bcf1']                           = "Số điện thoại bổ sung";
$lang['bcf2']                           = "Lời cảm ơn";
$lang['bcf3']                           = "Slogan";
$lang['bcf4']                           = "Biller Custom Field 4";
$lang['bcf5']                           = "Biller Custom Field 5";
$lang['bcf6']                           = "Biller Custom Field 6";
$lang['pcf1']                           = "Product Custom Field 1";
$lang['pcf2']                           = "Product Custom Field 2";
$lang['pcf3']                           = "Product Custom Field 3";
$lang['pcf4']                           = "Product Custom Field 4";
$lang['pcf5']                           = "Product Custom Field 5";
$lang['pcf6']                           = "Product Custom Field 6";
$lang['ccf1']                           = "Ghi chú";
$lang['ccf2']                           = "Ghi chú 2";
$lang['history']                        = "Lưu vết thông tin";
$lang['ccf3']                           = "Customer Custom Field 3";
$lang['ccf4']                           = "Customer Custom Field 4";
$lang['ccf5']                           = "Customer Custom Field 5";
$lang['ccf6']                           = "Customer Custom Field 6";
$lang['scf1']                           = "Supplier Custom Field 1";
$lang['scf2']                           = "Supplier Custom Field 2";
$lang['scf3']                           = "Supplier Custom Field 3";
$lang['scf4']                           = "Supplier Custom Field 4";
$lang['scf5']                           = "Supplier Custom Field 5";
$lang['scf6']                           = "Supplier Custom Field 6";

/* ----------------- DATATABLES LANGUAGE ---------------------- */
/*
* Below are datatables language entries
* Please only change the part after = and make sure you change the the words in between "";
* 'sEmptyTable'                     => "No data available in table",
* Don't change this                 => "You can change this part but not the word between and ending with _ like _START_;
* For support email support@tecdiary.com Thank you!
*/

$lang['datatables_lang']        = array(
    'sEmptyTable'                   => "Không có dữ liệu trong bảng",
    'sInfo'                         => "Showing _START_ to _END_ of _TOTAL_ entries",
    'sInfoEmpty'                    => "Showing 0 to 0 of 0 entries",
    'sInfoFiltered'                 => "(filtered from _MAX_ total entries)",
    'sInfoPostFix'                  => "",
    'sInfoThousands'                => ",",
    'sLengthMenu'                   => "Hiện _MENU_ ",
    'sLoadingRecords'               => "Đang tải...",
    'sProcessing'                   => "Đang xử lý...",
    'sSearch'                       => "Tìm kiếm",
    'sZeroRecords'                  => "Không tìm thấy dữ liệu",
    'oAria'                                     => array(
        'sSortAscending'                => ": activate to sort column ascending",
        'sSortDescending'               => ": activate to sort column descending"
    ),
    'oPaginate'                                 => array(
        'sFirst'                        => "<< First",
        'sLast'                         => "Last >>",
        'sNext'                         => "Next >",
        'sPrevious'                     => "< Previous",
    )
);

/* ----------------- Select2 LANGUAGE ---------------------- */
/*
* Below are select2 lib language entries
* Please only change the part after = and make sure you change the the words in between "";
* 's2_errorLoading'                 => "The results could not be loaded",
* Don't change this                 => "You can change this part but not the word between {} like {t};
* For support email support@tecdiary.com Thank you!
*/

$lang['select2_lang']               = array(
    'formatMatches_s'               => "Tìm thấy 1 kết quả, bấm Enter để chọn.",
    'formatMatches_p'               => "Kết quả tìm thấy, sử dụng phím mũi tên lên xuống để di chuyển.",
    'formatNoMatches'               => "Không tìm thấy kết quả nào",
    'formatInputTooShort'           => "Nhập {n} hoặc nhiều ký tự để tìm",
    'formatInputTooLong_s'          => "Hãy xóa {n} ký tự",
    'formatInputTooLong_p'          => "Vui lòng xóa {n} ký tự",
    'formatSelectionTooBig_s'       => "Bạn có thể chọn {n} mẩu tin",
    'formatSelectionTooBig_p'       => "Bạn có thể chọn {n} mẩu tin",
    'formatLoadMore'                => "Xem thêm...",
    'formatAjaxError'               => "Lỗi Ajax request",
    'formatSearching'               => "Đang tìm..."
);


/* ----------------- SMA GENERAL LANGUAGE KEYS -------------------- */

$lang['home']                               = "Trang chủ";
$lang['dashboard']                          = "Bảng điều khiển";
$lang['username']                           = "Tên đăng nhập hoặc email";
$lang['password']                           = "Mật khẩu";
$lang['first_name']                         = "Họ";
$lang['last_name']                          = "Tên";
$lang['confirm_password']                   = "Xác nhận mật khẩu";
$lang['email']                              = "Email";
$lang['phone']                              = "Điện thoại";
$lang['company']                            = "Công ty";
$lang['customer_no']                        = "Mã thẻ KH";
$lang['product_code']                       = "Mã sản phẩm";
$lang['product_name']                       = "Tên sản phẩm";
$lang['product_name_en']                    = "Tên sản phẩm (Tiếng Anh)";
$lang['cname']                              = "Tên khách hàng";
$lang['barcode_symbology']                  = "Ký tự mã vạch";
$lang['product_unit']                       = "Đơn vị";
$lang['product_price']                      = "Giá bán";
$lang['contact_person']                     = "Người liên hệ";
$lang['email_address']                      = "Địa chỉ Email";
$lang['address']                            = "Địa chỉ";
$lang['fb_link']                            = "Địa chỉ facebook";
$lang['city']                               = "Tỉnh/TP";
$lang['today']                              = "Hôm nay";
$lang['welcome']                            = "Chào mừng";
$lang['profile']                            = "Hồ sơ";
$lang['change_password']                    = "Đổi mật khẩu";
$lang['logout']                             = "Thoát";
$lang['notifications']                      = "Thông báo";
$lang['calendar']                           = "Lịch";
$lang['messages']                           = "Tin nhắn";
$lang['styles']                             = "Giao diện";
$lang['language']                           = "Ngôn ngữ";
$lang['alerts']                             = "Cảnh báo";
$lang['list_products']                      = "Danh sách sản phẩm";
$lang['add_product']                        = "Thêm sản phẩm";
$lang['print_barcodes']                     = "In Mã vạch";
$lang['print_labels']                       = "In Tem Dán";
$lang['print_labels_menu']                   = "In Tem Dán Mini";
$lang['import_products']                    = "Import sản phẩm";
$lang['update_price']                       = "Cập nhật giá";
$lang['damage_products']                    = "Sản phẩm hư hỏng";
$lang['sales']                              = "Bán hàng";
$lang['list_sales']                         = "Hóa đơn Online";
$lang['add_sale']                           = "Thêm hóa đơn mới";
$lang['deliveries']                         = "Giao hàng";
$lang['gift_cards']                         = "Phiếu mua hàng";
$lang['quotes']                             = "Báo giá";
$lang['list_quotes']                        = "Danh sách báo giá";
$lang['add_quote']                          = "Thêm báo giá";
$lang['purchases']                          = "Nhập hàng";
$lang['list_purchases']                     = "Danh sách nhập hàng";
$lang['add_purchase']                       = "Thêm nhập hàng";
$lang['add_purchase_by_csv']                = "Thêm bằng CSV";
$lang['transfers']                          = "Chuyển kho";
$lang['list_transfers']                     = "DS chuyển kho";
$lang['add_transfer']                       = "Thêm chuyển kho";
$lang['add_transfer_by_csv']                = "Thêm bằng CSV";
$lang['people']                             = "Khách hàng - Users";
$lang['list_users']                         = "Danh sách người dùng";
$lang['new_user']                           = "Thêm người dùng";
$lang['list_billers']                       = "Danh sách NV bán hàng";
$lang['add_biller']                         = "Thêm NV bán hàng";
$lang['list_customers']                     = "Danh sách khách hàng";
$lang['add_customer']                       = "Thêm khách hàng";
$lang['list_suppliers']                     = "Danh sách nhà cung cấp";
$lang['add_supplier']                       = "Thêm nhà cung cấp";
$lang['settings']                           = "Cài đặt";
$lang['system_settings']                    = "Cài đặt hệ thống";
$lang['change_logo']                        = "Đổi Logo";
$lang['currencies']                         = "Tiền tệ";
$lang['attributes']                         = "Thuộc tính sản phẩm";
$lang['customer_groups']                    = "Nhóm khách hàng";
$lang['size_group']                         = "Nhóm size";
$lang['promotion_list']                     = "Chương trình khuyến mãi";
$lang['categories']                         = "Nhóm hàng";
$lang['subcategories']                      = "Danh mục con";
$lang['tax_rates']                          = "Thuế suất";
$lang['warehouses']                         = "Kho hàng";
$lang['email_templates']                    = "Mẫu Email";
$lang['group_permissions']                  = "Phân quyền nhóm";
$lang['backup_database']                    = "Sao lưu dữ liệu";
$lang['reports']                            = "Báo cáo";
$lang['overview_chart']                     = "Tổng quan chung";
$lang['warehouse_stock']                    = "Biểu đồ tồn kho";
$lang['product_quantity_alerts']            = "Cảnh báo số lượng SP";
$lang['product_expiry_alerts']              = "Cảnh báo SP hết hạn";
$lang['products_report']                    = "Báo cáo sản phẩm";
$lang['daily_sales']                        = "Doanh số theo ngày";
$lang['monthly_sales']                      = "Doanh số theo tháng";
$lang['sales_report']                       = "Báo cáo doanh số";
$lang['payments_report']                    = "Báo cáo thanh toán";
$lang['profit_and_loss']                    = "Lợi nhuận và chi phí";
$lang['purchases_report']                   = "Báo cáo nhập hàng";
$lang['customers_report']                   = "Thống kê KH";
$lang['suppliers_report']                   = "Thống kê nhà cung cấp";
$lang['staff_report']                       = "Thống kê NV";
$lang['your_ip']                            = "Địa chỉ IP của bạn";
$lang['last_login_at']                      = "Đăng nhập gần đây";
$lang['notification_post_at']               = "Thông báo đăng tại";
$lang['quick_links']                        = "Liên kết nhanh";
$lang['date']                               = "Ngày";
$lang['reference_no']                       = "Mã hóa đơn";
$lang['products']                           = "Sản phẩm";
$lang['customers']                          = "Khách hàng";
$lang['suppliers']                          = "Nhà CC";
$lang['users']                              = "Người dùng";
$lang['latest_five']                        = "5 dữ liệu mới nhất";
$lang['total']                              = "Tổng";
$lang['payment_status']                     = "Trạng thái";
$lang['paid']                               = "Đã thanh toán";
$lang['customer']                           = "Khách hàng";
$lang['customer_short']                     = "KH";
$lang['status']                             = "Trạng thái";
$lang['amount']                             = "Tổng tiền";
$lang['supplier']                           = "Nhà CC";
$lang['from']                               = "Từ";
$lang['to']                                 = "Tới";
$lang['name']                               = "Tên";
$lang['create_user']                        = "Thêm người dùng";
$lang['gender']                             = "Giới tính";
$lang['biller']                             = "Người bán hàng";
$lang['select']                             = "Chọn";
$lang['warehouse']                          = "Kho hàng";
$lang['active']                             = "Kích hoạt";
$lang['inactive']                           = "Hủy kích hoạt";
$lang['all']                                = "Tất cả";
$lang['list_results']                       = "Vui lòng sử dụng bảng dưới đây để điều hướng hoặc lọc các kết quả. Bạn có thể tải về bảng như excel và pdf.";
$lang['actions']                            = "Tác vụ";
$lang['pos']                                = "POS";
$lang['access_denied']                      = "Access denied. Vui lòng liên hệ quản trị!";
$lang['add']                                = "Thêm";
$lang['edit']                               = "Sửa";
$lang['delete']                             = "Xóa";
$lang['view']                               = "Xem";
$lang['update']                             = "Cập nhật";
$lang['save']                               = "Save";
$lang['login']                              = "Đăng nhập";
$lang['submit']                             = "Hoàn thành";
$lang['submit1']                            = "Thanh Toán";
$lang['no']                                 = "Không";
$lang['stt']                                = "STT";
$lang['yes']                                = "Có";
$lang['disable']                            = "Tắt";
$lang['enable']                             = "Mở";
$lang['enter_info']                         = "Vui lòng điền vào các thông tin dưới đây. Các mục đánh dấu * là bắt buộc nhập.";
$lang['update_info']                        = "Vui lòng cập nhật thông tin dưới đây. Các mục đánh dấu * là các mục bắt buộc phải nhập vào.";
$lang['no_suggestions']                     = "Không tìm được dữ liệu, Vui lòng kiểm tra lại.";
$lang['i_m_sure']                           = 'Vâng tôi chắc chắn';
$lang['r_u_sure']                           = 'Bạn có chắc không?';
$lang['export_to_excel']                    = "Xuất ra file Excel";
$lang['export_to_pdf']                      = "Xuất ra file PDF";
$lang['image']                              = "Ảnh";
$lang['sale']                               = "Bán";
$lang['quote']                              = "Bảng báo giá";
$lang['purchase']                           = "Mua";
$lang['transfer']                           = "Chuyển kho";
$lang['payment']                            = "Thanh toán";
$lang['payments']                           = "Các khoản thanh toán";
$lang['orders']                             = "Đơn đặt hàng";
$lang['pdf']                                = "PDF";
$lang['vat_no']                             = "Số VAT";
$lang['country']                            = "Quốc gia";
$lang['add_user']                           = "Thêm người dùng";
$lang['type']                               = "Loại";
$lang['person']                             = "Người dùng";
$lang['state']                              = "Huyện";
$lang['postal_code']                        = "Mã bưu chính";
$lang['id']                                 = "ID";
$lang['close']                              = "Đóng";
$lang['male']                               = "Nam";
$lang['female']                             = "Nữ";
$lang['notify_user']                        = "Thông báo tới thành viên";
$lang['notify_user_by_email']               = "Thông báo tới thành viên bằng email";
$lang['billers']                            = "Người bán";
$lang['all_warehouses']                     = "Tất cả kho hàng";
$lang['category']                           = "Nhóm hàng";
$lang['product_cost']                       = "Giá nhập";
$lang['quantity']                           = "SL";
$lang['original_quantity']                  = "Tồn đầu";
$lang['lbl_original_quantity']              = "Xem tồn kho ban đầu (07/04/2021)";
$lang['loading_data_from_server']           = "Đang tải dữ liệu từ máy chủ";
$lang['excel']                              = "Excel";
$lang['print']                              = "In";
$lang['ajax_error']                         = "Lỗi Ajax xảy ra, hãy thử lại.";
$lang['product_tax']                        = "Thuế sản phẩm";
$lang['order_tax']                          = "Thuế mua hàng";
$lang['upload_file']                        = "Upload File";
$lang['download_sample_file']               = "Tải file mẫu";
$lang['csv1']                               = "Vui lòng không hiệu chỉnh dòng đầu tiên trong file csv và không thay đổi thứ tự cột.";
$lang['csv2']                               = "Thứ tự cột là";
$lang['csv3']                               = ".";
$lang['import']                             = "Import";
$lang['note']                               = "Ghi chú";
$lang['grand_total']                        = "Tổng tiền";
$lang['download_pdf']                       = "Tải về dạng PDF";
$lang['no_zero_required']                   = "The %s field is required";
$lang['no_product_found']                   = "Không có sản phẩm";
$lang['pending']                            = "Đang xử lý";
$lang['sent']                               = "Gửi";
$lang['completed']                          = "Hoàn thành";
$lang['shipping']                           = "Phí ship";
$lang['shop']                               = "Ghé lấy";
$lang['shipper']                            = "Shipper";
$lang['ghtk']                               = "GHTK";
$lang['vnpost']                             = "VNPost";
$lang['add_product_to_order']               = "Hãy thêm sản phẩm vào danh sách đặt hàng";
$lang['order_items']                        = "Order Items";
$lang['net_unit_cost']                      = "Đơn vị giá nhập";
$lang['net_unit_price']                     = "Giá";
$lang['expiry_date']                        = "Ngày hết hạn";
$lang['subtotal']                           = "Thành tiền";
$lang['reset']                              = "Làm lại";
$lang['items']                              = "Số lượng";
$lang['au_pr_name_tip']                     = "Nhập mã sản phẩm/tên sản phẩm hoặc quét mã vạch";
$lang['no_match_found']                     = "Không tìm thấy kết quả phù hợp.";
$lang['csv_file']                           = "CSV File";
$lang['document']                           = "Tài liệu đính kèm";
$lang['product']                            = "Sản phẩm";
$lang['user']                               = "Người dùng";
$lang['created_by']                         = "Tạo bởi";
$lang['loading_data']                       = "Đang tải dữ liệu";
$lang['tel']                                = "Điện thoại";
$lang['ref']                                = "Tham chiếu";
$lang['description']                        = "Mô tả";
$lang['code']                               = "Mã";
$lang['tax']                                = "Tax";
$lang['unit_price']                         = "Đơn giá";
$lang['discount']                           = "Chiết khấu";
$lang['order_discount']                     = "Chiết khấu";
$lang['total_amount']                       = "Tổng cộng";
$lang['download_excel']                     = "Tải về dạng Excel";
$lang['subject']                            = "Tiêu đề";
$lang['cc']                                 = "CC";
$lang['bcc']                                = "BCC";
$lang['message']                            = "Tin nhắn";
$lang['show_bcc']                           = "Hiện/Ẩn BCC";
$lang['price']                              = "Giá";
$lang['big_size_price']                     = "Giá ly lớn";
$lang['add_product_manually']               = "Thêm sản phẩm nhanh";
$lang['currency']                           = "Tiền tệ";
$lang['product_discount']                   = "Giảm giá";
$lang['email_sent']                         = "Gửi email thành công";
$lang['add_event']                          = "Thêm sự kiện";
$lang['add_modify_event']                   = "Thêm/Sửa đổi các tổ chức sự kiện";
$lang['adding']                             = "Đang thêm...";
$lang['delete']                             = "Xóa";
$lang['deleting']                           = "Đang xóa...";
$lang['calendar_line']                      = "Vui lòng click ngày để thêm/sửa đổi sự kiện.";
$lang['discount_label']                     = "Chiết khấu (5/5%)";
$lang['product_expiry']                     = "Sản phẩm hết hạn";
$lang['unit']                               = "Đơn vị";
$lang['cost']                               = "Chi phí";
$lang['tax_method']                         = "Loại thuế";
$lang['inclusive']                          = "Bao gồm";
$lang['exclusive']                          = "Không bao gồm";
$lang['expiry']                             = "Hạn sử dụng";
$lang['customer_group']                     = "Nhóm khách hàng";
$lang['customer_last_bill_date']            = "Đơn hàng gần nhất";
$lang['customer_created_date']              = "Ngày tham gia";
$lang['customer_no_last_bill_date']         = "Chưa có đơn hàng nào!";
$lang['is_required']                        = "là bắt buộc";
$lang['form_action']                        = "Thao tác";
$lang['return_sales']                       = "Hóa đơn khách trả";
$lang['list_return_sales']                  = "Danh sách hóa đơn khách trả";
$lang['no_data_available']                  = "Không có dữ liệu";
$lang['disabled_in_demo']                   = "Chúng tôi rất xin lỗi nhưng tính năng này bị vô hiệu hóa trong demo.";
$lang['payment_reference_no']               = "Mã thanh toán";
$lang['gift_card_no']                       = "Mã phiếu mua hàng";
$lang['paying_by']                          = "Thanh toán bằng";
$lang['cash']                               = "Tiền mặt";
$lang['gift_card']                          = "Phiếu mua hàng";
$lang['CC']                                 = "Chuyển khoản";
$lang['cheque']                             = "Séc";
$lang['cc_no']                              = "Chuyển khoản";
$lang['cc_holder']                          = "Tên chủ thẻ";
$lang['card_type']                          = "Loại thẻ";
$lang['Visa']                               = "Visa";
$lang['MasterCard']                         = "MasterCard";
$lang['Amex']                               = "Amex";
$lang['Discover']                           = "Discover";
$lang['month']                              = "Tháng";
$lang['year']                               = "Năm";
$lang['cvv2']                               = "CVV2";
$lang['cheque_no']                          = "Số séc";
$lang['Visa']                               = "Visa";
$lang['MasterCard']                         = "MasterCard";
$lang['Amex']                               = "Amex";
$lang['Discover']                           = "Discover";
$lang['send_email']                         = "Gửi Email";
$lang['order_by']                           = "Đặt hàng bởi";
$lang['updated_by']                         = "Cập nhật bởi";
$lang['update_at']                          = "Cập nhật lúc";
$lang['error_404']                          = "404 Page Not Found ";
$lang['default_customer_group']             = "Nhóm khách hàng mặc định";
$lang['pos_settings']                       = "Cài đặt bán hàng";
$lang['pos_sales']                          = "Hóa đơn bán hàng POS";
$lang['suspended_sales']                    = "Hóa đơn POS tạm";
$lang['seller']                             = "Người bán";
$lang['ip:']                                = "IP:";
$lang['sp_tax']                             = "Thuế bán hàng";
$lang['pp_tax']                             = "Thuế mua sản phẩm";
$lang['overview_chart_heading']             = "Biểu đồ tổng quan kho hàng tính bao gồm doanh số bán hàng hàng tháng đã có thuế, thuế bán hàng (cột), nhập hàng (dòng) và giá trị hiện tại của kho hàng theo giá nhập và giá bán (hình tròn). Bạn có thể lưu các biểu đồ dạng jpg, png và pdf.";
$lang['stock_value']                        = "Giá trị tồn kho";
$lang['stock_value_by_price']               = "Giá trị tính theo giá bán";
$lang['stock_value_by_cost']                = "Giá trị tính theo giá nhập";
$lang['sold']                               = "Đã bán";
$lang['purchased']                          = "Nhập hàng";
$lang['chart_lable_toggle']                 = "Bạn có thể thay đổi các biểu đồ bằng cách nhấp chuột và các ghi chú. Nhấp chuột vào một ghi chú bất kỳ để hiện/ẩn nó trên biểu đố.";
$lang['register_report']                    = "Báo cáo đăng ký";
$lang['sEmptyTable']                        = "Không có dữ liệu trong bảng";
$lang['upcoming_events']                    = "Sự kiện sắp tới";
$lang['clear_ls']                           = "Xóa dữ liệu đã lưu";
$lang['clear']                              = "Xóa";
$lang['edit_order_discount']                = "Sửa chiết khấu hóa đơn";
$lang['product_variant']                    = "Thuộc tính";
$lang['product_variants']                   = "Các thuộc tính sản phẩm";
$lang['product_barcode']                    = "Mã vạch";
$lang['prduct_not_found']                   = "Không có sản phẩm";
$lang['list_open_registers']                = "Lịch sử mở két tiền";
$lang['delivery']                           = "Giao hàng";
$lang['serial_no']                          = "Số serial";
$lang['logo']                               = "Logo";
$lang['attachment']                         = "Đính kèm";
$lang['balance']                            = "Dư nợ";
$lang['nothing_found']                      = "Không tìm thấy bản ghi phù hợp";
$lang['db_restored']                        = "Khôi phục dữ liệu thành công.";
$lang['backups']                            = "Backups";
$lang['best_seller']                        = "Bán chạy nhất";
$lang['chart']                              = "Biểu đồ";
$lang['received']                           = "Đã nhận";
$lang['returned']                           = "Trả hàng";
$lang['award_points_sale_notes']            = '<strong><u>KHÔNG</u></strong> <strong>TÍCH ĐIỂM ĐỐI VỚI HÀNG GIẢM GIÁ</strong>';
$lang['award_points_title']                 = 'THÔNG TIN ĐIỂM';
$lang['award_points']                       = 'Điểm tích lũy';
$lang['award_points_short']                 = 'Điểm';
$lang['award_points1']                      = 'Điểm tích lũy';
$lang['discount_percent']                   = 'Chiết khấu(APP)';
$lang['points_per_year']                    = 'Doanh số trong năm';
$lang['use_points']                         = "Sử dụng điểm";
$lang['pay_by_points']                      = "TT điểm";
$lang['change_points']                      = "Đổi điểm";
$lang['expenses']                           = "Chi phí khác";
$lang['add_expense']                        = "Chi phí khác";
$lang['other']                              = "Khác";
$lang['vnpay']                              = "VN PAY";
$lang['none']                               = "Không có";
$lang['calculator']                         = "Máy tính";
$lang['updates']                            = "Cập nhật";
$lang['update_available']                   = "Bản cập nhật mới có sẵn, cập nhật ngay bây giờ.";
$lang['please_select_customer_warehouse']   = "Vui lòng chọn khách hàng/kho hàng";
$lang['variants']                           = "Thuộc tính";
$lang['add_sale_by_csv']                    = "Thêm bằng CSV";
$lang['categories_report']                  = "Báo cáo danh mục";
$lang['adjust_quantity']                    = "Điều chỉnh số lượng";
$lang['quantity_adjustments']               = "Điều chỉnh số lượng";
$lang['partial']                            = "Từng phần";
$lang['unexpected_value']                   = "Nhập giá trị chưa đúng!";
$lang['select_above']                       = "Vui lòng nhập thông tin phía trên!";
$lang['no_user_selected']                   = "Không có người dùng được chọn, xin vui lòng chọn một người dùng";
$lang['sale_details']                       = "Chi tiết hóa đơn";
$lang['due']                                = "Nợ";
$lang['ordered']                            = "Ordered";
$lang['profit']                             = "Lợi nhuận";
$lang['unit_and_net_tip']                   = "Calculated on unit (with tax) and net (without tax) i.e <strong>unit(net)</strong> for all sales";
$lang['expiry_alerts']                      = "Cảnh báo hạn sử dụng";
$lang['quantity_alerts']                    = "Số lượng cảnh báo";
$lang['products_sale']                      = "Tổng số bán hàng";
$lang['products_cost']                      = "Chi phí sản phẩm";
$lang['products_cost_bn']                   = "Chi phí sản phẩm nhập tay (BN)";
$lang['day_profit']                         = "Lợi nhuận/Chi phí theo ngày";
$lang['get_day_profit']                     = "Bạn có thể click vào ngày để xem lợi nhuận/chi phí của ngày hôm nay.";
$lang['please_select_these_before_adding_product'] = "Vui lòng chọn các mục này trước khi thêm sản phẩm bất kỳ";
$lang['combine_to_pdf']                     = "Kết hợp file pdf";
$lang['print_barcode_label']                = "In mã vạch/nhãn";
$lang['list_gift_cards']                    = "Phiếu mua hàng";
$lang['today_profit']                       = "Lợi nhuận";
$lang['adjustments']                        = "Điều chỉnh";
$lang['download_xls']                       = "Tải về (xls)";
$lang['browse']                             = "Browse ...";
$lang['transferring']                       = "Đang chuyển";
$lang['supplier_part_no']                   = "Supplier Part Number";
$lang['deposit']                            = "Tiền gửi";
$lang['ppp']                                = "Paypal Pro";
$lang['stripe']                             = "Stripe";
$lang['amount_greater_than_deposit']        = "Số tiền lớn hơn tiền gửi của khách hàng, vui lòng thử lại với số tiền thấp hơn tiền gửi của khách hàng.";
$lang['stamp_sign']                         = "Ký tên &amp; Đóng dấu";
$lang['product_option']                     = "Product Variant";
$lang['Cheque']                             = "Cheque";
$lang['sale_reference']                     = "Tham chiếu hóa đơn";
$lang['surcharges']                         = "Phụ phí";
$lang['please_wait']                        = "Vui lòng chờ...";
$lang['list_expenses']                      = "Xem chi phí";
$lang['deposit']                            = "Tiền gửi";
$lang['deposit_amount']                     = "Tiền gửi";
$lang['return_purchases']                   = "Trả hàng NCC";
$lang['list_return_purchases']              = "Danh sách hàng trả NCC";
$lang['expense_categories']                 = "Phân loại phí";
$lang['authorize']                          = "Authorize.net";
$lang['expenses_report']                    = "Báo cáo chi phí khác";
$lang['expense_categories']                 = "Phân loại phí";
$lang['edit_event']                         = "Thay đổi sự kiện";
$lang['title']                              = "Tiêu đề";
$lang['event_error']                        = "Vui lòng nhập tiêu đề hoặc ngày bắt đầu.";
$lang['start']                              = "Bắt đầu";
$lang['end']                                = "Kết thúc";
$lang['event_added']                        = "Sự kiện được thêm thành công";
$lang['event_updated']                      = "Sự kiện được cập nhật thành công";
$lang['event_deleted']                      = "Sự kiện được xóa thành công";
$lang['event_color']                        = "Màu sự kiện";
$lang['toggle_alignment']                   = "Toggle Alignment";
$lang['images_location_tip']                = "Hình ảnh được upload vào thư mục <strong>uploads</strong>.";
$lang['this_sale']                          = "Điểm hóa đơn";
$lang['return_ref']                         = "Mã trả hàng";
$lang['return_total']                       = "Tổng khách trả";
$lang['return_amount']                      = "Trả hàng";
$lang['return_amount_note']                 = "Nhập mã hóa đơn trả hàng";
$lang['daily_purchases']                    = "Nhập hàng trong ngày";
$lang['monthly_purchases']                  = "Nhập hàng trong tháng";
$lang['reference']                          = "Tham chiếu";
$lang['no_subcategory']                     = "Không có danh mục con";
$lang['returned_items']                     = "Sản phẩm khách trả";
$lang['return_payments']                    = "Thanh toán trả hàng";
$lang['units']                              = "Đơn vị tính";
$lang['price_group']                        = "Bảng giá";
$lang['price_groups']                       = "Bảng giá";
$lang['no_record_selected']                 = "Chưa chọn mẩu tin.";
$lang['brand']                              = "Nơi sản xuất";
$lang['brands']                             = "Nơi sản xuất";
$lang['file_x_exist']                       = "Hệ thống không tìm được file, có thể bị xóa hoặc di chuyển.";
$lang['status_updated']                     = "Trạng thái được cập nhật thành công";
$lang['x_col_required']                     = "%d cột đầu tiền bắt buộc nhập, còn lại không bắt buộc.";
$lang['brands_report']                      = "Báo cáo nơi sản xuất";
$lang['add_adjustment']                     = "Thêm phiếu điều chỉnh kho";
$lang['best_sellers']                       = "Sản phẩm bán chạy";
$lang['adjustments_report']                 = "Báo cáo điều chỉnh kho";
$lang['stock_counts']                       = "Kiểm kho";
$lang['count_stock']                        = "Tạo phiếu kiểm kho mới";
$lang['download']                           = "Tải về";
$lang['list_printers']                      = "Danh sách máy in";
$lang['add_printer']                        = "Thêm máy in";
$lang['shop']                               = "Shop";
$lang['cart']                               = "Cart";
$lang['api_keys']                           = "API Keys";
$lang['slug']                               = "Slug";
$lang['symbol']                             = "Symbol";
$lang['packaging']                          = "Danh sách đóng gói";
$lang['rack']                               = "Rack";
$lang['staff_sales']                        = "Hóa đơn nhân viên";
$lang['all_sales']                          = "Tất cả hóa đơn";
$lang['call_back_heading']                  = "If you want to use social auth, your callback url should be as below";
$lang['replace_xxxxxx_with_provider']       = "Please replace XXXXXX with provider i.e, Google, Facebook, Twitter etc";
$lang['documentation_at']                   = "More info at";
$lang['enable_config_file']                 = "You can enable/disable providers in the following config file";
$lang['sales_x_delivered']                  = "Hóa đơn chưa giao được";
$lang['order_item']                         = "Sản phẩm";

$lang['please_select_these_before_adding_product'] = "Vui lòng chọn những mục này trước khi thêm sản phẩm";

$lang['front_end']                          = "Website";
$lang['shop_settings']                      = "Cấu hình website";
$lang['slider_settings']                    = "Cấu hình ảnh banner";
$lang['list_pages']                         = "Danh sách trang tĩnh";
$lang['add_page']                           = "Tạo trang tĩnh";
$lang['sms_settings']                       = "Cấu hình SMS";
$lang['send_sms']                           = "Gửi tin nhắn SMS";
$lang['sms_log']                            = "SMS Log";
$lang['header_pos_txt']                     = "Bán hàng";
$lang['header_mini_txt']                    = "Mini";
$lang['error_404_message']                  = "<h4>404 Not Found!</h4><p>Không tìm thấy trang</p>";
$lang['product_description_list'] = "Mô tả sản phẩm";