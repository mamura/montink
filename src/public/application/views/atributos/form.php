<h2><?= isset($atributo->id) ? 'Editar Atributo' : 'Novo Atributo' ?></h2>

<form method="post" action="<?= site_url(isset($atributo->id) ? 'atributo/update/'.$atributo->id : 'atributo/store') ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Nome do Atributo</label>
        <input type="text" name="name" id="name" class="form-control"
               value="<?= isset($atributo->name) ? htmlspecialchars($atributo->name, ENT_QUOTES, 'UTF-8') : '' ?>">
    </div>

    <div class="mb-3">
        <label for="input_type" class="form-label">Tipo</label>
        <select name="input_type" id="input_type" class="form-control">
            <option value="text" <?= (isset($atributo->input_type) && $atributo->input_type === 'text') ? 'selected' : '' ?>>Texto</option>
            <option value="select" <?= (isset($atributo->input_type) && $atributo->input_type === 'select') ? 'selected' : '' ?>>Seleção</option>
            <option value="number" <?= (isset($atributo->input_type) && $atributo->input_type === 'number') ? 'selected' : '' ?>>Número</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Categoria</label>
        <select name="category_id" id="category_id" class="form-control">
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat->id ?>"
                        <?= (isset($atributo->category_id) && $atributo->category_id == $cat->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat->name, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="options" class="form-label">Valores (opções)</label>
        <input type="text" name="options" id="options" class="form-control"
               value="<?= isset($atributo->options) ? htmlspecialchars(implode(',', $atributo->options), ENT_QUOTES, 'UTF-8') : '' ?>">
        <small class="form-text text-muted">Separe os valores por vírgula (ex: P,M,G)</small>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="<?= site_url('atributo') ?>" class="btn btn-secondary">Cancelar</a>
</form>
