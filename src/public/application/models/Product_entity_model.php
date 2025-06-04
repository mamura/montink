<?php
class Product_entity_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert($data) {
        $this->db->insert('product_entities', $data);
        return $this->db->insert_id();
    }

    public function get_all() {
        return $this->db->get('product_entities')->result();
    }

    public function get($id) {
        return $this->db->get_where('product_entities', ['id' => $id])->row();
    }

    public function update($id, $data) {
        return $this->db->update('product_entities', $data, ['id' => $id]);
    }
}
