<?php
session_start();
include_once '../../app/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['codigo' => false, 'msg' => 'Usuário não logado.']);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$sql = "SELECT id_projeto, nm_projeto, desc_projeto, data_criacao FROM projeto WHERE id_usuario = ? ORDER BY data_criacao DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$projetos = [];
while ($row = $result->fetch_assoc()) {
    $projetos[] = $row;
}
$stmt->close();
$conn->close();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($projetos);
