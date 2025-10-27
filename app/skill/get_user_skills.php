<?php
session_start();
include_once '../connection.php';

header("Content-Type: application/json; charset=utf-8");

$resposta = [];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Usuário não logado.';
    echo json_encode($resposta);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT s.id_skill, s.name_skill, s.skill_type 
        FROM skill s 
        INNER JOIN user_skill us ON s.id_skill = us.id_skill 
        WHERE us.id_user = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resultado = $stmt->get_result();

$skills = [];
while ($row = $resultado->fetch_assoc()) {
    $skills[] = $row;
}

$resposta['codigo'] = true;
$resposta['skills'] = $skills;

echo json_encode($resposta);

$stmt->close();
$conn->close();
?>
