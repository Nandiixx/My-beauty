<?php
// Inicia a sessão se já não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui os Modelos necessários
require_once __DIR__ . '/../Models/Funcionario.php';
require_once __DIR__ . '/../Models/ConexaoDB.php';

class FuncionarioController
{
    /**
     * Exibe a página de cadastro de funcionário/profissional.
     */
    public function mostrarCadastro()
    {
        $erro = null;
        require_once __DIR__ . '/../Views/Profissional/cadastrarprofissional.php';
    }

    /**
     * Processa o formulário de cadastro de funcionário/profissional.
     */
    public function salvarCadastro()
    {
        $erro = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Validações
            if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha']) || 
                empty($_POST['confirma_senha']) || empty($_POST['matricula']) || empty($_POST['cargo'])) {
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
                    
                    $funcionario = new Funcionario();
                    $funcionario->setNome($_POST['nome']);
                    $funcionario->setEmail($_POST['email']);
                    $funcionario->setSenha($_POST['senha']);
                    $funcionario->setCargo($_POST['cargo']);
                    $funcionario->setMatricula($_POST['matricula']);
                    
                    // Define especialidade se fornecida
                    if (!empty($_POST['especialidade'])) {
                        $funcionario->setEspecialidade($_POST['especialidade']);
                    }
                    
                    // Define telefone se fornecido
                    if (!empty($_POST['telefone'])) {
                        $funcionario->setTelefone($_POST['telefone']);
                    }

                    if ($funcionario->inserirBD($pdo)) {
                        $_SESSION['sucesso'] = "Funcionário cadastrado com sucesso!";
                        header("Location: Index.php?acao=funcionario_listar");
                        exit;
                    } else {
                        $erro = "Erro ao cadastrar profissional. Por favor, tente novamente.";
                    }
                } catch (Exception $e) {
                    if (str_contains($e->getMessage(), 'Duplicate entry')) {
                        $erro = "Este e-mail já está cadastrado.";
                    } else {
                        $erro = "Erro no servidor: " . $e->getMessage();
                    }
                }
            }
        }
        
        // Se houver erro ou for o primeiro acesso, exibe a view de cadastro
        require_once __DIR__ . '/../Views/Profissional/cadastrarprofissional.php';
    }
    
    /**
     * Lista todos os funcionários/profissionais.
     */
    public function listar()
    {
        try {
            $funcionario = new Funcionario();
            $lista_funcionarios = $funcionario->listarTodos();
            
            require_once __DIR__ . '/../Views/Admin/Funcionario/funcionario_listar.php';
        } catch (Exception $e) {
            $_SESSION['erro'] = "Erro ao carregar lista: " . $e->getMessage();
            header("Location: Index.php?acao=inicio_admin");
            exit;
        }
    }
    
    /**
     * Exibe o formulário de edição de funcionário.
     */
    public function mostrarEditar($id)
    {
        try {
            $funcionario = new Funcionario();
            
            if ($funcionario->carregarFuncionarioPorId($id)) {
                require_once __DIR__ . '/../Views/Admin/Funcionario/funcionario_editar.php';
            } else {
                $_SESSION['erro'] = "Funcionário não encontrado.";
                header("Location: Index.php?acao=funcionario_listar");
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['erro'] = "Erro ao carregar funcionário: " . $e->getMessage();
            header("Location: Index.php?acao=funcionario_listar");
            exit;
        }
    }
    
    /**
     * Processa a atualização de funcionário.
     */
    public function atualizar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: Index.php?acao=funcionario_editar_mostrar&id=" . $id);
            exit;
        }
        
        // Validações
        if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['cargo'])) {
            $_SESSION['erro'] = "Todos os campos obrigatórios devem ser preenchidos.";
            header("Location: Index.php?acao=funcionario_editar_mostrar&id=" . $id);
            exit;
        }
        
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erro'] = "E-mail inválido.";
            header("Location: Index.php?acao=funcionario_editar_mostrar&id=" . $id);
            exit;
        }
        
        try {
            $funcionario = new Funcionario();
            
            if ($funcionario->carregarFuncionarioPorId($id)) {
                $funcionario->setNome($_POST['nome']);
                $funcionario->setEmail($_POST['email']);
                $funcionario->setCargo($_POST['cargo']);
                $funcionario->setMatricula($_POST['matricula'] ?? '');
                
                if (!empty($_POST['especialidade'])) {
                    $funcionario->setEspecialidade($_POST['especialidade']);
                }
                
                if (!empty($_POST['telefone'])) {
                    $funcionario->setTelefone($_POST['telefone']);
                }
                
                // Se foi fornecida uma nova senha
                if (!empty($_POST['senha']) && !empty($_POST['confirma_senha'])) {
                    if ($_POST['senha'] !== $_POST['confirma_senha']) {
                        $_SESSION['erro'] = "As senhas não coincidem.";
                        header("Location: Index.php?acao=funcionario_editar_mostrar&id=" . $id);
                        exit;
                    }
                    if (strlen($_POST['senha']) < 6) {
                        $_SESSION['erro'] = "A senha deve ter pelo menos 6 caracteres.";
                        header("Location: Index.php?acao=funcionario_editar_mostrar&id=" . $id);
                        exit;
                    }
                    $funcionario->setSenha($_POST['senha']);
                }
                
                if ($funcionario->atualizarBD()) {
                    $_SESSION['sucesso'] = "Funcionário atualizado com sucesso!";
                    header("Location: Index.php?acao=funcionario_listar");
                    exit;
                } else {
                    $_SESSION['erro'] = "Nenhuma alteração foi realizada.";
                    header("Location: Index.php?acao=funcionario_editar_mostrar&id=" . $id);
                    exit;
                }
            } else {
                $_SESSION['erro'] = "Funcionário não encontrado.";
                header("Location: Index.php?acao=funcionario_listar");
                exit;
            }
        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                $_SESSION['erro'] = "Este e-mail já está cadastrado.";
            } else {
                $_SESSION['erro'] = "Erro ao atualizar: " . $e->getMessage();
            }
            header("Location: Index.php?acao=funcionario_editar_mostrar&id=" . $id);
            exit;
        }
    }
    
    /**
     * Exclui um funcionário.
     */
    public function excluir($id)
    {
        try {
            $funcionario = new Funcionario();
            
            if ($funcionario->carregarFuncionarioPorId($id)) {
                // Exclui o funcionário (mas não o usuário base, caso seja necessário manter o histórico)
                if ($funcionario->excluirBD(false)) {
                    $_SESSION['sucesso'] = "Funcionário excluído com sucesso!";
                } else {
                    $_SESSION['erro'] = "Erro ao excluir funcionário.";
                }
            } else {
                $_SESSION['erro'] = "Funcionário não encontrado.";
            }
        } catch (Exception $e) {
            $_SESSION['erro'] = "Erro ao excluir: " . $e->getMessage();
        }
        
        header("Location: Index.php?acao=funcionario_listar");
        exit;
    }
}
?>
