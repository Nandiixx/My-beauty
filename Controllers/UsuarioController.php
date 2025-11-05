<?php
// Inicia a sessão se já não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui os Modelos necessários
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Cliente.php';
require_once __DIR__ . '/../Models/Funcionario.php';
require_once __DIR__ . '/../Models/ConexaoDB.php';

class UsuarioController
{
    /**
     * Exibe a página de login.
     */
    public function mostrarLogin()
    {
        // Verifica se há uma mensagem de sucesso (ex: vindo do cadastro)
        $sucesso = $_GET['status'] ?? null;
        // Verifica se há uma mensagem de erro (vindo da autenticação)
        $erro = $_GET['erro'] ?? null;
        
        require_once __DIR__ . '/../Views/login.php';
    }

    /**
     * Exibe a página de cadastro.
     */
    public function mostrarCadastro()
    {
        require_once __DIR__ . '/../Views/cadastrar.php';
    }

    /**
     * Processa o formulário de cadastro de cliente.
     */
    public function salvarCadastroCliente()
    {
        $erro = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha']) ||
                empty($_POST['confirma_senha']) || empty($_POST['telefone'])) {
                $erro = "Todos os campos obrigatórios devem ser preenchidos.";
            } else if ($_POST['senha'] !== $_POST['confirma_senha']) {
                $erro = "As senhas não coincidem.";
            } else if (strlen($_POST['senha']) < 6) {
                $erro = "A senha deve ter pelo menos 6 caracteres.";
            } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $erro = "E-mail inválido.";
            } else {
                try {
                    $pdo = ConexaoDB::getConnection();
                    
                    $cliente = new Cliente();
                    $cliente->setNome($_POST['nome']);
                    $cliente->setEmail($_POST['email']);
                    $cliente->setSenha($_POST['senha']); 
                    $cliente->setTelefone($_POST['telefone']);

                    if ($cliente->inserirBD($pdo)) {
                        // Redireciona para o login com mensagem de sucesso
                        header("Location: Index.php?acao=login_mostrar&status=sucesso");
                        exit;
                    } else {
                        $erro = "Erro ao cadastrar. Por favor, tente novamente.";
                    }
                } catch (Exception $e) {
                    // Verifica se é erro de chave duplicada (email ou telefone)
                    if (str_contains($e->getMessage(), 'Duplicate entry') || (method_exists($e, 'getCode') && $e->getCode() == 23000)) {
                        $erro = "Este e-mail ou telefone já está cadastrado.";
                    } else {
                        $erro = "Erro no servidor: " . $e->getMessage();
                    }
                }
            }
        }
        
        // Se houver erro ou for o primeiro acesso, exibe a view de cadastro
        require_once __DIR__ . '/../Views/cadastrar.php';
    }

    /**
     * Autentica o usuário e redireciona.
     */
    public function autenticar()
    {
        $email = $_POST['email'] ?? null;
        $senha = $_POST['senha'] ?? null;

        if (empty($email) || empty($senha)) {
            header('Location: Index.php?acao=login_mostrar&erro=1'); 
            exit;
        }

        $usuario = new Usuario();

        // 1. Verifica se o e-mail e a senha do usuário base estão corretos
        if ($usuario->carregarUsuarioPorEmail($email) && $usuario->verificarSenha($senha)) {
            
            // 2. Usuário base autenticado. Salva dados básicos.
            $_SESSION['usuario_id'] = $usuario->getId();
            $_SESSION['usuario_nome'] = $usuario->getNome();

            // 3. Verifica se é um Funcionário (Admin ou Profissional)
            $funcionario = new Funcionario();
            if ($funcionario->carregarFuncionarioPorUsuarioId($usuario->getId())) {
                
                $cargo = $funcionario->getCargo();
                $_SESSION['funcionario_id'] = $funcionario->getId();
                $_SESSION['usuario_cargo'] = $cargo; // Salva o cargo (ex: 'PROFISSIONAL_BELEZA')

                // Diferencia Admin de Profissional
                if (in_array($cargo, ['PROPRIETARIO', 'GERENTE_FINANCEIRO'])) {
                    $_SESSION['usuario_tipo'] = 'ADMIN';
                    header('Location: Index.php?acao=inicio_admin'); 
                    exit;
                } else {
                    $_SESSION['usuario_tipo'] = 'PROFISSIONAL';
                    header('Location: Index.php?acao=inicio'); 
                    exit;
                }
            }

            // 4. Se não for funcionário, verifica se é um Cliente
            $cliente = new Cliente();
            if ($cliente->carregarClientePorUsuarioId($usuario->getId())) {
                $_SESSION['usuario_tipo'] = 'CLIENTE';
                $_SESSION['cliente_id'] = $cliente->getId();
                $_SESSION['usuario_cargo'] = 'CLIENTE'; // Define um cargo padrão
                header('Location: Index.php?acao=inicio'); 
                exit;
            }

            // 5. Se for um usuário sem tipo (nem cliente, nem funcionário), desloga e dá erro
            $this->logout(); // A logout já redireciona
            exit;

        } else {
            header('Location: Index.php?acao=login_mostrar&erro=1'); 
            exit;
        }
    }

    /**
     * Direciona o usuário para o dashboard correto com base no tipo e cargo.
     */
    public function direcionarDashboard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            $this->mostrarLogin();
            return;
        }

        $tipo = $_SESSION['usuario_tipo'] ?? null;
        $cargo = $_SESSION['usuario_cargo'] ?? null;
        
        if ($tipo == 'ADMIN') {
            // Se for admin, carrega a view de admin
            require_once __DIR__ . '/../Views/inicio_admi.php';
                
        } else if ($tipo == 'PROFISSIONAL') {
            // Se for profissional, carrega a view de profissional
            require_once __DIR__ . '/../Views/inicio_profissional.php';
            
        } elseif ($tipo == 'CLIENTE') {
            // Se for cliente, carrega a view de cliente
            require_once __DIR__ . '/../Views/inicio_cliente.php';
            
        } else {
            // Se o tipo for desconhecido, força o logout por segurança
            $this->logout();
        }
    }

    /**
     * Faz logout do usuário.
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: Index.php?acao=login_mostrar'); 
        exit;
    }
    
    /**
     * Verifica se o usuário está logado.
     */
    public function checkLogin()
    {
        return (isset($_SESSION['usuario_id']));
    }
    
    /**
     * Exibe a página de perfil do usuário.
     */
    public function mostrarPerfil()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }
        
        // Carrega os dados atuais do usuário
        $usuario = new Usuario();
        if ($usuario->carregarUsuarioPorId($_SESSION['usuario_id'])) {
            require_once __DIR__ . '/../Views/meu_perfil.php';
        } else {
            $_SESSION['erro_perfil'] = "Erro ao carregar dados do usuário.";
            header('Location: Index.php?acao=inicio');
            exit;
        }
    }
    
    /**
     * Atualiza os dados do perfil do usuário.
     */
    public function atualizarPerfil()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: Index.php?acao=login_mostrar');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $erros = [];
            
            // Validação dos campos
            if (empty($_POST['nome'])) {
                $erros[] = "O nome é obrigatório.";
            }
            
            if (empty($_POST['email'])) {
                $erros[] = "O e-mail é obrigatório.";
            } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $erros[] = "E-mail inválido.";
            }
            
            if (!empty($_POST['senha_nova'])) {
                if (empty($_POST['senha_atual'])) {
                    $erros[] = "Para alterar a senha, informe a senha atual.";
                } else if ($_POST['senha_nova'] !== $_POST['confirma_senha']) {
                    $erros[] = "A nova senha e a confirmação não coincidem.";
                } else if (strlen($_POST['senha_nova']) < 6) {
                    $erros[] = "A nova senha deve ter pelo menos 6 caracteres.";
                }
            }
            
            if (empty($erros)) {
                try {
                    $usuario = new Usuario();
                    $usuario->carregarUsuarioPorId($_SESSION['usuario_id']);
                    
                    // Se está tentando alterar a senha, valida a senha atual
                    if (!empty($_POST['senha_nova'])) {
                        if (!$usuario->verificarSenha($_POST['senha_atual'])) {
                            $erros[] = "Senha atual incorreta.";
                        } else {
                            // Atualiza a senha
                            $usuario->setSenha($_POST['senha_nova']);
                            
                            // Atualiza manualmente a senha no banco
                            $pdo = ConexaoDB::getConnection();
                            $sql = "UPDATE Usuario SET senha_hash = :senha_hash WHERE id = :id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':senha_hash' => $usuario->getSenhaHash(),
                                ':id' => $_SESSION['usuario_id']
                            ]);
                        }
                    }
                    
                    if (empty($erros)) {
                        // Atualiza nome e email
                        $usuario->setNome($_POST['nome']);
                        $usuario->setEmail($_POST['email']);
                        
                        if (!empty($_POST['telefone'])) {
                            $usuario->setTelefone($_POST['telefone']);
                        }
                        
                        if ($usuario->atualizarBD()) {
                            // Atualiza o nome na sessão
                            $_SESSION['usuario_nome'] = $_POST['nome'];
                            $_SESSION['sucesso_perfil'] = "Perfil atualizado com sucesso!";
                        } else {
                            $_SESSION['erro_perfil'] = "Nenhuma alteração foi realizada.";
                        }
                        
                        header('Location: Index.php?acao=perfil_mostrar');
                        exit;
                    }
                    
                } catch (Exception $e) {
                    if (str_contains($e->getMessage(), 'Duplicate entry')) {
                        $erros[] = "Este e-mail já está cadastrado.";
                    } else {
                        $erros[] = "Erro ao atualizar perfil: " . $e->getMessage();
                    }
                }
            }
            
            $_SESSION['erros_perfil'] = $erros;
            header('Location: Index.php?acao=perfil_mostrar');
            exit;
        }
    }
}
?>