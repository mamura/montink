<h2><?= isset($produto->id) ? 'Editar Produto' : 'Novo Produto' ?></h2>

<form method="post" action="<?= site_url(isset($produto->id) ? 'produto/update/' . $produto->id : 'produto/store') ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Nome do Produto</label>
        <input type="text" name="name" id="name" class="form-control"
               value="<?= isset($produto->name) ? htmlspecialchars($produto->name, ENT_QUOTES, 'UTF-8') : '' ?>">
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Descrição</label>
        <textarea name="description" id="description" class="form-control"><?= isset($produto->description) ? htmlspecialchars($produto->description, ENT_QUOTES, 'UTF-8') : '' ?></textarea>
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Categoria</label>
        <select name="category_id" id="category_id" class="form-control">
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat->id ?>" <?= isset($produto->category_id) && $produto->category_id == $cat->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat->name, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="<?= site_url('produto') ?>" class="btn btn-secondary">Cancelar</a>
</form>
