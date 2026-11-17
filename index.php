<?php
require_once 'includes/funcoes.php';
$titulo='LUMIA — Perfumaria Online';
$stmt=$pdo->query("SELECT p.*,c.nome categoria FROM produtos p LEFT JOIN categorias c ON c.id=p.categoria_id WHERE p.ativo=1 ORDER BY p.id DESC LIMIT 4");
$produtos=$stmt->fetchAll();
require 'includes/header.php';
?>
<section class="hero mb-5">
 <div class="row align-items-center"><div class="col-lg-7">
  <div class="hero-kicker mb-3">LUMIA • Perfumaria Online</div>
  <h1 class="display-5 mb-3">Fragrâncias que iluminam o seu dia.</h1>
  <p class="lead muted mb-4">Descubra fragrâncias, cuidados e presentes escolhidos para acompanhar a sua essência.</p>
  <a class="btn btn-lumia px-4 py-2" href="produtos.php">Explorar produtos</a>
 </div><div class="col-lg-5 text-center d-none d-lg-block"><div class="serif" style="font-size:8rem;color:#bd8987">L.</div><div class="hero-kicker">Sua essência, sua luz</div></div></div>
</section>
<div class="d-flex justify-content-between align-items-end section-title"><div><div class="hero-kicker">Seleção especial</div><h2 class="mb-0">Destaques da LUMIA</h2></div><a href="produtos.php" class="text-dark">Ver catálogo →</a></div>
<div class="row g-3"><?php foreach($produtos as $p): ?><div class="col-6 col-lg-3"><div class="product-card"><div class="product-art">LUMIA</div><div class="product-body"><span class="badge badge-soft"><?= e($p['categoria'] ?? 'Fragrância') ?></span><h3 class="h6 mt-2"><?= e($p['nome']) ?></h3><div class="product-price"><?= preco($p['preco']) ?></div><a class="btn btn-outline-dark btn-sm mt-3 w-100" href="detalhe_produto.php?id=<?= (int)$p['id'] ?>">Ver produto</a></div></div></div><?php endforeach; ?></div>
<?php require 'includes/footer.php'; ?>
