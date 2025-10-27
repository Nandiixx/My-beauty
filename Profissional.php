<?php
class Profissional extends Funcionario {
    private $especialidade;

    public function __construct($idFuncionario, $cargo, $login, $senha, $especialidade) {
        parent::__construct($idFuncionario, $cargo, $login, $senha);
        $this->especialidade = $especialidade;
    }

    public function getEspecialidade() { return $this->especialidade; }

    public function consultarAgenda() {
        return "Agenda de atendimentos do profissional: {$this->cargo}";
    }
}
?>