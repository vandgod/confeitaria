<?php
session_start();
// Verifica se usuário já está logado
if(isset($_SESSION['funcionario'])) {
    header("Location: painel.php");
    exit();
} elseif(isset($_SESSION['cliente'])) {
    header("Location: cardapio.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doce Sonho - Login</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/temas.css">
</head>

<body class="tema-confeitaria">
    <div class="container login-container">
        <div class="logo">
            <img src="img/logo.png" alt="Doce Sonho Confeitaria">
            <h1>Doce Sonho Confeitaria</h1>
        </div>

        <!-- Formulário de Login -->
        <form action="login.php" method="post" class="form-login">
            <h2>Área de Acesso</h2>

            <div class="input-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com">
            </div>

            <div class="input-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required placeholder="Sua senha">
            </div>

            <div class="input-group">
                <label>
                    <input type="checkbox" name="funcionario"> Sou funcionário
                </label>
            </div>

            <button type="submit" class="btn-doce">Entrar</button>

            <?php if(isset($_GET['erro'])): ?>
            <div class="mensagem erro">
                <p>🍰 Oops! E-mail ou senha incorretos.</p>
            </div>
            <?php endif; ?>
        </form>

        <div class="convite">
            <p>Não é cadastrado? <a href="cardapio.php">Acesse nosso cardápio como visitante!</a></p>
        </div>
    </div>

    <script src="js/scripts.js"></script>
</body>

</html>