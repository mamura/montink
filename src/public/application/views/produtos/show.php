<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><?= htmlspecialchars($produto->name) ?></h5>
    </div>

    <div class="card-body">
        <p class="card-text"><?= nl2br(htmlspecialchars($produto->description)) ?></p>

        <hr>

        <form method="post" action="<?= site_url('carrinho/adicionar') ?>">
            <input type="hidden" name="product_id" value="<?= $produto->id ?>">

            <?php if (!empty($produto->variants)): ?>
                <div class="mb-3">
                    <label for="variant_id" class="form-label">Escolha uma variação:</label>
                    <select name="variant_id" id="variant_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($produto->variants as $variant): ?>
                            <option value="<?= $variant->id ?>">
                                <?= htmlspecialchars($variant->sku) ?> -
                                R$ <?= number_format($variant->price, 2, ',', '.') ?>
                                (<?= $variant->quantity ?> em estoque)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="quantidade" class="form-label">Quantidade:</label>
                    <input type="number" name="quantidade" id="quantidade" value="1" min="1" class="form-control" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('/') ?>" class="btn btn-secondary">← Voltar</a>
                    <button type="submit" class="btn btn-success">Adicionar ao Carrinho</button>
                </div>
            <?php else: ?>
                <p class="text-danger mt-3">Nenhuma variação disponível para este produto.</p>
            <?php endif; ?>
        </form>
    </div>
</div>
