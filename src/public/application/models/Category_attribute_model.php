<?php
class Category_attribute_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_category($category_id) {
        $this->db->select('product_attributes.*');
        $this->db->from('category_attribute');
        $this->db->join('product_attributes', 'product_attributes.id = category_attribute.attribute_id');
        $this->db->where('category_attribute.category_id', $category_id);
        return $this->db->get()->result();
    }

    public function save_relations($category_id, $attribute_ids) {
        // Remove antigos
        $this->db->delete('category_attribute', ['category_id' => $category_id]);

        // Insere os novos
        foreach ($attribute_ids as $attr_id) {
            $this->db->insert('category_attribute', [
                'category_id' => $category_id,
                'attribute_id' => $attr_id
            ]);
        }
    }
}
