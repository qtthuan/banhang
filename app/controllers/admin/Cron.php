<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->admin_model('cron_model');
        $this->Settings = $this->cron_model->getSettings();
    }

    function index() {
        show_404();
    }

    function run() {
        if ($m = $this->cron_model->run_cron()) {
            echo '<!doctype html><html><head><title>Cron Job</title><style>p{background:#F5F5F5;border:1px solid #EEE; padding:15px;}</style></head><body>';
            echo '<p>Cron job successfully run.</p>' . $m;
            echo '</body></html>';
        }
    }

    public function alert_low_stock()
    {
        //$this->load->model('cron_model');

        $products = $this->cron_model->get_low_stock_products();

        if (!$products) {
            echo "No low stock products.\n";
            return;
        }

        $message = "<h3>Cảnh báo hết hàng</h3>";
        $message .= "<table border='1' cellpadding='5'>";
        $message .= "<tr><th>Mã</th><th>Tên SP</th><th>Tồn kho</th><th>Ngưỡng cảnh báo</th></tr>";

        foreach ($products as $p) {
            $message .= "<tr>
                            <td>{$p->code}</td>
                            <td>{$p->name}</td>
                            <td>{$p->quantity}</td>
                            <td>{$p->alert_quantity}</td>
                        </tr>";
        }

        $message .= "</table>";

        // email nhận
        $to = "qtthuan2003@gmail.com";
        $subject = "Cảnh báo tồn kho thấp";

        // dùng email thư viện có sẵn của SMa
        if ($this->sma->send_email($to, $subject, $message)) {
            echo "Email sent.\n";
        } else {
            echo "Failed to send email.\n";
        }
    }


}
