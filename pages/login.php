<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $stmt = getPDO()->prepare("
    SELECT u.*, p.id AS profissional_id
    FROM usuarios u
    LEFT JOIN profissionais p ON p.usuario_id = u.id
    WHERE u.email = ? AND u.ativo = 1
  ");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($senha, $user['senha_hash'])) {
      $_SESSION['usuario_id']      = $user['id'];
      $_SESSION['usuario_nome']    = $user['nome'];
      $_SESSION['usuario_perfil']  = $user['perfil'];
      $_SESSION['profissional_id'] = $user['profissional_id'];
      header('Location: dashboard.php');
      exit;
  }
    $erro = 'E-mail ou senha inválidos.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><title>Login — Senn Med AI</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-page">
  <form method="post" class="card login-card">
    <h1><span class="brand-icon" aria-hidden="true">$( )</span>Senn Med AI</h1>
    <?php if ($erro): ?><p class="erro"><?= htmlspecialchars($erro) ?></p><?php endif; ?>
    <label>E-mail<input type="email" name="email" required></label>
    <label>Senha<input type="password" name="senha" required></label>
    <button type="submit">Entrar</button>
  </form>
</body>
</html>