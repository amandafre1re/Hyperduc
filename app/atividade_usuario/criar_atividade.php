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
$id_atv = $_POST['id_atv'] ?? null;
$data_comeco = $_POST['data_comeco'] ?? null;
$data_termino = $_POST['data_termino'] ?? null;
$estado = $_POST['estado'] ?? 'A Fazer';

if (!$id_projeto || !$id_atv || !$data_comeco || !$data_termino) {
    http_response_code(400);
    echo json_encode(['codigo' => false, 'msg' => 'Preencha todos os campos obrigatórios.']);
    exit;
}

$sql = "INSERT INTO atividade_usuario (id_atv, id_usuario, id_projeto, data_comeco, data_termino, estado) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiisss", $id_atv, $id_usuario, $id_projeto, $data_comeco, $data_termino, $estado);
if ($stmt->execute()) {
    echo json_encode(['codigo' => true, 'msg' => 'Associação criada com sucesso.']);
} else {
    echo json_encode(['codigo' => false, 'msg' => 'Erro ao criar associação: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
