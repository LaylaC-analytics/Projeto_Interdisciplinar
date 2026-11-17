
<?php
require_once __DIR__ . '/funcoes.php';
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= e($titulo ?? 'LUMIA — Perfumaria Online') ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- CSS PRINCIPAL LUMIA -->
    <link href="/assets/css/style.css?v=2" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-lumia sticky-top">

    <div class="container">

        <a class="navbar-brand brand" href="/index.php">
            LUMIA
            <small>PERFUMARIA ONLINE</small>
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                <li class="nav-item">
                    <a class="nav-link" href="/index.php">Início</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/produtos.php">Produtos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/carrinho.php">
                        Carrinho
                        <span class="cart-count">
                            <?= array_sum($_SESSION['carrinho'] ?? []) ?>
                        </span>
                    </a>
                </li>

                <?php if (usuario_logado()): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/perfil.php">Perfil</a>
                    </li>

                    <?php if (($_SESSION['usuario']['tipo'] ?? '') === 'admin'): ?>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard.php">
                                Administração
                            </a>
                        </li>

                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="btn btn-outline-dark btn-sm" href="/logout.php">
                            Sair
                        </a>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="btn btn-lumia btn-sm" href="/login.php">
                            Entrar
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>

</nav>

<main class="container py-4">
