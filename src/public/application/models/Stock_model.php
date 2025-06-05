<?php

class Stock_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert($data) {
        return $this->db->insert('stocks', $data);
    }

    public function get_by_product($product_id) {
        return $this->db->get_where('stocks', ['product_entity_id' => $product_id])->row();
    }

    public function update_quantity($product_id, $amount) {
        $this->db->set('quantity', 'quantity + ' . (int)$amount, false);
        $this->db->where('product_entity_id', $product_id);
        $this->db->update('stocks');
    }
}
