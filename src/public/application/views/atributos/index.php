<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Atributos</h2>
    <a href="<?= site_url('atributo/create') ?>" class="btn btn-primaria">Novo Atributo</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Categoria</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($atributos as $atributo): ?>
            <tr>
                <td><?= htmlspecialchars($atributo->name, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($atributo->input_type ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($atributo->category_name ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <a href="<?= site_url('atributo/edit/'.$atributo->id) ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="<?= site_url('atributo/delete/'.$atributo->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este atributo?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
