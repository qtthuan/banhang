<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/pos_model', 'pos_model');
        $this->load->model('admin/companies_model', 'companies_model');
        $this->load->model('Group_model', 'group_model');
    }

    public function index() {
        

        $this->data['products'] = $this->pos_model->getAllMiniProducts();
        $this->load->view($this->mini_theme . 'order', $this->data);
    }

    function findCustomer($term = NULL, $limit = NULL)
    {
        // $this->sma->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->companies_model->findCustomerSuggestions($term);
        $this->sma->send_json($rows);
    }

    // public function findCustomer($term = NULL)
    // {
    //     $term = $this->input->get('term', TRUE);

    //     log_message('error', "TERM INPUT: $term");

    //     if (!$term || strlen($term) < 1) {
    //         $this->sma->send_json(['results' => []]);
    //     }

    //     $rows['results'] = $this->companies_model->findCustomerSuggestions($term);

    //     $this->sma->send_json($rows);
    // }

    public function create_group()
    {
        //die('OK CREATE'); // test trước
        // POST expected (AJAX)
        $name = $this->input->post('customer_name', TRUE);
        $phone = $this->input->post('customer_phone', TRUE);
        $address = $this->input->post('customer_address', TRUE);
        $note = $this->input->post('note', TRUE);
        $group = $this->group_model->create_group([
        'customer_name' => $name,
        'customer_phone' => $phone,
        'customer_address' => $address,
        'note' => $note
        ]);
        if ($group) {
        $this->sma->send_json(['success'=>1, 'code'=>$group->code, 'group_order_id'=>$group->id, 'link'=>site_url('order/'.$group->code)]);
        } else {
        $this->sma->send_json(['success'=>0, 'error'=>'Không tạo được mã nhóm']);
        }
    }

    // public function group_add_item()
    // {
    //     // POST JSON: group_code + item fields
    //     $code = $this->input->post('group_code', TRUE);
    //     $item = [
    //     'product_id' => $this->input->post('product_id', TRUE),
    //     'product_name' => $this->input->post('product_name', TRUE),
    //     'product_option' => $this->input->post('product_option', TRUE),
    //     'product_option_name' => $this->input->post('product_option_name', TRUE),
    //     'quantity' => $this->input->post('quantity', TRUE),
    //     'price' => $this->input->post('price', TRUE),
    //     'customer_name' => $this->input->post('customer_name', TRUE),
    //     'customer_phone' => $this->input->post('customer_phone', TRUE)
    //     ];
    //     $res = $this->group_model->add_item($code, $item);
    //     if ($res) $this->sma->send_json(['success'=>1, 'id'=>$res]);
    //     else $this->sma->send_json(['success'=>0]);
    // }
    public function group_add_item()
    {

        $data = [
            'group_order_id'    => (int)$this->input->post('group_order_id'),
            'product_id'    => (int)$this->input->post('product_id'),
            'product_name'  => $this->input->post('product_name', TRUE),
            'option_id'     => $this->input->post('option_id'),
            'quantity'      => (int)$this->input->post('quantity'),
            'price'         => (float)$this->input->post('price'),
            'comment'       => $this->input->post('comment', TRUE),
            'comment_name'  => $this->input->post('comment_name', TRUE),
            'meta'          => $this->input->post('meta', TRUE),
        ];

        $item = $this->group_model->add_item($data);

        if ($item) {
            $this->sma->send_json(['success' => 1]);
        } else {
            $this->sma->send_json(['success' => 0, 'error' => 'Add item failed']);
        }
    }



    public function group_items($code = NULL, $since_id = 0)
    {
        // GET: return items JSON
        $code = $code ?: $this->input->get('code', TRUE);
        $since_id = (int)($this->input->get('since_id', TRUE) ?: 0);
        $items = $this->group_model->get_items($code, $since_id);
        $this->sma->send_json(['success'=>1, 'items'=>$items]);
    }

    // route: order/{code} -> open order page in group mode
    public function group($code = null)
    {
        if (!$code) show_404();

        // Load group info
        $group = $this->group_model->get_group_by_code($code);
        if (!$group) show_404();

        // Lưu thông tin nhóm vào view
        $this->data['group'] = $group;

        // Load danh sách món
        $this->data['products'] = $this->pos_model->getAllMiniProducts();

        // Load món mà các thành viên đã đặt (nếu có)
        $this->data['group_items'] = $this->group_model->get_items($group->id);
        //$this->sma->print_arrays($this->data['group_items']);

        // Render view order như bình thường
        $this->load->view($this->mini_theme . 'order', $this->data);
    }

}

