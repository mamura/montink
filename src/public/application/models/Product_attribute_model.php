<?php
class Product_attribute_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        return $this->db->get('product_attributes')->result();
    }

    public function find($id) {
        $attribute = $this->db->get_where('product_attributes', ['id' => $id])->row();
        if (!$attribute) return null;

        // Busca os valores corretamente da tabela values
        $values = $this->db->get_where('product_attribute_values', ['attribute_id' => $id])->result();
        $attribute->values = array_column($values, 'value');

        return $attribute;
    }

    public function insert($data, $values = []) {
        $this->db->insert('product_attributes', $data);
        $attribute_id = $this->db->insert_id();

        // Insere valores relacionados
        if (!empty($values)) {
            foreach ($values as $value) {
                $this->db->insert('product_attribute_values', [
                    'attribute_id' => $attribute_id,
                    'value' => $value
                ]);
            }
        }

        return $attribute_id;
    }

    public function update($id, $data, $values = []) {
        $this->db->where('id', $id)->update('product_attributes', $data);

        // Atualiza valores: remove antigos e insere novos
        $this->db->where('attribute_id', $id)->delete('product_attribute_values');
        foreach ($values as $value) {
            $this->db->insert('product_attribute_values', [
                'attribute_id' => $id,
                'value' => $value
            ]);
        }
    }

    public function delete($id) {
        $this->db->where('attribute_id', $id)->delete('product_attribute_values');
        $this->db->where('id', $id)->delete('product_attributes');
    }

    public function get($id) {
        return $this->db->get_where('product_attributes', ['id' => $id])->row();
    }

    public function searchByName($term) {
        $this->db->like('name', $term);
        $query = $this->db->get('product_attributes');
        return $query->result();
    }
}
