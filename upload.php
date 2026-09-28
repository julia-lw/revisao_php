<?php
session_start();
header('Content-Type: application/json');
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Sessão expirada. Faça login.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $titulo = trim($_POST['titulo'] ?? 'Sem título');
    $foto   = $_FILES['foto']; // PHP Superglobal $_FILES

    $extensao = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extensao, $permitidas)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Formato não permitido.']);
        exit;
    }

    $pasta = 'uploads/';
    if (!is_dir($pasta)) {
        mkdir($pasta, 0755, true);
    }

    $nomeArquivo = uniqid('img_') . '.' . $extensao;
    $destino = $pasta . $nomeArquivo;

    // Salva o arquivo no disco do servidor
    if (move_uploaded_file($foto['tmp_name'], $destino)) {
        // Prepared Statement salvando a FK da sessão
        $stmt = $pdo->prepare("INSERT INTO fotos (usuario_id, titulo, caminho) VALUES (:u_id, :titulo, :caminho)");
        $stmt->execute([
            ':u_id'    => $_SESSION['usuario_id'],
            ':titulo'  => $titulo,
            ':caminho' => $destino
        ]);

        echo json_encode(['sucesso' => true, 'mensagem' => 'Foto publicada com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar o arquivo.']);
    }
}