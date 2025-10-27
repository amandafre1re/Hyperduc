<?php
session_start();
include_once './connection.php';

if (!isset($_POST['email']) || !isset($_POST['senha'])) {
    echo json_encode(['codigo' => false, 'msg' => 'Preencha todos os campos.']);
    exit;
}

$email = $_POST['email'];
$senha = $_POST['senha'];

$stmt = $conn->prepare("SELECT id_user, name_user, email_user, password_user FROM user WHERE email_user = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha, $usuario['password_user'])) {
        $_SESSION['user_id'] = $usuario['id_user'];

        echo json_encode(['codigo' => true, 'msg' => 'Login realizado com sucesso!']);
    } else {
        echo json_encode(['codigo' => false, 'msg' => 'Senha incorreta.']);
    }
} else {
    echo json_encode(['codigo' => false, 'msg' => 'Usuário não encontrado.']);
}

$stmt->close();
$conn->close();
