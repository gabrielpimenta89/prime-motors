<?php
require_once __DIR__ . '/../models/venda.php';
require_once __DIR__ . '/../models/produto.php';
require_once __DIR__ . '/../models/cliente.php';

class VendaController
{

    public function index()
    {
        $model = new Venda();
        $vendas = $model->listarTodas();
        require_once __DIR__ . '/../views/vendas/listar.php';
    }

    public function criar()
    {
        $clienteModel = new Cliente();
        $clientes = $clienteModel->listarTodos();

        $produtoModel = new Produto();
        $produtos = $produtoModel->listarDisponiveis();

        require_once __DIR__ . '/../views/vendas/form.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_cliente = $_POST['id_cliente'] ?? 0;
            $id_produto = $_POST['id_produto'] ?? 0;
            $valor_venda = $_POST['valor_venda'] ?? 0.0;
            $data_venda = date('Y-m-d H:i:s');

            $model = new Venda();

            if ($model->registrar($id_cliente, $id_produto, $valor_venda, $data_venda)) {
                $produtoModel = new Produto();
                $produtoModel->atualizarStatus($id_produto, 'vendido');

                header("Location: index.php?controller=venda&action=index&sucesso=1");
                exit;
            } else {
                die("Erro ao registrar a venda.");
            }
        }
    }

    public function excluir()
    {
        $id = $_GET['id'] ?? 0;
        $model = new Venda();

        if ($model->deletar($id)) {
            header("Location: index.php?controller=venda&action=index&excluido=1");
            exit;
        }
    }
}
