<?php
class Product_attribute_value_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert($data) {
        return $this->db->insert('product_attribute_values', $data);
    }

    public function get_by_product($product_id) {
        $this->db->select('product_attribute_values.*, product_attributes.name as attribute_name, product_attributes.input_type');
        $this->db->from('product_attribute_values');
        $this->db->join('product_attributes', 'product_attributes.id = product_attribute_values.attribute_id');
        $this->db->where('product_entity_id', $product_id);
        return $this->db->get()->result();
    }

    public function delete_by_product($product_id) {
        return $this->db->delete('product_attribute_values', ['product_entity_id' => $product_id]);
    }
}
