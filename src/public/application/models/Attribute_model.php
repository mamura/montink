<?php
class Attribute_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $this->db->select('attributes.*, categories.name as category_name');
        $this->db->from('attributes');
        $this->db->join('categories', 'categories.id = attributes.category_id');
        return $this->db->get()->result();
    }

    public function get_by_category_with_options($categoria_id)
    {
        $this->db->where('category_id', $categoria_id);
        $atributos = $this->db->get('attributes')->result();

        foreach ($atributos as &$atributo) {
            $this->db->where('attribute_id', $atributo->id);
            $opcoes = $this->db->get('attribute_options')->result();
            $atributo->options = $opcoes;
        }

        return $atributos;
    }


    public function get($id)
    {
        $attribute = $this->db->get_where('attributes', ['id' => $id])->row();
        if (!$attribute) return null;

        $options = $this->db->get_where('attribute_options', ['attribute_id' => $id])->result();
        $attribute->options = array_column($options, 'value');

        return $attribute;
    }

    public function insert($data, $options = [])
    {
        $this->db->insert('attributes', $data);
        $attribute_id = $this->db->insert_id();

        foreach ($options as $value) {
            $this->db->insert('attribute_options', [
                'attribute_id' => $attribute_id,
                'value' => $value
            ]);
        }

        return $attribute_id;
    }

    public function update($id, $data, $options = [])
    {
        $this->db->where('id', $id)->update('attributes', $data);

        // Atualiza opções se necessário
        $this->db->where('attribute_id', $id)->delete('attribute_options');
        foreach ($options as $value) {
            $this->db->insert('attribute_options', [
                'attribute_id' => $id,
                'value' => $value
            ]);
        }
    }

    public function delete($id)
    {
        $this->db->where('attribute_id', $id)->delete('attribute_options');
        $this->db->where('id', $id)->delete('attributes');
    }
}
