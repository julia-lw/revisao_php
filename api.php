<?php
session_start();
header('Content-Type: application/json');
require 'conexao.php';

$tipo = $_GET['tipo'] ?? 'fotos'; // PHP Superglobal $_GET

if ($tipo === 'fotos') {
    // MySQL INNER JOIN para relacionar a tabela de fotos com a de usuários
    $sql = "SELECT f.id, f.titulo, f.caminho, f.criado_em, u.nome AS autor 
            FROM fotos f 
            INNER JOIN usuarios u ON f.usuario_id = u.id 
            ORDER BY f.id DESC";
            
    $stmt = $pdo->query($sql);
    echo json_encode(['sucesso' => true, 'fotos' => $stmt->fetchAll()]);

} elseif ($tipo === 'estatisticas') {
    // MySQL COUNT() com LEFT JOIN e GROUP BY
    $sql = "SELECT u.nome, COUNT(f.id) AS total_fotos 
            FROM usuarios u 
            LEFT JOIN fotos f ON u.id = f.usuario_id 
            GROUP BY u.id";

    $stmt = $pdo->query($sql);
    echo json_encode(['sucesso' => true, 'estatisticas' => $stmt->fetchAll()]);
}