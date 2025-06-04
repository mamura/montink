<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produto extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Categoria_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title']      = 'Produtos';
        $data['produtos']   = $this->Product_model->get_all();

        $this->load->view('layouts/main', [
            'contents' => $this->load->view('produtos/index', $data, true)
        ]);
    }

    public function create()
    {
        $data['title'] = 'Novo Produto';
        $data['categorias'] = $this->Categoria_model->get_all();
        $data['produto'] = null;

        $this->load->view('layouts/main', [
            'contents' => $this->load->view('produtos/form', $data, true)
        ]);
    }

    public function store()
    {
        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('category_id', 'Categoria', 'required|numeric');

        if ($this->form_validation->run() === false) {
            return $this->create();
        }

        $data = [
            'name'        => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'category_id' => $this->input->post('category_id')
        ];

        $this->Product_model->insert($data);

        redirect('produto');
    }

    public function edit($id)
    {
        $produto = $this->Product_model->get($id);
        if (!$produto) show_404();

        $data['title'] = 'Editar Produto';
        $data['produto'] = $produto;
        $data['categorias'] = $this->Categoria_model->get_all();

        $this->load->view('layouts/main', [
            'contents' => $this->load->view('produtos/form', $data, true)
        ]);
    }

    public function update($id)
    {
        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('category_id', 'Categoria', 'required|numeric');

        if ($this->form_validation->run() === false) {
            return $this->edit($id);
        }

        $data = [
            'name'        => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'category_id' => $this->input->post('category_id')
        ];

        $this->Product_model->update($id, $data);

        redirect('produto');
    }

    public function delete($id)
    {
        $this->Product_model->delete($id);
        redirect('produto');
    }
}
