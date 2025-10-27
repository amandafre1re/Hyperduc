<?php
session_start();
include_once '../connection.php';

$resposta = [];

if (!isset($_SESSION['user_id'])) {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Usuário não logado.';
    echo json_encode($resposta);
    exit;
}

$id = $_SESSION['user_id'];

$nome = $_POST['name_user'];
$email = $_POST['email_user'];
$cidade = $_POST['city'];
$uf = $_POST['uf'];
$pais = $_POST['country'];

$sql = "UPDATE user SET name_user = ?, email_user = ?, city = ?, uf = ?, country = ? WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $nome, $email, $cidade, $uf, $pais, $id);

if ($stmt->execute()) {
    $resposta['codigo'] = true;
    $resposta['msg'] = 'Conta atualizada com sucesso.';
} else {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Erro ao atualizar conta.';
}

$stmt->close();
$conn->close();
header("Content_type: application/json; charset: utf-8;");
echo json_encode($resposta);
?>
