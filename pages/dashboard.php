<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();

$stats = [
  'fichas_aguardando'  => $pdo->query("SELECT COUNT(*) FROM fichas WHERE status='aguardando_triagem'")->fetchColumn(),
  'fichas_atendimento' => $pdo->query("SELECT COUNT(*) FROM fichas WHERE status='em_atendimento'")->fetchColumn(),
  'espacos_livres'     => $pdo->query("SELECT COUNT(*) FROM espacos WHERE status='livre'")->fetchColumn(),
  'alertas_abertos'    => $pdo->query("SELECT COUNT(*) FROM alertas WHERE status='aberto'")->fetchColumn(),
];

$triagens = $pdo->query("
  SELECT risco_confirmado, COUNT(*) qtd FROM triagens
  WHERE DATE(realizada_em) = CURDATE()
  GROUP BY risco_confirmado
")->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<div class="dashboard-layout">
  <div class="dashboard-cards">
    <div class="dashboard-card stat">
      <div>
        <span class="metric-label">Fichas aguardando</span>
        <strong><?= $stats['fichas_aguardando'] ?></strong>
      </div>
    </div>

    <div class="dashboard-card stat">
      <div>
        <span class="metric-label">Fichas em atendimento</span>
        <strong><?= $stats['fichas_atendimento'] ?></strong>
      </div>
    </div>

    <div class="dashboard-card stat">
      <div>
        <span class="metric-label">Espaços livres</span>
        <strong><?= $stats['espacos_livres'] ?></strong>
      </div>
    </div>

    <div class="dashboard-card stat alerta">
      <div>
        <span class="metric-label">Alertas abertos</span>
        <strong><?= $stats['alertas_abertos'] ?></strong>
      </div>
    </div>
  </div>

  <div class="dashboard-panel">
    <h2>Triagens de hoje por risco</h2>
    <table class="tabela">
      <thead>
        <tr>
          <th>Risco</th>
          <th>Quantidade</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($triagens as $risco => $qtd): ?>
          <tr>
            <td><?= htmlspecialchars($risco) ?></td>
            <td><?= $qtd ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>