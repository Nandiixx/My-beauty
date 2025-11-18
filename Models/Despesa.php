<?php
// Arquivo: Models/Despesa.php

require_once __DIR__ . '/ConexaoDB.php';

class Despesa {
    private $id;
    private $descricao;
    private $valor;
    private $data;
    private $categoria;

    // Getters e Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getDescricao() { return $this->descricao; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
    public function getValor() { return $this->valor; }
    public function setValor($valor) { $this->valor = $valor; }
    public function getData() { return $this->data; }
    public function setData($data) { $this->data = $data; }
    public function getCategoria() { return $this->categoria; }
    public function setCategoria($categoria) { $this->categoria = $categoria; }

    // --- MÉTODOS DE BANCO DE DADOS ---

    public function listarTodos() {
        try {
            $conexao = ConexaoDB::getConnection();
            $stmt = $conexao->prepare("SELECT id, descricao, valor, data, categoria FROM Despesa ORDER BY data DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Despesa');
        } catch (PDOException $e) {
            error_log("Erro ao listar despesas: " . $e->getMessage());
            return [];
        }
    }
    
    public function carregarPorId($id) {
        try {
            $conexao = ConexaoDB::getConnection();
            $stmt = $conexao->prepare("SELECT id, descricao, valor, data, categoria FROM Despesa WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_INTO, $this);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao carregar despesa: " . $e->getMessage());
            return false;
        }
    }

    public function inserirBD() {
        try {
            $conexao = ConexaoDB::getConnection();
            $sql = "INSERT INTO Despesa (descricao, valor, data, categoria) 
                    VALUES (:descricao, :valor, :data, :categoria)";
            
            $stmt = $conexao->prepare($sql);
            
            $stmt->bindParam(':descricao', $this->descricao);
            $stmt->bindParam(':valor', $this->valor);
            $stmt->bindParam(':data', $this->data);
            $stmt->bindParam(':categoria', $this->categoria);

            $stmt->execute();
            $this->id = $conexao->lastInsertId();
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao inserir despesa: " . $e->getMessage());
            return false;
        }
    }

    public function atualizarBD() {
        try {
            $conexao = ConexaoDB::getConnection();
            $sql = "UPDATE Despesa SET descricao = :descricao, valor = :valor, data = :data, categoria = :categoria WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':descricao', $this->descricao);
            $stmt->bindParam(':valor', $this->valor);
            $stmt->bindParam(':data', $this->data);
            $stmt->bindParam(':categoria', $this->categoria);

            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar despesa: " . $e->getMessage());
            return false;
        }
    }

    public function excluirBD($id) {
        try {
            $conexao = ConexaoDB::getConnection();
            $sql = "DELETE FROM Despesa WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao excluir despesa: " . $e->getMessage());
            return false;
        }
    }
}
?>
