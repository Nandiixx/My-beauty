<?php
// Arquivo: Controllers/ServicoController.php

require_once __DIR__ . '/../Models/Servico.php';

class ServicoController {

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
        
        $servicoModel = new Servico();
        $servicos = $servicoModel->listarTodos();
        
        // Chama a View
        require_once __DIR__ . '/../Views/servico_listar.php';
    }

    // CREATE (Mostrar formulário de cadastro)
    public function formularioCadastrar() {
        $this->checarAdmin();
        
        $servico = new Servico(); // Objeto vazio para o formulário
        $acao = 'cadastrar'; // Informa ao formulário que é um cadastro
        $titulo = 'Cadastrar Novo Serviço';
        
        // Reutiliza o formulário
        require_once __DIR__ . '/../Views/servico_formulario.php';
    }

    // CREATE (Processar dados do formulário)
    public function cadastrar() {
        $this->checarAdmin();

        $servico = new Servico();
        $servico->setNome($_POST['nome']);
        $servico->setDescricao($_POST['descricao']);
        $servico->setDuracaoMin($_POST['duracao_min']);
        $servico->setPreco($_POST['preco']);

        if ($servico->inserirBD()) {
            header("Location: Index.php?acao=servico_listar&status=sucesso");
        } else {
            header("Location: Index.php?acao=servico_listar&status=erro");
        }
        exit;
    }

    // UPDATE (Mostrar formulário de edição)
    public function formularioEditar($id) {
        $this->checarAdmin();
        
        $servico = new Servico();
        if (!$servico->carregarPorId($id)) {
            header("Location: Index.php?acao=servico_listar&status=erro_carregar");
            exit;
        }
        
        $acao = 'editar'; // Informa ao formulário que é uma edição
        $titulo = 'Editar Serviço';
        
        // Reutiliza o formulário
        require_once __DIR__ . '/../Views/servico_formulario.php';
    }

    // UPDATE (Processar dados do formulário)
    public function editar($id) {
        $this->checarAdmin();

        $servico = new Servico();
        $servico->setId($id);
        $servico->setNome($_POST['nome']);
        $servico->setDescricao($_POST['descricao']);
        $servico->setDuracaoMin($_POST['duracao_min']);
        $servico->setPreco($_POST['preco']);

        if ($servico->atualizarBD()) {
            header("Location: Index.php?acao=servico_listar&status=sucesso_update");
        } else {
            header("Location: Index.php?acao=servico_listar&status=erro_update");
        }
        exit;
    }

    // DELETE
    public function excluir($id) {
        $this->checarAdmin();
        
        $servicoModel = new Servico();
        $resultado = $servicoModel->excluirBD($id);

        if ($resultado === true) {
            header("Location: Index.php?acao=servico_listar&status=sucesso_delete");
        } else if ($resultado === "ERRO_FK") {
             header("Location: Index.php?acao=servico_listar&status=erro_fk");
        } else {
            header("Location: Index.php?acao=servico_listar&status=erro_delete");
        }
        exit;
    }
}
?>