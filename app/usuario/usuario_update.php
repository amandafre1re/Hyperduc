<?php
session_start();
include_once '../conexao.php';

$resposta = [];

if (!isset($_SESSION['usuario_id'])) {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Usuário não logado.';
    echo json_encode($resposta);
    exit;
}

$id = $_SESSION['usuario_id'];

$nome = $_POST['nome_usuario'];
$email = $_POST['email_usuario'];
$cidade = $_POST['cidade'];
$uf = $_POST['uf'];
$pais = $_POST['pais'];

$sql = "UPDATE usuario SET nome_usuario = ?, email_usuario = ?, cidade = ?, uf = ?, pais = ? WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $nome, $email, $cidade, $uf, $pais, $id);

if ($stmt->execute()) {
    $resposta['codigo'] = true;
    $resposta['msg'] = 'Perfil atualizado com sucesso.';
} else {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Erro ao atualizar perfil.';
}

$stmt->close();
$conn->close();
echo json_encode($resposta);
?>
