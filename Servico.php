<?php
class Servico {
    private $idServico;
    private $nome;
    private $descricao;
    private $preco;
    private $duracaoEmMinutos;

    public function __construct($idServico, $nome, $descricao, $preco, $duracaoEmMinutos) {
        $this->idServico = $idServico;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->duracaoEmMinutos = $duracaoEmMinutos;
    }

    public function getNome() { return $this->nome; }
}
?>