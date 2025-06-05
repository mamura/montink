<h2>Produtos em Destaque</h2>

<div class="row">
    <?php foreach ($produtos as $produto): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5><?= htmlspecialchars($produto->name) ?></h5>
                    <p><?= htmlspecialchars($produto->description) ?></p>
                    <p><strong>R$ <?= number_format($produto->price, 2, ',', '.') ?></strong></p>
                    <form method="post" action="<?= site_url('carrinho/adicionar') ?>">
                        <input type="hidden" name="variant_id" value="<?= $produto->variant_id ?>">
                        <button type="submit" class="btn btn-primary">Comprar</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
