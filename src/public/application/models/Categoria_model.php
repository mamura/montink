<?php
class Categoria_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        return $this->db->get('categories')->result();
    }

    public function get($id)
    {
        return $this->db->get_where('categories', ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('categories', $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('categories', $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete('categories');
    }
}
