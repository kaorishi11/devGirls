<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();
requirePerfil(['administrador','recepcionista','enfermeiro']);

$msg = '';

// Criar espaço
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'criar') {
    try {
        $pdo->prepare("INSERT INTO espacos (codigo, setor_id, tipo, status, proxima_manutencao, observacoes)
                       VALUES (?,?,?,?,?,?)")
            ->execute([
                $_POST['codigo'], $_POST['setor_id'], $_POST['tipo'],
                $_POST['status'] ?? 'livre',
                $_POST['proxima_manutencao'] ?: null,
                $_POST['observacoes'] ?: null,
            ]);
        $msg = 'Espaço cadastrado.';
    } catch (PDOException $e) {
        $msg = 'Erro: ' . $e->getMessage();
    }
}

// Alterar status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'status') {
    $pdo->prepare("UPDATE espacos SET status=? WHERE id=?")
        ->execute([$_POST['novo_status'], $_POST['id']]);
    header('Location: espacos.php'); exit;
}

$setores = $pdo->query("SELECT id, nome FROM setores WHERE ativo=1 ORDER BY nome")->fetchAll();

$filtroSetor = (int)($_GET['setor_id'] ?? 0);
$sql = "SELECT e.*, s.nome AS setor FROM espacos e JOIN setores s ON s.id=e.setor_id";
$params = [];
if ($filtroSetor) { $sql .= " WHERE e.setor_id=?"; $params[] = $filtroSetor; }
$sql .= " ORDER BY s.nome, e.codigo";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$espacos = $stmt->fetchAll();

$porStatus = ['livre'=>0,'ocupado'=>0,'higienizacao'=>0,'manutencao'=>0];
foreach ($espacos as $e) $porStatus[$e['status']]++;
?>
<h1>Espaços (Leitos / Consultórios)</h1>
<?php if ($msg): ?><p class="sucesso"><?= htmlspecialchars($msg) ?></p><?php endif; ?>

<div class="cards">
  <?php foreach ($porStatus as $st => $qtd): ?>
    <div class="card stat"><span><?= ucfirst($st) ?></span><strong><?= $qtd ?></strong></div>
  <?php endforeach; ?>
</div>

<details class="card">
  <summary>+ Novo espaço</summary>
  <form method="post" class="form-grid">
    <input type="hidden" name="acao" value="criar">
    <label>Código<input name="codigo" required placeholder="Ex: UTI-01"></label>
    <label>Setor
      <select name="setor_id" required>
        <?php foreach ($setores as $s): ?>
          <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nome']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Tipo
      <select name="tipo">
        <option>consultorio</option><option>observacao</option>
        <option>uti</option><option>isolamento</option>
      </select>
    </label>
    <label>Status inicial
      <select name="status">
        <option>livre</option><option>ocupado</option>
        <option>higienizacao</option><option>manutencao</option>
      </select>
    </label>
    <label>Próxima manutenção<input type="date" name="proxima_manutencao"></label>
    <label class="full">Observações<textarea name="observacoes" rows="2"></textarea></label>
    <button>Cadastrar espaço</button>
  </form>
</details>

<form method="get" class="filtro">
  <select name="setor_id" onchange="this.form.submit()">
    <option value="0">Todos os setores</option>
    <?php foreach ($setores as $s): ?>
      <option value="<?= $s['id'] ?>" <?= $filtroSetor===$s['id']?'selected':'' ?>>
        <?= htmlspecialchars($s['nome']) ?>
      </option>
    <?php endforeach; ?>
  </select>
</form>

<table class="tabela">
  <tr><th>Código</th><th>Setor</th><th>Tipo</th><th>Status</th><th>Próx. manutenção</th><th>Ação</th></tr>
  <?php foreach ($espacos as $e): ?>
  <tr>
    <td><?= htmlspecialchars($e['codigo']) ?></td>
    <td><?= htmlspecialchars($e['setor']) ?></td>
    <td><?= $e['tipo'] ?></td>
    <td><span class="badge <?= $e['status'] ?>"><?= $e['status'] ?></span></td>
    <td><?= $e['proxima_manutencao'] ? date('d/m/Y', strtotime($e['proxima_manutencao'])) : '—' ?></td>
    <td>
      <form method="post" style="display:inline">
        <input type="hidden" name="acao" value="status">
        <input type="hidden" name="id" value="<?= $e['id'] ?>">
        <select name="novo_status" onchange="this.form.submit()">
          <?php foreach (['livre','ocupado','higienizacao','manutencao'] as $st): ?>
            <option value="<?= $st ?>" <?= $e['status']===$st?'selected':'' ?>><?= $st ?></option>
          <?php endforeach; ?>
        </select>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>