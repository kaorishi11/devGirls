<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolver'])) {
    $pdo->prepare("UPDATE alertas SET status='resolvido', resolvido_em=NOW() WHERE id=?")
        ->execute([$_POST['resolver']]);
    header('Location: alertas.php'); exit;
}

$alertas = $pdo->query("SELECT * FROM alertas WHERE status='aberto'
                         ORDER BY FIELD(prioridade,'critica','alta','media','baixa'), criado_em DESC")
                ->fetchAll();
?>
<h1>Alertas</h1>
<table class="tabela">
  <tr><th>#</th><th>Tipo</th><th>Mensagem</th><th>Prioridade</th><th>Ação</th></tr>
  <?php foreach ($alertas as $a): ?>
  <tr class="<?= $a['prioridade'] ?>">
    <td><?= $a['id'] ?></td>
    <td><?= $a['tipo'] ?></td>
    <td><?= htmlspecialchars($a['mensagem']) ?></td>
    <td><span class="badge <?= $a['prioridade'] ?>"><?= $a['prioridade'] ?></span></td>
    <td>
      <form method="post" style="display:inline">
        <button name="resolver" value="<?= $a['id'] ?>">Resolver</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>