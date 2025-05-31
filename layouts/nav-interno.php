<style>h1{
    color:white ;
} </style>

<!-- Barra de navegação -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>

         </button>
         
         <h1>
    <span class="navbar-text text-white ml-3">
        <?php
        if (isset($_SESSION['logado']) && isset($_SESSION['nome'])) {
            echo ' <img src="../../assets/img/icon.png" alt="Imagem Barbearia" style="width: 50px; height: 50px; margin-right: 10px;">';
            echo " Barbeiro online: " . htmlspecialchars($_SESSION['nome']);
        } else {
            echo "Bem-vindo!";
        }
        ?>
    </span>
</h1>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <?php if (!isset($_SESSION['logado'])) { ?>
                <li class="nav-item">
                    <form name="form-login" method="post" action="../app/controllers/autenticacao.php" class="form-inline">
                        <input type="email" name="email" placeholder="E-mail" required class="form-control mr-sm-2">
                        <input type="password" name="senha" placeholder="Senha" class="form-control mr-sm-2">
                        <input type="hidden" name="op" value="login">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Login</button>
                    </form>
                </li>
            <?php } else { ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="configuracoesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Configurações
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="configuracoesDropdown">
                        <a class="dropdown-item" href="../../views/agenda.php">Voltar</a>
                        <a class="dropdown-item" href="../../app/controllers/autenticacao.php?op=logout">Sair</a>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>