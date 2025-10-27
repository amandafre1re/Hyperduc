<?php
session_start();
include_once '../../app/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['codigo' => false, 'msg' => 'Usuário não logado.']);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id_projeto = $_POST['id_projeto'] ?? null;
if (!$id_projeto) {
    http_response_code(400);
    echo json_encode(['codigo' => false, 'msg' => 'ID do projeto não informado.']);
    exit;
}

$sql = "DELETE FROM projeto WHERE id_projeto = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_projeto, $id_usuario);
if ($stmt->execute()) {
    echo json_encode(['codigo' => true, 'msg' => 'Projeto excluído com sucesso.']);
} else {
    echo json_encode(['codigo' => false, 'msg' => 'Erro ao excluir projeto: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
