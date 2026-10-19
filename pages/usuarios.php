<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();
requirePerfil(['administrador']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'criar') {
    $hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO usuarios (nome, email, senha_hash, perfil) VALUES (?,?,?,?)")
        ->execute([$_POST['nome'], $_POST['email'], $hash, $_POST['perfil']]);
    header('Location: usuarios.php'); exit;
}

if (isset($_POST['toggle'])) {
    $pdo->prepare("UPDATE usuarios SET ativo = NOT ativo WHERE id = ?")
        ->execute([$_POST['toggle']]);
    header('Location: usuarios.php'); exit;
}

$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY nome")->fetchAll();
?>
<h1>Usuários do Sistema</h1>

<details class="card">
  <summary>+ Novo usuário</summary>
  <form method="post" class="form-grid">
    <input type="hidden" name="acao" value="criar">
    <label>Nome<input name="nome" required></label>
    <label>E-mail<input type="email" name="email" required></label>
    <label>Senha<input type="password" name="senha" required minlength="6"></label>
    <label>Perfil
      <select name="perfil">
        <option>administrador</option><option>medico</option>
        <option>enfermeiro</option><option>recepcionista</option>
      </select>
    </label>
    <button>Cadastrar</button>
  </form>
</details>

<table class="tabela">
  <tr><th>#</th><th>Nome</th><th>E-mail</th><th>Perfil</th><th>Ativo</th><th>Ação</th></tr>
  <?php foreach ($usuarios as $u): ?>
  <tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['nome']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= $u['perfil'] ?></td>
    <td><?= $u['ativo'] ? '✅' : '❌' ?></td>
    <td>
      <form method="post" style="display:inline">
        <button name="toggle" value="<?= $u['id'] ?>">
          <?= $u['ativo'] ? 'Desativar' : 'Ativar' ?>
        </button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>