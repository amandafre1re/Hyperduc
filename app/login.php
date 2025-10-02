<?php
session_start();
include '../conexao_banco.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuario WHERE email_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if ($senha === $usuario['senha_usuario']) {
        $_SESSION['usuario'] = [
            'id' => $usuario['id_usuario'],
            'nome' => $usuario['nome_usuario'],
            'email' => $usuario['email_usuario'],
            'cidade' => $usuario['cidade'],
            'uf' => $usuario['uf'],
            'pais' => $usuario['pais'],
            'data_nasc' => $usuario['data_nasc']
        ];

        echo json_encode(['codigo' => true, 'msg' => 'Login bem-sucedido']);
    } else {
        echo json_encode(['codigo' => false, 'msg' => 'Senha incorreta']);
    }
} else {
    echo json_encode(['codigo' => false, 'msg' => 'Usuário não encontrado']);
}
