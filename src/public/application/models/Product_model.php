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

    public function allWithVariants()
    {
        $this->db->select('p.*, pv.id as variant_id, pv.price');
        $this->db->from('products p');
        $this->db->join('(SELECT MIN(id) as id, product_id FROM product_variants GROUP BY product_id) as first_variant', 'first_variant.product_id = p.id', 'left');
        $this->db->join('product_variants pv', 'pv.id = first_variant.id', 'left');
        $this->db->order_by('p.id', 'desc');

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

    public function get_variants($product_id)
    {
        $this->db->select('pv.*, s.quantity');
        $this->db->from('product_variants pv');
        $this->db->join('stock s', 's.variant_id = pv.id', 'left');
        $this->db->where('pv.product_id', $product_id);
        $variants = $this->db->get()->result();

        foreach ($variants as &$variant) {
            $variant->attributes = $this->get_variant_attributes($variant->id);
        }

        return $variants;
    }

    public function get_variant_attributes($variant_id)
    {
        $this->db->select('pvv.id, a.id as attribute_id, a.name as attribute, ao.id as option_id, ao.value');
        $this->db->from('product_variant_values pvv');
        $this->db->join('attribute_options ao', 'ao.id = pvv.attribute_option_id');
        $this->db->join('attributes a', 'a.id = ao.attribute_id');
        $this->db->where('pvv.variant_id', $variant_id);
        return $this->db->get()->result();
    }

    public function insert_variants($product_id, $variantes)
    {
        foreach ($variantes as $var) {
            $data = [
                'product_id' => $product_id,
                'sku'        => $var['sku'],
                'price'      => $var['price'],
            ];

            $this->db->insert('product_variants', $data);
            $variant_id = $this->db->insert_id();

            $this->db->insert('stock', [
                'variant_id' => $variant_id,
                'quantity'  => $var['stock'],
            ]);

            if (!empty($var['atributos'])) {
                foreach ($var['atributos'] as $atributo) {
                    $this->db->insert('product_variant_values', [
                        'variant_id'          => $variant_id,
                        'attribute_option_id' => $atributo['opcao_id'],
                    ]);
                }
            }
        }
    }

    public function update_variants($product_id, $variantes)
    {
        $query              = $this->db->select('id')->where('product_id', $product_id)->get('product_variants');
        $existingVariants   = $query->result();

         foreach ($existingVariants as $variant) {
            $this->db->where('variant_id', $variant->id)->delete('product_variant_values');
        }
 
        $this->db->where('product_id', $product_id)->delete('product_variants');
        $this->insert_variants($product_id, $variantes);
    }
}
