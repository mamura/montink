# Loja Virtual em CodeIgniter 3

Este projeto é uma loja virtual desenvolvida em **CodeIgniter 3**, com um CRUD completo de produtos com variações, atributos filtrados por categoria, carrinho de compras utilizando **sessão**, e processo de **checkout completo** com cálculo de frete, envio de pedido e persistência em banco de dados relacional.

## Funcionalidades implementadas

### ✅ Catálogo de Produtos
- Cadastro de produtos com nome, descrição e categoria.
- Cada produto pode ter múltiplas variações (ex: Tamanho, Cor, Modelo).
- Atributos e valores são vinculados por categoria.
- Interface dinâmica para adicionar atributos às variações.
- Estoque controlado por variação (SKU).

### ✅ Atributos e Categorias
- Cadastro de categorias de produtos.
- Cadastro de atributos vinculados à categoria.
- Cada atributo pode ter diversas opções (ex: "Cor" → "Preta", "Azul").
- Filtros dinâmicos com campos de atributo variando por categoria.

### ✅ Carrinho de Compras
- Carrinho gerenciado via **sessão** (`$_SESSION`), sem login obrigatório.
- Possibilidade de adicionar produtos com variações e quantidade.
- Incremento automático da quantidade se o produto já estiver no carrinho.
- Página do carrinho com resumo dos itens, subtotal, frete e total.

### ✅ Checkout
- Página de checkout com:
  - Dados do usuário
  - Endereço com busca automática via [ViaCEP](https://viacep.com.br/)
  - Opções de pagamento (simulado)
- Cálculo de frete:
  - Subtotal entre R$52,00 e R$166,59 → R$15,00
  - Subtotal acima de R$200,00 → Frete grátis
  - Outros casos → R$20,00
- Finalização do pedido persiste:
  - Usuário
  - Pedido
  - Itens do pedido
  - Endereço de entrega
  - Histórico de status

## Estrutura do banco de dados

As tabelas foram criadas com chaves estrangeiras e estrutura normalizada. Algumas principais:

- `categories`, `attributes`, `attribute_options`
- `products`, `product_variants`, `product_variant_values`
- `stock`
- `users`
- `orders`, `order_items`, `order_shipping`, `order_status_history`

**Obs:** o dump completo do banco está no arquivo `database.sql`.

## Requisitos

- PHP 7.4+
- MySQL
- CodeIgniter 3.x
- Bootstrap 5 para layout
- Composer (para autoload e libs auxiliares)

## Como rodar localmente

1. Clone o repositório
2. Configure o banco de dados no arquivo `application/config/database.php`
3. Importe o dump `database.sql`
4. Configure o `base_url` no `application/config/config.php`
5. Inicie o servidor embutido:

```bash
php -S localhost:8000 -t public/
