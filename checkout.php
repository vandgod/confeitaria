<?php
session_start();

if (empty($_SESSION['carrinho'])) {
    header("Location: cardapio.php");
    exit();
}

// Processar o pedido quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aqui você salvaria no banco de dados
    $pedido = [
        'cliente' => $_POST['nome_cliente'],
        'itens' => $_SESSION['carrinho'],
        'total' => array_reduce($_SESSION['carrinho'], function($total, $item) {
            return $total + ($item['preco'] * ($item['quantidade'] ?? 1));
        }, 0),
        'status' => 'recebido',
        'data' => date('Y-m-d H:i:s')
    ];
    
    // Limpa o carrinho
    $_SESSION['carrinho'] = [];
    
    // Redireciona com mensagem de sucesso
    $_SESSION['mensagem'] = "Pedido realizado com sucesso! Nº " . rand(1000, 9999);
    header("Location: cardapio.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Finalizar Pedido</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body class="tema-confeitaria">
    <div class="container">
        <h1>Finalizar Pedido</h1>

        <div class="resumo-pedido">
            <?php foreach($_SESSION['carrinho'] as $item): ?>
            <div class="item-pedido">
                <h4><?= htmlspecialchars($item['nome']) ?></h4>
                <p><?= $item['quantidade'] ?? 1 ?> x R$ <?= number_format($item['preco'], 2, ',', '.') ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="total-pedido">
            <h3>Total: R$ <?= number_format(array_reduce($_SESSION['carrinho'], function($total, $item) {
                return $total + ($item['preco'] * ($item['quantidade'] ?? 1));
            }, 0), 2, ',', '.') ?></h3>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="nome_cliente">Nome para o pedido:</label>
                <input type="text" id="nome_cliente" name="nome_cliente" required>
            </div>

            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea id="observacoes" name="observacoes"></textarea>
            </div>

            <button type="submit" class="btn-doce grande">Confirmar Pedido</button>
        </form>
    </div>
</body>

</html>