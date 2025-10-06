<?php
session_start();
include_once '../../app/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['codigo' => false, 'msg' => 'Usuário não logado.']);
    exit;
}


$id_atv_usuario = $_POST['id_atv_usuario'] ?? null;
if (!$id_atv_usuario) {
    http_response_code(400);
    echo json_encode(['codigo' => false, 'msg' => 'Dados insuficientes para deletar.']);
    exit;
}

$sql = "DELETE FROM atividade_usuario WHERE id_atv_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_atv_usuario);
if ($stmt->execute()) {
    echo json_encode(['codigo' => true, 'msg' => 'Associação removida com sucesso.']);
} else {
    echo json_encode(['codigo' => false, 'msg' => 'Erro ao remover associação: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
