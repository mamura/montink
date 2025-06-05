<h2><?= isset($produto->id) ? 'Editar Produto' : 'Novo Produto' ?></h2>

<form id="form-produto" method="post" action="<?= site_url(isset($produto->id) ? 'produto/update/' . $produto->id : 'produto/store') ?>">
    <div class="card">
        <div class="card-header">
            <strong>Informações do Produto</strong>
        </div>
        <div class="card-body">
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
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat->id ?>" <?= isset($produto->category_id) && $produto->category_id == $cat->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat->name, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Variações do Produto</strong>
            <button type="button" id="btn-adicionar-variacao" class="btn btn-sm btn-primary" disabled onclick="adicionarVariacao()">+ Adicionar Variação</button>
        </div>
        <div class="card-body" id="variacoes-container">
            <!-- Variações serão adicionadas aqui -->
        </div>
    </div>

    <div class="card-footer text-end mt-3">
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="<?= site_url('produto') ?>" class="btn btn-secondary ms-2">Cancelar</a>
    </div>
</form>

<template id="template-variacao">
    <div class="variacao border p-3 mb-3 rounded bg-light">
        <div class="row g-2">
            <div class="col-md-4">
                <label>SKU</label>
                <input type="text" name="variantes[__index__][sku]" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Preço</label>
                <input type="number" step="0.01" name="variantes[__index__][price]" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Estoque</label>
                <input type="number" name="variantes[__index__][stock]" class="form-control">
            </div>
        </div>

        <div class="mt-2">
            <label>Atributos</label>
            <div class="atributos-container"></div>
            <button type="button" class="btn btn-sm btn-outline-secondary mt-2 adicionar-atributo-btn" onclick="adicionarAtributo(this)">+ Adicionar Atributo</button>
        </div>


        <div class="text-end mt-2">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.variacao').remove()">Remover</button>
        </div>
    </div>
</template>

<script>
    let variacaoIndex           = 0;
    let atributosPorCategoria   = {};
    const btnAdicionarVariacao  = document.getElementById('btn-adicionar-variacao');

<?php if (!empty($variantes)): ?>
    const variantesPreCarregadas = <?= json_encode($variantes) ?>;

    window.addEventListener('DOMContentLoaded', async () => {
        const categoriaId = document.getElementById('category_id').value;

        if (categoriaId) {
            const res = await fetch('<?= site_url('atributo/por_categoria/') ?>' + categoriaId);
            
            atributosPorCategoria           = await res.json();
            btnAdicionarVariacao.disabled   = false;

            variantesPreCarregadas.forEach(variacao => {
                adicionarVariacaoPreCarregada(variacao);
            });
        }
    });
