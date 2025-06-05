<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function index()
    {
        $this->load->model('Product_model');
        $data['produtos'] = $this->Product_model->allWithVariants();

        $this->load->view('layouts/main', [
            'contents' => $this->load->view('home/index', $data, true)
        ]);
        
    }
}
