<form method="post" action="<?= site_url('carrinho/finalizar') ?>">

<div class="row">
    <!-- Coluna da Esquerda -->
    <div class="col-md-7">
        <!-- Card: Dados do Usuário -->
        <div class="card mb-4">
            <div class="card-header"><strong>1. Dados do Usuário</strong></div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="nome">Nome completo</label>
                    <input type="text" class="form-control" name="nome" id="nome" required>
                </div>
                <div class="mb-3">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control" name="cpf" id="cpf" required>
                </div>
                <div class="mb-3">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="form-control" name="telefone" id="telefone" required>
                </div>
            </div>
        </div>

        <!-- Card: Endereço -->
        <div class="card mb-4">
            <div class="card-header"><strong>2. Endereço de Entrega</strong></div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="cep">CEP</label>
                    <input type="text" class="form-control" name="cep" id="cep" required>
                    <div id="cep-erro" class="text-danger d-none mt-1">CEP inválido ou não encontrado.</div>
                </div>
                <div class="mb-3">
                    <label for="endereco">Endereço</label>
                    <input type="text" class="form-control" name="endereco" id="endereco" required>
                </div>
                <div class="mb-3">
                    <label for="numero">Número</label>
                    <input type="text" class="form-control" name="numero" id="numero" required>
                </div>
                <div class="mb-3">
                    <label for="complemento">Complemento</label>
                    <input type="text" class="form-control" name="complemento" id="complemento">
                </div>
                <div class="mb-3">
                    <label for="bairro">Bairro</label>
                    <input type="text" class="form-control" name="bairro" id="bairro" required>
                </div>
                <div class="mb-3">
                    <label for="cidade">Cidade</label>
                    <input type="text" class="form-control" name="cidade" id="cidade" required>
                </div>
                <div class="mb-3">
                    <label for="estado">Estado</label>
                    <input type="text" class="form-control" name="estado" id="estado" required>
                </div>
            </div>
        </div>

        <!-- Card: Pagamento -->
        <div class="card mb-4">
            <div class="card-header"><strong>3. Pagamento</strong></div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="pagamento">Forma de pagamento</label>
                    <select class="form-control" name="pagamento" id="pagamento" required>
                        <option value="">Selecione</option>
                        <option value="boleto">Boleto Bancário</option>
                        <option value="cartao">Cartão de Crédito</option>
                        <option value="pix">Pix</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Coluna da Direita: Resumo -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><strong>Resumo da Compra</strong></div>
            <div class="card-body">
                <?php if (!empty($carrinho)): ?>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Qtd</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($carrinho as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['produto'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= (int) $item['quantidade'] ?></td>
                                    <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <p><strong>Subtotal:</strong> R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
                    <p><strong>Frete:</strong> <?= $frete == 0 ? 'Grátis' : 'R$ ' . number_format($frete, 2, ',', '.') ?></p>
                    <p><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>

                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-success">Finalizar Pedido</button>
                    </div>
                <?php else: ?>
                    <p class="alert alert-warning">Carrinho vazio.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</form>

<script>
document.getElementById('cep').addEventListener('blur', async function () {
    const cepInput  = this;
    const cep       = cepInput.value.replace(/\D/g, '');
    const erroEl    = document.getElementById('cep-erro');

    if (cep.length !== 8) {
        erroEl.classList.remove('d-none');
        return;
    }

    try {
        const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await res.json();

        if (data.erro) throw new Error('CEP não encontrado');

        document.getElementById('endereco').value = data.logradouro || '';
        document.getElementById('bairro').value = data.bairro || '';
        document.getElementById('cidade').value = data.localidade || '';
        document.getElementById('estado').value = data.uf || '';
        erroEl.classList.add('d-none');
    } catch (e) {
        erroEl.classList.remove('d-none');
    }
});
</script>
