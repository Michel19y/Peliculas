<?php
require_once('./app/models/Conexao.php');
session_start();
// Verifica se há filtro
$filtro = isset($_GET['filtro']) ? trim($_GET['filtro']) : '';

try {
    $banco = Conexao::getInstancia()->getConexao();

    $sql = "SELECT p.id, p.nome AS pelicula, p.quantidades, m.nome AS marca, mo.modelo AS celular_compativel
            FROM peliculas p
            JOIN marcas m ON p.marca_id = m.id
            JOIN modelos mo ON mo.pelicula_id = p.id";

    if ($filtro) {
        $sql .= " WHERE p.nome LIKE :filtro OR m.nome LIKE :filtro OR mo.modelo LIKE :filtro";
    }

    $sql .= " ORDER BY m.nome, p.nome";

    $stmt = $banco->prepare($sql);
    if ($filtro) {
        $stmt->bindValue(':filtro', '%' . $filtro . '%', PDO::PARAM_STR);
    }
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Películas Silvano</title>

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="./assets/css/estilo.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="./assets/img/icon.png">

    <style>

    </style>
</head>
<body>

<header class="text-center mb-4">
    <h1>Películas Silvano</h1>
    

    <a href="./views/peliculas-criar.php" class="btn btn-success btn-add">
        <i class="bi bi-plus-circle"></i> Cadastrar Películas
    </a>
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
</header>


<div class="container">
    <!-- Formulário de busca -->
    <form method="GET" class="search-bar input-group mb-4">
        <input type="text" name="filtro" class="form-control" placeholder="Buscar película, marca ou modelo..." value="<?= htmlspecialchars($filtro) ?>">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
    </form>

    <div class="table-responsive shadow rounded">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Quantidades</th>
                    <th>Película</th>
                    <th>Marca</th>
                    <th>Modelos Compatíveis</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($resultados)) : ?>
                    <?php foreach ($resultados as $row) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['quantidades']) ?></td>
                            <td><?= htmlspecialchars($row['pelicula']) ?></td>
                            <td><?= htmlspecialchars($row['marca']) ?></td>
                            <td><?= htmlspecialchars($row['celular_compativel']) ?></td>
                            <td class="text-center">
                                <a href="./views/peliculas-editar.php?id=<?= $row['id']; ?>" class="btn btn-primary">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Nenhuma película encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
