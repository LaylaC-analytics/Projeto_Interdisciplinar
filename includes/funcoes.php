<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/conexao.php';

function e($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function usuario_logado() {
    return !empty($_SESSION['usuario']['id']);
}

function exigir_login() {
    if (!usuario_logado()) {
        header('Location: login.php');
        exit;
    }
}

function exigir_admin() {
    exigir_login();
    if (($_SESSION['usuario']['tipo'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Acesso restrito ao administrador.');
    }
}

function log_acao($acao, $tabela = null, $registroId = null) {
    global $pdo;
    try {
        $usuarioId = !empty($_SESSION['usuario']['id']) ? (int)$_SESSION['usuario']['id'] : null;
        $ip = substr($_SERVER['REMOTE_ADDR'] ?? '', 0, 50);
        $stmt = $pdo->prepare('INSERT INTO logs (usuario_id, acao, tabela_afetada, registro_id, ip) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute(array($usuarioId, $acao, $tabela, $registroId, $ip));
    } catch (PDOException $e) {
        // O registro de log nao deve impedir a operacao principal.
        error_log('LUMIA - falha ao registrar log: ' . $e->getMessage());
    }
}

function preco($valor) {
    return 'R$ ' . number_format((float)$valor, 2, ',', '.');
}

function url_base($path = '') {
    return $path;
}
