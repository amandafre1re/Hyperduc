<?php
include_once '../conexao.php';

$sql = "SELECT p.id_projeto, p.nm_projeto, p.desc_projeto, p.data_criacao, u.nome_usuario FROM projeto p JOIN usuario u ON p.id_usuario = u.id_usuario ORDER BY p.data_criacao DESC LIMIT 12";
$result = $conn->query($sql);
$projetos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
    $projetos[] = $row;
    }
}
$conn->close();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($projetos);