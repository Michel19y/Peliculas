<?php
require_once('../app/models/Conexao.php');
session_start();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    die("Erro: Nenhuma película selecionada para edição.");
}

try {
    $banco = Conexao::getInstancia()->getConexao();

    $sql = "SELECT p.id, p.nome, p.quantidades, p.marca_id, 
                   GROUP_CONCAT(mo.modelo SEPARATOR ', ') AS modelos_compativeis
            FROM peliculas p
            LEFT JOIN modelos mo ON mo.pelicula_id = p.id
            WHERE p.id = :id
            GROUP BY p.id, p.nome, p.quantidades, p.marca_id";

    $consulta = $banco->prepare($sql);
    $consulta->bindParam(':id', $id, PDO::PARAM_INT);
    $consulta->execute();
    $pelicula = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$pelicula) {
        die("Erro: Película não encontrada.");
    }

    $_SESSION['dados_pelicula'] = $pelicula;

    $consultaMarcas = $banco->query("SELECT id, nome FROM marcas");
    $marcas = $consultaMarcas->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Película</title>

    <!-- Bootstrap 5 e Ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/img/icon.png">
          <link href="../assets/css/editar.css" rel="stylesheet">

    
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <h2 class="text-center mb-4">Editar Película</h2>
               <?php if (isset($_SESSION['mensagem'])): ?>
    <div id="alerta-msg" class="alert alert-<?= $_SESSION['tipoMensagem'] ?> alert-dismissible fade show mt-3" role="alert">
        <?= $_SESSION['mensagem'] ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <script>
        // Oculta o alerta após 5 segundos
        setTimeout(function () {
            const alerta = document.getElementById('alerta-msg');
            if (alerta) {
                alerta.classList.remove('show');
                alerta.classList.add('fade');
                setTimeout(() => alerta.remove(), 500);
            }
        }, 5000);
    </script>
    <?php unset($_SESSION['mensagem'], $_SESSION['tipoMensagem']); ?>
<?php endif; ?>



                <form method="post" action="../app/controllers/peliculas.php">
                    <input type="hidden" name="op" value="atualizar">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($pelicula['id']) ?>">

                    <div class="mb-3">
                        <label for="input-nome" class="form-label">Nome da Película</label>
                        <input type="text" name="nome" id="input-nome" class="form-control" value="<?= htmlspecialchars($pelicula['nome']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="input-quantidades" class="form-label">Quantidades</label>
                        <input type="number" name="quantidades" id="input-quantidades" class="form-control" value="<?= htmlspecialchars($pelicula['quantidades']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="input-compativeis" class="form-label">Modelos Compatíveis</label>
                        <input type="text" name="compativeis" id="input-compativeis" class="form-control" 
                               value="<?= htmlspecialchars($pelicula['modelos_compativeis'] ?? '') ?>">
                    </div>

                    <div class="mb-4">
                        <label for="input-marca" class="form-label">Marca</label>
                        <select name="marca_id" id="input-marca" class="form-select" required>
                            <option value="">Selecione uma marca</option>
                            <?php foreach ($marcas as $id_marca => $nome): ?>
                                <option value="<?= htmlspecialchars($id_marca) ?>" <?= $pelicula['marca_id'] == $id_marca ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($nome) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Atualizar
                        </button>
                        <a href="../index.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
