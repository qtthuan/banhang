<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mini extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Chưa đăng nhập → chuyển về login
        if (!$this->loggedIn) {
            $this->session->set_userdata(
                'requested_page',
                $this->uri->uri_string()
            );

            $this->sma->md('login');
        }

        // Mini dùng chung POS Model
        $this->load->admin_model('pos_model');

        $this->load->helper('text');
        $this->load->library('form_validation');
        $this->load->library('user_agent');

        // Ngôn ngữ admin
        $this->lang->admin_load(
            'pos',
            $this->Settings->user_language
        );

        // Lấy POS settings giống POS cũ
        $this->pos_settings = $this->pos_model->getSetting();

        $this->pos_settings->pin_code =
            $this->pos_settings->pin_code
                ? md5($this->pos_settings->pin_code)
                : NULL;

        $this->data['pos_settings'] = $this->pos_settings;

        // Last activity
        $this->session->set_userdata(
            'last_activity',
            now()
        );
    }


    /**
     * Mini POS
     *
     * URL:
     * /admin/mini
     */
    public function index()
    {
        $this->sma->checkPermissions('index', TRUE, 'sales');

        /*
        * ================================
        * MINI POS - PRODUCT DATA
        * ================================
        */

        // Sản phẩm Mini hiện tại
        $this->data['products'] = $this->pos_model->getAllMiniProducts();

        // ID category gốc của Mini
        $this->data['mini_category_id'] = 38;

        /*
        * Lấy danh sách category hiện có.
        * Bước này chưa tự ý lọc category vì
        * database hiện tại đang xác định Mini
        * bằng category_id = 38.
        */
        $mini_category_id = (int)$this->config->item('mini_category_id'); // Mã nhóm Nguyên Liệu
        $this->data['categories'] = $this->site->getSubCategories($mini_category_id, 'id');
        //$this->sma->print_arrays($mini_category_id, $this->data['categories']);

        // POS settings để sau này dùng tiếp
        $this->data['pos_settings'] = $this->pos_settings;

        // User hiện tại
        $this->data['user'] = $this->site->getUser();

        $this->data['order_comment_list'] = $this->pos_model->getOrderCommentList();



        /*
        * Debug tạm thời
        *
        * Khi test xong nhớ comment lại.
        */
        // echo '<pre>';
        // print_r($this->data['products']);
        // exit;

        $this->load->view(
            $this->theme . 'pos/mini',
            $this->data
        );
    }
}