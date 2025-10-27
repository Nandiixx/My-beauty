<?php
require_once '../Models/Agendamento.php';

class AgendamentoController {

    public function index() {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?acao=login');
            exit;
        }

        $agendamentoModel = new AgendamentoModel();
        $agendamentos = $agendamentoModel->listar();

        if (!$agendamentos) {
        $agendamentos = [];

        require 'Views/AgendamentoView.php';
    }

    public function salvar() {
        $cliente = $_POST['cliente'];
        $profissional = $_POST['profissional'];
        $servico = $_POST['servico'];
        $dataHora = $_POST['dataHora'];

        $agendamento = new Agendamento($cliente, $profissional, $servico, $dataHora);
        if ($agendamento->cadastrar()) {
            echo "<script>alert('Agendamento realizado com sucesso!');window.location='index.php?acao=agendamentos';</script>";
        } else {
            echo "<script>alert('Erro ao agendar.');window.location='index.php?acao=agendamentos';</script>";
        }
    }
}
?>