<?php
session_start();
if(!isset($_SESSION['funcionario'])) {
    header("Location: index.php");
    exit();
}

// Dados completos de pedidos com categoria
$pedidos = [
    [
        'id' => 1001, 
        'cliente' => 'Maria Silva', 
        'itens' => 'Bolo de Chocolate, 2 Cafés Especiais', 
        'total' => 97.50, 
        'status' => 'preparo',
        'categoria' => 'bolos',
        'data' => '2023-06-15 14:30:00'
    ],
    [
        'id' => 1002, 
        'cliente' => 'João Santos', 
        'itens' => 'Torta de Limão, Suco Natural', 
        'total' => 53.00, 
        'status' => 'entregue',
        'categoria' => 'tortas',
        'data' => '2023-06-14 10:15:00'
    ],
    [
        'id' => 1003, 
        'cliente' => 'Ana Oliveira', 
        'itens' => '3 Cafés Especiais', 
        'total' => 19.50, 
        'status' => 'recebido',
        'categoria' => 'bebidas',
        'data' => '2023-06-15 16:45:00'
    ],
    [
        'id' => 1004, 
        'cliente' => 'Analita Santos', 
        'itens' => '3 Cafés Especiais', 
        'total' => 39.50, 
        'status' => 'recebido',
        'categoria' => 'bebidas',
        'data' => '2023-06-15 16:49:00'
    ],
    [
        'id' => 1005, 
        'cliente' => 'Vanderlei Strider', 
        'itens' => 'Croassant,', 
        'total' => 49.50, 
        'status' => 'recebido',
        'categoria' => 'doces',
        'data' => '2023-06-15 16:51:00'
    ]
];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Doce Sonho Confeitaria</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/temas.css">
    <style>
    .tabela-pedidos {
        width: 100%;
        overflow-x: auto;
        margin-top: 20px;
        background-color: rgba(255, 255, 255, 0.8);
        /* Transparência adicionada */
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .tabela-pedidos table {
        width: 100%;
        border-collapse: collapse;
        background-color: rgba(255, 255, 255, 0.7);
        /* Transparência na tabela */
    }

    .tabela-pedidos th,
    .tabela-pedidos td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid rgba(243, 209, 230, 0.7);
        /* Transparência na borda */
    }

    .tabela-pedidos th {
        background-color: rgba(248, 215, 218, 0.8);
        /* Transparência no cabeçalho */
        color: #721c24;
    }

    .status-select {
        padding: 5px;
        border-radius: 5px;
        border: 1px solid rgba(243, 209, 230, 0.7);
        background-color: rgba(255, 255, 255, 0.8);
    }

    .painel-acoes {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        background-color: rgba(255, 255, 255, 0.7);
        /* Transparência no painel */
        padding: 15px;
        border-radius: 10px;
    }

    .filtros-pedidos {
        margin-bottom: 20px;
        background-color: rgba(255, 255, 255, 0.7);
        /* Transparência nos filtros */
        padding: 15px;
        border-radius: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .header-doce {
        background-color: rgba(255, 182, 193, 0.9);
        /* Transparência no header */
    }

    .container {
        background-color: rgba(255, 255, 255, 0.5);
        /* Transparência no container principal */
        padding: 20px;
        border-radius: 15px;
        margin: 20px auto;
    }
    </style>
</head>

<body class="tema-confeitaria">
    <header class="header-doce">
        <div class="container">
            <div class="logo">
                <img src="img/logo.png" alt="Doce Sonho Confeitaria">
                <h1>Painel de Controle</h1>
            </div>
            <div class="usuario">
                <span>Olá, <?= htmlspecialchars(explode('@', $_SESSION['funcionario'])[0]) ?></span>
                <a href="logout.php" class="btn-doce pequeno">Sair</a>
            </div>
        </div>
    </header>

    <main class="container">
        <h2 class="titulo-rosa">Pedidos Recentes</h2>

        <div class="filtros-pedidos">
            <button class="btn-doce filtro-ativo" data-status="todos">Todos</button>
            <button class="btn-doce" data-status="recebido">Recebidos</button>
            <button class="btn-doce" data-status="preparo">Em Preparo</button>
            <button class="btn-doce" data-status="pronto">Prontos</button>
            <button class="btn-doce" data-status="entregue">Entregues</button>
            <button class="btn-doce" data-categoria="bolos">Bolos</button>
            <button class="btn-doce" data-categoria="tortas">Tortas</button>
            <button class="btn-doce" data-categoria="bebidas">Bebidas</button>
        </div>

        <div class="tabela-pedidos">
            <table>
                <thead>
                    <tr>
                        <th>Nº Pedido</th>
                        <th>Cliente</th>
                        <th>Itens</th>
                        <th>Total</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($pedidos) > 0): ?>
                    <?php foreach($pedidos as $pedido): ?>
                    <tr data-status="<?= $pedido['status'] ?>" data-categoria="<?= $pedido['categoria'] ?>">
                        <td>#<?= $pedido['id'] ?></td>
                        <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                        <td><?= htmlspecialchars($pedido['itens']) ?></td>
                        <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                        <td><?= ucfirst($pedido['categoria']) ?></td>
                        <td>
                            <span class="status-badge <?= $pedido['status'] ?>">
                                <?= ucfirst($pedido['status']) ?>
                            </span>
                        </td>
                        <td>
                            <select class="status-select" data-pedido="<?= $pedido['id'] ?>">
                                <option value="recebido" <?= $pedido['status'] == 'recebido' ? 'selected' : '' ?>>
                                    Recebido</option>
                                <option value="preparo" <?= $pedido['status'] == 'preparo' ? 'selected' : '' ?>>Em
                                    Preparo</option>
                                <option value="pronto" <?= $pedido['status'] == 'pronto' ? 'selected' : '' ?>>Pronto
                                </option>
                                <option value="entregue" <?= $pedido['status'] == 'entregue' ? 'selected' : '' ?>>
                                    Entregue</option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px;">Nenhum pedido encontrado</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="painel-acoes">
            <button class="btn-doce grande" onclick="window.print()">🖨️ Imprimir Pedidos</button>
            <button class="btn-doce grande" onclick="alert('Funcionalidade será implementada')">📊 Relatório de
                Vendas</button>
            <button class="btn-doce grande" onclick="alert('Funcionalidade será implementada')">➕ Novo Pedido
                Manual</button>
        </div>
    </main>

    <script src="js/scripts.js"></script>
</body>

</html>