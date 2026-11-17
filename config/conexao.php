<?php
/*
 * CONFIGURACAO DA HOSPEDAGEM INFINITYFREE
 * Preencha os dados exibidos em Control Panel > MySQL Databases.
 * Nao compartilhe a senha do banco em conversas ou prints.
 */
$host = 'sql113.infinityfree.com';
$db   = 'if0_43178581_lumiadb';
$user = 'if0_43178581';
$pass = 'eTwlfxie7u';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
);

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Horário oficial de Brasília
    date_default_timezone_set('America/Sao_Paulo');

    // Ajusta o fuso da conexão MySQL
    $pdo->exec("SET time_zone = '-03:00'");

} catch (PDOException $e) {
    error_log('LUMIA - erro de conexao: ' . $e->getMessage());
    http_response_code(500);
    exit('Nao foi possivel conectar ao banco.');
}