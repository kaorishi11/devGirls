<?php
session_start();

function requireLogin() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: /pages/login.php');
        exit;
    }
}

function requirePerfil($perfis) {
    requireLogin();
    if (!in_array($_SESSION['usuario_perfil'], (array)$perfis)) {
        http_response_code(403);
        die("Acesso negado para o perfil: " . $_SESSION['usuario_perfil']);
    }
}

function usuarioAtual() {
    return [
        'id'     => $_SESSION['usuario_id']     ?? null,
        'nome'   => $_SESSION['usuario_nome']   ?? null,
        'perfil' => $_SESSION['usuario_perfil'] ?? null,
    ];
}