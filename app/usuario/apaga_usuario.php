<?php
session_start();
include_once '../conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['codigo' => false, 'msg' => 'Usuário não logado.']);
    exit;
}

$id = $_SESSION['usuario_id'];


// Excluir projetos do usuário primeiro
$sql_projetos = "DELETE FROM projeto WHERE id_usuario = ?";
$stmt_projetos = $conn->prepare($sql_projetos);
$stmt_projetos->bind_param("i", $id);
if (!$stmt_projetos->execute()) {
    echo json_encode(['codigo' => false, 'msg' => 'Erro ao excluir projetos: ' . $stmt_projetos->error]);
    $stmt_projetos->close();
    $conn->close();
    exit;
}
$stmt_projetos->close();

// Agora excluir o usuário
$sql_usuario = "DELETE FROM usuario WHERE id_usuario = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $id);
if ($stmt_usuario->execute()) {
    session_destroy();
    echo json_encode(['codigo' => true]);
} else {
    echo json_encode(['codigo' => false, 'msg' => 'Erro ao excluir conta: ' . $stmt_usuario->error]);
}
$stmt_usuario->close();
$conn->close();
