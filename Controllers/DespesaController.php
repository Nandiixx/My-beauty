<?php
// Arquivo: Controllers/DespesaController.php

require_once __DIR__ . '/../Models/Despesa.php';

class DespesaController {

    // Garante que apenas admins acessem
    private function checarAdmin() {
        if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_cargo'], ['PROPRIETARIO', 'GERENTE_FINANCEIRO'])) {
            header("Location: Index.php?acao=login");
            exit;
        }
    }

    // READ (Listar todos)
    public function listar() {
        $this->checarAdmin(); // Protege a página
        
        $despesaModel = new Despesa();
        $despesas = $despesaModel->listarTodos();
        
        // Chama a View
        require_once __DIR__ . '/../Views/Admin/Despesas/listar.php';
    }

    // CREATE (Mostrar formulário de cadastro)
    public function formularioCadastrar() {
        $this->checarAdmin();
        
        $despesa = new Despesa(); // Objeto vazio para o formulário
        $acao = 'cadastrar'; // Informa ao formulário que é um cadastro
        $titulo = 'Cadastrar Nova Despesa';
        
        // Reutiliza o formulário
        require_once __DIR__ . '/../Views/Admin/Despesas/formulario.php';
    }

    // CREATE (Processar dados do formulário)
    public function cadastrar() {
        $this->checarAdmin();

        $despesa = new Despesa();
        $despesa->setDescricao($_POST['descricao']);
        $despesa->setValor($_POST['valor']);
        $despesa->setData($_POST['data']);
        $despesa->setCategoria($_POST['categoria']);

        if ($despesa->inserirBD()) {
            header("Location: Index.php?acao=despesa_listar&status=sucesso");
        } else {
            header("Location: Index.php?acao=despesa_listar&status=erro");
        }
        exit;
    }

    // UPDATE (Mostrar formulário de edição)
    public function formularioEditar($id) {
        $this->checarAdmin();
        
        $despesa = new Despesa();
        if (!$despesa->carregarPorId($id)) {
            header("Location: Index.php?acao=despesa_listar&status=erro_carregar");
            exit;
        }
        
        $acao = 'editar'; // Informa ao formulário que é uma edição
        $titulo = 'Editar Despesa';
        
        // Reutiliza o formulário
        require_once __DIR__ . '/../Views/Admin/Despesas/formulario.php';
    }

    // UPDATE (Processar dados do formulário)
    public function editar($id) {
        $this->checarAdmin();

        $despesa = new Despesa();
        $despesa->setId($id);
        $despesa->setDescricao($_POST['descricao']);
        $despesa->setValor($_POST['valor']);
        $despesa->setData($_POST['data']);
        $despesa->setCategoria($_POST['categoria']);

        if ($despesa->atualizarBD()) {
            header("Location: Index.php?acao=despesa_listar&status=sucesso_update");
        } else {
            header("Location: Index.php?acao=despesa_listar&status=erro_update");
        }
        exit;
    }

    // DELETE
    public function excluir($id) {
        $this->checarAdmin();
        
        $despesaModel = new Despesa();
        $resultado = $despesaModel->excluirBD($id);

        if ($resultado === true) {
            header("Location: Index.php?acao=despesa_listar&status=sucesso_delete");
        } else {
            header("Location: Index.php?acao=despesa_listar&status=erro_delete");
        }
        exit;
    }
}
?>
