<?php
session_start();
require_once('../models/Peliculas.php');

$operacao = $_REQUEST['op'] ?? '';

switch ($operacao) {
    case 'salvar':
        salvar();
        break;
    case 'editar':
        editar();
        break;
    case 'atualizar':
        atualizar();
        break;
    case 'excluir':
        excluir();
        break;
    default:
        $_SESSION['mensagem'] = "Operação inválida.";
        $_SESSION['tipoMensagem'] = "danger";
        header('Location: ../../index.php');
        exit;
}

function salvar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = trim($_POST['nome']);
        $quantidades = intval($_POST['quantidades']);
        $marca_id = intval($_POST['marca_id']);
        $compativeis = explode(',', $_POST['compativeis'] ?? '');

        $pelicula = new Peliculas();
        $pelicula->setNome($nome);
        $pelicula->setQuantidades($quantidades);
        $pelicula->setMarcaId($marca_id);

        if ($pelicula->salvar($compativeis)) {
            $_SESSION['mensagem'] = "Película salva com sucesso!";
            $_SESSION['tipoMensagem'] = "success";
            header("Location: ../../index.php");
        } else {
            $_SESSION['mensagem'] = "Erro ao salvar a película.";
            $_SESSION['tipoMensagem'] = "danger";
            header("Location: ../../views/Peliculas.php");
        }
        exit;
    }
}

function editar() {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        $_SESSION['mensagem'] = "Nenhuma película selecionada para edição.";
        $_SESSION['tipoMensagem'] = "danger";
        header('Location: ../../index.php');
        exit;
    }

    $id = intval($_GET['id']);
    $tela = new Peliculas();
    $dados = $tela->selecionarPorId($id);

    if (!$dados) {
        $_SESSION['mensagem'] = "Película não encontrada.";
        $_SESSION['tipoMensagem'] = "danger";
        header('Location: ../../index.php');
        exit;
    }

    $_SESSION['dados_tela'] = $dados;
    header('Location: ../../views/Peliculas-editar.php');
    exit;
}

function atualizar() {
    $id = intval($_POST['id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $quantidades = $_POST['quantidades'] ?? '';
    $marca_id = intval($_POST['marca_id'] ?? 0);
    $compativeis = $_POST['compativeis'] ?? '';

    if ($id <= 0 || $nome === '' || $quantidades === '' || $marca_id <= 0) {
        $_SESSION['mensagem'] = "Preencha todos os campos obrigatórios.";
        $_SESSION['tipoMensagem'] = "danger";
        header('Location: ../../views/Peliculas-editar.php?id=' . $id);
        exit;
    }

    $pelicula = new Peliculas();
    $pelicula->setId($id);
    $pelicula->setNome($nome);
    $pelicula->setQuantidades(intval($quantidades)); // Permite 0
    $pelicula->setMarcaId($marca_id);
    $pelicula->setModelosCompativeis($compativeis);

    if ($pelicula->atualizar()) {
    $_SESSION['mensagem'] = "Película <strong>{$nome}</strong> atualizada com sucesso! Quantidade: {$quantidades}.";
$_SESSION['tipoMensagem'] = "success";

        header('Location: ../../index.php');
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar a película.";
        $_SESSION['tipoMensagem'] = "danger";
        header('Location: ../../views/Peliculas-editar.php?id=' . $id);
    }
    exit;
}

function excluir() {
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        $_SESSION['mensagem'] = "ID inválido para exclusão.";
        $_SESSION['tipoMensagem'] = "danger";
        header('Location: ../../index.php');
        exit;
    }

    $tela = new Peliculas();
    if ($tela->excluir($id)) {
        $_SESSION['mensagem'] = "Película excluída com sucesso!";
        $_SESSION['tipoMensagem'] = "success";
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir a película.";
        $_SESSION['tipoMensagem'] = "danger";
    }

    header('Location: ../../index.php');
    exit;
}
