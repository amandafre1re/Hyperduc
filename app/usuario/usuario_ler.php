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
$sql = "SELECT nome_usuario, email_usuario, cidade, uf, pais FROM usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $resposta['codigo'] = true;
    $resposta['usuario'] = $resultado->fetch_assoc();
} else {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Usuário não encontrado.';
}

$stmt->close();
$conn->close();
header("Content_type: application/json; charset: utf-8;");
echo json_encode($resposta);
?>
