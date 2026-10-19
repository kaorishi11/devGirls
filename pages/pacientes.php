<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("INSERT INTO pacientes
      (nome, data_nascimento, cpf, telefone, email, convenio, contato_emergencia)
      VALUES (?,?,?,?,?,?,?)")
      ->execute([
        $_POST['nome'], $_POST['data_nascimento'] ?: null,
        $_POST['cpf'] ?: null, $_POST['telefone'] ?: null,
        $_POST['email'] ?: null, $_POST['convenio'] ?: null,
        $_POST['contato_emergencia'] ?: null,
      ]);
    header('Location: pacientes.php'); exit;
}

$lista = $pdo->query("SELECT * FROM pacientes ORDER BY nome")->fetchAll();
?>
<h1>Pacientes</h1>
<details class="card">
  <summary>+ Novo paciente</summary>
  <form method="post" class="form-grid">
    <label>Nome<input name="nome" required></label>
    <label>Nascimento<input type="date" name="data_nascimento"></label>
    <label>CPF<input name="cpf" maxlength="14"></label>
    <label>Telefone<input name="telefone"></label>
    <label>E-mail<input type="email" name="email"></label>
    <label>Convênio<input name="convenio"></label>
    <label class="full">Contato de emergência<input name="contato_emergencia"></label>
    <button>Cadastrar</button>
  </form>
</details>

<table class="tabela">
  <tr><th>#</th><th>Nome</th><th>CPF</th><th>Telefone</th><th>Convênio</th></tr>
  <?php foreach ($lista as $p): ?>
  <tr>
    <td><?= $p['id'] ?></td>
    <td><?= htmlspecialchars($p['nome']) ?></td>
    <td><?= htmlspecialchars($p['cpf'] ?? '') ?></td>
    <td><?= htmlspecialchars($p['telefone'] ?? '') ?></td>
    <td><?= htmlspecialchars($p['convenio'] ?? '') ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>