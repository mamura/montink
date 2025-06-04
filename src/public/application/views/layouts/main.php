<?php
    $current = $this->router->fetch_class();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Mini ERP' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
</head>

<body class=" bg-main">

    <!-- Topbar -->
    <nav class="navbar navbar-dark bg-dark shadow-sm topbar">
        <div class="container d-flex justify-content-center">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url() ?>">
                <img src="<?= base_url('assets/images/montink-white.png') ?>" alt="Logo" style="height:40px;">
            </a>
        </div>
    </nav>

    <!-- Menu horizontal abaixo da topbar -->
    <nav class="navbar secondary-nav">
        <div class="container d-flex justify-content-end">
            <ul class="navbar-nav flex-row gap-3">
                <li class="nav-item">
                    <a class="nav-link <?= $current === 'product' ? 'active' : '' ?>" href="<?= site_url('product') ?>">Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current === 'cart' ? 'active' : '' ?>" href="<?= site_url('cart') ?>">Carrinho</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current === 'order' ? 'active' : '' ?>" href="<?= site_url('order') ?>">Pedidos</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <main class="container py-4">
        <?= $contents ?>
    </main>

    <!-- Rodapé -->
    <footer class="mt-auto">
        <small>&copy; <?= date('Y') ?> - Mini ERP. Desenvolvido por Mamura</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
