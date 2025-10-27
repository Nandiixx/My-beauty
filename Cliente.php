<?php
class Cliente extends Pessoa {
    private $idCliente;

    public function __construct($idCliente, $nome, $telefone, $email) {
        parent::__construct($nome, $telefone, $email);
        $this->idCliente = $idCliente;
    }

    public function getIdCliente() { return $this->idCliente; }

    public function consultaHistorico() {
        return "Histórico de agendamentos do cliente {$this->nome}";
    }
}
?>