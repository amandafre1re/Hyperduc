<?php
include '../connection.php'; 

header("Content-Type: application/json; charset=utf-8");
session_start();

$nome = $_POST['name_user'];
$email = $_POST['email_user'];
$senha = $_POST['password_user'];
$data_nasc = $_POST['birth_date'];
$cidade = $_POST['city'];
$uf = $_POST['uf'];
$pais = $_POST['country'];

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO user (name_user, email_user, password_user, birth_date, city, uf, country, is_moderator) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
$stmt->bind_param("sssssss", $nome, $email, $senha_hash, $data_nasc, $cidade, $uf, $pais);

$resposta = [];
if ($stmt->execute()) {
    $resposta['msg'] = "Usuário cadastrado com sucesso!";
    $resposta['codigo'] = true;
    $resposta['user_id'] = $conn->insert_id; // Return the new user ID for skills assignment
} else {
    $resposta['msg'] = "Erro ao cadastrar usuário: " . $stmt->error;
    $resposta['codigo'] = false;
}

$stmt->close();

echo json_encode($resposta);
?>