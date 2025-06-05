<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Atributo extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Attribute_model');
        $this->load->model('Categoria_model');
        $this->load->helper(['form', 'url']);
    }

    public function index()
    {
        $data['title'] = 'Atributos';
        $data['atributos'] = $this->Attribute_model->get_all();

        $this->load->view('layouts/main', [
            'contents' => $this->load->view('atributos/index', $data, true)
        ]);
    }

    public function create()
    {
        $data['title'] = 'Novo Atributo';
        $data['categorias'] = $this->Categoria_model->get_all();
        $data['atributo'] = null;

        $this->load->view('layouts/main', [
            'contents' => $this->load->view('atributos/form', $data, true)
        ]);
    }

    public function edit($id)
    {
        $atributo = $this->Attribute_model->get($id);
        if (!$atributo) {
            show_404();
        }

        $data['title']      = 'Editar Atributo';
        $data['atributo']   = $atributo;
        $data['categorias'] = $this->Categoria_model->get_all();

        $this->load->view('layouts/main', [
            'contents' => $this->load->view('atributos/form', $data, true)
        ]);
    }

    public function store()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('category_id', 'Categoria', 'required');

        if ($this->form_validation->run() === false) {
            return $this->create();
        }

        $data = [
            'name' => $this->input->post('name'),
            'input_type' => $this->input->post('input_type'),
            'category_id' => $this->input->post('category_id'),
        ];

        $options = array_filter(array_map('trim', explode(',', $this->input->post('options') ?? '')));

        $this->Attribute_model->insert($data, $options);

        redirect('atributo');
    }

    public function update($id)
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('category_id', 'Categoria', 'required');

        if ($this->form_validation->run() === false) {
            return $this->edit($id);
        }

        $data = [
            'name' => $this->input->post('name'),
            'input_type' => $this->input->post('input_type'),
            'category_id' => $this->input->post('category_id'),
        ];

        $options = array_filter(array_map('trim', explode(',', $this->input->post('options') ?? '')));

        $this->Attribute_model->update($id, $data, $options);

        redirect('atributo');
    }

    public function delete($id)
    {
        $this->Attribute_model->delete($id);
        redirect('atributo');
    }

    public function por_categoria($categoria_id)
    {
        $atributos = $this->Attribute_model->get_by_category_with_options($categoria_id);
        echo json_encode($atributos);
    }

}
