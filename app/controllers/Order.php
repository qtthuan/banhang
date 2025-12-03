<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/companies_model');
        $this->load->model('admin/pos_model');
    }

    public function index() {
        $this->data['products'] = $this->pos_model->getAllMiniProducts();
        $this->load->view($this->mini_theme . 'order', $this->data);
    }

    public function findCustomer($term = NULL, $limit = NULL)
    {
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }

        $limit = $this->input->get('limit', TRUE);

        $rows['results'] = $this->companies_model
            ->findCustomerSuggestions($term, $limit, lang('award_points_short'));

        $this->sma->send_json($rows);
    }
}


