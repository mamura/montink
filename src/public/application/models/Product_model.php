<?php

class Product_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id');
        return $this->db->get()->result();
    }

    public function get($id)
    {
        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id');
        $this->db->where('products.id', $id);
        $product = $this->db->get()->row();

        if (!$product) return null;

        // Variantes do produto
        $product->variants = $this->get_variants($id);

        return $product;
    }

    public function insert($data)
    {
        $this->db->insert('products', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id)->update('products', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id)->delete('products');
    }

    public function get_by_category($category_id)
    {
        return $this->db->get_where('products', ['category_id' => $category_id])->result();
    }

    private function get_variants($product_id)
    {
        $this->db->select('pv.*, s.quantity');
        $this->db->from('product_variants pv');
        $this->db->join('stock s', 's.varian_id = pv.id', 'left');
        $this->db->where('pv.variant_id', $product_id);
        $variants = $this->db->get()->result();

        foreach ($variants as &$variant) {
            $variant->attributes = $this->get_variant_attributes($variant->id);
        }

        return $variants;
    }

    private function get_variant_attributes($variant_id)
    {
        $this->db->select('ao.id, ao.value, a.name as attribute');
        $this->db->from('product_variant_values pvv');
        $this->db->join('attribute_options ao', 'ao.id = pvv.attribute_option_id');
        $this->db->join('attributes a', 'a.id = ao.attribute_id');
        $this->db->where('pvv.variant_id', $variant_id);
        return $this->db->get()->result();
    }
}
