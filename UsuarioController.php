<?php
require_once '../Models/Usuario.php';

class UsuarioController {

    public function login() {
        require '../Views/LoginView.php';
    }

    public function autenticar() {
        $login = $_POST['login'];
        $senha = $_POST['senha'];

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->autenticar($login, $senha);

        if ($usuario) {
            session_start();
            $_SESSION['usuario'] = $usuario['nome'];
            header('Location: index.php?acao=dashboard');
        } else {
            echo "<script>alert('Login ou senha incorretos');window.location='index.php?acao=login';</script>";
        }
    }

    public function cadastro() {
        require '../Views/CadastroView.php';
    }

    public function salvarCadastro() {
        $usuario = new Usuario($_POST['nome'], $_POST['email'], $_POST['login'], $_POST['senha']);
        if ($usuario->cadastrar()) {
            echo "<script>alert('Usuário cadastrado com sucesso!');window.location='index.php?acao=login';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar usuário.');</script>";
        }
    }
}
?>