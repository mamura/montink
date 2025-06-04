<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 m-0">Produtos</h1>
    <a href="<?= site_url('produto/create') ?>" class="btn btn-primaria">+ Novo Produto</a>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= $produto->id ?></td>
                <td><?= htmlspecialchars($produto->name, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($produto->category_name, ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <a href="<?= site_url('produto/edit/' . $produto->id) ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="<?= site_url('produto/delete/' . $produto->id) ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
