<?php
session_start();
include_once '../../app/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['codigo' => false, 'msg' => 'Usuário não logado.']);
    exit;
}


$id_atv_usuario = $_POST['id_atv_usuario'] ?? null;
$id_atv = $_POST['id_atv'] ?? null;
$data_comeco = $_POST['data_comeco'] ?? null;
$data_termino = $_POST['data_termino'] ?? null;
$estado = $_POST['estado'] ?? null;

if (!$id_atv_usuario || !$id_atv || !$data_comeco || !$data_termino || !$estado) {
    http_response_code(400);
    echo json_encode(['codigo' => false, 'msg' => 'Preencha todos os campos obrigatórios.']);
    exit;
}

$sql = "UPDATE atividade_usuario SET id_atv = ?, data_comeco = ?, data_termino = ?, estado = ? WHERE id_atv_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssi", $id_atv, $data_comeco, $data_termino, $estado, $id_atv_usuario);
if ($stmt->execute()) {
    echo json_encode(['codigo' => true, 'msg' => 'Associação atualizada com sucesso.']);
} else {
    echo json_encode(['codigo' => false, 'msg' => 'Erro ao atualizar associação: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
