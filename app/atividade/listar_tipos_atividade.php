<?php
include_once '../../app/conexao.php';
$sql = "SELECT idAtividade, nm_atividade FROM Atividade ORDER BY nm_atividade";
$result = $conn->query($sql);
$atividades = [];
while ($row = $result->fetch_assoc()) {
    $atividades[] = $row;
}
$conn->close();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($atividades);
