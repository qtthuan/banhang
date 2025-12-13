<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_model extends CI_Model {
    public function create_group($data = []) {
        // $data: ['customer_name','customer_phone','note','expires_at']
        $code = $this->generate_code();
        $row = [
          'code' => $code,
          'customer_name' => $data['customer_name'] ?? null,
          'customer_phone' => $data['customer_phone'] ?? null,
          'customer_address' => $data['customer_address'] ?? null,
          'bill_note' => $data['note'] ?? null,
          'expires_at' => $data['expires_at'] ?? null
        ];
        $this->db->insert('mini_group_orders', $row);
        $id = $this->db->insert_id();
        return $this->db->get_where('mini_group_orders', ['id'=>$id])->row();
    }

    protected function generate_code($len = 8) {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // tránh nhầm lẫn 0 O 1 I
        $code = '';
        for ($i=0;$i<$len;$i++) $code .= $chars[rand(0, strlen($chars)-1)];
        // ensure uniqueness
        if ($this->db->get_where('mini_group_orders', ['code'=>$code])->num_rows()) {
          return $this->generate_code($len);
        }
        return $code;
    }

    // public function add_items($group_order_id, $items)
    // {
    //     foreach ($items as $it) {
    //         $this->db->insert('sma_mini_group_order_items', [
    //             'group_order_id' => $group_order_id,
    //             'product_id'     => $it['product_id'],
    //             'product_name'   => $it['product_name'],
    //             'option_id'      => $it['option_id'],
    //             'quantity'       => $it['quantity'],
    //             'price'          => $it['price'],
    //             'comment'        => $it['comment'],
    //             'comment_name'   => $it['comment_name'],
    //             'meta'           => json_encode($it['meta'], JSON_UNESCAPED_UNICODE)
    //         ]);
    //     }
    // }


    // public function add_item($group_code, $item) {
    //     $g = $this->db->get_where('mini_group_orders', ['code'=>$group_code])->row();
    //     if (!$g) return false;
    //     $insert = [
    //       'group_order_id' => $g->id,
    //       'product_id' => $item['product_id'],
    //       'product_name' => $item['product_name'],
    //       'option_id' => $item['option_id'] ?? 0,
    //       'comment' => $item['comment'] ?? '',
    //       'comment_name' => $item['comment_name'] ?? '',
    //       'quantity' => $item['quantity'] ?? 1,
    //       'price' => $item['price'] ?? 0,
    //       'meta' => isset($item['meta']) ? json_encode($item['meta']) : null
    //     ];
    //     $this->db->insert('mini_group_order_items', $insert);
    //     return $this->db->insert_id();
    // }
    
    public function add_item($data)
    {
        return $this->db->insert('sma_mini_group_order_items', $data);
    }

    // public function add_item($group_code, $item)
    // {
    //     $group = $this->db
    //         ->where('code', $group_code)
    //         ->get('sma_mini_group_orders')
    //         ->row();

    //     if (!$group) return false;

    //     $item['group_order_id'] = $group->id;

    //     return $this->db->insert('sma_mini_group_order_items', $item);
    // }

    // public function get_items($group_code, $since_id = 0) {
    //     $g = $this->db->get_where('mini_group_orders', ['code'=>$group_code])->row();
    //     if (!$g) return [];
    //     $this->db->where('group_order_id', $g->id);
    //     if ($since_id) $this->db->where('id >', $since_id);
    //     $this->db->order_by('id','asc');
    //     $q = $this->db->get('mini_group_order_items');
    //     return $q->result();
    // }

    public function get_items($group_order_id)
    {
        return $this->db
                    ->where('group_order_id', $group_order_id)
                    ->get('sma_mini_group_order_items')
                    ->result();
    }


    public function get_group_by_code($code) {
        return $this->db->get_where('mini_group_orders', ['code'=>$code])->row();
    }
    
}
