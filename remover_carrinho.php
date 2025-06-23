<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $index = (int)$_POST['index'];
    if (isset($_SESSION['carrinho'][$index])) {
        array_splice($_SESSION['carrinho'], $index, 1);
    }
}
echo "OK";
?>