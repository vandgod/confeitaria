<?php
session_start();

// Dados de exemplo (em produção, usar banco de dados)
$funcionarios = [
    'admin@docesonho.com' => '123',
    'paula@docesonho.com' => '123'
];

$clientes = [
    'cliente@email.com' => '123'
];

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$isFuncionario = isset($_POST['funcionario']);

// Verifica se é login de funcionário
if($isFuncionario) {
    if(array_key_exists($email, $funcionarios) && $funcionarios[$email] === $senha) {
        $_SESSION['funcionario'] = $email;
        header("Location: painel.php");
        exit();
    }
} else {
    // Verifica login de cliente
    if(array_key_exists($email, $clientes) && $clientes[$email] === $senha) {
        $_SESSION['cliente'] = $email;
        header("Location: cardapio.php");
        exit();
    }
}

// Se chegou aqui, o login falhou
header("Location: index.php?erro=1");
exit();
?>