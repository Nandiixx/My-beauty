<?php
class Funcionario extends Usuario {
    protected $idFuncionario;
    protected $cargo;

    public function __construct($idFuncionario, $cargo, $login, $senha) {
        parent::__construct($login, $senha);
        $this->idFuncionario = $idFuncionario;
        $this->cargo = $cargo;
    }

    public function getIdFuncionario() { return $this->idFuncionario; }
    public function getCargo() { return $this->cargo; }

    public function setCargo($cargo) { $this->cargo = $cargo; }
}
?>