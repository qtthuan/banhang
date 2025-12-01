<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

    public function index() {
        // Load model trong thư mục admin
        $this->load->model('admin/pos_model');

        $this->data['products'] = $this->pos_model->getAllMiniProducts();
        //$this->load->view('mini/mini/views/order', $this->data);
        $this->load->view($this->mini_theme . 'order', $this->data);
    }
}

