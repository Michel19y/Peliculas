<?php
require_once('../app/models/Conexao.php');

try {
    $banco = Conexao::getInstancia()->getConexao();
    $consulta = $banco->query("SELECT id, nome FROM marcas");
    $marcas = $consulta->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    die("Erro ao buscar marcas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Película</title>

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/img/icon.png">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-top: 50px;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 500;
        }
        .btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <h2 class="text-center mb-4">Cadastrar Nova Película</h2>

                <form method="post" action="../app/controllers/peliculas.php">
                    <input type="hidden" name="op" value="salvar">

                    <div class="mb-3">
                        <label for="input-nome" class="form-label">Nome da Película</label>
                        <input type="text" name="nome" id="input-nome" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="input-quantidades" class="form-label">Quantidades</label>
                        <input type="number" name="quantidades" id="input-quantidades" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="input-compativeis" class="form-label">Modelos Compatíveis</label>
                        <input type="text" name="compativeis" id="input-compativeis" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label for="input-marca" class="form-label">Marca</label>
                        <select name="marca_id" id="input-marca" class="form-select" required>
                            <option value="">Selecione uma marca</option>
                            <?php foreach ($marcas as $id => $nome): ?>
                                <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($nome) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Salvar
                        </button>
                        <a href="../index.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
