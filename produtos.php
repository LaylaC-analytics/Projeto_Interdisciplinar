<?php
require_once 'includes/funcoes.php';$q=trim($_GET['q']??'');$cat=(int)($_GET['categoria']??0);
$sql="SELECT p.*,c.nome categoria FROM produtos p LEFT JOIN categorias c ON c.id=p.categoria_id WHERE p.ativo=1";$args=[];
if($q!==''){$sql.=" AND (p.nome LIKE ? OR p.descricao LIKE ?)";$args[]="%$q%";$args[]="%$q%";}
if($cat){$sql.=" AND p.categoria_id=?";$args[]=$cat;}
$sql.=" ORDER BY p.nome";$s=$pdo->prepare($sql);$s->execute($args);$produtos=$s->fetchAll();$cats=$pdo->query("SELECT * FROM categorias ORDER BY nome")->fetchAll();
$titulo='Produtos';require 'includes/header.php';?>
<div class="section-title"><div class="hero-kicker">Catálogo inteligente</div><h1>Encontre sua fragrância</h1><p class="muted mb-0">Explore perfumes, cuidados corporais e presentes.</p></div>
<form class="row g-2 mb-4"><div class="col-md-7"><input class="form-control" name="q" value="<?=e($q)?>" placeholder="Buscar produto..."></div><div class="col-md-3"><select class="form-select" name="categoria"><option value="0">Todas as categorias</option><?php foreach($cats as $c):?><option value="<?=$c['id']?>" <?=$cat===$c['id']?'selected':''?>><?=e($c['nome'])?></option><?php endforeach;?></select></div><div class="col-md-2"><button class="btn btn-lumia w-100">Filtrar</button></div></form>
<div class="row g-3"><?php foreach($produtos as $p):?><div class="col-6 col-md-4 col-lg-3"><div class="product-card"><div class="product-art">LUMIA</div><div class="product-body"><span class="badge badge-soft"><?=e($p['categoria']??'')?></span><h3 class="h6 mt-2"><?=e($p['nome'])?></h3><div class="product-price"><?=preco($p['preco'])?></div><small class="muted">Estoque: <?= (int)$p['estoque'] ?></small><a href="detalhe_produto.php?id=<?=$p['id']?>" class="btn btn-outline-dark btn-sm w-100 mt-3">Detalhes</a></div></div></div><?php endforeach;?></div>
<?php if(!$produtos):?><p class="muted">Nenhum produto encontrado.</p><?php endif;?><?php require 'includes/footer.php';?>
