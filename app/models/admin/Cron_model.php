<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->lang->admin_load('cron', $this->Settings->language);
        $this->load->admin_model('companies_model');
        $this->load->admin_model('products_model');
    }

    public function run_cron()
    {
        $m = '';
        if ($this->resetOrderRef()) {
            $m .= '<p>'.lang('order_ref_updated').'</p>';
        }
        if ($pendingInvoices = $this->getAllPendingInvoices()) {
            $p = 0;
            foreach ($pendingInvoices as $invoice) {
                $this->updateInvoiceStatus($invoice->id);
                $p++;
            }
            $m .= '<p>' . sprintf(lang('x_pending_to_due'), $p) . '</p>';

        }
        if ($partialInvoices = $this->getAllPPInvoices()) {
            $pp = 0;
            foreach ($partialInvoices as $invoice) {
                $this->updateInvoiceStatus($invoice->id);
                $pp++;
            }
            $m .= '<p>' . sprintf(lang('x_partial_to_due'), $pp) . '</p>';
        }
        if ($unpaidpurchases = $this->getUnpaidPuchases()) {
            $up = 0;
            foreach ($unpaidpurchases as $purchase) {
                $this->db->update('purchases', array('payment_status' => 'due'), array('id' => $purchase->id));
                $up++;
            }
            $m .= '<p>' . sprintf(lang('x_purchases_changed'), $up) . '</p>';
        }
        if ($pis = $this->get_expired_products()) {
            $e = 0; $ep = 0;
            foreach($pis as $pi) {
                $this->db->update('purchase_items', array('quantity_balance' => 0), array('id' => $pi->id));
                $e++;
                $ep += $pi->quantity_balance;
            }
            $this->site->syncQuantity(NULL, NULL, $pis);
            $m .= '<p>' . sprintf(lang('x_products_expired'), $e, $ep) . '</p>';
        }
        if ($promos = $this->getPromoProducts()) {
            $pro = 0;
            foreach($promos as $pr) {
                $this->db->update('products', array('promotion' => 0), array('id' => $pr->id));
                $pro++;
            }
            $m .= '<p>' . sprintf(lang('x_promotions_expired'), $pro) . '</p>';
        }
        $date = date('Y-m-d H:i:s', strtotime('-1 month'));
        if ($this->deleteUserLgoins($date)) {
            $m .= '<p>' . sprintf(lang('user_login_deleted'), $date) . '</p>';
        }


        if ($this->excuteUpdateOptionNameExtra()) {
            $m .= '<p>' . sprintf(lang('excute_update_option_name_extra'), $date) . '</p>';
        }

        if ($this->fixProductCostingOnEditInfo()) {
            $m .= '<p>' . sprintf(lang('excute_fix_product_costing'), $date) . '</p>';
        }

        if ($this->AdjustBillDateOnCosting()) {
            $m .= '<p>' . sprintf(lang('excute_adjust_bill_date_costing'), $date) . '</p>';
        }
//        if ($this->excuteUpdateGrandTotalSaleExtra()) {
//            $m .= '<p>' . sprintf('update grand total extra ok', $date) . '</p>';
//        }
//        if ($this->db_backup()) {
//            $m .= '<p>' . lang('backup_done') . '</p>';
//        }
        //$this->updateMemberJoiningDate();

        //$this->updateCostingOnEditProduct();

        $this->clearSuspendedBills();

        $this->deleteExpiredCustomers();

        // Xóa sản phẩm ko có nơi sản xuất theo nhóm hàng
        //$this->deleteProductTemp(4);

        $date_now = date('Y-m-d');
        //$date_now = '2019-12-31';
        date('Y-m-d', strtotime('last day of december'));
        //$this->checkCustomersForUpgrade(); // Tự động nâng bậc theo tổng điểm nâng bậc   // 07/09/2019
        //if ($date_now == date('Y-m-d', strtotime('last day of december'))) {
            //$this->checkCustomersForUpgrade(); // Tự động nâng bậc theo tổng điểm nâng bậc   // 07/09/2019
        //}


        if ($this->checkUpdate()) {
             $m .= '<p>' . lang('update_available') . '</p>';
        }
        $r = $m != '' ? $m : false;
        $this->sent_email($r);
        $this->db->truncate('sessions');
        return $r;
    }

    /**
     * qtthuan
     */
    private function excuteUpdateOptionNameExtra() {
        $variants = $this->getOptionsWithEmptyExtra();
        $success = false;
        $i = 0;
        foreach ($variants as $option) {
            $i++;
            $option_name_extra = $this->sma->getSizeNumber($option->name);
            $data = array(
                'option_name_extra' => $option_name_extra
            );
            //echo $i . ') option_id:' . $option->id . ' - option_name_extra: ' . $option_name_extra . '<br />';
            if ($this->updateExtraOptions($option->id, $data)) {
                $success = true;
            }
        }
        return $success;
    }

    /**
     * qtthuan
     */
    private function excuteUpdateGrandTotalSaleExtra() {
        $sales = $this->getSales();
        $success = false;
        foreach ($sales as $sale) {
            $data = array(
                'grand_total_extra' => $sale->grand_total
            );
            if ($this->updateExtraGrandTotal($sale->id, $data)) {
                $success = true;
            }
        }
        return $success;
    }

    /**
     * qtthuan
     * @return array
     */
    private function getSales() {
        $q = $this->db->get_where('sales', array());
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    private function updateExtraGrandTotal($id, $data = array()) {
        if ($this->db->update("sales", $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    /**
     * qtthuan
     * @return array
     */
    private function getOptions() {
        $q = $this->db->get_where('product_variants', array());
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    /**
     * qtthuan
     * Lay danh sach option voi option_name_extra = ''
     * @return array
     */
    private function getOptionsWithEmptyExtra() {
        $q = $this->db->get_where('product_variants', array('option_name_extra=' => NULL));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    /**
     * qtthuan
     * @param $id
     * @param array $data
     * @return bool
     */
    private function updateExtraOptions($id, $data = array()) {
        if ($this->db->update("product_variants", $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    /**
     * qtthuan
     * Nang bac hoac ha bac nhom Dong, bac, vang, bach kim - reset diem
     * @return array
     */
    public function checkCustomersForUpgrade()
    {
        // KH than thiet: select record voi award_points > 0, check neu diem < 50 (KH dong) => reset ve 0, neu >= 50, nang bac
        // check tiep tung nhom con lai, neu đủ doanh số duy trì hạng thì kiểm tiếp, nếu không đủ điểm nâng bậc thì trừ % điểm
        // nếu đủ điều kiện nâng bậc thì nâng => đổi điểm nâng bậc, ngược lại thì hạ bậc trừ điểm
        $reject_month = date('Y-m-d', strtotime('first day of ' . 'december')); // KH vừa tạo trong tháng 12 thì không trừ điểm
        $this->db->select("id, name, history, phone, award_points, points_per_year, customer_group_name, customer_group_id, created_date", FALSE);
        $this->db->where(array(
                        "created_date < " => $reject_month));
        $q = $this->db->get('companies');

        $sql = $this->db->where(array(
            "created_date < " => $reject_month))->get_compiled_select('companies');
        echo $sql;

        if ($q->num_rows() > 0) {
            $i = 0;
            $task = '';

            foreach (($q->result()) as $row) {
                $i++;
                //$history = $row->history;
                $customer_info = '';
                //$customer_info .= $row->history;
                $current_points = $row->award_points;
                $current_group = $this->site->getCustomerGroupByID($row->customer_group_id);
                $next_group = $this->site->getCustomerGroupByLevel($current_group->level + 1);
				//$str_point_per_year = lang('points_per_year') . ' ' . $current_group->maintain_sales . ' ';
                $customer_info .= '#' . date('d-m-Y');

                // comments
//                $customer_info .= ' | ' . $row->name;
//                $customer_info .= ' | ' . $row->phone;
//                $customer_info .= ' | ' . $current_group->name;

                if  ($current_points >= $next_group->points_required) { // Nâng bậc
                    $task = lang('task_upgrade_customer');

                    //$remaining_points = $current_points - $next_group->points_required;

                    $customer_info .= ': ' . $task;
                    $customer_info .= ', ' . $current_group->name;
                    $customer_info .= ' => ' . $next_group->name;
                    $customer_info .= ' | ' . lang('current_points') . ': ' . $current_points;
                    $customer_info .= ' | ' . lang('minus_points') . ': ' . $current_points;
                    $customer_info .= ' | ' . lang('remaining_points') . ': 0';


                    // comments
                    //$customer_info .= ' | ' . lang('points_per_year') . ': 0';

                    $data = array(
                        'customer_group_id' => $next_group->id,
                        'customer_group_name' => $next_group->name,
                        'award_points' => 0,
                        'points_per_year' => 0,
                        'history' =>  $customer_info . '<br />' . $row->history
                    );

                } else {
                    if ($current_group->level == 0) { // Khach hang than thiet => 0

                        $task = lang('task_reset_customer');

                        $customer_info .= ': ' . $task;
                        $customer_info .= ' | ' . lang('current_points') . ': ' . $current_points;
                        $customer_info .= ' | ' . lang('minus_points') . ': ' . $current_points;
                        $customer_info .= ' | ' . lang('remaining_points') . ': 0';

                        // comments
                        //$customer_info .= ' | ' . lang('points_per_year') . ': 0';

                        $data = array(
                            'award_points' => 0,
                            'points_per_year' => 0,
                            'history' => $customer_info . '<br />' . $row->history
                        );

                    } else {
                        $maintain_points = round(($current_group->maintain_sales / $this->Settings->each_spent) * $this->Settings->ca_point, 1);

                        if ($current_points < $maintain_points) { // Hạ Xuống 1 bậc

                            $task = lang('task_downgrade_customer');

                            $down_group = $this->site->getCustomerGroupByLevel($current_group->level - 1);
                            $customer_info .= ': ' . $task;
                            $customer_info .= ', ' . $current_group->name;
                            $customer_info .= ' => ' . $down_group->name;
                            $customer_info .= ' | ' . lang('task_reset_customer');
                            $customer_info .= ' | ' . lang('current_points') . ': ' . $current_points;
                            $customer_info .= ' | ' . lang('minus_points') . ': ' . $current_points;
                            $customer_info .= ' | ' . lang('remaining_points') . ': 0';

                            // comments
                           // $customer_info .= ' | ' . lang('points_per_year') . ': 0';

                            $data = array(
                                'customer_group_id' => $down_group->id,
                                'customer_group_name' => $down_group->name,
                                'award_points' => 0,
                                'points_per_year' => 0,
                                'history' => $customer_info . '<br />' . $row->history
                            );

                        } else { // Duy trì bậc

                            $task = lang('task_staygrade_customer');

                            $remaining_points = $current_points * (100-$current_group->reset_points_percent)/100;
                            $minus_points = $current_points * ($current_group->reset_points_percent/100);

                            $customer_info .= ': ' . $task;
                            $customer_info .= ', ' . $current_group->name;
                            $customer_info .= ', ' . sprintf(lang('x_minus_points'), $current_group->reset_points_percent . '%');
                            $customer_info .= ' | ' . lang('current_points') . ': ' . $current_points;
                            $customer_info .= ' | ' . lang('minus_points') . ': ' . $minus_points;
                            $customer_info .= ' | ' . lang('remaining_points') . ': ' . $remaining_points;

                            // comments
                            //$customer_info .= ' | ' . lang('points_per_year') . ': ' . $this->sma->formatMoney($this->Settings->each_spent * $remaining_points);


                            $data = array(
                                'award_points' => $remaining_points,
                                'points_per_year' => $remaining_points,
                                'history' => $customer_info . '<br />' . $row->history
                            );



                        }

                    }
                }

                // comments
                //echo '<br />' . $i . ') ' . $customer_info;


                $data_tracking = array('task' => $task,
                    'user_id' => $row->id,
                    'user_name' => $row->name,
                    'content' =>  $customer_info,
                    'phone' => $row->phone,
                    'tracking_date' => date('Y-m-d'));


//                if ($this->db->insert('customer_tracking', $data_tracking)) {
//                    $this->db->update("companies", $data, array('id' => $row->id));
//                }

            }
            echo '<br /> Total: ' . $i;

            return TRUE;
        }
        return FALSE;
    }
    public function checkCustomersForUpgrade1()
    {
        // KH than thiet: select record voi award_points > 0, check neu diem < 50 (KH dong) => reset ve 0, neu >= 50, nang bac
        // check tiep tung nhom con lai, neu đủ doanh số duy trì hạng thì kiểm tiếp, nếu không đủ điểm nâng bậc thì trừ % điểm
        // nếu đủ điều kiện nâng bậc thì nâng => đổi điểm nâng bậc, ngược lại thì hạ bậc trừ điểm

        $this->db->select("id, name, award_points, customer_group_name, customer_group_id", FALSE);
        $this->db->where("award_points >=", 100);
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {

                $customer_group = $this->site->getCustomerGroupByID($row->customer_group_id);
                $new_customer_group = $this->site->getCustomerGroupByLevel($customer_group->level + 1);
                if ($row->award_points >= $new_customer_group->points_required) {
                    $data = array('customer_group_id' => $new_customer_group->id,
                        'customer_group_name' => $new_customer_group->name,
                        'award_points' => $row->award_points - $new_customer_group->points_required);

                    $str_content = $customer_group->name . ' => ' . $new_customer_group->name;
                    $str_content .= ' | Reset ' . $new_customer_group->points_required . 'đ (' . $row->award_points . ' => ' . ($row->award_points - $new_customer_group->points_required) . ')';
                    $data_tracking = array('task' => 'Upgrade member',
                                            'user_id' => $row->id,
                                            'user_name' => $row->name,
                                            'content' =>  $str_content,
                                            'tracking_date' => date('Y-m-d H:i:s'));

                    if ($this->db->insert('customer_tracking', $data_tracking)) {
                        $this->db->update("companies", $data, array('id' => $row->id));
                    }
                }

            }

            return TRUE;
        }
        return FALSE;
    }


    /**
     * @param $reject_month (january,...november, december)
     * @return bool
     */
    public function resetCustomerAwardPoints($reject_month) {

        $top_points = 70; // Khách Vip | Bạch Kim | Kim cương có điểm 70 trở lên thì chỉ reset 50% điểm
        $reject_month = date('Y-m-d', strtotime('first day of ' . $reject_month)); // KH vừa tạo trong tháng 12 thì không trừ điểm
        $this->db->select("id, name, award_points, customer_group_name, customer_group_id, created_date", FALSE);
        $this->db->where(array(
            "created_date < " => $reject_month,
            "award_points >" => 0));
        $q = $this->db->get('companies');

        if ($q->num_rows() > 0) {
            $i = 0;
            foreach (($q->result()) as $row) {
                $i++;
                $new_points = 0;
                $str_content = '';

                $customer_group = $this->site->getCustomerGroupByID($row->customer_group_id);
                if (($customer_group->percent) * -1 > 0 && $row->award_points >= $top_points) {
                    $str_content = $customer_group->name . ' | ';
                    $new_points = $row->award_points / 2;
                }
                $data = array('award_points' => $new_points);
                $str_content .= $row->award_points . 'đ => ' . $new_points . 'đ';
                $data_tracking = array('task' => 'Reset points',
                    'user_id' => $row->id,
                    'user_name' => $row->name,
                    'content' =>  $str_content,
                    'tracking_date' => date('Y-m-d H:i:s'));

                if ($this->db->update("companies", $data, array('id' => $row->id, 'award_points >' => 0))) {
                    echo $i . ') user_id: ' . $row->id . ' name: ' . $row->name . ' - ' . $str_content . '#' . $customer_group->name . '<br />';
                    $this->db->insert('customer_tracking', $data_tracking);
                }
            }

            return TRUE;
        }
        return FALSE;
    }

    /**
     * @qtthuan
     * Điều chỉnh ngày bị lệch giữa table sale và costing => Báo cáo chi phí lợi nhuận theo ngày chưa đúng
     *
     */
    public function AdjustBillDateOnCosting() {

        $query = "SELECT sales.id, sales.customer, sales.reference_no, sales.date as bill_date, cost.date as cost_date, cost.quantity, cost.purchase_net_unit_cost, cost.sale_net_unit_price, sales.sale_status";
        $query .= " FROM " . $this->db->dbprefix('sales') . " AS sales LEFT JOIN";
        $query .= " " . $this->db->dbprefix('costing') . " AS cost ON sales.id=cost.sale_id";
        $query .= " WHERE date_format(sales.date, '%Y-%m-%e') <> date_format(cost.date, '%Y-%m-%e')";
        $query .= " AND sales.sale_status <> 'returned'";
        $q = $this->db->query($query);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $this->db->where('sale_id', $row->id);
                if ($this->db->update('costing', array('date' => $this->sma->fld(trim($row->bill_date))))) {
                    $str_content =  'Sale_id: ' . $row->id . ' | No: ' . $row->reference_no . ' | Customer: ' . $row->customer;
                    $str_content .= ' | Change costing date from ' . $row->cost_date;
                    $str_content .= ' to ' . $this->sma->fld(trim($row->bill_date));
                    $data_tracking = array(
                        'task' => lang('task_adjust_bill_date_costing'),
                        'content' =>  $str_content,
                        'tracking_date' => date('Y-m-d H:i:s'));

                    $this->db->insert('cron_tracking', $data_tracking);
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @qtthuan
     * Fix giá nhập không đồng bộ khi sửa giá nhập của sản phẩm
     *  => Cập nhật trên 3 table: purchase_items, costing, warehouses_products
     */
    public function fixProductCostingOnEditInfo() {

        $isOk = 0;

        $query1 = "SELECT DISTINCT pro.id, pro.name, pro.code, pro.cost, purchase.net_unit_cost, purchase.quantity, pro.created_date";
        $query1 .= " FROM " . $this->db->dbprefix('products') . " AS pro LEFT JOIN";
        $query1 .= " " . $this->db->dbprefix('purchase_items') . " AS purchase ON pro.id = purchase.product_id";
        $query1 .= " WHERE pro.cost <> purchase.net_unit_cost";
        $q1 = $this->db->query($query1);

        if ($q1->num_rows() > 0) {
            foreach (($q1->result()) as $row) {
                $purchase_subtotal = $row->quantity*$row->cost;
                $this->db->where('product_id', $row->id);
                // Fix cost difference between products and purchase_items
                if ($this->db->update('purchase_items',
                    array(
                        'net_unit_cost' => $row->cost,
                        'unit_cost' => $row->cost,
                        'real_unit_cost' => $row->cost,
                        'subtotal' => $purchase_subtotal))) {
                    $str_content =  'Pro_id: ' . $row->id . ' | Code: ' . $row->code . ' | Name: ' . $row->name;
                    $str_content .= ' | Change cost from ' . $this->sma->formatMoney($row->net_unit_cost);
                    $str_content .= ' to ' . $this->sma->formatMoney($row->cost);
                    $data_tracking = array(
                        'task' => lang('task_fix_cost_on_purchase_items'),
                        'content' =>  $str_content,
                        'tracking_date' => date('Y-m-d H:i:s'));

                    $this->db->insert('cron_tracking', $data_tracking);
                }
            }
            $isOk += 1;
        }

        $query2 = "SELECT DISTINCT pro.id, pro.name, pro.code, pro.cost, cost.purchase_net_unit_cost, pro.created_date ";
        $query2 .= " FROM " . $this->db->dbprefix('products') . " AS pro JOIN " . $this->db->dbprefix('costing') . " AS cost ";
        $query2 .= " ON pro.id = cost.product_id WHERE pro.cost <> cost.purchase_net_unit_cost";

        $q2 = $this->db->query($query2);
        if ($q2->num_rows() > 0) {
            foreach (($q2->result()) as $row) {
                // Fix cost difference between products and costing
                $this->db->where('product_id', $row->id);
                if ($this->db->update('costing',
                    array(
                        'purchase_net_unit_cost' => $row->cost,
                        'purchase_unit_cost' => $row->cost))) {
                    $str_content =  'Pro_id: ' . $row->id . ' | Code: ' . $row->code . ' | Name: ' . $row->name;
                    $str_content .= ' | Change cost from ' . $this->sma->formatMoney($row->purchase_net_unit_cost);
                    $str_content .= ' to ' . $this->sma->formatMoney($row->cost);
                    $data_tracking = array(
                        'task' => lang('task_fix_cost_on_costing'),
                        'content' =>  $str_content,
                        'tracking_date' => date('Y-m-d H:i:s'));

                    $this->db->insert('cron_tracking', $data_tracking);
                }
            }
            $isOk += 1;
        }

        $query3 = "SELECT pro.id, pro.name, pro.code, pro.cost, ware.avg_cost, pro.created_date ";
        $query3 .= " FROM " . $this->db->dbprefix('products') . " AS pro JOIN " . $this->db->dbprefix('warehouses_products') . " AS ware ";
        $query3 .= " ON pro.id = ware.product_id WHERE pro.cost <> ware.avg_cost";

        $q3 = $this->db->query($query3);
        if ($q3->num_rows() > 0) {
            foreach (($q3->result()) as $row) {
                // Fix cost difference between products and warehouses products
                $this->db->where('product_id', $row->id);
                if ($this->db->update('warehouses_products', array('avg_cost' => $row->cost))) {
                    $str_content =  'Pro_id: ' . $row->id . ' | Code: ' . $row->code . ' | Name: ' . $row->name;
                    $str_content .= ' | Change cost from ' . $this->sma->formatMoney($row->avg_cost);
                    $str_content .= ' to ' . $this->sma->formatMoney($row->cost);
                    $data_tracking = array(
                        'task' => lang('task_fix_cost_on_warehouses_pro'),
                        'content' =>  $str_content,
                        'tracking_date' => date('Y-m-d H:i:s'));

                    $this->db->insert('cron_tracking', $data_tracking);
                }
            }
            $isOk += 1;
        }
        if ($isOk > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /** */
    public function deleteProductTemp($category_id) {

        //$category_id = 4;
        $query = "SELECT products.id AS id, products.name AS name";        
        $query .= " FROM ". $this->db->dbprefix('products') . " AS products";
        $query .= " WHERE category_id = ".$category_id." AND (brand=0 OR brand=NULL)";
        // /exit($query);

        $q = $this->db->query($query);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $this->products_model->deleteProduct($row->id);
            }
            return TRUE;
        }
        RETURN FALSE;
    }

    /**
     * Kiem tra va xoa khach hang qua 2 nam khong phat sinh hoa don
     */
    public function deleteExpiredCustomers() {
        $expired_days = 365; // Khách quá 1 năm sẽ bị xóa
        $query = "SELECT sales.customer_id AS id, sales.customer AS name";
        $query .= ", DATEDIFF(date_format(now(), '%Y-%m-%d'), date_format(MAX(sales.date), '%Y-%m-%d')) AS count_days";
        $query .= ", date_format(MAX(sales.date), '%d-%m-%Y') AS last_bill_date";
        $query .= ", customer.phone, customer.award_points";
        $query .= " FROM ". $this->db->dbprefix('sales') . " AS sales";
        $query .= " JOIN " . $this->db->dbprefix('companies') . " AS customer";
        $query .= " ON customer.id = sales.customer_id";
        $query .= " WHERE customer_id != 1 GROUP BY customer_id HAVING count_days > " . $expired_days;

        //exit($query);

        $q = $this->db->query($query);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $str_content = '';
                // Chuyển toàn bộ HĐ của KH này về Khách Ba-Ni (Khách lẻ) và xóa KH
                $this->db->where('customer_id', $row->id);

                if ($this->db->update('sales', array('customer_id' => 1, 'customer' => lang('quest_customer')))) {
                    $str_content .=  $row->name . ' | ' . lang('current_points') . ':' . $row->award_points;
                    $str_content .= ' => ' . lang('quest_customer') . ' (' . $row->count_days . ')';
                    $str_content .= ' | ' . lang('last_bill_date') . ': ' . $row->last_bill_date;
                    $data_tracking_clear = array('task' => lang('task_del_expired_customer'),
                        'user_id' => $row->id,
                        'user_name' => $row->name,
                        'content' =>  $str_content,
                        'phone' => $row->phone,
                        'tracking_date' => date('Y-m-d H:i:s'));

                    $this->db->insert('customer_tracking', $data_tracking_clear);
                    $this->db->delete('companies', array('id' => $row->id, 'group_name' => 'customer'));
                }
            }
            return TRUE;
        }
        RETURN FALSE;
    }


/**
     * qtthuan
     * Cập nhật giá nhập trên hệ thống sau khi thay đổi giá nhập sửa sản phẩm
     * + Cập nhật chi phí option
     * @return array
     */
    public function updateCostingOnEditProduct()
    {
        $success = 0;

        // Lấy danh sách sản phẩm đã thay đổi giá nhập mà chưa cập nhật lại bên table purchase_items
        $query = "SELECT cost.*, variant.cost";
        $query .= " FROM " . $this->db->dbprefix('costing') . " AS cost ";
        $query .= " JOIN " . $this->db->dbprefix('product_variants') . " AS variant"; 
        $query .= " ON cost.option_id=variant.id ";
        $query .= " where (variant.cost+cost.purchase_unit_cost) > cost.purchase_unit_cost";

        $q = $this->db->query($query);

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                if ($row->quantity > 0) {
                    $data = array(
                                'net_unit_cost' => $row->cost,
                                'real_unit_cost' => $row->cost,
                                'unit_cost' => $row->cost,
                                'subtotal' => $row->cost*$row->quantity);
                    // => Cập nhật lại giá nhập vừa thay đổi lên table purchase_items
                    $this->db->update("purchase_items", $data, array('product_id' => $row->product_id));
                }
            }
            $success++;
        }

        // Kiểm tra giá nhập ở table warehouses_products sau khi thay đổi giá nhập sản phẩm
        $query = "SELECT DISTINCT pro.id AS product_id, pro.name, pro.code, pro.cost, ware.avg_cost, ware.quantity, pro.created_date";
        $query .= " FROM " . $this->db->dbprefix('products') . " AS pro ";
        $query .= " JOIN " . $this->db->dbprefix('warehouses_products') . " AS ware"; 
        $query .= " ON pro.id = ware.product_id where pro.cost <> ware.avg_cost order by pro.created_date desc";

        $q = $this->db->query($query);

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                if ($row->quantity > 0) {
                    $data = array('avg_cost' => $row->cost);

                    // Cập nhật giá nhập vừa thay đổi bên danh sách sản phẩm qua table warehouses_products
                    $this->db->update("warehouses_products", $data, array('product_id' => $row->product_id));
                }
            }
            $success++;
        }

        // Kiểm tra giá nhập trên từng option => cập nhật lên table costing để tính chi phí chính xác
        $query = "SELECT ";
        $query .= " FROM " . $this->db->dbprefix('products') . " AS pro ";
        $query .= " LEFT JOIN " . $this->db->dbprefix('purchase_items') . " AS purchase"; 
        $query .= " ON pro.id=purchase.product_id where pro.cost <> purchase.net_unit_cost order by pro.created_date desc";

        //exit($query);

        $q = $this->db->query($query);

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                if ($row->quantity > 0) {
                    $data = array(
                                'net_unit_cost' => $row->cost,
                                'real_unit_cost' => $row->cost,
                                'unit_cost' => $row->cost,
                                'subtotal' => $row->cost*$row->quantity);
                    $this->db->update("costing", $data, array('product_id' => $row->product_id));
                }
            }
            $success++;
        }


        if ($success > 0 ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * qtthuan
     * Cập nhật ngày tạo khách hàng, lấy từ ngày mua đơn hàng đầu tiên
     * @return array
     */
    public function updateMemberJoiningDate()
    {
        $this->db->select("id, name, award_points, customer_group_name, customer_group_id", FALSE);
        $this->db->limit(500);
        //$this->db->limit(100, 2000);

        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $this->db->select("id, date", FALSE);
                $this->db->order_by('id', 'asc');
                $this->db->limit(1);
                $query = $this->db->get_where("sales", array('customer_id' => $row->id));
                if ($query->num_rows() > 0) {
                    foreach (($query->result()) as $row1) {
                        $data = array('created_date' => $row1->date);
                        $this->db->update("companies", $data, array('id' => $row->id));
                    }
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @return bool
     * Xoá hoá đơn tạm, chừa hđ của ngày hiện tại
     */
    public function clearSuspendedBills()
    {
        $date = new DateTime("now");
        $curr_date = $date->format('Y-m-d ');

        $this->db->select("id, date", FALSE);
        $this->db->where('DATE(date) < ', $curr_date);
        $q = $this->db->get('suspended_bills');


        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $this->db->delete('suspended_items', array('suspend_id' => $row->id));
                $this->db->delete('suspended_bills', array('id' => $row->id));
            }
            return TRUE;
        }
        return FALSE;
    }

    private function getAllPendingInvoices()
    {
        $today = date('Y-m-d');
        $paid = $this->lang->line('paid');
        $canceled = $this->lang->line('cancelled');
        $q = $this->db->get_where('sales', array('due_date <=' => $today, 'due_date !=' => '1970-01-01', 'due_date !=' => NULL, 'payment_status' => 'pending'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    private function getAllPPInvoices()
    {
        $today = date('Y-m-d');
        $paid = $this->lang->line('paid');
        $canceled = $this->lang->line('cancelled');
        $q = $this->db->get_where('sales', array('due_date <=' => $today, 'due_date !=' => '1970-01-01', 'due_date !=' => NULL, 'payment_status' => 'partial'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    private function updateInvoiceStatus($id)
    {
        if ($this->db->update('sales', array('payment_status' => 'due'), array('id' => $id))) {
            return TRUE;
        }
        return FALSE;
    }

    private function resetOrderRef()
    {
        if ($this->Settings->reference_format == 1 || $this->Settings->reference_format == 2) {
            $month = date('Y-m') . '-01';
            $year = date('Y') . '-01-01';
            if ($ref = $this->getOrderRef()) {
                $reset_ref = array('so' => 1, 'qu' => 1, 'po' => 1, 'to' => 1, 'pos' => 1, 'do' => 1, 'pay' => 1, 'ppay' => 1, 're' => 1, 'rep' => 1, 'ex' => 1, 'qa' => 1);
                if ($this->Settings->reference_format == 1 && strtotime($ref->date) < strtotime($year)) {
                    $reset_ref['date'] = $year;
                    $this->db->update('order_ref', $reset_ref, array('ref_id' => 1));
                    return TRUE;
                } elseif ($this->Settings->reference_format == 2 && strtotime($ref->date) < strtotime($month)) {
                    $reset_ref['date'] = $month;
                    $this->db->update('order_ref', $reset_ref, array('ref_id' => 1));
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    private function getOrderRef()
    {
        $q = $this->db->get_where('order_ref', array('ref_id' => 1), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSettings()
    {
        $q = $this->db->get_where('settings', array('setting_id' => 1), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    private function deleteUserLgoins($date)
    {
        $this->db->where('time <', $date);
        if ($this->db->delete('user_logins')) {
            return true;
        }
        return FALSE;
    }

    private function checkUpdate()
    {
        $fields = array('version' => $this->Settings->version, 'code' => $this->Settings->purchase_code, 'username' => $this->Settings->envato_username, 'site' => base_url());
        $this->load->helper('update');
        $protocol = is_https() ? 'https://' : 'http://';
        $updates = get_remote_contents($protocol.'tecdiary.com/api/v1/update/', $fields);
        $response = json_decode($updates);
        if (!empty($response->data->updates)) {
            $this->db->update('settings', array('update' => 1), array('setting_id' => 1));
            return TRUE;
        }
        return FALSE;
    }

    private function get_expired_products() {
        if ($this->Settings->remove_expired) {
            $date = date('Y-m-d');
            $this->db->where('expiry <=', $date)->where('expiry !=', NULL)->where('expiry !=', '0000-00-00')->where('quantity_balance >', 0);
            $q = $this->db->get('purchase_items');
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return FALSE;
    }

    private function getUnpaidPuchases()
    {
        $today = date('Y-m-d');
        $q = $this->db->get_where('purchases', array('payment_status !=' => 'paid', 'payment_status !=' => 'due', 'payment_term >' => 0, 'due_date !=' => NULL, 'due_date <=' => $today));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    private function getPromoProducts()
    {
        $today = date('Y-m-d');
        $q = $this->db->get_where('products', array('promotion' => 1, 'end_date !=' => NULL, 'end_date <=' => $today));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    private function db_backup() {
        $this->load->dbutil();
        $prefs = array(
            'format' => 'txt',
            'filename' => 'sma_db_backup.sql'
        );
        $back = $this->dbutil->backup($prefs);
        $backup =& $back;
        $db_name = 'db-backup-on-' . date("Y-m-d-H-i-s") . '.txt';
        $save = './files/backups/' . $db_name;
        $this->load->helper('file');
        write_file($save, $backup);

        $files = glob('./files/backups/*.txt', GLOB_BRACE);
        $now   = time();
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * 2) { // backup 2 ngay
                    unlink($file);
                }
            }
        }

        return TRUE;
    }

    function sent_email($details) {
        if ($details) {
            $table_html = '';
            $tables = $this->cron_model->yesterday_report();
            foreach ($tables as $table) {
                $table_html .= $table.'<div style="clear:both"></div>';
            }
            $msg_with_yesterday_report = $table_html.$details;
            $owners = $this->db->get_where('users', array('group_id' => 1))->result();
            $this->load->library('email');
            $config['useragent'] = "Stock Manager Advance";
            $config['protocol'] = $this->Settings->protocol;
            $config['mailtype'] = "html";
            $config['crlf'] = "\r\n";
            $config['newline'] = "\r\n";
            if ($this->Settings->protocol == 'sendmail') {
                $config['mailpath'] = $this->Settings->mailpath;
            } elseif ($this->Settings->protocol == 'smtp') {
                $this->load->library('UnsafeCrypto', NULL, 'uc');
                $config['smtp_host'] = $this->Settings->smtp_host;
                $config['smtp_user'] = $this->Settings->smtp_user;
                $config['smtp_pass'] = $this->uc->decrypt($this->Settings->smtp_pass);
                $config['smtp_port'] = $this->Settings->smtp_port;
                if (!empty($this->Settings->smtp_crypto)) {
                    $config['smtp_crypto'] = $this->Settings->smtp_crypto;
                }
            }
            $this->email->initialize($config);

            foreach ($owners as $owner) {
                list($user, $domain) = explode('@', $owner->email);
                if ($domain != 'tecdiary.com') {
                    $this->load->library('parser');
                    $parse_data = array(
                        'name' => $owner->first_name . ' ' . $owner->last_name,
                        'email' => $owner->email,
                        'msg' => $msg_with_yesterday_report,
                        'site_link' => base_url(),
                        'site_name' => $this->Settings->site_name,
                        'logo' => '<img src="' . base_url('assets/uploads/logos/' . $this->Settings->logo) . '" alt="' . $this->Settings->site_name . '"/>'
                        );
                    $msg = file_get_contents('./themes/' . $this->Settings->theme . '/admin/views/email_templates/cron.html');
                    $message = $this->parser->parse_string($msg, $parse_data);
                    $subject = lang('cron_job') . ' - ' . $this->Settings->site_name;

                    $this->email->from($this->Settings->default_email, $this->Settings->site_name);
                    $this->email->to($owner->email);
                    $this->email->subject($subject);
                    $this->email->message($message);
                    $this->email->send();
                }
            }
        }
    }

    private function yesterday_report() {
        $date = date('Y-m-d', strtotime('-1 day'));
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $warehouses = $this->db->get('warehouses')->result();
        foreach ($warehouses as $warehouse) {
            $costing = $this->getCosting($date, $warehouse->id);
            $discount = $this->getOrderDiscount($sdate, $edate, $warehouse->id);
            $expenses = $this->getExpenses($sdate, $edate, $warehouse->id);
            $returns = $this->getReturns($sdate, $edate, $warehouse->id);
            $total_purchases = $this->getTotalPurchases($sdate, $edate, $warehouse->id);
            $total_sales = $this->getTotalSales($sdate, $edate, $warehouse->id);
            $html[] = $this->gen_html($costing, $discount, $expenses, $returns, $total_purchases, $total_sales, $warehouse);
        }

        $costing = $this->getCosting($date);
        $discount = $this->getOrderDiscount($sdate, $edate);
        $expenses = $this->getExpenses($sdate, $edate);
        $returns = $this->getReturns($sdate, $edate);
        $total_purchases = $this->getTotalPurchases($sdate, $edate);
        $total_sales = $this->getTotalSales($sdate, $edate);
        $html[] = $this->gen_html($costing, $discount, $expenses, $returns, $total_purchases, $total_sales);

        return $html;
    }

    private function gen_html($costing, $discount, $expenses, $returns, $purchases, $sales, $warehouse = NULL) {
        $html = '<div style="border:1px solid #DDD; padding:10px; margin:10px 0;"><h3>'.($warehouse ? $warehouse->name.' ('.$warehouse->code.')' : lang('all_warehouses')).'</h3>
        <table width="100%" class="stable">
        <tr>
            <td style="border-bottom: 1px solid #EEE;">'.lang('products_sale').'</td>
            <td style="text-align:right; border-bottom: 1px solid #EEE;">'.$this->sma->formatMoney($costing->sales).'</td>
        </tr>';
        if ($discount && $discount->order_discount > 0) {
            $html .= '
            <tr>
                <td style="border-bottom: 1px solid #DDD;">'.lang('order_discount').'</td>
                <td style="text-align:right;border-bottom: 1px solid #DDD;">'. $this->sma->formatMoney($discount->order_discount).'</td>
            </tr>';
        }
        $html .= '
        <tr>
            <td style="border-bottom: 1px solid #EEE;">'.lang('products_cost').'</td>
            <td style="text-align:right; border-bottom: 1px solid #EEE;">'.$this->sma->formatMoney($costing->cost).'</td>
        </tr>';
        if ($expenses && $expenses->total > 0) {
            $html .= '
            <tr>
                <td style="border-bottom: 1px solid #DDD;">'.lang('expenses').'</td>
                <td style="text-align:right;border-bottom: 1px solid #DDD;">'. $this->sma->formatMoney($expenses->total).'</td>
            </tr>';
        }
        $html .= '
        <tr>
            <td width="300px;" style="border-bottom: 1px solid #DDD;"><strong>'.lang('profit').'</strong></td>
            <td style="text-align:right;border-bottom: 1px solid #DDD;">
                <strong>'.$this->sma->formatMoney($costing->sales - $costing->cost - ($discount ? $discount->order_discount : 0) - ($expenses ? $expenses->total : 0)).'</strong>
            </td>
        </tr>';
        if (isset($returns->total)) {
            $html .= '
            <tr>
                <td width="300px;" style="border-bottom: 2px solid #DDD;"><strong>'.lang('return_sales').'</strong></td>
                <td style="text-align:right;border-bottom: 2px solid #DDD;"><strong>'.$this->sma->formatMoney($returns->total).'</strong></td>
            </tr>';
        }
        $html .= '</table><h4 style="margin-top:15px;">'. lang('general_ledger') .'</h4>
        <table width="100%" class="stable">';
        if ($sales) {
            $html .= '
            <tr>
                <td width="33%" style="border-bottom: 1px solid #DDD;">'.lang('total_sales').': <strong>'.$this->sma->formatMoney($sales->total_amount).'('.$sales->total.')</strong></td>
                <td width="33%" style="border-bottom: 1px solid #DDD;">'.lang('received').': <strong>'.$this->sma->formatMoney($sales->paid).'</strong></td>
                <td width="33%" style="border-bottom: 1px solid #DDD;">'.lang('taxes').': <strong>'.$this->sma->formatMoney($sales->tax).'</strong></td>
            </tr>';
        }
        if ($purchases) {
            $html .= '
            <tr>
                <td width="33%">'.lang('total_purchases').': <strong>'.$this->sma->formatMoney($purchases->total_amount).'('.$purchases->total.')</strong></td>
                <td width="33%">'.lang('paid').': <strong>'.$this->sma->formatMoney($purchases->paid).'</strong></td>
                <td width="33%">'.lang('taxes').': <strong>'.$this->sma->formatMoney($purchases->tax).'</strong></td>
            </tr>';
        }
        $html .= '</table></div>';
        return $html;
    }

    private function getCosting($date, $warehouse_id = NULL)
    {
        $this->db->select('SUM( COALESCE( purchase_unit_cost, 0 ) * quantity ) AS cost, SUM( COALESCE( sale_unit_price, 0 ) * quantity ) AS sales, SUM( COALESCE( purchase_net_unit_cost, 0 ) * quantity ) AS net_cost, SUM( COALESCE( sale_net_unit_price, 0 ) * quantity ) AS net_sales', FALSE);
        $this->db->where('costing.date', $date);
        if ($warehouse_id) {
            $this->db->join('sales', 'sales.id=costing.sale_id')
            ->where('sales.warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('costing');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    private function getOrderDiscount($sdate, $edate, $warehouse_id = NULL)
    {
        $this->db->select('SUM( COALESCE( order_discount, 0 ) ) AS order_discount', FALSE);
        $this->db->where('date >=', $sdate)->where('date <=', $edate);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    private function getExpenses($sdate, $edate, $warehouse_id = NULL)
    {
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE);
        $this->db->where('date >=', $sdate)->where('date <=', $edate);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    private function getReturns($sdate, $edate, $warehouse_id = NULL)
    {
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total', FALSE)
        ->where('sale_status', 'returned');
        $this->db->where('date >=', $sdate)->where('date <=', $edate);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    private function getTotalPurchases($sdate, $edate, $warehouse_id = NULL)
    {
        $this->db->select('count(id) as total, sum(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid, SUM(COALESCE(total_tax, 0)) as tax', FALSE)
            ->where('status !=', 'pending')
            ->where('date >=', $sdate)->where('date <=', $edate);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    private function getTotalSales($sdate, $edate, $warehouse_id = NULL)
    {
        $this->db->select('count(id) as total, sum(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid, SUM(COALESCE(total_tax, 0)) as tax', FALSE)
            ->where('sale_status !=', 'pending')
            ->where('date >=', $sdate)->where('date <=', $edate);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

}
