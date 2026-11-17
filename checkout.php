<?php
require_once 'includes/funcoes.php';exigir_login();$carrinho=$_SESSION['carrinho']??[];if(!$carrinho){header('Location: carrinho.php');exit;}
$ids=array_keys($carrinho);$in=implode(',',array_fill(0,count($ids),'?'));$s=$pdo->prepare("SELECT * FROM produtos WHERE id IN ($in) FOR UPDATE");$s->execute($ids);$produtos=$s->fetchAll();$total=0;foreach($produtos as $p)$total+=(float)$p['preco']*(int)$carrinho[$p['id']];
$erro='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{$pdo->beginTransaction();$s=$pdo->prepare("INSERT INTO pedidos(usuario_id,total,status,endereco) VALUES(?,?,'Pendente',?)");$s->execute([$_SESSION['usuario']['id'],$total,trim($_POST['endereco']??'')]);$pedido=$pdo->lastInsertId();
 foreach($produtos as $p){$q=(int)$carrinho[$p['id']];if($q>(int)$p['estoque'])throw new Exception('Estoque insuficiente para '.$p['nome']);$pdo->prepare("INSERT INTO itens_pedido(pedido_id,produto_id,quantidade,preco_unitario) VALUES(?,?,?,?)")->execute([$pedido,$p['id'],$q,$p['preco']]);$pdo->prepare("UPDATE produtos SET estoque=estoque-? WHERE id=?")->execute([$q,$p['id']]);}
 $pdo->commit();$_SESSION['carrinho']=[];log_acao("Pedido #$pedido criado");header("Location: pedido_sucesso.php?id=$pedido");exit;
 }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$erro=$e->getMessage();}
}
$titulo='Finalizar pedido';require 'includes/header.php';?>
<div class="row justify-content-center"><div class="col-lg-8"><div class="panel"><div class="hero-kicker">Última etapa</div><h1>Finalizar pedido</h1><?php if($erro):?><div class="alert alert-danger"><?=e($erro)?></div><?php endif;?><p class="muted">Total do pedido: <strong><?=preco($total)?></strong></p><form method="post"><label class="form-label">Endereço para entrega</label><textarea class="form-control mb-3" name="endereco" rows="3" required></textarea><div class="alert alert-light border">Projeto acadêmico: esta versão registra o pedido, mas não processa pagamentos reais.</div><button class="btn btn-lumia w-100">Confirmar pedido</button></form></div></div></div>
<?php require 'includes/footer.php';?>
