<?php
// Inicia a sessão para todas as requisições
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui os controllers principais
require_once 'Controllers/UsuarioController.php';
require_once 'Controllers/AgendamentoController.php';
require_once 'Controllers/ServicoController.php';

// Instancia os controllers
$userController = new UsuarioController();
$agendamentoController = new AgendamentoController();
$servicoController = new ServicoController(); // Instanciado

// Define a ação padrão (mostrar login) se nenhuma for especificada
$acao = $_GET['acao'] ?? 'login_mostrar';

// Estrutura switch para gerenciar as ações (Roteamento)
switch ($acao) {

    // --- FLUXO DE AUTENTICAÇÃO E CADASTRO ---
    case 'login_mostrar':
        $userController->mostrarLogin();
        break;

    case 'autenticar': // Ação do formulário de login
        $userController->autenticar();
        break;

    case 'cadastro_mostrar':
        $userController->mostrarCadastro();
        break;
    
    case 'salvar_cliente': // Ação do formulário de cadastro
        $userController->salvarCadastroCliente();
        break;

    case 'logout':
        $userController->logout();
        break;

    // --- FLUXO PRINCIPAL (APÓS LOGIN) ---
    case 'inicio':
        $userController->direcionarDashboard(); // Direciona para o dashboard correto
        break;
    
    // --- FLUXO DE PERFIL DO USUÁRIO ---
    case 'perfil_mostrar':
        $userController->mostrarPerfil();
        break;
    
    case 'perfil_atualizar':
        $userController->atualizarPerfil();
        break;
    
    // --- FLUXO DE AGENDAMENTO (CLIENTE) ---
    case 'agendamento_criar':
        $agendamentoController->index(); // (Visão do Cliente)
        break;
    
    case 'agendamento_salvar':
        $agendamentoController->salvar(); // (Ação do Cliente)
        break;
    
    // --- GERENCIAMENTO DE AGENDAMENTOS (CLIENTE) ---
    case 'gerenciar_agendamento_mostrar':
        $agendamentoController->gerenciarAgendamentos(); // Nova página de gerenciamento
        break;
    
    case 'agendamento_cancelar':
        $agendamentoController->cancelar(); // Cancelar agendamento
        break;
    
    case 'agendamento_excluir':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $agendamentoController->excluir($id); // Excluir agendamento (método legado)
        } else {
            header('Location: Index.php?acao=gerenciar_agendamento_mostrar&status=erro_id');
        }
        break;
        
    // --- FLUXO DE AGENDA (PROFISSIONAL) ---
    case 'agenda_profissional_mostrar':
        $agendamentoController->mostrarAgendaProfissional();
        break;

    // --- LÓGICA DE REDIRECIONAMENTO CORRIGIDA ---
    case 'confirmar':
        if (isset($_GET['id'])) {
            $idAgendamento = $_GET['id'];
            $idStatusConfirmado = 2; // ID 'CONCLUIDO'
            
            if ($agendamentoController->mudarStatusAgendamento($idAgendamento, $idStatusConfirmado)) {
                // ANTES: header('Location: ../index.php?acao=agenda_profissional_mostrar&status=confirmado');
                header('Location: Index.php?acao=agenda_profissional_mostrar&status=confirmado'); // DEPOIS
            } else {
                // ANTES: header('Location: ../index.php?acao=agenda_profissional_mostrar&status=erro');
                header('Location: Index.php?acao=agenda_profissional_mostrar&status=erro'); // DEPOIS
            }
        }
        exit; // Adicionado exit

    // --- LÓGICA DE REDIRECIONAMENTO CORRIGIDA ---
    case 'cancelar':
         if (isset($_GET['id'])) {
            $idAgendamento = $_GET['id'];
            $idStatusCancelado = 3; // ID 'CANCELADO'
            
            if ($agendamentoController->mudarStatusAgendamento($idAgendamento, $idStatusCancelado)) {
                // ANTES: header('Location: ../index.php?acao=agenda_profissional_mostrar&status=cancelado');
                header('Location: Index.php?acao=agenda_profissional_mostrar&status=cancelado'); // DEPOIS
            } else {
                // ANTES: header('Location: ../index.php?acao=agenda_profissional_mostrar&status=erro');
                header('Location: Index.php?acao=agenda_profissional_mostrar&status=erro'); // DEPOIS
            }
        }
        exit; // Adicionado exit
        
    // --- ROTAS DE ADMIN (SERVIÇOS) ---
    // (Estas já estavam corretas)
    case 'inicio_admin': // Rota adicionada para o dashboard de admin
        $userController->direcionarDashboard();
        break;
        
    case 'servico_listar':
        $servicoController->listar();
        break;

    case 'servico_formulario_cadastrar':
        $servicoController->formularioCadastrar();
        break;

    case 'servico_cadastrar':
        $servicoController->cadastrar();
        break;

    case 'servico_formulario_editar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $servicoController->formularioEditar($id);
        } else {
            header('Location: Index.php?acao=servico_listar&status=erro_id');
        }
        break;

    case 'servico_editar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $servicoController->editar($id);
        } else {
            header('Location: Index.php?acao=servico_listar&status=erro_id');
        }
        break;
        
    case 'servico_excluir':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $servicoController->excluir($id);
        } else {
            header('Location: Index.php?acao=servico_listar&status=erro_id');
        }
        break;

    // --- MELHORIA: ROTAS FALTANTES DO PAINEL ADMIN ---
    case 'funcionario_listar':
        // TODO: Criar o Controller e o Model para listar funcionários
        // Por enquanto, vamos carregar uma view de placeholder:
        echo "Página 'Gerenciar Profissionais' ainda não implementada.";
        // require_once __DIR__ . '/../Views/funcionario_listar.php';
        break;
        
    case 'cliente_listar':
        // TODO: Criar o Controller e o Model para listar clientes
        // Por enquanto, vamos carregar uma view de placeholder:
         echo "Página 'Gerenciar Clientes' ainda não implementada.";
        // require_once __DIR__ . '/../Views/cliente_listar.php';
        break;

    // Ação padrão
    default:
        $userController->mostrarLogin();
        break;
}
?>