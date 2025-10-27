<?php
session_start();
include_once '../../app/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['codigo' => false, 'msg' => 'Usuário não logado.']);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id_projeto = $_GET['id_projeto'] ?? null;
if (!$id_projeto) {
    http_response_code(400);
    echo json_encode(['codigo' => false, 'msg' => 'ID do projeto não informado.']);
    exit;
}

$sql = "SELECT au.id_atv_usuario, au.id_atv, au.id_usuario, au.id_projeto, au.data_comeco, au.data_termino, au.estado, a.nm_atividade
        FROM atividade_usuario au
        JOIN atividade a ON au.id_atv = a.idAtividade
        WHERE au.id_usuario = ? AND au.id_projeto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_projeto);
$stmt->execute();
$result = $stmt->get_result();
$atividades = [];
while ($row = $result->fetch_assoc()) {
    $atividades[] = $row;
}
$stmt->close();
$conn->close();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($atividades);
