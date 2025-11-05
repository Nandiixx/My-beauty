<?php
class Usuario
{
    private $id;
    private $nome;
    private $email;
    private $senha; 
    private $senha_hash; 
    private $telefone; 

    // --- Getters e Setters ---
    public function getId() { return $this->id; }
    public function getNome() { return $this->nome ?? null; }
    public function setNome($nome) { $this->nome = $nome; }
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
    
    public function setSenha($senha) {
        $this->senha = $senha;
        $this->senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    }
    public function getSenhaHash() { return $this->senha_hash; }
    
    public function getTelefone() { return $this->telefone; }
    public function setTelefone($telefone) { $this->telefone = $telefone; }
    

    /**
     * Insere o usuário base no banco de dados.
     * Este método é chamado pelo 'inserirBD()' de Cliente ou Funcionario.
     */
    public function inserirBD(PDO $pdo) // Recebe a conexão da transação
    {
        try {
            $sql = "INSERT INTO Usuario (nome, email, senha_hash, telefone) 
                    VALUES (:nome, :email, :senha_hash, :telefone)";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':nome' => $this->nome,
                ':email' => $this->email,
                ':senha_hash' => $this->senha_hash,
                ':telefone' => $this->telefone
            ]);

            // Retorna o ID do usuário recém-criado
            $this->id = $pdo->lastInsertId();
            return $this->id;

        } catch (Exception $e) {
            error_log("Error in Usuario::inserirBD: " . $e->getMessage());
            throw $e; // Lança a exceção para o Cliente.php fazer o rollback
        }
    }
    
    // --- MÉTODO ADICIONADO DA AGENDA 11 [cite: 1449] ---
    /**
     * Atualiza os dados de um usuário no banco de dados.
     * (Não atualiza a senha por padrão)
     */
    public function atualizarBD()
    {
        require_once __DIR__ . '/ConexaoDB.php';
        try {
            $pdo = ConexaoDB::getConnection();
            $sql = "UPDATE Usuario SET nome = :nome, email = :email, telefone = :telefone 
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':nome' => $this->nome,
                ':email' => $this->email,
                ':telefone' => $this->telefone,
                ':id' => $this->id
            ]);
            
            return $stmt->rowCount() > 0;

        } catch (Exception $e) {
            error_log("Error in Usuario::atualizarBD: " . $e->getMessage());
            throw $e;
        }
    }
    // --- FIM DO MÉTODO ADICIONADO ---


    /**
     * Carrega os dados de um usuário pelo seu ID.
     */
    public function carregarUsuarioPorId($usuario_id)
    {
        require_once __DIR__ . '/ConexaoDB.php';
        try {
            $pdo = ConexaoDB::getConnection();
            $sql = "SELECT id, nome, email, senha_hash, telefone 
                    FROM Usuario WHERE id = :usuario_id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':usuario_id' => $usuario_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $this->id = $row['id'];
                $this->nome = $row['nome'];
                $this->email = $row['email'];
                $this->senha_hash = $row['senha_hash'];
                $this->telefone = $row['telefone']; 
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error in Usuario::carregarUsuarioPorId: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Carrega os dados do usuário pelo email.
     */
    public function carregarUsuarioPorEmail($email)
    {
        require_once __DIR__ . '/ConexaoDB.php'; // Adicionado require_once
        try {
            $pdo = ConexaoDB::getConnection();
            
            $sql = "SELECT id, nome, email, senha_hash, telefone FROM Usuario WHERE email = :email";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData) {
                $this->id = $userData['id'];
                $this->nome = $userData['nome'];
                $this->email = $userData['email'];
                $this->senha_hash = $userData['senha_hash'];
                $this->telefone = $userData['telefone']; 
                return true;
            }
            return false;

        } catch (Exception $e) {
            error_log("Erro ao carregar usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se a senha fornecida bate com o hash no banco.
     */
    public function verificarSenha($senhaFornecida)
    {
        if (empty($this->senha_hash)) {
            return false;
        }
        return password_verify($senhaFornecida, $this->senha_hash);
    }
}
?>