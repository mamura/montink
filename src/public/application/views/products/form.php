<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 m-0"><?= isset($product) ? 'Editar Produto' : 'Novo Produto' ?></h1>
    <a href="<?= site_url('product') ?>" class="btn btn-outline-secondary">Voltar</a>
</div>

<form method="post" action="<?= site_url('product/store') ?>">
    <?php if (isset($product)): ?>
        <input type="hidden" name="id" value="<?= $product->id ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" name="name" id="name" class="form-control" required
               value="<?= $product->name ?? '' ?>">
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Preço</label>
        <input type="number" step="0.01" name="price" id="price" class="form-control" required
               value="<?= $product->price ?? '' ?>">
    </div>

    <h5 class="mt-4">Atributos do Produto</h5>
    <?php foreach ($attributes as $attr): ?>
        <?php
            $existing_value = '';
            if (isset($attribute_values)) {
                foreach ($attribute_values as $v) {
                    if ($v->attribute_id == $attr->id) {
                        $existing_value = $v->value;
                        break;
                    }
                }
            }
        ?>
        <div class="mb-3">
            <label class="form-label"><?= $attr->name ?></label>
            <input type="<?= $attr->input_type ?>" name="attributes[<?= $attr->id ?>]" class="form-control"
                   value="<?= htmlspecialchars($existing_value) ?>">
        </div>
    <?php endforeach; ?>

    <div class="mb-4">
        <label for="stock" class="form-label">Estoque</label>
        <input type="number" name="stock" id="stock" class="form-control"
               value="<?= $stock->quantity ?? 0 ?>">
    </div>

    <button type="submit" class="btn btn-primaria">Salvar Produto</button>
</form>
