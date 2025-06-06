<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Carrinho de Compras</h5>
    </div>

    <div class="card-body">
        <?php if (empty($carrinho)): ?>
            <div class="alert alert-info">Seu carrinho está vazio.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th>SKU</th>
                            <th class="text-center">Quantidade</th>
                            <th class="text-end">Preço Unitário</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carrinho as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['produto'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($item['sku'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-center"><?= $item['quantidade'] ?></td>
                                <td class="text-end">R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                <td class="text-end">R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="mb-3">
                <p><strong>Subtotal:</strong> R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
                <p><strong>Frete:</strong> <?= $frete == 0 ? 'Grátis' : 'R$ ' . number_format($frete, 2, ',', '.') ?></p>
                <p class="fs-5"><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= site_url('/') ?>" class="btn btn-outline-secondary">← Continuar Comprando</a>
                <a href="<?= site_url('carrinho/checkout') ?>"" class="btn btn-success">Finalizar Pedido</a>
            </div>
        <?php endif; ?>
    </div>
</div>
