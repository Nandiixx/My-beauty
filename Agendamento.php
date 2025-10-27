<?php
class Agendamento {
    private $idAgendamento;
    private $dataHora;
    private $status;
    private $cliente;
    private $profissional;
    private $servico;

    public function __construct($idAgendamento, $dataHora, $status, Cliente $cliente, Profissional $profissional, Servico $servico) {
        $this->idAgendamento = $idAgendamento;
        $this->dataHora = $dataHora;
        $this->status = $status;
        $this->cliente = $cliente;
        $this->profissional = $profissional;
        $this->servico = $servico;
    }

    public function getResumo() {
        return "Agendamento de {$this->cliente->getNome()} com {$this->profissional->getCargo()} para o serviço {$this->servico->getNome()} em {$this->dataHora}.";
    }
}
?>