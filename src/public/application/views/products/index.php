<!DOCTYPE html>
<html>
<head>
    <title>Produtos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 m-0">Produtos</h1>
        <a href="<?= site_url('product/create') ?>" class="btn btn-novo-produto">+ Novo Produto</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p->name ?></td>
                    <td>R$ <?= number_format($p->price, 2, ',', '.') ?></td>
                    <td>
                        <a href="<?= site_url('product/edit/'.$p->id) ?>" class="btn btn-primaria btn-sm">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
