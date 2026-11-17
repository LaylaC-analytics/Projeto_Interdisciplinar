
<?php

require_once __DIR__ . '/../includes/funcoes.php';

exigir_admin();

$titulo = 'Dashboard — Administração LUMIA';

$avisos = array();

$visitas = 0;
$pedidos = 0;
$receita = 0;
$ticket = 0;
$clientes = 0;
$estoqueBaixo = 0;

$labels = array();
$vals = array();

/*
|--------------------------------------------------------------------------
| VISITAS
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->query("SELECT COUNT(*) FROM visitas");
    $visitas = (int) $stmt->fetchColumn();

} catch (PDOException $e) {

    error_log('LUMIA Dashboard - visitas: ' . $e->getMessage());
    $avisos[] = 'Não foi possível carregar a quantidade de visitas.';
}

/*
|--------------------------------------------------------------------------
| PEDIDOS E RECEITA
|--------------------------------------------------------------------------
*/

try {

    $pedidos = (int) $pdo->query(
        "SELECT COUNT(*) FROM pedidos"
    )->fetchColumn();

    $receita = (float) $pdo->query(
        "SELECT COALESCE(SUM(total), 0)
         FROM pedidos
         WHERE status <> 'Cancelado'"
    )->fetchColumn();

    $ticket = $pedidos > 0 ? $receita / $pedidos : 0;

} catch (PDOException $e) {

    error_log('LUMIA Dashboard - pedidos: ' . $e->getMessage());
    $avisos[] = 'Não foi possível carregar os dados dos pedidos.';
}

/*
|--------------------------------------------------------------------------
| VENDAS DOS ÚLTIMOS 14 DIAS
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->query(
        "SELECT
            DATE(criado_em) AS dia,
            COUNT(*) AS quantidade
         FROM pedidos
         WHERE criado_em >= DATE_SUB(CURDATE(), INTERVAL 13 DAY)
         GROUP BY DATE(criado_em)
         ORDER BY dia"
    );

    $rows = $stmt->fetchAll();

    foreach ($rows as $r) {

        $labels[] = date('d/m', strtotime($r['dia']));
        $vals[] = (int) $r['quantidade'];
    }

} catch (PDOException $e) {

    error_log('LUMIA Dashboard - gráfico: ' . $e->getMessage());
    $avisos[] = 'Não foi possível carregar o gráfico de vendas.';
}

/*
|--------------------------------------------------------------------------
| TOTAL DE CLIENTES
|--------------------------------------------------------------------------
*/

try {

    $clientes = (int) $pdo->query(
        "SELECT COUNT(*)
         FROM usuarios
         WHERE tipo = 'cliente'"
    )->fetchColumn();

} catch (PDOException $e) {

    error_log('LUMIA Dashboard - clientes: ' . $e->getMessage());
    $avisos[] = 'Não foi possível carregar a quantidade de clientes.';
}

/*
|--------------------------------------------------------------------------
| PRODUTOS COM ESTOQUE BAIXO
|--------------------------------------------------------------------------
*/

try {

    $estoqueBaixo = (int) $pdo->query(
        "SELECT COUNT(*)
         FROM produtos
         WHERE estoque <= 5 AND ativo = 1"
    )->fetchColumn();

} catch (PDOException $e) {

    error_log('LUMIA Dashboard - estoque: ' . $e->getMessage());
    $avisos[] = 'Não foi possível carregar os dados do estoque.';
}

/*
|--------------------------------------------------------------------------
| CARREGAMENTO DA INTERFACE
|--------------------------------------------------------------------------
*/

require __DIR__ . '/../includes/header.php';

?>

<div class="admin-shell">

    <?php require __DIR__ . '/_menu.php'; ?>

    <section>

        <div class="section-title">

            <div class="hero-kicker">
                Administração LUMIA
            </div>

            <h1>
                Dashboard de Performance
            </h1>

            <p class="muted">
                Acompanhe os principais indicadores da sua perfumaria.
            </p>

        </div>

        <?php if (!empty($avisos)): ?>

            <div class="alert alert-warning">

                <strong>Atenção:</strong>

                <ul class="mb-0 mt-2">

                    <?php foreach ($avisos as $aviso): ?>

                        <li><?= e($aviso) ?></li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>

        <!-- INDICADORES PRINCIPAIS -->

        <div class="row g-3 mb-4">

            <div class="col-6 col-xl-3">

                <div class="metric-card">

                    <div class="metric-label">
                        Visitas registradas
                    </div>

                    <div class="metric-value">
                        <?= number_format($visitas, 0, ',', '.') ?>
                    </div>

                </div>

            </div>

            <div class="col-6 col-xl-3">

                <div class="metric-card">

                    <div class="metric-label">
                        Conversão em pedidos
                    </div>

                    <div class="metric-value">

                        <?= $visitas > 0
                            ? number_format(($pedidos / $visitas) * 100, 1, ',', '.')
                            : '0,0' ?>%

                    </div>

                </div>

            </div>

            <div class="col-6 col-xl-3">

                <div class="metric-card">

                    <div class="metric-label">
                        Ticket médio
                    </div>

                    <div class="metric-value">
                        <?= preco($ticket) ?>
                    </div>

                </div>

            </div>

            <div class="col-6 col-xl-3">

                <div class="metric-card">

                    <div class="metric-label">
                        Receita registrada
                    </div>

                    <div class="metric-value">
                        <?= preco($receita) ?>
                    </div>

                </div>

            </div>

        </div>

        <!-- GRÁFICO E INDICADORES -->

        <div class="row g-3">

            <div class="col-xl-8">

                <div class="panel">

                    <h2 class="h5 mb-3">
                        Vendas por dia
                    </h2>

                    <p class="muted">
                        Últimos 14 dias
                    </p>

                    <div class="chart-wrap">

                        <canvas id="vendasChart"></canvas>

                    </div>

                </div>

            </div>

            <div class="col-xl-4">

                <div class="panel mb-3">

                    <h2 class="h5 mb-3">
                        Indicadores gerais
                    </h2>

                    <p>
                        Pedidos
                        <strong class="float-end">
                            <?= number_format($pedidos, 0, ',', '.') ?>
                        </strong>
                    </p>

                    <hr>

                    <p>
                        Compra média
                        <strong class="float-end">
                            <?= preco($ticket) ?>
                        </strong>
                    </p>

                    <hr>

                    <p class="mb-0">
                        Clientes cadastrados
                        <strong class="float-end">
                            <?= number_format($clientes, 0, ',', '.') ?>
                        </strong>
                    </p>

                </div>

                <div class="panel">

                    <h2 class="h5 mb-3">
                        Estoque
                    </h2>

                    <p class="mb-0">
                        Produtos com estoque baixo:

                        <strong class="float-end">
                            <?= number_format($estoqueBaixo, 0, ',', '.') ?>
                        </strong>
                    </p>

                    <small class="muted">
                        Consideramos estoque baixo quando há 5 unidades ou menos.
                    </small>

                </div>

            </div>

        </div>

    </section>

</div>

<!-- CARREGAR CHART.JS ANTES DE CRIAR O GRÁFICO -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const elemento = document.getElementById('vendasChart');

    if (elemento && typeof Chart !== 'undefined') {

        new Chart(elemento, {

            type: 'bar',

            data: {

                labels: <?= json_encode($labels) ?>,

                datasets: [{

                    label: 'Pedidos',

                    data: <?= json_encode($vals) ?>,

                    backgroundColor: '#9bcbe0',

                    borderRadius: 4

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    }

                },

                scales: {

                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }

                }

            }

        });

    }

});

</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>
