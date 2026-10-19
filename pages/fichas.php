<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();

$status = $_GET['status'] ?? '';
$sql = "SELECT f.id, p.nome AS paciente, f.queixa_principal, f.status, f.criada_em
        FROM fichas f JOIN pacientes p ON p.id = f.paciente_id";
$params = [];
if ($status) { $sql .= " WHERE f.status = ?"; $params[] = $status; }
$sql .= " ORDER BY f.criada_em DESC";

$stmt = $pdo->prepare($sql); $stmt->execute($params);
$fichas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Fichas</h1>
<form method="get" class="filtro">
  <select name="status" onchange="this.form.submit()">
    <option value="">Todos os status</option>
    <?php foreach (['aguardando_triagem','aguardando_medico','em_atendimento','em_medicacao','em_observacao','alta','cancelada'] as $s): ?>
      <option value="<?= $s ?>" <?= $status===$s?'selected':'' ?>><?= $s ?></option>
    <?php endforeach; ?>
  </select>
</form>

<table class="tabela">
  <tr><th>#</th><th>Paciente</th><th>Queixa</th><th>Status</th><th>Criada em</th><th>Ações</th></tr>
  <?php foreach ($fichas as $f): ?>
    <tr>
      <td><?= $f['id'] ?></td>
      <td><?= htmlspecialchars($f['paciente']) ?></td>
      <td><?= htmlspecialchars($f['queixa_principal']) ?></td>
      <td><span class="badge <?= $f['status'] ?>"><?= $f['status'] ?></span></td>
      <td><?= date('d/m/Y H:i', strtotime($f['criada_em'])) ?></td>
      <td>
        <a href="triagem.php?ficha_id=<?= $f['id'] ?>">Triar</a> |
        <a href="atendimentos.php?ficha_id=<?= $f['id'] ?>">Atender</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>