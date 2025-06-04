<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 m-0">Atributos</h1>
    <a href="<?= site_url('atributo/create') ?>" class="btn btn-primaria">Novo Atributo</a>
</div>

<?php if (empty($attributes)): ?>
    <p class="text-muted">Nenhum atributos cadastrado ainda.</p>
<?php else: ?>
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Valores</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($attributes as $attr): ?>
            <tr>
                <td><?= $attr->id ?></td>
                <td><?= $attr->name ?></td>
                <td><?= ucfirst($attr->input_type) ?></td>
                <td>
                    <?php if (!empty($attr->values)): ?>
                        <?= implode(', ', array_column(json_decode($attr->values), 'value')) ?>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?= site_url("atributo/edit/{$attr->id}") ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                    <a href="<?= site_url("atributo/delete/{$attr->id}") ?>" class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Tem certeza que deseja excluir este atributo?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
