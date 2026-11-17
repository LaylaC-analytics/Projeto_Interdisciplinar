<?php
require_once 'includes/funcoes.php';if(!isset($_SESSION['carrinho']))$_SESSION['carrinho']=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(isset($_POST['remover']))unset($_SESSION['carrinho'][(int)$_POST['remover']]);
 elseif(isset($_POST['produto_id'])){$id=(int)$_POST['produto_id'];$q=max(1,(int)($_POST['quantidade']??1));$s=$pdo->prepare("SELECT estoque FROM produtos WHERE id=? AND ativo=1");$s->execute([$id]);$p=$s->fetch();if($p && $p['estoque']>0)$_SESSION['carrinho'][$id]=min(($_SESSION['carrinho'][$id]??0)+$q,(int)$p['estoque']);}
 header('Location: carrinho.php');exit;
}
$itens=[];$total=0;if($_SESSION['carrinho']){$ids=array_keys($_SESSION['carrinho']);$in=implode(',',array_fill(0,count($ids),'?'));$s=$pdo->prepare("SELECT * FROM produtos WHERE id IN ($in)");$s->execute($ids);foreach($s as $p){$q=$_SESSION['carrinho'][$p['id']];$sub=$q*$p['preco'];$total+=$sub;$itens[]=['p'=>$p,'q'=>$q,'sub'=>$sub];}}
$titulo='Carrinho';require 'includes/header.php';?>
<div class="section-title"><div class="hero-kicker">Sua seleção</div><h1>Meu carrinho</h1></div>
<div class="panel"><?php if(!$itens):?><p class="muted mb-3">Seu carrinho está vazio.</p><a class="btn btn-lumia" href="produtos.php">Conhecer produtos</a><?php else:?>
<?php foreach($itens as $i):?><div class="d-flex align-items-center justify-content-between border-bottom py-3 gap-3"><div><strong><?=e($i['p']['nome'])?></strong><div class="muted"><?=preco($i['p']['preco'])?> × <?=$i['q']?></div></div><div class="text-end"><strong><?=preco($i['sub'])?></strong><form method="post"><button class="btn btn-link text-danger p-0" name="remover" value="<?=$i['p']['id']?>">Remover</button></form></div></div><?php endforeach;?>
<div class="text-end mt-4"><div class="muted">Subtotal</div><div class="fs-3 serif"><?=preco($total)?></div><a class="btn btn-lumia mt-2" href="checkout.php">Continuar para checkout</a></div><?php endif;?></div>
<?php require 'includes/footer.php';?>
