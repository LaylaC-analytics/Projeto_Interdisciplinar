LUMIA — Projeto N2
Sistema acadêmico de perfumaria online desenvolvido em PHP, MySQL, Bootstrap e JavaScript.

INSTALAÇÃO LOCAL (XAMPP)
1. Copie a pasta LUMIA_N2 para htdocs (ou renomeie para lumia).
2. Inicie Apache e MySQL no XAMPP.
3. Acesse phpMyAdmin e importe database/lumia.sql.
4. Confira as credenciais em config/conexao.php.
5. Acesse http://localhost/LUMIA_N2/setup_admin.php.
6. Crie o administrador. Depois, APAGUE setup_admin.php.
7. Acesse http://localhost/LUMIA_N2/login.php.

TELAS PRINCIPAIS
- index.php: início e destaques da loja.
- produtos.php / detalhe_produto.php: catálogo.
- carrinho.php / checkout.php: compra e registro de pedidos.
- perfil.php: dados do cliente e histórico de pedidos.
- admin/dashboard.php: análise global.
- admin/produtos_estrela.php: ranking de produtos.
- admin/crescimento.php: evolução de faturamento.
- admin/produtos.php, categorias.php, marcas.php: gestão de catálogo.
- admin/pedidos.php, usuarios.php, logs.php: gestão administrativa.
- api/produtos.php: API pública de produtos (JSON).
- api/clientes.php e api/pedidos.php: APIs restritas ao administrador.

IMPORTANTE
- O checkout registra pedidos para demonstração acadêmica; não integra gateway de pagamento.
- Os gráficos usam dados registrados no banco. Para resultados coerentes, cadastre clientes e realize pedidos de teste.
- A página de setup_admin.php deve ser removida após a criação do administrador.
- Antes da publicação, configure usuário/senha MySQL da hospedagem, HTTPS e permissões adequadas.
- O projeto deve ser testado no ambiente da faculdade/hospedagem antes da apresentação.
