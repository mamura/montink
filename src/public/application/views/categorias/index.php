<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 m-0">Categorias</h1>
    <a href="<?= site_url('categoria/create') ?>" class="btn btn-primary">+ Nova Categoria</a>
</div>

<?php if (count($categorias) === 0): ?>
    <p class="text-muted">Nenhuma categoria cadastrada.</p>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?= htmlspecialchars($categoria->name, ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="text-end">
                        <a href="<?= site_url('categoria/edit/' . $categoria->id) ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                        <a href="<?= site_url('categoria/delete/' . $categoria->id) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
