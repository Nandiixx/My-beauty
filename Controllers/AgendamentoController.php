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
        include_once __DIR__ . '/../Views/Cliente/agendamento.php';
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
        if (empty($_POST['servico_id'])) {
            $erros[] = "Você deve selecionar um serviço.";
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
            
            // Adiciona o serviço único
            $agendamento->addServico((int)$_POST['servico_id']);

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
        include_once __DIR__ . '/../Views/Profissional/agenda_profissional.php';
    }
    
    /**
     * Mostra o dashboard do profissional com estatísticas
     * Processa toda a lógica de negócio antes de passar para a View
     */
    public function mostrarDashboardProfissional()
    {
        // Requer login de Profissional
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'PROFISSIONAL') {
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }
        
        $agendamentoModel = new Agendamento();
        $funcionario_id = $_SESSION['funcionario_id'] ?? null;
        
        // Busca todos os agendamentos do profissional
        $agendamentos = [];
        if ($funcionario_id) {
            $agendamentos = $agendamentoModel->listarAgendaPorProfissional($funcionario_id);
        }
        
        // Processa estatísticas
        $usuario_nome = $_SESSION['usuario_nome'] ?? 'Profissional';
        $total_agendamentos = count($agendamentos);
        
        // Filtra agendamentos de hoje
        $hoje = new DateTime();
        $hoje->setTime(0, 0, 0);
        $agendamentos_hoje = array_filter($agendamentos, function($ag) use ($hoje) {
            $data = new DateTime($ag->data_hora);
            $data->setTime(0, 0, 0);
            return $data == $hoje && $ag->status === 'AGENDADO';
        });
        
        // Filtra próximos agendamentos
        $agendamentos_proximos = array_filter($agendamentos, function($ag) {
            $data = new DateTime($ag->data_hora);
            return $data > new DateTime() && $ag->status === 'AGENDADO';
        });
        
        // Conta agendamentos concluídos
        $total_concluidos = count(array_filter($agendamentos, fn($a) => $a->status === 'CONCLUIDO'));
        
        // Prepara dados para a View
        $dados = [
            'usuario_nome' => $usuario_nome,
            'total_agendamentos' => $total_agendamentos,
            'agendamentos_hoje' => $agendamentos_hoje,
            'agendamentos_proximos' => $agendamentos_proximos,
            'total_concluidos' => $total_concluidos,
            'proximos_count' => count($agendamentos_proximos)
        ];
        
        // Inclui a View
        include_once __DIR__ . '/../Views/Profissional/inicio_profissional.php';
    }
    
    /**
     * Mostra o dashboard do cliente com estatísticas
     * Processa toda a lógica de negócio antes de passar para a View
     */
    public function mostrarDashboardCliente()
    {
        // Requer login de Cliente
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'CLIENTE') {
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }
        
        $agendamentoModel = new Agendamento();
        $cliente_id = $_SESSION['cliente_id'] ?? null;
        
        // Busca todos os agendamentos do cliente
        $agendamentos = [];
        if ($cliente_id) {
            $agendamentos = $agendamentoModel->listarAgendamentosCliente($cliente_id);
        }
        
        // Processa estatísticas
        $usuario_nome = $_SESSION['usuario_nome'] ?? 'Cliente';
        $total_agendamentos = count($agendamentos);
        
        // Filtra próximos agendamentos
        $agendamentos_proximos = array_filter($agendamentos, function($ag) {
            $data = new DateTime($ag->data_hora);
            return $data > new DateTime();
        });
        
        // Conta por status
        $total_concluidos = count(array_filter($agendamentos, fn($a) => $a->status === 'CONCLUIDO'));
        $total_agendados = count(array_filter($agendamentos, fn($a) => $a->status === 'AGENDADO'));
        
        // Prepara dados para a View
        $dados = [
            'usuario_nome' => $usuario_nome,
            'total_agendamentos' => $total_agendamentos,
            'agendamentos' => $agendamentos,
            'agendamentos_proximos' => $agendamentos_proximos,
            'proximos_count' => count($agendamentos_proximos),
            'total_concluidos' => $total_concluidos,
            'total_agendados' => $total_agendados
        ];
        
        // Inclui a View
        include_once __DIR__ . '/../Views/Cliente/inicio_cliente.php';
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
        // Requer login (Cliente, Profissional ou Admin)
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }
        
        $usuario_tipo = $_SESSION['usuario_tipo'] ?? '';
        
        // --- Carrega dados para a View ---
        
        // 1. Carrega lista de serviços
        $servicoModel = new Servico();
        $lista_servicos = $servicoModel->listarTodos();
        
        // 2. Carrega lista de profissionais
        $funcModel = new Funcionario();
        $lista_profissionais = $funcModel->listarTodosProfissionais(); 
        
        // 3. Carrega agendamentos baseado no tipo de usuário
        $agendamentoModel = new Agendamento();
        
        if ($usuario_tipo === 'CLIENTE') {
            // Para clientes, carrega apenas seus agendamentos
            $cliente_id = $_SESSION['cliente_id'] ?? null;
            if (empty($cliente_id) && isset($_SESSION['usuario_id'])) {
                require_once __DIR__ . '/../Models/Cliente.php';
                $clienteModel = new Cliente();
                if ($clienteModel->carregarClientePorUsuarioId($_SESSION['usuario_id'])) {
                    $cliente_id = $clienteModel->getId();
                    $_SESSION['cliente_id'] = $cliente_id;
                }
            }
            $lista_agendamentos = $agendamentoModel->listarAgendamentosCliente($cliente_id);
            
            // Inclui a view do Cliente/Admin (mesmo arquivo)
            include_once __DIR__ . '/../Views/Admin/gerenciar_agendamento.php';
            
        } elseif ($usuario_tipo === 'PROFISSIONAL') {
            // Para profissionais, carrega os agendamentos deles
            $profissional_id = $_SESSION['profissional_id'] ?? $_SESSION['usuario_id'] ?? null;
            $lista_agendamentos = $agendamentoModel->listarAgendaPorProfissional($profissional_id);
            
            // Inclui a view do Profissional
            include_once __DIR__ . '/../Views/Profissional/gerenciar_agendamento.php';
            
        } elseif ($usuario_tipo === 'ADMIN') {
            // Para admin, carrega todos os agendamentos
            $lista_agendamentos = $agendamentoModel->listarTodos();
            
            // Inclui a view do Admin
            include_once __DIR__ . '/../Views/Admin/gerenciar_agendamento.php';
        } else {
            // Tipo de usuário desconhecido
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }
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

    /**
     * Método para exibir o formulário de edição de agendamento
     */
    public function mostrarFormularioEdicao()
    {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: Index.php?acao=login_mostrar');
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
        
        // Busca o agendamento
        $agendamento = $agendamentoModel->buscarPorId($id);
        
        if (!$agendamento) {
            $_SESSION['erros_agendamento'] = ["Agendamento não encontrado."];
            header("Location: Index.php?acao=gerenciar_agendamento_mostrar");
            exit;
        }
        
        // Verifica se o usuário pode editar este agendamento
        $usuario_tipo = $_SESSION['usuario_tipo'] ?? '';
        if ($usuario_tipo === 'CLIENTE') {
            $cliente_id = $_SESSION['cliente_id'] ?? null;
            if ($agendamento->cliente_id != $cliente_id) {
                $_SESSION['erros_agendamento'] = ["Você não tem permissão para editar este agendamento."];
                header("Location: Index.php?acao=gerenciar_agendamento_mostrar");
                exit;
            }
        }
        
        // Carrega lista de serviços e profissionais
        $servicoModel = new Servico();
        $lista_servicos = $servicoModel->listarTodos();
        
        $funcModel = new Funcionario();
        $lista_profissionais = $funcModel->listarTodosProfissionais();
        
        // Inclui a View de edição
        include_once __DIR__ . '/../Views/Cliente/editar_agendamento.php';
    }
    
    /**
     * Método para processar a atualização de um agendamento
     */
    public function atualizar()
    {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }
        
        $erros = [];
        
        // Validação dos dados de entrada
        if (empty($_POST['id'])) {
            $erros[] = "ID do agendamento não informado.";
        }
        if (empty($_POST['profissional_id'])) {
            $erros[] = "Você deve selecionar um profissional.";
        }
        if (empty($_POST['dataHora'])) {
            $erros[] = "Você deve selecionar uma data e hora.";
        } else {
            // Validação de formato de data/hora
            $dataHora = $_POST['dataHora'];
            $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $dataHora);
            if (!$dateTime || $dateTime->format('Y-m-d\TH:i') !== $dataHora) {
                $erros[] = "Formato de data/hora inválido.";
            } else {
                // Validação de data no futuro
                $now = new DateTime();
                if ($dateTime < $now) {
                    $erros[] = "A data e hora devem ser no futuro.";
                }
                
                // Validação de horário de expediente (8h às 16h)
                $hora = (int)$dateTime->format('H');
                if ($hora < 8 || $hora >= 16) {
                    $erros[] = "O horário deve estar entre 8:00 e 16:00.";
                }
            }
        }
        if (empty($_POST['servico_id'])) {
            $erros[] = "Você deve selecionar um serviço.";
        }
        
        // Se houver erros, armazena na sessão e redireciona de volta
        if (!empty($erros)) {
            $_SESSION['erros_agendamento'] = $erros;
            header('Location: Index.php?acao=agendamento_editar&id=' . ($_POST['id'] ?? ''));
            exit;
        }
        
        try {
            $id = (int)$_POST['id'];
            $agendamentoModel = new Agendamento();
            
            // Busca o agendamento para verificar permissões
            $agendamento = $agendamentoModel->buscarPorId($id);
            
            if (!$agendamento) {
                $_SESSION['erros_agendamento'] = ["Agendamento não encontrado."];
                header("Location: Index.php?acao=gerenciar_agendamento_mostrar");
                exit;
            }
            
            // Verifica permissões
            $usuario_tipo = $_SESSION['usuario_tipo'] ?? '';
            if ($usuario_tipo === 'CLIENTE') {
                $cliente_id = $_SESSION['cliente_id'] ?? null;
                if ($agendamento->cliente_id != $cliente_id) {
                    $_SESSION['erros_agendamento'] = ["Você não tem permissão para editar este agendamento."];
                    header("Location: Index.php?acao=gerenciar_agendamento_mostrar");
                    exit;
                }
            }
            
            // Atualiza o agendamento
            $profissional_id = (int)$_POST['profissional_id'];
            $data_hora = $_POST['dataHora'];
            $servico_id = [(int)$_POST['servico_id']]; // Array com um único serviço
            
            if ($agendamentoModel->atualizarBD($id, $profissional_id, $data_hora, $servico_id)) {
                $_SESSION['sucesso_agendamento'] = "Agendamento atualizado com sucesso!";
                header('Location: Index.php?acao=gerenciar_agendamento_mostrar');
                exit;
            } else {
                $_SESSION['erros_agendamento'] = ["Erro ao atualizar o agendamento no banco de dados."];
                header('Location: Index.php?acao=agendamento_editar&id=' . $id);
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['erros_agendamento'] = ["Erro inesperado no servidor: " . $e->getMessage()];
            header('Location: Index.php?acao=agendamento_editar&id=' . ($_POST['id'] ?? ''));
            exit;
        }
    }
    
    /**
     * Exibe a página de agendamento para recepcionista (com seleção de cliente)
     */
    public function mostrarAgendamentoRecepcionista()
    {
        // Requer login de Recepcionista
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'RECEPCIONISTA') {
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
        
        // 3. Carrega lista de clientes para o dropdown
        require_once __DIR__ . '/../Models/Cliente.php';
        $clienteModel = new Cliente();
        $lista_clientes = $clienteModel->listarTodos();
        
        // --- Inclui a View ---
        include_once __DIR__ . '/../Views/Recepcionista/agendamento.php';
    }
    
    /**
     * Salva agendamento criado pela recepcionista em nome de um cliente
     */
    public function salvarAgendamentoRecepcionista()
    {
        // Requer login de Recepcionista
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'RECEPCIONISTA') {
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }

        $erros = []; // Array para armazenar os erros de validação

        // 1. Validação dos dados de entrada (POST)
        if (empty($_POST['cliente_id'])) {
            $erros[] = "Você deve selecionar um cliente.";
        }
        if (empty($_POST['profissional_id'])) {
            $erros[] = "Você deve selecionar um profissional.";
        }
        if (empty($_POST['dataHora'])) {
            $erros[] = "Você deve selecionar uma data e hora.";
        }
        if (empty($_POST['servico_id'])) {
            $erros[] = "Você deve selecionar um serviço.";
        }

        // 2. Se houver erros, armazena na sessão e redireciona de volta
        if (!empty($erros)) {
            $_SESSION['erros_agendamento'] = $erros;
            header('Location: Index.php?acao=agendamento_recepcionista_mostrar');
            exit;
        }

        // 3. Se a validação passou, continua com o processo
        try {
            $agendamento = new Agendamento();
            
            // Define os dados do agendamento usando o cliente_id do formulário
            $agendamento->setClienteId((int)$_POST['cliente_id']);
            $agendamento->setProfissionalId((int)$_POST['profissional_id']);
            $agendamento->setDataHora($_POST['dataHora']);
            
            // Adiciona o serviço único
            $agendamento->addServico((int)$_POST['servico_id']);

            // Salva no banco
            if ($agendamento->inserirBD()) {
                $_SESSION['sucesso_agendamento'] = "Agendamento realizado com sucesso!";
                header('Location: Index.php?acao=agendamento_recepcionista_mostrar');
                exit;
            } else {
                $_SESSION['erros_agendamento'] = ["Erro ao salvar o agendamento no banco de dados."];
                header('Location: Index.php?acao=agendamento_recepcionista_mostrar');
                exit;
            }
        } catch (Exception $e) {
            // Captura exceções (ex: erro de conexão ou SQL)
            $_SESSION['erros_agendamento'] = ["Erro inesperado no servidor: " . $e->getMessage()];
            header('Location: Index.php?acao=agendamento_recepcionista_mostrar');
            exit;
        }
    }
    
    /**
     * Mostra o histórico de serviços de um cliente específico para o profissional
     */
    public function mostrarHistoricoCliente()
    {
        // Requer login de Profissional
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'PROFISSIONAL') {
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }
        
        // Carrega lista de clientes para o dropdown
        require_once __DIR__ . '/../Models/Cliente.php';
        $clienteModel = new Cliente();
        $lista_clientes = $clienteModel->listarTodos();
        
        // Verifica se foi selecionado um cliente
        $cliente_selecionado = $_GET['cliente_id'] ?? null;
        $historico = [];
        $cliente_nome = '';
        
        if ($cliente_selecionado) {
            $agendamentoModel = new Agendamento();
            $historico = $agendamentoModel->listarHistoricoCliente((int)$cliente_selecionado);
            
            // Busca o nome do cliente selecionado
            foreach ($lista_clientes as $cliente) {
                if ($cliente->id == $cliente_selecionado) {
                    $cliente_nome = $cliente->nome;
                    break;
                }
            }
        }
        
        // Prepara dados para a View
        $dados = [
            'lista_clientes' => $lista_clientes,
            'cliente_selecionado' => $cliente_selecionado,
            'cliente_nome' => $cliente_nome,
            'historico' => $historico
        ];
        
        // Inclui a View
        include_once __DIR__ . '/../Views/Profissional/historico_cliente.php';
    }
    
    /**
     * Mostra o histórico de serviços do cliente logado (Visão do Cliente)
     */
    public function mostrarHistoricoClienteLogado()
    {
        // Requer login de Cliente
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'CLIENTE') {
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }
        
        $agendamentoModel = new Agendamento();
        $cliente_id = $_SESSION['cliente_id'] ?? null;
        
        // Busca o histórico de serviços concluídos do cliente
        $historico = [];
        if ($cliente_id) {
            $historico = $agendamentoModel->listarHistoricoCliente((int)$cliente_id);
        }
        
        // Prepara dados para a View
        $dados = [
            'historico' => $historico,
            'usuario_nome' => $_SESSION['usuario_nome'] ?? 'Cliente'
        ];
        
        // Inclui a View
        require_once __DIR__ . '/../Views/Cliente/historico.php';
    }
    
    /**
     * Mostra a agenda completa do salão para a Recepcionista
     * Permite visualizar agendamentos de todos os profissionais
     */
    public function mostrarAgendaRecepcionista()
    {
        // Requer login de Recepcionista
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'RECEPCIONISTA') {
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }
        
        $agendamentoModel = new Agendamento();
        
        // Usa o método listarTodos() para obter todos os agendamentos
        $lista_agenda_completa = $agendamentoModel->listarTodos();
        
        // Prepara dados para a View
        $dados = [
            'lista_agenda_completa' => $lista_agenda_completa,
            'usuario_nome' => $_SESSION['usuario_nome'] ?? 'Recepcionista'
        ];
        
        // Inclui a View
        require_once __DIR__ . '/../Views/Recepcionista/agenda_completa.php';
    }
}
?>