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
<h1>Dashboard</h1>
<div class="cards">
  <div class="card stat"><span>Fichas aguardando triagem</span><strong><?= $stats['fichas_aguardando'] ?></strong></div>
  <div class="card stat"><span>Fichas em atendimento</span><strong><?= $stats['fichas_atendimento'] ?></strong></div>
  <div class="card stat"><span>Espaços livres</span><strong><?= $stats['espacos_livres'] ?></strong></div>
  <div class="card stat alerta"><span>Alertas abertos</span><strong><?= $stats['alertas_abertos'] ?></strong></div>
</div>

<h2>Triagens de hoje por risco</h2>
<table class="tabela">
  <tr><th>Risco</th><th>Quantidade</th></tr>
  <?php foreach ($triagens as $risco => $qtd): ?>
    <tr><td><?= htmlspecialchars($risco) ?></td><td><?= $qtd ?></td></tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>