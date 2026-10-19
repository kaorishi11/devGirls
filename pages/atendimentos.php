<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();
requirePerfil(['medico','administrador']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO atendimentos
          (ficha_id, medico_id, espaco_id, diagnostico_cid, evolucao_clinica,
           prescricao, conduta, status, fim_em)
        VALUES (?,?,?,?,?,?,?,?, NOW())
    ");
    $stmt->execute([
        $_POST['ficha_id'],
        $_SESSION['profissional_id'] ?? 1,
        $_POST['espaco_id'] ?: null,
        $_POST['cid'] ?: null,
        $_POST['evolucao'] ?: null,
        $_POST['prescricao'] ?: null,
        $_POST['conduta'] ?: null,
        $_POST['status'] ?? 'concluido',
    ]);

    $pdo->prepare("UPDATE fichas SET status=? WHERE id=?")
        ->execute([$_POST['status'] === 'concluido' ? 'alta' : $_POST['status'], $_POST['ficha_id']]);

    $pdo->prepare("INSERT INTO historico_status (ficha_id, status_anterior, status_novo, usuario_id)
                   VALUES (?, 'em_atendimento', ?, ?)")
        ->execute([$_POST['ficha_id'], $_POST['status'], $_SESSION['usuario_id']]);

    header('Location: atendimentos.php?ok=1'); exit;
}

$fichas = $pdo->query("
  SELECT f.id, p.nome AS paciente, f.status
  FROM fichas f JOIN pacientes p ON p.id=f.paciente_id
  WHERE f.status = 'aguardando_medico'
  ORDER BY f.criada_em
")->fetchAll(PDO::FETCH_ASSOC);

$espacos = $pdo->query("SELECT id, codigo, tipo FROM espacos WHERE status='livre'")->fetchAll();
?>
<h1>Atendimento Médico</h1>
<?php if (isset($_GET['ok'])): ?><p class="sucesso">Atendimento registrado.</p><?php endif; ?>

<form method="post" class="card form-grid">
  <label>Ficha / Paciente
    <select name="ficha_id" required>
      <?php foreach ($fichas as $f): ?>
        <option value="<?= $f['id'] ?>">#<?= $f['id'] ?> — <?= htmlspecialchars($f['paciente']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Espaço (opcional)
    <select name="espaco_id">
      <option value="">—</option>
      <?php foreach ($espacos as $e): ?>
        <option value="<?= $e['id'] ?>"><?= $e['codigo'] ?> (<?= $e['tipo'] ?>)</option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Diagnóstico CID<input name="cid" maxlength="10"></label>
  <label class="full">Evolução clínica<textarea name="evolucao" rows="3"></textarea></label>
  <label class="full">Prescrição<textarea name="prescricao" rows="3"></textarea></label>
  <label class="full">Conduta<textarea name="conduta" rows="3"></textarea></label>
  <label>Status final
    <select name="status">
      <option value="concluido">Concluído (alta)</option>
      <option value="em_medicacao">Em medicação</option>
      <option value="em_observacao">Em observação</option>
    </select>
  </label>
  <button type="submit">Salvar atendimento</button>
</form>