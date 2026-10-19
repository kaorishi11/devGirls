<?php
require_once __DIR__ . '/auth.php';
$u = usuarioAtual();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Senn Med AI</title>
<link rel="stylesheet" href="/senn_med_ai/assets/css/style.css">
</head>
<body>
<nav class="sidebar">
  <h2>Senn Med AI</h2>
  <a href="dashboard.php">Dashboard</a>
  <a href="fichas.php">Fichas</a>
  <a href="triagem.php">Triagem</a>
  <a href="atendimentos.php">Atendimentos</a>
  <a href="pacientes.php">Pacientes</a>
  <a href="espacos.php">Espaços</a>
  <a href="profissionais.php">Profissionais</a>
  <a href="alertas.php">Alertas</a>
  <?php if ($u['perfil'] === 'administrador'): ?>
    <a href="usuarios.php">Usuários</a>
  <?php endif; ?>
  <a href="logout.php" class="logout">Sair (<?= htmlspecialchars($u['nome']) ?>)</a>
</nav>
<main class="content"></main>