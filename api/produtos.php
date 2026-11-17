<?php
require_once '../includes/funcoes.php';header('Content-Type: application/json; charset=utf-8');
$rows=$pdo->query("SELECT p.id,p.nome,p.descricao,p.preco,p.estoque,c.nome categoria FROM produtos p LEFT JOIN categorias c ON c.id=p.categoria_id WHERE p.ativo=1 ORDER BY p.id")->fetchAll();
echo json_encode(['sucesso'=>true,'total'=>count($rows),'dados'=>$rows],JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
