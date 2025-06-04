<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 m-0"><?= isset($category) ? 'Editar Categoria' : 'Nova Categoria' ?></h1>
    <a href="<?= site_url('category') ?>" class="btn btn-outline-secondary">Voltar</a>
</div>

<form method="post" action="<?= site_url('category/store') ?>">
    <?php if (isset($category)): ?>
        <input type="hidden" name="id" value="<?= $category->id ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label for="name" class="form-label">Nome da Categoria</label>
        <input type="text" name="name" id="name" class="form-control" required
               value="<?= $category->name ?? '' ?>">
    </div>

    <div class="mb-4">
        <label class="form-label">Atributos da Categoria</label>
        
        <div class="mb-3">
            <label for="attribute-search" class="form-label">+ Adicionar Atributo</label>
            <input type="text" id="attribute-search" class="form-control" placeholder="Digite o nome do atributo...">
            <div id="attribute-suggestions" class="list-group mt-1" style="position: absolute; z-index: 1000;"></div>
        </div>
        <div id="new-attributes-container" class="mb-3"></div>
        <button type="button" class="btn btn-sm btn-outline-secondary mb-3" data-bs-toggle="modal" data-bs-target="#attributeModal">
            + Criar Novo Atributo
        </button>

    </div>

    <button type="submit" class="btn btn-primaria">Salvar Categoria</button>
</form>

<!-- Modal -->
<div class="modal fade" id="attributeModal" tabindex="-1" aria-labelledby="attributeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?= site_url('category/add_attribute_modal') ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="attributeModalLabel">Novo Atributo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="attr-name" class="form-label">Nome do atributo</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="attr-type" class="form-label">Tipo</label>
                        <select class="form-select" name="input_type" required>
                        <option value="text">Texto</option>
                        <option value="number">Número</option>
                        <option value="select">Seleção (ex: P,M,G)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="attr-values" class="form-label">Valores (separados por vírgula)</label>
                        <input type="text" class="form-control" name="values" placeholder="Ex: P,M,G">
                    </div>

                    <input type="hidden" name="category_id" value="<?= $category->id ?? '' ?>">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitAttributeModal()">Salvar Atributo</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function addNewAttributeField() {
        const container = document.getElementById('new-attributes-container');
        const index = container.children.length;

        const wrapper = document.createElement('div');
        wrapper.classList.add('row', 'mb-2');
        wrapper.innerHTML = `
            <div class="col-md-6">
                <input type="text" name="new_attributes[${index}][name]" class="form-control" placeholder="Nome do atributo" required>
            </div>
            <div class="col-md-4">
                <select name="new_attributes[${index}][input_type]" class="form-select" required>
                    <option value="text">Texto</option>
                    <option value="number">Número</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.parentElement.remove()">✕</button>
            </div>
        `;
        container.appendChild(wrapper);
    }

    function submitAttributeModal() {
        const form      = document.querySelector('#attributeModal form');
        const formData  = new FormData(form);

        fetch('<?= site_url('category/add_attribute_ajax') ?>', {
            method: 'POST',
            body: new URLSearchParams([...formData])
        })
        .then(async res => {
            try {
                const data = await res.json();
                if (!data.success) {
                    alert('Erro ao criar atributo.');
                    return;
                }

                const container = document.getElementById('new-attributes-container');
                const col       = document.createElement('div');
                col.className   = 'col-md-4 mb-2';
                col.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked
                            name="attributes[]" value="${data.attribute.id}" id="attr${data.attribute.id}">
                        <label class="form-check-label" for="attr${data.attribute.id}">
                            ${data.attribute.name} (${data.attribute.input_type})
                        </label>
                    </div>`;
                container.appendChild(col);

                document.querySelector('#attributeModal form').reset();
                bootstrap.Modal.getInstance(document.getElementById('attributeModal')).hide();
            } catch (e) {
                console.error('Erro ao interpretar resposta JSON:', e);
                alert('Resposta inesperada do servidor.');
            }
        })
        .catch(err => {
            console.error('Erro na requisição fetch:', err);
            alert('Erro de rede ou servidor.');
        });
    }

    // Busca de atributos
    document.getElementById('attribute-search').addEventListener('input', function() {
        const query = this.value.trim();
        const suggestionBox = document.getElementById('attribute-suggestions');

        if (query.length < 2) {
            suggestionBox.innerHTML = '';
            return;
        }

        fetch('<?= site_url('category/search_attributes') ?>?q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                suggestionBox.innerHTML = '';
                data.forEach(attr => {
                    const item = document.createElement('button');
                    item.className = 'list-group-item list-group-item-action';
                    item.type = 'button';
                    item.innerText = `${attr.name} (${attr.input_type})`;
                    item.onclick = () => {
                        addAttributeToCategory(attr);
                        suggestionBox.innerHTML = '';
                        document.getElementById('attribute-search').value = '';
                    };
                    suggestionBox.appendChild(item);
                });
            });
    });

    function addAttributeToCategory(attr) {
        const container = document.getElementById('new-attributes-container');

        // Evita duplicados
        if (document.getElementById(`attr${attr.id}`)) return;

        const col = document.createElement('div');
        col.className = 'col-md-4 mb-2';
        col.innerHTML = `
            <div class="form-check">
                <input class="form-check-input" type="checkbox" checked
                    name="attributes[]" value="${attr.id}" id="attr${attr.id}">
                <label class="form-check-label" for="attr${attr.id}">
                    ${attr.name} (${attr.input_type})
                </label>
            </div>`;
        container.appendChild(col);
    }

</script>
