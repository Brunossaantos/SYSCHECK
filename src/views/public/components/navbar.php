<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <div class="container">
        <?php
            $nome = "";
            if(isset($_SESSION['nome'])){
                $nome = $_SESSION['nome'];
            }
        ?>
        <a class="navbar-brand" href="#">Bem vindo ao Syscheck - <?=$nome?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/syscheck/">Home</a>
                </li>

                <!--
                <li class="nav-item">
                    <a class="nav-link" href="cadastro.php">Cadastro</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="consulta.php">Consulta</a>
                </li>
                -->

                <li class="nav-item">
                    <a class="nav-link" href="/syscheck/usuario/logout">Logout</a>
                </li>

            </ul>
        </div>
    </div>
</nav>
