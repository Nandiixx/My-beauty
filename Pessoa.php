<?php
class Pessoa {
    protected $nome;
    protected $telefone;
    protected $email;

    public function __construct($nome, $telefone, $email) {
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->email = $email;
    }

    public function getNome() { return $this->nome; }
    public function getTelefone() { return $this->telefone; }
    public function getEmail() { return $this->email; }

    public function setNome($nome) { $this->nome = $nome; }
    public function setTelefone($telefone) { $this->telefone = $telefone; }
    public function setEmail($email) { $this->email = $email; }
}