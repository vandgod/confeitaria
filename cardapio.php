<?php
session_start();

// Redireciona funcionários para o painel
if(isset($_SESSION['funcionario'])) {
    header("Location: painel.php");
    exit();
}

// Dados completos dos produtos - definido no início
$produtos = [
    1 => ['nome' => 'Bolo de Chocolate', 'desc' => 'Bolo de chocolate com recheio de brigadeiro', 'preco' => 85.00, 'categoria' => 'bolos'],
    2 => ['nome' => 'Torta de Cereja', 'desc' => 'Torta de Cereja', 'preco' => 65.00, 'categoria' => 'tortas'],
    3 => ['nome' => 'Bolo de Frutas Vermelhas', 'desc' => 'Bolo de Frutas Vermelhas', 'preco' => 92.00, 'categoria' => 'bolos'],
    4 => ['nome' => 'Torta Feliz', 'desc' => 'Torta Feliz', 'preco' => 88.00, 'categoria' => 'tortas'],
    5 => ['nome' => 'Torta Especial', 'desc' => 'Torta Especial', 'preco' => 76.50, 'categoria' => 'tortas'],
    6 => ['nome' => 'Torta de Chocolate xCake', 'desc' => 'Torta de Chocolate xCake', 'preco' => 109.00, 'categoria' => 'tortas'],
    7 => ['nome' => 'Torta Marta Rocha', 'desc' => 'Torta Marta Rocha', 'preco' => 145.00, 'categoria' => 'tortas'],
    8 => ['nome' => 'Sonho', 'desc' => 'Sonho Doce de Leite', 'preco' => 15.00, 'categoria' => 'doces'],
    9 => ['nome' => 'Donuts', 'desc' => 'Donuts', 'preco' => 12.00, 'categoria' => 'doces'],
    10 => ['nome' => 'Sabores', 'desc' => 'Diversos', 'preco' => 18.00, 'categoria' => 'doces'],
    11 => ['nome' => 'Croassant', 'desc' => 'Croassant Chocolate', 'preco' => 16.50, 'categoria' => 'doces'],
    12 => ['nome' => 'Cueca Virada', 'desc' => 'Krostoli', 'preco' => 4.00, 'categoria' => 'doces'],
    13 => ['nome' => 'Churros', 'desc' => 'Churros Doce de Leite', 'preco' => 8.00, 'categoria' => 'doces'],
    14 => ['nome' => 'Café Gourmet', 'desc' => 'Café gourmet da casa', 'preco' => 12.50, 'categoria' => 'bebidas'],
    15 => ['nome' => 'Expresso', 'desc' => 'Café', 'preco' => 12.00, 'categoria' => 'bebidas']
];

// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Adiciona item ao carrinho via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_item'])) {
    $produtoId = (int)$_POST['produto_id'];
    $quantidade = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 1;
    
    // Verifica se o produto existe
    if (isset($produtos[$produtoId])) {
        $item = $produtos[$produtoId];
        $item['quantidade'] = $quantidade;
        $_SESSION['carrinho'][] = $item;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio - Doce Sonho Confeitaria</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/temas.css">
    <style>
    .grade-produtos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .card-produto {
        border: 1px solid #f3d1e6;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s;
        background: white;
    }

    .card-produto:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .produto-imagem {
        height: 180px;
        background-size: cover;
        background-position: center;
        background-color: #f8f9fa;
    }

    .produto-info {
        padding: 15px;
    }

    .produto-rodape {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }

    .preco {
        font-weight: bold;
        color: #d63384;
        font-size: 1.1em;
    }

    /* Carrinho flutuante */
    .carrinho-flutuante {
        position: fixed;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.95);
        padding: 12px 20px;
        border-radius: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        border: 1px solid #f3d1e6;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .carrinho-flutuante:hover {
        transform: scale(1.05);
        background: rgba(255, 182, 193, 0.9);
    }

    .badge-carrinho {
        background: #d63384;
        color: white;
        border-radius: 50%;
        padding: 3px 8px;
        font-size: 0.9em;
    }

    /* Modal do carrinho */
    .modal-carrinho {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        justify-content: center;
        align-items: center;
    }

    .conteudo-modal {
        background: white;
        padding: 25px;
        border-radius: 15px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
    }

    .item-carrinho {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px dashed #f3d1e6;
    }

    .total-carrinho {
        font-size: 1.3em;
        text-align: right;
        margin: 20px 0;
        padding-top: 15px;
        border-top: 2px solid #f3d1e6;
    }

    .acoes-carrinho {
        display: flex;
        justify-content: space-between;
        gap: 15px;
    }

    /* Filtros */
    .filtros {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .grade-produtos {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }

        .carrinho-flutuante {
            top: 10px;
            right: 10px;
            padding: 8px 15px;
        }
    }
    </style>
</head>

