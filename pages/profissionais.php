<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();
requirePerfil(['administrador']);

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'criar') {
    try {
        $pdo->beginTransaction();
        // Cria usuário
        $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO usuarios (nome, email, senha_hash, perfil)
                       VALUES (?,?,?,?)")
            ->execute([
                $_POST['nome'], $_POST['email'], $senhaHash, $_POST['perfil'],
            ]);
        $usuarioId = $pdo->lastInsertId();

        // Cria profissional
        $pdo->prepare("INSERT INTO profissionais
          (usuario_id, registro_profissional, conselho, validade_registro,
           especialidade, telefone, turno, disponibilidade, setor_id)
          VALUES (?,?,?,?,?,?,?,?,?)")
          ->execute([
            $usuarioId, $_POST['registro'], $_POST['conselho'],
            $_POST['validade'] ?: null, $_POST['especialidade'] ?: null,
            $_POST['telefone'] ?: null, $_POST['turno'] ?: null,
            isset($_POST['disponibilidade']) ? 1 : 0,
            $_POST['setor_id'] ?: null,
          ]);
        $pdo->commit();
        $msg = 'Profissional cadastrado.';
    } catch (PDOException $e) {
        $pdo->rollBack();
        $msg = 'Erro: ' . $e->getMessage();
    }
}

$setores = $pdo->query("SELECT id, nome FROM setores WHERE ativo=1")->fetchAll();

$profissionais = $pdo->query("
  SELECT p.*, u.nome, u.email, s.nome AS setor
  FROM profissionais p
  JOIN usuarios u ON u.id=p.usuario_id
  LEFT JOIN setores s ON s.id=p.setor_id
  ORDER BY u.nome
")->fetchAll();
?>
<h1>Profissionais de Saúde</h1>
<?php if ($msg): ?><p class="sucesso"><?= htmlspecialchars($msg) ?></p><?php endif; ?>

<details class="card">
  <summary>+ Novo profissional</summary>
  <form method="post" class="form-grid">
    <input type="hidden" name="acao" value="criar">
    <label>Nome<input name="nome" required></label>
    <label>E-mail<input type="email" name="email" required></label>
    <label>Senha inicial<input type="password" name="senha" required minlength="6"></label>
    <label>Perfil
      <select name="perfil">
        <option>medico</option><option>enfermeiro</option>
      </select>
    </label>
    <label>Registro profissional<input name="registro" required placeholder="CRM/COREN"></label>
    <label>Conselho
      <select name="conselho">
        <option>CRM</option><option>COREN</option><option>OUTRO</option>
      </select>
    </label>
    <label>Validade do registro<input type="date" name="validade"></label>
    <label>Especialidade<input name="especialidade"></label>
    <label>Telefone<input name="telefone"></label>
    <label>Turno
      <select name="turno">
        <option>manha</option><option>tarde</option><option>noite</option><option>integral</option>
      </select>
    </label>
    <label>Setor
      <select name="setor_id">
        <option value="">—</option>
        <?php foreach ($setores as $s): ?>
          <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nome']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label><input type="checkbox" name="disponibilidade" checked> Disponível</label>
    <button>Cadastrar profissional</button>
  </form>
</details>

<table class="tabela">
  <tr><th>Nome</th><th>Registro</th><th>Conselho</th><th>Especialidade</th><th>Setor</th><th>Turno</th><th>Disponível</th></tr>
  <?php foreach ($profissionais as $p): ?>
  <tr>
    <td><?= htmlspecialchars($p['nome']) ?><br><small><?= htmlspecialchars($p['email']) ?></small></td>
    <td><?= htmlspecialchars($p['registro_profissional']) ?></td>
    <td><?= $p['conselho'] ?></td>
    <td><?= htmlspecialchars($p['especialidade'] ?? '') ?></td>
    <td><?= htmlspecialchars($p['setor'] ?? '—') ?></td>
    <td><?= $p['turno'] ?? '—' ?></td>
    <td><?= $p['disponibilidade'] ? '✅' : '❌' ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>