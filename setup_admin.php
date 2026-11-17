<?php
require_once 'includes/funcoes.php';
$existe=(int)$pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo='admin'")->fetchColumn();
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST' && !$existe){
 $nome=trim($_POST['nome']);$email=trim($_POST['email']);$senha=$_POST['senha'];
 if(strlen($senha)<8)$msg='Use uma senha com pelo menos 8 caracteres.';
 else{$s=$pdo->prepare("INSERT INTO usuarios(nome,email,senha,tipo) VALUES(?,?,?,'admin')");$s->execute([$nome,$email,password_hash($senha,PASSWORD_DEFAULT)]);$msg='Administrador criado. Apague setup_admin.php do servidor e faça login.';$existe=1;}
}
?><!doctype html><html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"><title>Configurar administrador LUMIA</title></head><body><main class="container py-5"><div class="col-md-6 mx-auto panel"><h1>Criar administrador LUMIA</h1><?php if($msg):?><div class="alert alert-info"><?=e($msg)?></div><?php endif;?><?php if(!$existe):?><form method="post"><input class="form-control mb-3" name="nome" placeholder="Nome" required><input class="form-control mb-3" name="email" type="email" placeholder="E-mail" required><input class="form-control mb-3" name="senha" type="password" minlength="8" placeholder="Senha (mínimo 8 caracteres)" required><button class="btn btn-lumia">Criar administrador</button></form><?php else:?><p>Já existe um administrador cadastrado. Por segurança, remova este arquivo do servidor.</p><a href="login.php">Ir para login</a><?php endif;?></div></main></body></html>
