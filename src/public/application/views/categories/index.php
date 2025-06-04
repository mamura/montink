<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 m-0">Categorias</h1>
    <a href="<?= site_url('category/create') ?>" class="btn btn-primaria">Nova Categoria</a>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th class="text-end">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= $category->id ?></td>
                <td><?= $category->name ?></td>
                <td class="text-end">
                    <a href="<?= site_url('category/edit/' . $category->id) ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                    <a href="<?= site_url('category/delete/' . $category->id) ?>" class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
