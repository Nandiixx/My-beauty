<?php
// Inicia a sessão se já não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui os Modelos necessários
require_once __DIR__ . '/../Models/Cliente.php';
require_once __DIR__ . '/../Models/ConexaoDB.php';

class ClienteController
{
    /**
     * Lista todos os clientes cadastrados.
     * Apenas ADMIN (PROPRIETARIO, GERENTE_FINANCEIRO) ou RECEPCIONISTA pode acessar.
     */
    public function listar()
    {
        // Verifica autenticação e autorização
        if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_cargo'] ?? '', ['PROPRIETARIO', 'GERENTE_FINANCEIRO', 'RECEPCIONISTA'])) {
            $_SESSION['erro'] = "Acesso negado. Apenas administradores ou recepcionistas podem gerenciar clientes.";
            header("Location: Index.php?acao=login_mostrar");
            exit;
        }
        
        try {
            $cliente = new Cliente();
            $lista_clientes = $cliente->listarTodos();
            
            // Inclui a View
            require_once __DIR__ . '/../Views/Admin/Cliente/cliente_listar.php';
        } catch (Exception $e) {
            $_SESSION['erro'] = "Erro ao carregar lista de clientes: " . $e->getMessage();
            header("Location: Index.php?acao=inicio_admin");
            exit;
        }
    }
    
    /**
     * Mostra formulário de cadastro de cliente pela recepcionista.
     * Apenas RECEPCIONISTA ou ADMIN pode acessar.
     */
    public function mostrarFormularioCadastroRecepcionista()
    {
        // Verifica autenticação e autorização
        if (!isset($_SESSION['usuario_id']) || 
            !in_array($_SESSION['usuario_cargo'] ?? '', ['PROPRIETARIO', 'GERENTE_FINANCEIRO', 'RECEPCIONISTA'])) {
            $_SESSION['erro'] = "Acesso negado. Apenas administradores ou recepcionistas podem cadastrar clientes.";
            header("Location: Index.php?acao=login_mostrar");
            exit;
        }
        
        // Carrega a view de cadastro
        require_once __DIR__ . '/../Views/Recepcionista/cliente_cadastro.php';
    }
    
    /**
     * Salva o cadastro de cliente criado pela recepcionista.
     * Apenas RECEPCIONISTA ou ADMIN pode acessar.
     */
    public function salvarCadastroRecepcionista()
    {
        // Verifica autenticação e autorização
        if (!isset($_SESSION['usuario_id']) || 
            !in_array($_SESSION['usuario_cargo'] ?? '', ['PROPRIETARIO', 'GERENTE_FINANCEIRO', 'RECEPCIONISTA'])) {
            $_SESSION['erro'] = "Acesso negado. Apenas administradores ou recepcionistas podem cadastrar clientes.";
            header("Location: Index.php?acao=login_mostrar");
            exit;
        }
        
        try {
            // Validação dos dados recebidos
            $erros = [];
            
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');
            
            if (empty($nome)) {
                $erros[] = "Nome é obrigatório.";
            }
            
            if (empty($email)) {
                $erros[] = "E-mail é obrigatório.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erros[] = "E-mail inválido.";
            }
            
            if (empty($telefone)) {
                $erros[] = "Telefone é obrigatório.";
            }
            
            // Se houver erros, redireciona de volta ao formulário
            if (!empty($erros)) {
                $_SESSION['erros_cadastro'] = $erros;
                $_SESSION['dados_form'] = $_POST;
                header("Location: Index.php?acao=recepcionista_cliente_cadastro_mostrar");
                exit;
            }
            
            // Cria novo cliente com senha temporária
            $cliente = new Cliente();
            $cliente->setNome($nome);
            $cliente->setEmail($email);
            $cliente->setTelefone($telefone);
            $cliente->setSenha('mudar123'); // Senha temporária padrão
            
            // Insere no banco de dados
            $pdo = ConexaoDB::getConnection();
            $cliente->inserirBD($pdo);
            
            // Redireciona com mensagem de sucesso
            $_SESSION['sucesso'] = "Cliente cadastrado com sucesso! Senha temporária: mudar123";
            header("Location: Index.php?acao=cliente_listar");
            exit;
            
        } catch (Exception $e) {
            $_SESSION['erro'] = "Erro ao cadastrar cliente: " . $e->getMessage();
            header("Location: Index.php?acao=recepcionista_cliente_cadastro_mostrar");
            exit;
        }
    }
}
?>