<?php endif; ?>

    document.getElementById('category_id').addEventListener('change', async function () {
        const categoriaId = this.value;

        // Atualiza atributos disponíveis da categoria
        const res = await fetch('<?= site_url('atributo/por_categoria/') ?>' + categoriaId);
        atributosPorCategoria = await res.json();

        // Ativa o botão de adicionar variação
        btnAdicionarVariacao.disabled = !categoriaId;
    });

    document.getElementById('form-produto').addEventListener('submit', function (e) {
        const container = document.getElementById('variacoes-container');
        const variacoes = container.querySelectorAll('.variacao');

        if (variacoes.length === 0) {
            e.preventDefault();
            alert('Você deve adicionar pelo menos uma variação do produto antes de salvar.');
        }
    });

    function adicionarVariacao() {
        const container = document.getElementById('variacoes-container');
        const template = document.getElementById('template-variacao').innerHTML;
        const novaVariacao = template.replace(/__index__/g, variacaoIndex);
        container.insertAdjacentHTML('beforeend', novaVariacao);

        variacaoIndex++;
    }

    function adicionarAtributo(botao) {
        const container     = botao.closest('.variacao').querySelector('.atributos-container');
        const index         = container.closest('.variacao').querySelector('input[name^="variantes["]').name.match(/\d+/)[0];
        const atributoIndex = container.querySelectorAll('.atributo-linha').length;

        const wrapper = document.createElement('div');
        wrapper.classList.add('atributo-linha', 'row', 'mb-2');

        // Select de Atributo
        const selectAtributo = document.createElement('select');
        selectAtributo.classList.add('form-select', 'col');
        selectAtributo.name = `variantes[${index}][atributos][${atributoIndex}][id]`;
        selectAtributo.innerHTML = '<option value="">Selecione o Atributo</option>';

        atributosPorCategoria.forEach(attr => {
            selectAtributo.innerHTML += `<option value="${attr.id}">${attr.name}</option>`;
        });

        // Select de Opção
        const selectOpcao = document.createElement('select');
        selectOpcao.classList.add('form-select', 'col');
        selectOpcao.name = `variantes[${index}][atributos][${atributoIndex}][opcao_id]`;

        selectAtributo.addEventListener('change', function () {
            const idSelecionado = this.value;
            const atributo = atributosPorCategoria.find(a => a.id == idSelecionado);

            selectOpcao.innerHTML = '';
            if (atributo && atributo.options) {
                atributo.options.forEach(op => {
                    selectOpcao.innerHTML += `<option value="${op.id}">${op.value}</option>`;
                });
            }

            atualizarSelectsDeAtributo(container);
        });

        wrapper.appendChild(selectAtributo);
        wrapper.appendChild(selectOpcao);
        container.appendChild(wrapper);

        atualizarSelectsDeAtributo(container);
    }

    function atualizarSelectsDeAtributo(container) {
        const selects = container.querySelectorAll('select[name*="[atributos]"][name*="[id]"]');

        const valoresSelecionados = Array.from(selects)
            .map(s => s.value)
            .filter(v => v);

        selects.forEach(select => {
            const valorAtual = select.value;
            select.innerHTML = '<option value="">Selecione o Atributo</option>';

            atributosPorCategoria.forEach(attr => {
                const jaSelecionado = valoresSelecionados.includes(attr.id.toString());
                const estaSelecionadoNeste = valorAtual == attr.id;

                if (!jaSelecionado || estaSelecionadoNeste) {
                    const opt = document.createElement('option');
                    opt.value = attr.id;
                    opt.textContent = attr.name;
                    if (estaSelecionadoNeste) opt.selected = true;
                    select.appendChild(opt);
                }
            });
        });
    }

    function adicionarVariacaoPreCarregada(variacao) {
        const container     = document.getElementById('variacoes-container');
        const template      = document.getElementById('template-variacao').innerHTML;
        const novaVariacao  = template.replace(/__index__/g, variacaoIndex);
        container.insertAdjacentHTML('beforeend', novaVariacao);

        const nova = container.lastElementChild;

        nova.querySelector('input[name$="[sku]"]').value    = variacao.sku || '';
        nova.querySelector('input[name$="[price]"]').value  = variacao.price || '';
        nova.querySelector('input[name$="[stock]"]').value  = variacao.quantity || '';

        const btnAddAtributo    = nova.querySelector('.adicionar-atributo-btn');
        btnAddAtributo.disabled = !document.getElementById('category_id').value;

        // Adiciona os atributos
        if (variacao.attributes && Array.isArray(variacao.attributes)) {

            variacao.attributes.forEach(attr => {
                adicionarAtributo(btnAddAtributo);
                const linhas  = nova.querySelectorAll('.atributo-linha');
                const ultima  = linhas[linhas.length - 1];
                const selects = ultima.querySelectorAll('select');

                if (selects[0]) selects[0].value = attr.attribute_id;
                if (selects[1]) selects[1].innerHTML = ''; // limpa antes de preencher

                const atributo = atributosPorCategoria.find(a => a.id == attr.attribute_id);

                if (atributo && atributo.options) {
                    console.log(atributo.options);
                    atributo.options.forEach(op => {
                        const option        = document.createElement('option');
                        option.value        = op.id;
                        option.textContent  = op.value;
                        
                        if (op.id == attr.option_id) {
                            option.selected = true;
                        }

                        selects[1].appendChild(option);
                    });
                }
            });
        }

        variacaoIndex++;
    }
</script>
