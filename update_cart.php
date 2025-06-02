<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_data = json_decode(file_get_contents('php://input'), true);
    $_SESSION['cart'] = $cart_data;
    echo json_encode(['status' => 'success']);
}
?>