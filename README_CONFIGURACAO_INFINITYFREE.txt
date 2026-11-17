LUMIA - CONFIGURACAO INFINITYFREE

1. Abra config/conexao.php.
2. Confirme os dados em InfinityFree > Control Panel > MySQL Databases:
   Host: sql113.infinityfree.com (confirme no painel)
   Banco: if0_43178581_lumiadb
   Usuario: if0_43178581 (confirme no painel)
   Senha: a senha do banco MySQL definida na hospedagem.
3. Substitua COLOQUE_A_SENHA_DO_MYSQL_AQUI pela senha do banco.
4. Envie os arquivos para htdocs pelo Gerenciador de Arquivos ou FTP.
5. Teste primeiro a pagina inicial e depois login/cadastro.

ADMINISTRADOR IMPORTADO DO SQL:
E-mail: admin@lumia.com
Senha inicial: 123456

IMPORTANTE: o SQL original armazenava essa senha em MD5. O login atualizado aceita
essa senha inicial uma unica vez e converte automaticamente para password_hash().
Depois do primeiro acesso, altere a senha do administrador por uma senha forte.
Nao envie sua senha do banco ou do painel de hospedagem para ninguem.

O projeto usa as tabelas e colunas do dump if0_43178581_lumiadb.sql. A tabela
'visitas' nao existe no banco enviado; por isso o registro de visitas foi retirado
do cabecalho. Os indicadores de visitas precisarao de uma tabela propria ou serao
adaptados para os dados disponiveis.
