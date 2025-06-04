<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 m-0"><?= isset($attribute) ? 'Editar Atributo' : 'Novo Atributo' ?></h1>
    <a href="<?= site_url('atributo') ?>" class="btn btn-outline-secondary">Voltar</a>
</div>

<form method="post" action="<?= site_url('atributo/store') ?>">
    <?php if (isset($attribute)): ?>
        <input type="hidden" name="id" value="<?= $attribute->id ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label for="name" class="form-label">Nome do Atributo</label>
        <input type="text" class="form-control" name="name" id="name" required
               value="<?= $attribute->name ?? '' ?>">
    </div>

    <div class="mb-3">
        <label for="input_type" class="form-label">Tipo de Entrada</label>
        <select name="input_type" class="form-select" required>
            <option value="text" <?= (isset($attribute) && $attribute->input_type == 'text') ? 'selected' : '' ?>>Texto</option>
            <option value="number" <?= (isset($attribute) && $attribute->input_type == 'number') ? 'selected' : '' ?>>Número</option>
            <option value="select" <?= (isset($attribute) && $attribute->input_type == 'select') ? 'selected' : '' ?>>Seleção</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="values" class="form-label">Valores (separados por vírgula)</label>
        <input type="text" class="form-control" name="values" id="values"
               placeholder="Ex: P,M,G"
               value="<?= isset($attribute->values) ? implode(',', $attribute->values) : '' ?>">
    </div>

    <button type="submit" class="btn btn-primaria">Salvar</button>
</form>
