<?php
// logout.php - Encerra a sessão e redireciona para login

// Inicia a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Registra o logout (opcional - para logs)
if (isset($_SESSION['funcionario'])) {
    error_log("Logout realizado por: " . $_SESSION['funcionario']);
}

// Limpa todos os dados da sessão
$_SESSION = [];

// Invalida o cookie de sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destrói a sessão
session_destroy();

// Redireciona para a página de login com mensagem (opcional)
header("Location: login.php?logout=1");
exit();
?>