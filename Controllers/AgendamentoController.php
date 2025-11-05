<?php
// Inicia a sessão se já não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui os Modelos necessários
require_once __DIR__ . '/../Models/Agendamento.php';
require_once __DIR__ . '/../Models/Servico.php';
require_once __DIR__ . '/../Models/Funcionario.php';

class AgendamentoController
{
    // Método para exibir a página de agendamentos
    public function index()
    {
        // Requer login de Cliente
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'CLIENTE') {
            // ANTES: header('Location: ../index.php?acao=login_mostrar');
            header('Location: Index.php?acao=login_mostrar'); // DEPOIS
            exit;
        }
        
        // --- Carrega dados para a View ---
        
        // 1. Carrega lista de serviços
        $servicoModel = new Servico();
        $lista_servicos = $servicoModel->listarTodos();
        
        // 2. Carrega lista de profissionais
        $funcModel = new Funcionario();
        $lista_profissionais = $funcModel->listarTodosProfissionais(); 
        
        // 3. Carrega agendamentos existentes do cliente
        $agendamentoModel = new Agendamento();
        // Tenta obter o cliente_id diretamente da sessão. Se não existir,
        // tenta resolver a partir do usuario_id (fallback robusto).
        $cliente_id = $_SESSION['cliente_id'] ?? null; // Pega da sessão
        if (empty($cliente_id) && isset($_SESSION['usuario_id'])) {
            // Tentativa de fallback: carregar cliente pelo usuario_id
            require_once __DIR__ . '/../Models/Cliente.php';
            $clienteModel = new Cliente();
            if ($clienteModel->carregarClientePorUsuarioId($_SESSION['usuario_id'])) {
                $cliente_id = $clienteModel->getId();
                // Atualiza a sessão para evitar futuras consultas
                $_SESSION['cliente_id'] = $cliente_id;
            }
        }
        $lista_agendamentos = $agendamentoModel->listarAgendamentosCliente($cliente_id);

