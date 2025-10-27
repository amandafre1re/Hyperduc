<?php
session_start();
include_once '../../app/conexao.php';

// Permite visualização pública de projetos por id
$id_projeto = $_GET['id'] ?? null;
if (!$id_projeto) {
    http_response_code(400);
    echo json_encode(['codigo' => false, 'msg' => 'ID do projeto não informado.']);
    exit;
}

$sql = "SELECT p.id_projeto, p.nm_projeto, p.desc_projeto, p.data_criacao, u.nome_usuario, p.id_usuario FROM projeto p LEFT JOIN usuario u ON p.id_usuario = u.id_usuario WHERE p.id_projeto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_projeto);
$stmt->execute();
$result = $stmt->get_result();
$projeto = $result->fetch_assoc();
$stmt->close();
$conn->close();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($projeto);
