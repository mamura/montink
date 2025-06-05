<?php
class Product_variant_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_by_product($product_id) {
        return $this->db->get_where('product_variants', ['variant_id' => $product_id])->result();
    }

    public function find($id) {
        $variant = $this->db->get_where('product_variants', ['id' => $id])->row();

        if ($variant) {
            $this->db->select('ao.id, ao.value, a.name as attribute_name');
            $this->db->from('product_variant_values pvv');
            $this->db->join('attribute_options ao', 'pvv.attribute_option_id = ao.id');
            $this->db->join('attributes a', 'ao.attribute_id = a.id');
            $this->db->where('pvv.variant_id', $id);
            $query = $this->db->get();
            $variant->attributes = $query->result();
        }

        return $variant;
    }

    public function create($data, $option_ids = []) {
        $this->db->insert('product_variants', $data);
        $variant_id = $this->db->insert_id();

        foreach ($option_ids as $option_id) {
            $this->db->insert('product_variant_values', [
                'variant_id' => $variant_id,
                'attribute_option_id' => $option_id
            ]);
        }

        return $variant_id;
    }

    public function update($id, $data, $option_ids = []) {
        $this->db->where('id', $id)->update('product_variants', $data);

        $this->db->where('variant_id', $id)->delete('product_variant_values');

        foreach ($option_ids as $option_id) {
            $this->db->insert('product_variant_values', [
                'variant_id' => $id,
                'attribute_option_id' => $option_id
            ]);
        }
    }

    public function delete($id) {
        $this->db->where('variant_id', $id)->delete('product_variant_values');
        $this->db->where('id', $id)->delete('product_variants');
    }
}
