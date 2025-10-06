<?php
session_start();
include_once '../../app/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['codigo' => false, 'msg' => 'Usuário não logado.']);
    exit;
}

$id_atv_usuario = isset($_GET['id_atv_usuario']) ? (int)$_GET['id_atv_usuario'] : null;
if (!$id_atv_usuario) {
    http_response_code(400);
    echo json_encode(['codigo' => false, 'msg' => 'ID da associação não informado.']);
    exit;
}

$sql = "SELECT au.id_atv_usuario, au.id_atv, au.id_usuario, au.id_projeto, au.data_comeco, au.data_termino, au.estado, a.nm_atividade
        FROM atividade_usuario au
        JOIN atividade a ON au.id_atv = a.idAtividade
        WHERE au.id_atv_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_atv_usuario);
$stmt->execute();
$result = $stmt->get_result();
$atividade = $result->fetch_assoc();
$stmt->close();
$conn->close();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($atividade);
