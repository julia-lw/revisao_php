<?php
session_start();
header('Content-Type: application/json');
require 'conexao.php';

$acao = $_POST['acao'] ?? '';

if ($acao === 'cadastrar') {
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$nome || !$email || !$senha) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos.']);
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // PHP Prepared Statement (PDO)
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
    try {
        $stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $senhaHash]);
        echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro realizado!']);
    } catch (PDOException $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail já cadastrado.']);
    }

} elseif ($acao === 'login') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // PHP Prepared Statement (PDO)
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // PHP Sessions
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        echo json_encode(['sucesso' => true, 'nome' => $usuario['nome']]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail ou senha incorretos.']);
    }
}