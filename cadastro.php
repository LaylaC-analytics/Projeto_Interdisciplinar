<?php
require_once 'includes/funcoes.php';$erro='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $nome=trim($_POST['nome']??'');$email=trim($_POST['email']??'');$senha=$_POST['senha']??'';
 if(strlen($senha)<8)$erro='A senha deve ter pelo menos 8 caracteres.';
 else{
  try{$s=$pdo->prepare("INSERT INTO usuarios(nome,email,senha,telefone,tipo) VALUES(?,?,?,?, 'cliente')");$s->execute([$nome,$email,password_hash($senha,PASSWORD_DEFAULT),trim($_POST['telefone']??'')]);header('Location: login.php?cadastro=ok');exit;}
  catch(PDOException $e){$erro='Não foi possível cadastrar. Verifique se o e-mail já está em uso.';}
 }
}
$titulo='Criar conta';require 'includes/header.php';?>
<div class="row justify-content-center"><div class="col-md-7 col-lg-6"><div class="panel"><div class="hero-kicker">Faça parte da LUMIA</div><h1>Criar minha conta</h1><?php if($erro):?><div class="alert alert-danger"><?=e($erro)?></div><?php endif;?>
<form method="post"><label class="form-label">Nome completo</label><input class="form-control mb-3" name="nome" required><label class="form-label">E-mail</label><input class="form-control mb-3" type="email" name="email" required><label class="form-label">Telefone</label><input class="form-control mb-3" name="telefone"><label class="form-label">Senha (mínimo 8 caracteres)</label><input class="form-control mb-4" type="password" name="senha" minlength="8" required><button class="btn btn-lumia w-100">Cadastrar</button></form></div></div></div>
<?php require 'includes/footer.php';?>
