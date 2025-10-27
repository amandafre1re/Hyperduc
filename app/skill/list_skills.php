<?php
session_start();
include_once '../connection.php';

header("Content-Type: application/json; charset=utf-8");

$skill_type = isset($_GET['skill_type']) ? $_GET['skill_type'] : null;

if ($skill_type) {
    $stmt = $conn->prepare("SELECT id_skill, name_skill, skill_type FROM skill WHERE skill_type = ?");
    $stmt->bind_param("s", $skill_type);
} else {
    $stmt = $conn->prepare("SELECT id_skill, name_skill, skill_type FROM skill");
}

$stmt->execute();
$resultado = $stmt->get_result();

$skills = [];
while ($row = $resultado->fetch_assoc()) {
    $skills[] = $row;
}

echo json_encode(['codigo' => true, 'skills' => $skills]);

$stmt->close();
$conn->close();
?>
