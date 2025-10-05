<?php
session_start();
include_once '../../app/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['codigo' => false, 'msg' => 'Usuário não logado.']);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id_projeto = $_GET['id'] ?? null;
if (!$id_projeto) {
    http_response_code(400);
    echo json_encode(['codigo' => false, 'msg' => 'ID do projeto não informado.']);
    exit;
}

$sql = "SELECT id_projeto, nm_projeto, desc_projeto FROM projeto WHERE id_projeto = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_projeto, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$projeto = $result->fetch_assoc();
$stmt->close();
$conn->close();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($projeto);
