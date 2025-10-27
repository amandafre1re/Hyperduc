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
$sql = "SELECT name_user, email_user, city, uf, country FROM user WHERE id_user = ?";
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
