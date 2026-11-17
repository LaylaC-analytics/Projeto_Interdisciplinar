
<?php

require_once __DIR__ . '/includes/funcoes.php';

$erro = '';

/*
|--------------------------------------------------------------------------
| VERIFICAR SE O USUÁRIO JÁ ESTÁ LOGADO
|--------------------------------------------------------------------------
*/

if (usuario_logado()) {

    if (($_SESSION['usuario']['tipo'] ?? '') === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: index.php');
    }

    exit;
}

/*
|--------------------------------------------------------------------------
| PROCESSAMENTO DO LOGIN
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {

        $erro = 'Preencha o e-mail e a senha.';

    } else {

        try {

            $stmt = $pdo->prepare(
                'SELECT * FROM usuarios WHERE email = ? AND ativo = 1 LIMIT 1'
            );

            $stmt->execute(array($email));

            $u = $stmt->fetch();

            $senhaValida = false;

            if ($u) {

                // Verificação de senha com password_hash.
                if (password_verify($senha, $u['senha'])) {

                    $senhaValida = true;

                // Compatibilidade com senhas antigas em MD5.
                } elseif (
                    strlen($u['senha']) === 32 &&
                    hash_equals(strtolower($u['senha']), md5($senha))
                ) {

                    $senhaValida = true;

                    // Atualiza a senha antiga para hash seguro.
                    $novaHash = password_hash($senha, PASSWORD_DEFAULT);

                    $up = $pdo->prepare(
                        'UPDATE usuarios SET senha = ? WHERE id = ?'
                    );

                    $up->execute(array($novaHash, $u['id']));
                }
            }

            if ($senhaValida) {

                // Regenera o identificador da sessão.
                session_regenerate_id(true);

                // Armazena os dados do usuário na sessão.
                $_SESSION['usuario'] = array(
                    'id' => (int) $u['id'],
                    'nome' => $u['nome'],
                    'email' => $u['email'],
                    'tipo' => $u['tipo']
                );

                // Atualiza a data do último acesso.
                $atualizarLogin = $pdo->prepare(
                    'UPDATE usuarios SET ultimo_login = NOW() WHERE id = ?'
                );

                $atualizarLogin->execute(array($u['id']));

                // Registra a ação no sistema.
                log_acao(
                    'Login realizado',
                    'usuarios',
                    (int) $u['id']
                );

                // Redirecionamento conforme o tipo de usuário.
                if ($u['tipo'] === 'admin') {

                    header('Location: admin/dashboard.php');

                } else {

                    header('Location: index.php');
                }

                exit;

            } else {

                $erro = 'E-mail ou senha inválidos.';
            }

        } catch (PDOException $e) {

            error_log('LUMIA - erro no login: ' . $e->getMessage());

            $erro = 'Não foi possível realizar o login. Tente novamente.';
        }
    }
}

/*
|--------------------------------------------------------------------------
| EXIBIÇÃO DA TELA DE LOGIN
|--------------------------------------------------------------------------
*/

$titulo = 'Entrar';

require __DIR__ . '/includes/header.php';

?>

<div class="row justify-content-center">

    <div class="col-md-6 col-lg-5">

        <div class="panel">

            <div class="hero-kicker">
                Bem-vinda(o) de volta
            </div>

            <h1 class="mb-4">
                Acessar minha conta
            </h1>

            <?php if ($erro): ?>

                <div class="alert alert-danger">
                    <?= e($erro) ?>
                </div>

            <?php endif; ?>

            <?php if (isset($_GET['cadastro'])): ?>

                <div class="alert alert-success">
                    Cadastro realizado! Entre com seu e-mail e senha.
                </div>

            <?php endif; ?>

            <form method="post" action="login.php" autocomplete="on">

                <label class="form-label" for="email">
                    E-mail
                </label>

                <input
                    id="email"
                    class="form-control mb-3"
                    type="email"
                    name="email"
                    required
                    autocomplete="username"
                    value="<?= e($_POST['email'] ?? '') ?>"
                >

                <label class="form-label" for="senha">
                    Senha
                </label>

                <input
                    id="senha"
                    class="form-control mb-4"
                    type="password"
                    name="senha"
                    required
                    autocomplete="current-password"
                >

                <button
                    class="btn btn-lumia w-100"
                    type="submit"
                >
                    Entrar
                </button>

            </form>

            <p class="mt-3 mb-0 text-center">

                Ainda não tem conta?

                <a href="cadastro.php">
                    Cadastre-se
                </a>

            </p>

        </div>

    </div>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