        // --- Inclui a View ---
        // Passa as 3 listas para a View
        include_once __DIR__ . '/../Views/agendamento.php';
    }

    /**
     * Método para salvar um novo agendamento
     * (MÉTODO ATUALIZADO COM VALIDAÇÃO)
     */
    public function salvar()
    {
        // Requer login de Cliente
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'CLIENTE') {
            // ANTES: header('Location: ../index.php?acao=login_mostrar');
            header('Location: Index.php?acao=login_mostrar'); // DEPOIS
            exit;
        }

        $erros = []; // Array para armazenar os erros de validação

        // 1. Validação dos dados de entrada (POST)
        if (empty($_POST['profissional_id'])) {
            $erros[] = "Você deve selecionar um profissional.";
        }
        if (empty($_POST['dataHora'])) {
            $erros[] = "Você deve selecionar uma data e hora.";
        }
        if (empty($_POST['servicos_ids']) || !is_array($_POST['servicos_ids'])) {
            $erros[] = "Você deve selecionar pelo menos um serviço.";
        }
        // (Você pode adicionar mais validações, ex: verificar se a data é no futuro)

        // 2. Se houver erros, armazena na sessão e redireciona de volta
        if (!empty($erros)) {
            $_SESSION['erros_agendamento'] = $erros;
            // ANTES: header('Location: ../index.php?acao=agendamento_mostrar');
            header('Location: Index.php?acao=agendamento_criar'); // DEPOIS
            exit;
        }

        // 3. Se a validação passou, continua com o processo
        try {
            $agendamento = new Agendamento();
            
            // Define os dados do agendamento
            $agendamento->setClienteId($_SESSION['cliente_id']);
            $agendamento->setProfissionalId((int)$_POST['profissional_id']);
            $agendamento->setDataHora($_POST['dataHora']);
            
            // Adiciona os serviços (pode ser mais de um)
            foreach ($_POST['servicos_ids'] as $servico_id) {
                $agendamento->addServico((int)$servico_id);
            }

            // Salva no banco
            if ($agendamento->inserirBD()) {
                $_SESSION['sucesso_agendamento'] = "Agendamento realizado com sucesso!";
                // ANTES: header('Location: ../index.php?acao=agendamento_mostrar');
                header('Location: Index.php?acao=agendamento_criar'); // DEPOIS
                exit;
            } else {
                $_SESSION['erros_agendamento'] = ["Erro ao salvar o agendamento no banco de dados."];
                // ANTES: header('Location: ../index.php?acao=agendamento_mostrar');
                header('Location: Index.php?acao=agendamento_criar'); // DEPOIS
                exit;
            }
        } catch (Exception $e) {
            // Captura exceções (ex: erro de conexão ou SQL)
            $_SESSION['erros_agendamento'] = ["Erro inesperado no servidor: " . $e->getMessage()];
            // ANTES: header('Location: ../index.php?acao=agendamento_mostrar');
            header('Location: Index.php?acao=agendamento_criar'); // DEPOIS
            exit;
        }
    }
    
    /**
     * Mostra a agenda do profissional
     * (Seu método original, mantido como está)
     */
    public function mostrarAgendaProfissional()
    {
        // Requer login de Profissional
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'PROFISSIONAL') {
            // ANTES: header('Location: ../index.php?acao=login_mostrar');
            header('Location: Index.php?acao=login_mostrar'); // DEPOIS
            exit;
        }
        
        $agendamentoModel = new Agendamento();
        
        // Pega o ID do profissional logado na sessão
        $funcionario_id = $_SESSION['funcionario_id']; 
        
        // Usa o novo método do Model
        $lista_agenda_profissional = $agendamentoModel->listarAgendaPorProfissional($funcionario_id);
        
        // Inclui a nova View
        include_once __DIR__ . '/../Views/agenda_profissional.php';
    }

    /**
     * Processa a mudança de status de um agendamento.
     * (Seu método original, mantido como está)
     */
    public function mudarStatusAgendamento($idAgendamento, $idStatus) {
        $agendamento = new Agendamento();
        
        if ($agendamento->atualizarStatus($idAgendamento, $idStatus)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Método para exibir a página de gerenciamento de agendamentos
     * Com opções de criar, visualizar e cancelar agendamentos
     */
    public function gerenciarAgendamentos()
    {
        // Requer login de Cliente
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'CLIENTE') {
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }
        
        // --- Carrega dados para a View ---
        
        // 1. Carrega lista de serviços
        $servicoModel = new Servico();
        $lista_servicos = $servicoModel->listarTodos();
        
        // 2. Carrega lista de profissionais
        $funcModel = new Funcionario();
        $lista_profissionais = $funcModel->listarTodosProfissionais(); 
        
        // 3. Carrega agendamentos existentes do cliente
        $agendamentoModel = new Agendamento();
        // Tenta obter o cliente_id da sessão. Se não existir, usa fallback
        $cliente_id = $_SESSION['cliente_id'] ?? null; // Pega da sessão
        if (empty($cliente_id) && isset($_SESSION['usuario_id'])) {
            require_once __DIR__ . '/../Models/Cliente.php';
            $clienteModel = new Cliente();
            if ($clienteModel->carregarClientePorUsuarioId($_SESSION['usuario_id'])) {
                $cliente_id = $clienteModel->getId();
                $_SESSION['cliente_id'] = $cliente_id;
            }
        }
        $lista_agendamentos = $agendamentoModel->listarAgendamentosCliente($cliente_id);

        // --- Inclui a View ---
        // Passa as 3 listas para a View
        include_once __DIR__ . '/../Views/gerenciar_agendamento.php';
    }
    
    /**
     * Método para cancelar um agendamento (altera status para CANCELADO)
     */
    public function cancelar()
    {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['usuario_id'])) {
             header("Location: Index.php?acao=login_mostrar");
             exit;
        }
        
        // Verifica se foi passado o ID do agendamento
        if (!isset($_GET['id'])) {
            $_SESSION['erros_agendamento'] = ["ID do agendamento não informado."];
            header("Location: Index.php?acao=gerenciar_agendamento_mostrar");
            exit;
        }
        
        $id = (int)$_GET['id'];
        $agendamentoModel = new Agendamento();
        
        // TODO: Adicionar verificação se o usuário pode cancelar
        // (verificar se é o dono do agendamento)
        
        // Cancela o agendamento (altera status para CANCELADO)
        if ($agendamentoModel->atualizarStatus($id, 3)) { // 3 = CANCELADO
            $_SESSION['sucesso_agendamento'] = "Agendamento cancelado com sucesso!";
            header("Location: Index.php?acao=gerenciar_agendamento_mostrar");
        } else {
            $_SESSION['erros_agendamento'] = ["Erro ao cancelar o agendamento."];
            header("Location: Index.php?acao=gerenciar_agendamento_mostrar");
        }
        exit;
    }
    
    /**
     * Método legado - mantido para compatibilidade
     * @deprecated Use o método cancelar() em vez de excluir()
     */
    public function excluir($id) {
        // Verifica se o usuário logado é o dono do agendamento ou um admin
        if (!isset($_SESSION['usuario_id'])) {
             header("Location: Index.php?acao=login_mostrar");
             exit;
        }
        
        $agendamentoModel = new Agendamento();
        
        // TODO: Adicionar verificação se o usuário pode excluir
        // (Ex: $agendamento = $agendamentoModel->carregarPorId($id); 
        //  if($agendamento->getClienteId() != $_SESSION['cliente_id']) ... )
        // Por enquanto, vamos permitir a exclusão
        
        if ($agendamentoModel->excluirBD($id)) {
            // Redireciona de volta para a lista de agendamentos do cliente
            header("Location: Index.php?acao=gerenciar_agendamento_mostrar&status=excluido_sucesso");
        } else {
            header("Location: Index.php?acao=gerenciar_agendamento_mostrar&status=excluido_erro");
        }
        exit;
    }
}
?>