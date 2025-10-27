<?php
session_start();
include_once '../conexao.php';

$resposta = [];

if (!isset($_SESSION['usuario_id'])) {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Usuário não logado.';
    echo json_encode($resposta);
    exit;
}

$nome = $_POST['nm_projeto'] ?? '';
$descricao = $_POST['desc_projeto'] ?? '';

if (empty($nome) || empty($descricao)) {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Preencha todos os campos.';
    echo json_encode($resposta);
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$data_criacao = date("Y-m-d H:i:s");

$sql = "INSERT INTO projeto (nm_projeto, desc_projeto, data_criacao, id_usuario) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $descricao, $data_criacao, $id_usuario);

if ($stmt->execute()) {
    $resposta['codigo'] = true;
    $resposta['msg'] = 'Projeto criado com sucesso.';
} else {
    $resposta['codigo'] = false;
    $resposta['msg'] = 'Erro ao criar projeto.';
}

$stmt->close();
$conn->close();
echo json_encode($resposta);
?>
