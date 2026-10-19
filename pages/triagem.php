<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();

// Somente enfermeiros e admin
requirePerfil(['enfermeiro','administrador']);

$fichaId = (int)($_GET['ficha_id'] ?? 0);
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO triagens
          (ficha_id, enfermeiro_id, pressao_arterial, temperatura, batimentos,
           saturacao, sintomas, risco_sugerido, risco_confirmado, observacoes_ia)
        VALUES (?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([
        $_POST['ficha_id'],
        $_SESSION['profissional_id'] ?? 1, // idealmente armazenar profissional_id na sessão
        $_POST['pa'], $_POST['temp'] ?: null, $_POST['bpm'] ?: null,
        $_POST['sat'] ?: null, $_POST['sintomas'],
        $_POST['risco_sugerido'] ?: null, $_POST['risco_confirmado'] ?: null,
        $_POST['obs_ia'] ?? null,
    ]);

    // Atualiza status da ficha
    $pdo->prepare("UPDATE fichas SET status='aguardando_medico' WHERE id=?")
        ->execute([$_POST['ficha_id']]);

    // Registra histórico
    $pdo->prepare("INSERT INTO historico_status (ficha_id, status_anterior, status_novo, usuario_id)
                   VALUES (?, 'aguardando_triagem', 'aguardando_medico', ?)")
        ->execute([$_POST['ficha_id'], $_SESSION['usuario_id']]);

    $msg = 'Triagem registrada com sucesso.';
}

$ficha = null;
if ($fichaId) {
    $stmt = $pdo->prepare("SELECT f.*, p.nome AS paciente FROM fichas f
                           JOIN pacientes p ON p.id=f.paciente_id WHERE f.id=?");
    $stmt->execute([$fichaId]);
    $ficha = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<h1>Triagem</h1>
<?php if ($msg): ?><p class="sucesso"><?= $msg ?></p><?php endif; ?>

<?php if ($ficha): ?>
<form method="post" class="card form-grid">
  <input type="hidden" name="ficha_id" value="<?= $ficha['id'] ?>">
  <p><strong>Paciente:</strong> <?= htmlspecialchars($ficha['paciente']) ?></p>
  <p><strong>Queixa:</strong> <?= htmlspecialchars($ficha['queixa_principal']) ?></p>

  <label>Pressão arterial<input name="pa" placeholder="120/80"></label>
  <label>Temperatura (°C)<input name="temp" type="number" step="0.1"></label>
  <label>Batimentos (bpm)<input name="bpm" type="number"></label>
  <label>Saturação (%)<input name="sat" type="number" step="0.01"></label>
  <label class="full">Sintomas<textarea name="sintomas" rows="3"></textarea></label>

  <label>Risco sugerido (IA)
    <select name="risco_sugerido">
      <option value="">—</option>
      <option>emergencia</option><option>muito_urgente</option>
      <option>urgente</option><option>pouco_urgente</option>
      <option>nao_urgente</option>
    </select>
  </label>
  <label>Risco confirmado
    <select name="risco_confirmado" required>
      <option>emergencia</option><option>muito_urgente</option>
      <option>urgente</option><option>pouco_urgente</option>
      <option>nao_urgente</option>
    </select>
  </label>
  <label class="full">Observações da IA<textarea name="obs_ia" rows="2"></textarea></label>

  <button type="submit">Salvar triagem</button>
</form>
<?php else: ?>
<p>Selecione uma ficha na <a href="fichas.php">lista de fichas</a>.</p>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>