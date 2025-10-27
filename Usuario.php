<?php
abstract class Usuario {
    protected $login;
    protected $senha;

    public function __construct($login, $senha) {
        $this->login = $login;
        $this->senha = $senha;
    }

    public function getLogin() { return $this->login; }
    public function setLogin($login) { $this->login = $login; }

    public function setSenha($senha) { $this->senha = password_hash($senha, PASSWORD_DEFAULT); }

    public function fazerLogin($login, $senha) {
        return $this->login === $login && password_verify($senha, $this->senha);
    }
}

?>