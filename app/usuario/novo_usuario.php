<?php
include '../conexao.php'; 

header("Content-Type: application/json; charset=utf-8");
session_start();

$nome = $_POST['nome_usuario'];
$email = $_POST['email_usuario'];
$senha = $_POST['senha_usuario'];
$data_nasc = $_POST['data_nasc'];
$cidade = $_POST['cidade'];
$uf = $_POST['uf'];
$pais = $_POST['pais'];

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuario (nome_usuario, email_usuario, senha_usuario, data_nasc, cidade, uf, pais) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $nome, $email, $senha_hash, $data_nasc, $cidade, $uf, $pais);

$resposta = [];
if ($stmt->execute()) {
    $resposta['msg'] = "Usuário cadastrado com sucesso!";
    $resposta['codigo'] = true;
} else {
    $resposta['msg'] = "Erro ao cadastrar usuário: " . $stmt->error;
    $resposta['codigo'] = false;
}

$stmt->close();

echo json_encode($resposta);
?>