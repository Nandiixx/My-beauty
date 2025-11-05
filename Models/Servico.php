<?php
// Arquivo: Models/Servico.php

require_once __DIR__ . '/ConexaoDB.php';

class Servico {
    private $id;
    private $nome;
    private $descricao;
    private $duracao_min;
    private $preco;

    // ... (Getters e Setters) ...
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }
    public function getDescricao() { return $this->descricao; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
    public function getDuracaoMin() { return $this->duracao_min; }
    public function setDuracaoMin($duracao_min) { $this->duracao_min = $duracao_min; }
    public function getPreco() { return $this->preco; }
    public function setPreco($preco) { $this->preco = $preco; }


    // --- MÉTODOS DE BANCO DE DADOS ---

    public function listarTodos() {
        try {
            $conexao = ConexaoDB::getConnection();
            $stmt = $conexao->prepare("SELECT id, nome, descricao, duracao_minutos AS duracao_min, preco FROM Servico ORDER BY nome");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Servico');
        } catch (PDOException $e) {
            error_log("Erro ao listar serviços: " . $e->getMessage());
            return [];
        }
    }
    
    public function carregarPorId($id) {
        try {
            $conexao = ConexaoDB::getConnection();
            $stmt = $conexao->prepare("SELECT id, nome, descricao, duracao_minutos AS duracao_min, preco FROM Servico WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_INTO, $this);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao carregar serviço: " . $e->getMessage());
            return false;
        }
    }

    public function inserirBD() {
        try {
            $conexao = ConexaoDB::getConnection();
            $sql = "INSERT INTO Servico (nome, descricao, duracao_minutos, preco) 
                    VALUES (:nome, :descricao, :duracao, :preco)"; // DEPOIS
            
            $stmt = $conexao->prepare($sql);
            
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':descricao', $this->descricao);
            $stmt->bindParam(':duracao', $this->duracao_min, PDO::PARAM_INT); 
            $stmt->bindParam(':preco', $this->preco);

            $stmt->execute();
            $this->id = $conexao->lastInsertId();
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao inserir serviço: " . $e->getMessage());
            return false;
        }
    }

    public function atualizarBD() {
        try {
            $conexao = ConexaoDB::getConnection();
            $sql = "UPDATE Servico SET nome = :nome, descricao = :descricao, duracao_minutos = :duracao, preco = :preco WHERE id = :id"; // DEPOIS
            $stmt = $conexao->prepare($sql);
            
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':descricao', $this->descricao);
            $stmt->bindParam(':duracao', $this->duracao_min, PDO::PARAM_INT);
            $stmt->bindParam(':preco', $this->preco);

            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar serviço: " . $e->getMessage());
            return false;
        }
    }

    public function excluirBD($id) {
        try {
            $conexao = ConexaoDB::getConnection();
            $sql = "DELETE FROM Servico WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao excluir serviço: " . $e->getMessage());
            if ($e->getCode() == '23000') {
                return "ERRO_FK";
            }
            return false;
        }
    }
}
?>