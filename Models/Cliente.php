<?php

require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/ConexaoDB.php';

class Cliente extends Usuario 
{
    private $id; // ID da tabela Cliente
    private $usuario_id; // FK para a tabela Usuario
    
    // ANTES: O construtor e os getters/setters para nome, email, senha foram removidos
    // Eles agora são herdados automaticamente da classe Usuario

    // --- Métodos de Banco de Dados ---

    public function inserirBD(PDO $pdo)
    {
        // require_once __DIR__ . '/ConexaoDB.php'; // Movido para o topo
        try {
            $pdo->beginTransaction();

            // 1. Insere o Usuário base (agora usando os métodos herdados)
            // ANTES: $this->usuario_id = $this->usuario->inserirBD($pdo);
            $this->usuario_id = parent::inserirBD($pdo); // DEPOIS
            
            if (!$this->usuario_id) {
                throw new Exception('Falha ao criar usuário');
            }

            // 2. Insere o Cliente linkando o usuario_id
            $sql = "INSERT INTO Cliente (usuario_id) VALUES (:usuario_id)";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':usuario_id' => $this->usuario_id
            ]);
            
            $this->id = $pdo->lastInsertId(); // Salva o ID da tabela Cliente

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            error_log("Error in Cliente::inserirBD: " . $e->getMessage());
            // Lança a exceção para o Controller capturar
            throw $e;
        }
    }
    
    // Carrega um cliente pelo ID do usuário
    public function carregarClientePorUsuarioId($usuario_id)
    {
        // require_once __DIR__ . '/ConexaoDB.php'; // Movido para o topo
        try {
            $pdo = ConexaoDB::getConnection();

            $sql = "SELECT * FROM Cliente WHERE usuario_id = :usuario_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':usuario_id' => $usuario_id]);

            $row = $stmt->fetch(PDO::FETCH_OBJ);

            if ($row) {
                $this->id = $row->id;
                $this->usuario_id = $row->usuario_id;
                
                // Carrega os dados do usuário (nome, email, etc.) na classe pai
                parent::carregarUsuarioPorId($this->usuario_id);
                
                return true;
            }
            
            return false;

        } catch (Exception $e) {
            error_log("Error in Cliente::carregarClientePorUsuarioId: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lista todos os clientes cadastrados com dados de usuário
     * @return array Lista de clientes completos
     */
    public function listarTodos()
    {
        try {
            $pdo = ConexaoDB::getConnection();
            $lista = [];

            $sql = "SELECT c.id, c.usuario_id,
                           u.nome, u.email, u.telefone,
                           (SELECT COUNT(*) FROM Agendamento WHERE cliente_id = c.id) as total_agendamentos,
                           (SELECT MAX(data_hora) FROM Agendamento WHERE cliente_id = c.id) as ultimo_agendamento
                    FROM Cliente c
                    JOIN Usuario u ON c.usuario_id = u.id
                    ORDER BY u.nome";
                    
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $lista[] = $row;
            }

            return $lista;

        } catch (Exception $e) {
            error_log("Error in Cliente::listarTodos: " . $e->getMessage());
            return [];
        }
    }
    
    public function getId() { 
        return $this->id; 
    }
}
?>