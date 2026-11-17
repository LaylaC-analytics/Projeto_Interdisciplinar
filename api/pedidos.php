<?php
require_once '../includes/funcoes.php';header('Content-Type: application/json; charset=utf-8');exigir_admin();
$rows=$pdo->query("SELECT p.id,u.nome cliente,p.total,p.status,p.criado_em FROM pedidos p JOIN usuarios u ON u.id=p.usuario_id ORDER BY p.id DESC")->fetchAll();
echo json_encode(['sucesso'=>true,'total'=>count($rows),'dados'=>$rows],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
