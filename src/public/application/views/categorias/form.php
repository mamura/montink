<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 m-0"><?= isset($categoria) ? 'Editar Categoria' : 'Nova Categoria' ?></h1>
    <a href="<?= site_url('categoria') ?>" class="btn btn-outline-secondary">Voltar</a>
</div>

<form method="post" action="<?= isset($categoria) ? site_url('categoria/update/' . $categoria->id) : site_url('categoria/store') ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Nome da Categoria</label>
        <input type="text" name="name" id="name" class="form-control" required
               value="<?= isset($categoria) ? htmlspecialchars($categoria->name, ENT_QUOTES, 'UTF-8') : '' ?>">
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
