<?php

class Usuario extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['usuarios'] = $this->User_model->get_all();
        $data['title'] = 'Usuários';
        $this->load->view('layouts/main', [
            'contents' => $this->load->view('usuarios/index', $data, true)
        ]);
    }

    public function create()
    {
        $data['title'] = 'Novo Usuário';
        $data['usuario'] = null;
        $this->load->view('layouts/main', [
            'contents' => $this->load->view('usuarios/form', $data, true)
        ]);
    }

    public function store()
    {
        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Senha', 'required');

        if (!$this->form_validation->run()) {
            return $this->create();
        }

        $data = [
            'name'     => $this->input->post('name'),
            'email'    => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'is_admin' => (bool) $this->input->post('is_admin'),
        ];

        $this->User_model->insert($data);
        redirect('usuario');
    }

    public function edit($id)
    {
        $usuario = $this->User_model->get($id);
        if (!$usuario) show_404();

        $data['usuario'] = $usuario;
        $data['title'] = 'Editar Usuário';

        $this->load->view('layouts/main', [
            'contents' => $this->load->view('usuarios/form', $data, true)
        ]);
    }

    public function update($id)
    {
        $usuario = $this->User_model->get($id);
        if (!$usuario) show_404();

        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');

        if (!$this->form_validation->run()) {
            return $this->edit($id);
        }

        $data = [
            'name'     => $this->input->post('name'),
            'email'    => $this->input->post('email'),
            'is_admin' => (bool) $this->input->post('is_admin'),
        ];

        if ($this->input->post('password')) {
            $data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        }

        $this->User_model->update($id, $data);
        redirect('usuario');
    }

    public function delete($id)
    {
        $this->User_model->delete($id);
        redirect('usuario');
    }
}
