<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Carrinho extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->library('session');
    }

    public function index()
    {
        $carrinho = $this->session->userdata('carrinho') ?? [];

        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        // Regra de frete grátis: acima de R$100
        $frete = $this->calcular_frete($subtotal);

        $total = $subtotal + $frete;

        $data = [
            'carrinho' => $carrinho,
            'subtotal' => $subtotal,
            'frete'    => $frete,
            'total'    => $total,
        ];

        $this->load->view('layouts/main', [
            'contents' => $this->load->view('carrinho/index', $data, true)
        ]);
    }


    public function adicionar()
    {
        $variant_id = $this->input->post('variant_id');
        $quantidade = (int) $this->input->post('quantidade');

        if ($quantidade < 1) $quantidade = 1;

        $carrinho = $this->session->userdata('carrinho') ?? [];

        if (isset($carrinho[$variant_id])) {
            $carrinho[$variant_id]['quantidade'] += $quantidade;
        } else {
            $produto = $this->Product_model->get_by_variant($variant_id);

            if (!$produto) {
                show_404();
            }

            $carrinho[$variant_id] = [
                'produto'    => $produto->name,
                'sku'        => $produto->sku,
                'preco'      => $produto->price,
                'quantidade' => $quantidade
            ];
        }

        $this->session->set_userdata('carrinho', $carrinho);
        redirect('carrinho');
    }

    public function remover($variant_id)
    {
        $carrinho = $this->session->userdata('carrinho') ?? [];

        unset($carrinho[$variant_id]);

        $this->session->set_userdata('carrinho', $carrinho);
        redirect('carrinho');
    }

    public function checkout()
    {
        $carrinho = $this->session->userdata('carrinho') ?? [];

        if (empty($carrinho)) {
            redirect('home');
        }

        // Calcular subtotal e total
        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        // Regras de frete
        $frete = $subtotal >= 100 ? 0 : 20;
        $total = $subtotal + $frete;

        $data = [
            'carrinho' => $carrinho,
            'subtotal' => $subtotal,
            'frete'    => $frete,
            'total'    => $total
        ];

        $this->load->view('layouts/main', [
            'contents' => $this->load->view('carrinho/checkout', $data, true)
        ]);
    }

    public function finalizar()
    {
        $this->load->model('User_model');
        $this->load->model('Product_model');

        $carrinho = $this->session->userdata('carrinho') ?? [];

        if (empty($carrinho)) {
            show_error('Carrinho vazio.');
        }

        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        $frete = $this->calcular_frete($subtotal);
        $total = $subtotal + $frete;

        $post = $this->input->post();

        // Cria usuário (ou reaproveita, se já existe por e-mail)
        $user = $this->db->where('email', $post['email'])->get('users')->row();
        if (!$user) {
            $this->db->insert('users', [
                'name'  => $post['nome'],
                'email' => $post['email'],
                'cpf'   => $post['cpf'],
            ]);
            $user_id = $this->db->insert_id();
        } else {
            $user_id = $user->id;
        }

        // Cria o pedido
        $this->db->insert('orders', [
            'user_id'       => $user_id,
            'total'         => $total,
            'subtotal'      => $subtotal,
            'freight_value' => $frete,
            'freight_type'  => $this->tipo_frete($frete),
            'status'        => 'pending'
        ]);
        $order_id = $this->db->insert_id();

        // Endereço
        $this->db->insert('order_shipping', [
            'order_id'       => $order_id,
            'cep'            => $post['cep'],
            'street'         => $post['endereco'],
            'number'         => $post['numero'],
            'complement'     => $post['complemento'],
            'neighborhood'   => $post['bairro'],
            'city'           => $post['cidade'],
            'state'          => $post['estado'],
            'recipient_name' => $post['nome'],
            'phone'          => $post['telefone']
        ]);

        // Itens do pedido
        foreach ($carrinho as $variant_id => $item) {
            $this->db->insert('order_items', [
                'order_id'     => $order_id,
                'variant_id'   => $variant_id,
                'product_name' => $item['produto'],
                'quantity'     => $item['quantidade'],
                'unit_price'   => $item['preco'],
                'total_price'  => $item['preco'] * $item['quantidade']
            ]);
        }

        // Histórico de status
        $this->db->insert('order_status_history', [
            'order_id' => $order_id,
            'status'   => 'pending'
        ]);

        // Limpa carrinho
        $this->session->unset_userdata('carrinho');

        $this->load->view('layouts/main', [
            'contents' => $this->load->view('carrinho/sucesso', [], true)
        ]);
    }

    private function tipo_frete($frete)
    {
        if ($frete == 0) return 'gratis';
        if ($frete == 15) return 'variavel';
        return 'fixo';
    }

    public function limpar()
    {
        $this->session->unset_userdata('carrinho');
        redirect('carrinho');
    }

    private function calcular_frete($subtotal)
    {
        if ($subtotal > 200) {
            return 0.00; // frete grátis
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15.00;
        } else {
            return 20.00;
        }
    }

}
