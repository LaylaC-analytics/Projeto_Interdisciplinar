
<?php

require_once __DIR__ . '/includes/funcoes.php';

exigir_login();

$id = (int) $_SESSION['usuario']['id'];

$erro = '';
$erroPedidos = '';
$sucesso = isset($_GET['ok']);

$u = null;
$pedidos = [];

try {

    // Busca os dados do usuário
    $stmt = $pdo->prepare("
        SELECT id, nome, email, telefone, tipo
        FROM usuarios
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$id]);
    $u = $stmt->fetch();

    if (!$u) {
        http_response_code(404);
        exit('Usuário não encontrado.');
    }

    // Atualização dos dados pessoais
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = trim($_POST['nome'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');

        if ($nome === '') {

            $erro = 'O nome não pode ficar vazio.';

        } elseif (mb_strlen($nome) > 120) {

            $erro = 'O nome deve ter no máximo 120 caracteres.';

        } elseif (mb_strlen($telefone) > 30) {

            $erro = 'O telefone deve ter no máximo 30 caracteres.';

        } else {

            $stmt = $pdo->prepare("
                UPDATE usuarios
                SET nome = ?, telefone = ?
                WHERE id = ?
            ");

            $stmt->execute([$nome, $telefone, $id]);

            $_SESSION['usuario']['nome'] = $nome;

            log_acao('Perfil atualizado', 'usuarios', $id);

            header('Location: perfil.php?ok=1');
            exit;
        }

        $u['nome'] = $nome;
        $u['telefone'] = $telefone;
    }

} catch (PDOException $e) {

    error_log('LUMIA - erro nos dados do perfil: ' . $e->getMessage());

    http_response_code(500);
    exit('Não foi possível carregar os dados do perfil.');

}


// BUSCA OS PEDIDOS VINCULADOS AO USUÁRIO

try {

    $stmt = $pdo->prepare("
        SELECT
            id,
            data_pedido,
            status,
            total
        FROM pedidos
        WHERE usuario_id = ?
        ORDER BY data_pedido DESC
    ");

    $stmt->execute([$id]);

    $pedidos = $stmt->fetchAll();

} catch (PDOException $e) {

    error_log('LUMIA - erro ao buscar pedidos: ' . $e->getMessage());

    $erroPedidos = 'Não foi possível carregar seus pedidos neste momento.';
}


$titulo = 'Meu perfil';

require __DIR__ . '/includes/header.php';

?>

<div class="section-title">

    <div class="hero-kicker">Minha conta</div>

    <h1>
        Olá, <?= e($u['nome']) ?>
    </h1>

    <p class="muted">
        Gerencie seus dados pessoais e acompanhe suas compras.
    </p>

</div>


<?php if ($sucesso): ?>

    <div class="alert alert-success">
        Seus dados foram atualizados com sucesso!
    </div>

<?php endif; ?>


<?php if ($erro): ?>

    <div class="alert alert-danger">
        <?= e($erro) ?>
    </div>

<?php endif; ?>


<div class="row g-4">

    <!-- DADOS PESSOAIS -->

    <div class="col-lg-5">

        <div class="panel">

            <h2 class="h4 mb-3">
                Dados pessoais
            </h2>

            <form method="POST" action="perfil.php">

                <div class="mb-3">

                    <label class="form-label" for="nome">
                        Nome completo
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        class="form-control"
                        maxlength="120"
                        value="<?= e($u['nome']) ?>"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label" for="email">
                        E-mail
                    </label>

                    <input
                        type="email"
                        id="email"
                        class="form-control"
                        value="<?= e($u['email']) ?>"
                        disabled
                    >

                    <small class="text-muted">
                        O e-mail não pode ser alterado por esta tela.
                    </small>

                </div>


                <div class="mb-4">

                    <label class="form-label" for="telefone">
                        Telefone
                    </label>

                    <input
                        type="text"
                        id="telefone"
                        name="telefone"
                        class="form-control"
                        maxlength="30"
                        value="<?= e($u['telefone'] ?? '') ?>"
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-lumia w-100"
                >
                    Salvar alterações
                </button>

            </form>

        </div>

    </div>


    <!-- PEDIDOS -->

    <div class="col-lg-7">

        <div class="panel">

            <h2 class="h4 mb-3">

                <?= $u['tipo'] === 'admin'
                    ? 'Pedidos vinculados à conta'
                    : 'Meus pedidos' ?>

            </h2>


            <?php if ($erroPedidos): ?>

                <div class="alert alert-warning">
                    <?= e($erroPedidos) ?>
                </div>


            <?php elseif (empty($pedidos)): ?>

                <p class="muted mb-0">
                    Você ainda não possui pedidos registrados.
                </p>


            <?php else: ?>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>
                                <th>Pedido</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($pedidos as $p): ?>

                                <tr>

                                    <td>
                                        #<?= (int) $p['id'] ?>
                                    </td>


                                    <td>

                                        <?php

                                        if (!empty($p['data_pedido'])) {

                                            echo date(
                                                'd/m/Y H:i',
                                                strtotime($p['data_pedido'])
                                            );

                                        } else {

                                            echo '-';

                                        }

                                        ?>

                                    </td>


                                    <td>
                                        <?= e($p['status'] ?? 'Pendente') ?>
                                    </td>


                                    <td>
                                        <?= preco($p['total'] ?? 0) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<?php require __DIR__ . '/includes/footer.php'; ?>
