<?php

require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/ConexaoDB.php'; 

class Funcionario extends Usuario
{
    private $id;
    private $usuario_id;
    private $cargo;
    private $especialidade;
    private $matricula;
    private $usuario; // Objeto do tipo Usuario

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function getId() { return $this->id; }
    public function getUsuarioId() { return $this->usuario_id; }

    // --- Getters e Setters ---
    public function setCargo($cargo) { $this->cargo = $cargo; }
    public function getCargo() { return $this->cargo ?? null; }
    
    public function setEspecialidade($especialidade) { $this->especialidade = $especialidade; }
    public function getEspecialidade() { return $this->especialidade ?? null; }
    
    public function setMatricula($matricula) { $this->matricula = $matricula; }
    public function getMatricula() { return $this->matricula ?? null; }
    
    // Métodos do objeto Usuario encapsulado
    public function setNome($nome) { $this->usuario->setNome($nome); }
    public function getNome() { return $this->usuario->getNome(); }
    
    public function setEmail($email) { $this->usuario->setEmail($email); }
    public function getEmail() { return $this->usuario->getEmail(); }
    
    public function setSenha($senha) { $this->usuario->setSenha($senha); }
    
    public function setTelefone($telefone) { $this->usuario->setTelefone($telefone); }
    public function getTelefone() { return $this->usuario->getTelefone(); }
    
    public function getUsuario() { return $this->usuario; }

    
    // --- Métodos de Banco de Dados ---

    /**
     * Insere o Usuário e depois o Funcionario
     * @return bool Retorna true se a inserção foi bem sucedida, false caso contrário
     */
    public function inserirBD(PDO $pdo)
    {
        try {
            $pdo->beginTransaction();

            // 1. Insere o Usuário base
            $this->usuario_id = $this->usuario->inserirBD($pdo);
            
            if (!$this->usuario_id) {
                throw new Exception('Falha ao criar usuário');
            }

            // 2. Insere o Funcionario linkando o usuario_id
            $sql = "INSERT INTO Funcionario (usuario_id, cargo, especialidade, matricula) 
                    VALUES (:usuario_id, :cargo, :especialidade, :matricula)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':usuario_id' => $this->usuario_id,
                ':cargo' => $this->cargo,
                ':especialidade' => $this->especialidade,
                ':matricula' => $this->matricula
            ]);

            $this->id = $pdo->lastInsertId();
            
            $pdo->commit();
            return true;

        } catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            error_log("Error in Funcionario::inserirBD: " . $e->getMessage());
            throw $e; // Re-lança para o controller tratar
        }
    }

    /**
     * Carrega dados do funcionário baseado no ID do usuário
     * @param int $usuario_id ID do usuário associado
     * @return bool true se encontrou o funcionário, false caso contrário
     */
    public function carregarFuncionarioPorUsuarioId($usuario_id)
    {

        try {

            $pdo = ConexaoDB::getConnection(); 

            $sql = "SELECT * FROM Funcionario WHERE usuario_id = :usuario_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':usuario_id' => $usuario_id]);

            $row = $stmt->fetch(PDO::FETCH_OBJ);

            if ($row) {
                $this->id = $row->id;
                $this->usuario_id = $row->usuario_id;
                $this->cargo = $row->cargo;
                return true;
            }
            
            return false;

        } catch (Exception $e) {
            error_log("Error in Funcionario::carregarFuncionarioPorUsuarioId: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Carrega dados do funcionário baseado no ID do funcionário
     * @param int $id ID do funcionário
     * @return bool true se encontrou o funcionário, false caso contrário
     */
    public function carregarFuncionarioPorId($id)
    {
        try {
            $pdo = ConexaoDB::getConnection();

            $sql = "SELECT f.*, u.nome, u.email, u.telefone
                    FROM Funcionario f
                    JOIN Usuario u ON f.usuario_id = u.id
                    WHERE f.id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            $row = $stmt->fetch(PDO::FETCH_OBJ);

            if ($row) {
                $this->id = $row->id;
                $this->usuario_id = $row->usuario_id;
                $this->cargo = $row->cargo;
                $this->especialidade = $row->especialidade ?? null;
                $this->matricula = $row->matricula ?? null;
                
                // Carrega os dados do usuário
                $this->usuario->carregarUsuarioPorId($row->usuario_id);
                
                return true;
            }
            
            return false;

        } catch (Exception $e) {
            error_log("Error in Funcionario::carregarFuncionarioPorId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todos os funcionários cadastrados
     * @return array Lista de funcionários completos
     */
    public function listarTodos()
    {
        try {
            $pdo = ConexaoDB::getConnection();
            $lista = [];

            $sql = "SELECT f.id, f.cargo, f.especialidade, f.matricula,
                           u.nome, u.email, u.telefone
                    FROM Funcionario f
                    JOIN Usuario u ON f.usuario_id = u.id
                    ORDER BY u.nome";
                    
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $lista[] = $row;
            }

            return $lista;

        } catch (Exception $e) {
            error_log("Error in Funcionario::listarTodos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lista todos os profissionais cadastrados (compatibilidade)
     * @return array Lista de profissionais com id e nome
     */
    public function listarTodosProfissionais()
    {
        try {
            $pdo = ConexaoDB::getConnection(); 
            $lista = [];

            $sql = "SELECT f.id, u.nome 
                    FROM Funcionario f 
                    JOIN Usuario u ON f.usuario_id = u.id
                    ORDER BY u.nome";
                    
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $lista[] = $row;
            }

            return $lista;

        } catch (Exception $e) {
            error_log("Error in Funcionario::listarTodosProfissionais: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualiza os dados do funcionário e usuário
     * @return bool true se atualizou, false caso contrário
     */
    public function atualizarBD()
    {
        try {
            $pdo = ConexaoDB::getConnection();
            $pdo->beginTransaction();

            // 1. Atualiza o usuário base
            if (!$this->usuario->atualizarBD()) {
                throw new Exception('Falha ao atualizar usuário');
            }

            // 2. Atualiza os dados do funcionário
            $sql = "UPDATE Funcionario 
                    SET cargo = :cargo,
                        especialidade = :especialidade,
                        matricula = :matricula
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                ':cargo' => $this->cargo,
                ':especialidade' => $this->especialidade,
                ':matricula' => $this->matricula,
                ':id' => $this->id
            ]);

            $pdo->commit();
            return $result;

        } catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            error_log("Error in Funcionario::atualizarBD: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui o funcionário (e opcionalmente o usuário)
     * @param bool $excluirUsuario Se true, exclui também o usuário base
     * @return bool true se excluiu, false caso contrário
     */
    public function excluirBD($excluirUsuario = false)
    {
        try {
            $pdo = ConexaoDB::getConnection();
            $pdo->beginTransaction();

            // 1. Exclui o funcionário
            $sql = "DELETE FROM Funcionario WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $this->id]);

            // 2. Opcionalmente exclui o usuário base
            if ($excluirUsuario && $this->usuario_id) {
                $sqlUsuario = "DELETE FROM Usuario WHERE id = :id";
                $stmtUsuario = $pdo->prepare($sqlUsuario);
                $stmtUsuario->execute([':id' => $this->usuario_id]);
            }

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            error_log("Error in Funcionario::excluirBD: " . $e->getMessage());
            return false;
        }
    }
}
?>