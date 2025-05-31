<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Para cookies apenas em conexões HTTPS
session_start(); // Cria ou retoma uma sessão de usuário
require_once('../models/Usuario.php');

$operacao = $_REQUEST['op'] ?? ''; // $_REQUEST procura no POST e no GET

switch ($operacao) {
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    default:
        header('Location: ../../index.php');
        exit;
}

function login()
{
    if (empty($_POST['email']) || empty($_POST['senha'])) {
        header('Location: ../../index.php?erro=1');
        exit;
    }

    $email = ($_POST['email']);
    $senha = $_POST['senha'];

    $usuario = new Usuario();
    $usuarioEncontrado = $usuario->verificarCredenciais($email, $senha);

    if (!$usuarioEncontrado) {
        header('Location: ../../index.php?erro=2');
        exit;
    }

    session_regenerate_id(true); // Gera um novo ID para evitar sequestro de sessão

    // Definindo as variáveis de sessão
    $_SESSION['id'] = $usuarioEncontrado->getId();
    $_SESSION['nome'] = $usuarioEncontrado->getNome();
    $_SESSION['email'] = $usuarioEncontrado->getEmail();
    $_SESSION['tipo_usuario'] = $usuarioEncontrado->getTipo(); // tipo de usuário (admin ou barbeiro)
    $_SESSION['usuario_id'] = $usuarioEncontrado->getId(); // ID do barbeiro ou admin
    $_SESSION['logado'] = true;

    header('Location: ../../views/agenda.php');
    exit;
}

function logout()
{
    session_unset(); // Remove todas as variáveis de sessão
    session_destroy(); // Destrói a sessão
    header('Location: ../../index.php?sucesso=3');
    exit;
}
