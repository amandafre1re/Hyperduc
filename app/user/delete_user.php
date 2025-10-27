<?php
session_start();
include_once '../connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['codigo' => false, 'msg' => 'Usuário não logado.']);
    exit;
}

$id = $_SESSION['user_id'];

// Delete user's skills first
$sql_skills = "DELETE FROM user_skill WHERE id_user = ?";
$stmt_skills = $conn->prepare($sql_skills);
$stmt_skills->bind_param("i", $id);
$stmt_skills->execute();
$stmt_skills->close();

// Delete user's projects first
$sql_projetos = "DELETE FROM project WHERE id_user = ?";
$stmt_projetos = $conn->prepare($sql_projetos);
$stmt_projetos->bind_param("i", $id);
if (!$stmt_projetos->execute()) {
    echo json_encode(['codigo' => false, 'msg' => 'Erro ao excluir projetos: ' . $stmt_projetos->error]);
    $stmt_projetos->close();
    $conn->close();
    exit;
}
$stmt_projetos->close();

// Now delete the user
$sql_usuario = "DELETE FROM user WHERE id_user = ?";
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
