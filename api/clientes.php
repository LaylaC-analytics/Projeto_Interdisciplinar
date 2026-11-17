<?php
require_once '../includes/funcoes.php';header('Content-Type: application/json; charset=utf-8');exigir_admin();
$rows=$pdo->query("SELECT id,nome,email,telefone,criado_em FROM usuarios WHERE tipo='cliente' AND ativo=1")->fetchAll();
echo json_encode(['sucesso'=>true,'total'=>count($rows),'dados'=>$rows],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
