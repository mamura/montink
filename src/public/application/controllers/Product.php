<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_entity_model');
        $this->load->model('Product_attribute_model');
        $this->load->model('Product_attribute_value_model');
        $this->load->model('Stock_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('template');
    }

    public function index() {
        $data['products'] = $this->Product_entity_model->get_all();
        $this->template->set('title', 'Produtos');
        $this->template->load('layouts/main', 'products/index', $data);
    }

    public function create() {
        $data['attributes'] = $this->Product_attribute_model->get_all();
        $this->template->set('title', 'Novo Produto');
        $this->template->load('layouts/main', 'products/form', $data);
    }

    public function edit($id) {
        $data['product'] = $this->Product_entity_model->get($id);
        $data['attributes'] = $this->Product_attribute_model->get_all();
        $data['attribute_values'] = $this->Product_attribute_value_model->get_by_product($id);
        $data['stock'] = $this->Stock_model->get_by_product($id);

        $this->template->set('title', 'Editar Produto');
        $this->template->load('layouts/main', 'products/form', $data);
    }

    public function store() {
        $product_data = [
            'name'  => $this->input->post('name'),
            'price' => $this->input->post('price')
        ];

        $id = $this->input->post('id');
        $attributes = $this->input->post('attributes');
        $stock = (int) $this->input->post('stock');

        if ($id) {
            $this->Product_entity_model->update($id, $product_data);
            $this->Product_attribute_value_model->delete_by_product($id);
        } else {
            $id = $this->Product_entity_model->insert($product_data);
        }

        if ($attributes) {
            foreach ($attributes as $attribute_id => $value) {
                $this->Product_attribute_value_model->insert([
                    'product_entity_id' => $id,
                    'attribute_id'      => $attribute_id,
                    'value'             => $value
                ]);
            }
        }

        $existing_stock = $this->Stock_model->get_by_product($id);
        if ($existing_stock) {
            $this->Stock_model->update_quantity($id, $stock - $existing_stock->quantity);
        } else {
            $this->Stock_model->insert([
                'product_entity_id' => $id,
                'quantity' => $stock
            ]);
        }

        redirect('product');
    }
}
