<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Product_attribute_model');
    }

    public function index() {
        $data['attributes'] = $this->Product_attribute_model->get_all();
        $this->load->view('layouts/header');
        $this->load->view('attributes/index', $data);
        $this->load->view('layouts/footer');
    }

    public function create() {
        $this->load->view('layouts/header');
        $this->load->view('attributes/form');
        $this->load->view('layouts/footer');
    }

    public function edit($id) {
        $data['attribute'] = $this->Product_attribute_model->find($id);
        $this->load->view('layouts/header');
        $this->load->view('attributes/form', $data);
        $this->load->view('layouts/footer');
    }

    public function store() {
        $id         = $this->input->post('id');
        $name       = $this->input->post('name');
        $input_type = $this->input->post('input_type');
        $values     = $this->input->post('values');
        $valuesArr  = array_filter(array_map('trim', explode(',', $values)));

        if ($id) {
            $this->Product_attribute_model->update($id, [
                'name' => $name,
                'input_type' => $input_type
            ], $valuesArr);
        } else {
            $this->Product_attribute_model->insert([
                'name' => $name,
                'input_type' => $input_type
            ], $valuesArr);
        }

        redirect('attribute');
    }

    public function delete($id) {
        $this->Product_attribute_model->delete($id);
        redirect('attribute');
    }
}
