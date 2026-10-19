<?php
require_once __DIR__ . '/auth.php';
$u = usuarioAtual();
$perfilCssMap = [
  'administrador' => '../assets/css/perfil-admin.css',
  'medico' => '../assets/css/perfil-medico.css',
  'enfermeiro' => '../assets/css/perfil-enfermeiro.css',
  'paciente' => '../assets/css/perfil-paciente.css',
];
$perfilCssAtual = $perfilCssMap[$u['perfil'] ?? ''] ?? '../assets/css/style.css';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Senn Med AI</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="<?= htmlspecialchars($perfilCssAtual) ?>">
</head>
<body>
<div class="app-shell">
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
  <?php if (($u['perfil'] ?? '') === 'administrador'): ?>
    <a href="usuarios.php">Usuários</a>
  <?php endif; ?>
  <a href="logout.php" class="logout">Sair (<?= htmlspecialchars($u['nome']) ?>)</a>
</nav>
<main class="content">