<body class="tema-confeitaria">
    <!-- Carrinho flutuante -->
    <div class="carrinho-flutuante" onclick="abrirCarrinho()">
        🛒 <span>Carrinho</span> <span class="badge-carrinho"><?= count($_SESSION['carrinho']) ?></span>
    </div>

    <!-- Modal do Carrinho -->
    <div class="modal-carrinho" id="modalCarrinho">
        <div class="conteudo-modal">
            <h2 class="titulo-rosa">Seu Carrinho</h2>
            <div id="itensCarrinho">
                <?php if(empty($_SESSION['carrinho'])): ?>
                <p style="text-align: center; padding: 20px;">Seu carrinho está vazio</p>
                <?php else: ?>
                <?php foreach($_SESSION['carrinho'] as $index => $item): ?>
                <div class="item-carrinho">
                    <div>
                        <h4 style="margin: 0;"><?= htmlspecialchars($item['nome']) ?></h4>
                        <small><?= number_format($item['preco'], 2, ',', '.') ?> cada</small>
                    </div>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span><?= $item['quantidade'] ?? 1 ?>x</span>
                        <button onclick="removerDoCarrinho(<?= $index ?>)" class="btn-doce pequeno">Remover</button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="total-carrinho">
                <strong>Total: R$
                    <?= number_format(array_reduce($_SESSION['carrinho'], function($total, $item) {
                        return $total + ($item['preco'] * ($item['quantidade'] ?? 1));
                    }, 0), 2, ',', '.') ?>
                </strong>
            </div>
            <div class="acoes-carrinho">
                <button onclick="fecharCarrinho()" class="btn-doce">Continuar Comprando</button>
                <a href="checkout.php" class="btn-doce" style="background: #d63384; color: white;">Finalizar Pedido</a>
            </div>
        </div>
    </div>

    <header class="header-doce">
        <div class="container">
            <div class="logo">
                <img src="img/logo.png" alt="Doce Sonho Confeitaria">
                <h1>Doce Sonho Confeitaria</h1>
            </div>
            <?php if(isset($_SESSION['cliente'])): ?>
            <div class="usuario">
                <span>Bem-vindo, <?= htmlspecialchars(explode('@', $_SESSION['cliente'])[0]) ?></span>
                <a href="logout.php" class="btn-doce pequeno">Sair</a>
            </div>
            <?php else: ?>
            <a href="login.php" class="btn-doce pequeno">Login</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
        <h2 class="titulo-rosa">Nosso Cardápio</h2>

        <div class="filtros">
            <button class="btn-doce filtro-ativo" data-categoria="todos">Todos</button>
            <button class="btn-doce" data-categoria="bolos">Bolos</button>
            <button class="btn-doce" data-categoria="tortas">Tortas</button>
            <button class="btn-doce" data-categoria="doces">Doces</button>
            <button class="btn-doce" data-categoria="bebidas">Bebidas</button>
        </div>

        <div class="grade-produtos">
            <?php foreach($produtos as $id => $produto): ?>
            <div class="card-produto" data-categoria="<?= $produto['categoria'] ?>">
                <div class="produto-imagem" style="background-image: url('img/<?= $id ?>.png')"></div>
                <div class="produto-info">
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    <p><?= htmlspecialchars($produto['desc']) ?></p>
                    <div class="produto-rodape">
                        <span class="preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="produto_id" value="<?= $id ?>">
                            <input type="number" name="quantidade" value="1" min="1" max="10"
                                style="width: 50px; padding: 5px;">
                            <button type="submit" name="adicionar_item" class="btn-doce pequeno">
                                Adicionar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="js/scripts.js"></script>
    <script>
    // Funções do carrinho
    function abrirCarrinho() {
        document.getElementById('modalCarrinho').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function fecharCarrinho() {
        document.getElementById('modalCarrinho').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function removerDoCarrinho(index) {
        if (confirm('Remover este item do carrinho?')) {
            fetch('remover_carrinho.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'index=' + index
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
        }
    }

    // Fechar modal ao clicar fora
    document.getElementById('modalCarrinho').addEventListener('click', function(e) {
        if (e.target === this) {
            fecharCarrinho();
        }
    });

    // Filtros por categoria
    document.querySelectorAll('.filtros button').forEach(btn => {
        btn.addEventListener('click', function() {
            const categoria = this.dataset.categoria;

            // Atualiza botão ativo
            document.querySelectorAll('.filtros button').forEach(b => b.classList.remove(
                'filtro-ativo'));
            this.classList.add('filtro-ativo');

            // Filtra produtos
            document.querySelectorAll('.card-produto').forEach(produto => {
                if (categoria === 'todos' || produto.dataset.categoria === categoria) {
                    produto.style.display = 'block';
                } else {
                    produto.style.display = 'none';
                }
            });
        });
    });
    </script>
</body>

</html>