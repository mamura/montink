<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categoria extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Categoria_model');
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $data['categorias'] = $this->Categoria_model->get_all();
        $data['title']      = 'Categorias';

        $this->load->view('layouts/main', ['contents' => $this->load->view('categorias/index', $data, true)]);
    }

    public function create()
    {
        $data['title'] = 'Nova Categoria';
        $this->load->view('layouts/main', ['contents' => $this->load->view('categorias/form', [], true)]);
    }

    public function store()
    {
        $this->form_validation->set_rules('name', 'Nome', 'required|is_unique[categories.name]');

        if ($this->form_validation->run()) {
            $this->Categoria_model->insert(['name' => $this->input->post('name')]);
            redirect('categoria');
        }

        $data['title'] = 'Nova Categoria';
        $this->load->view('layouts/main', ['contents' => $this->load->view('categorias/form', [], true)]);
    }

    public function edit($id)
    {
        $categoria = $this->Categoria_model->get($id);

        if (!$categoria) {
            show_404();
        }

        $data = [
            'title'     => 'Editar Categoria',
            'categoria' => $categoria,
        ];

        $this->load->view('layouts/main', ['contents' => $this->load->view('categorias/form', $data, true)]);
    }

    public function update($id)
    {
        $categoria = $this->Categoria_model->get($id);

        if (!$categoria) {
            show_404();
        }

        $this->form_validation->set_rules('name', 'Nome', 'required');

        if ($this->form_validation->run()) {
            $this->Categoria_model->update($id, ['name' => $this->input->post('name')]);
            redirect('categoria');
        }

        $data = [
            'title' => 'Editar Categoria',
            'categoria' => $categoria,
        ];

        $this->load->view('layouts/main', ['contents' => $this->load->view('categorias/form', $data, true)]);
    }

    public function delete($id)
    {
        $this->Categoria_model->delete($id);
        redirect('categoria');
    }
}
