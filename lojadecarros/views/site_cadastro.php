<?php $titulo = 'Criar conta | Montclair Motors'; require __DIR__ . '/site_topo.php'; ?>
<main class="auth">
  <form class="auth-card" method="post" action="index.php?controller=site&amp;action=cadastro">
    <h1>Criar conta</h1>
    <p>Cadastre-se para registrar interesse nos carros.</p>
    <?php if ($erro): ?><div class="aviso" role="alert"><?= site_e($erro) ?></div><?php endif; ?>
    <input type="hidden" name="csrf" value="<?= site_csrf() ?>">
    <input type="hidden" name="voltar" value="<?= $voltar ?>">
    <label class="campo"><span>Nome completo</span><input type="text" name="nome" value="<?= site_e($d['nome']) ?>" required autocomplete="name" autofocus></label>
    <label class="campo"><span>CPF</span><input type="text" id="cpf" name="cpf" value="<?= site_e($d['cpf']) ?>" required inputmode="numeric" maxlength="14" placeholder="000.000.000-00"></label>
    <label class="campo"><span>E-mail</span><input type="email" name="email" value="<?= site_e($d['email']) ?>" required autocomplete="email"></label>
    <label class="campo"><span>Senha</span><input type="password" name="senha" required minlength="6" autocomplete="new-password" placeholder="Mínimo de 6 caracteres"></label>
    <button class="btn">Criar conta</button>
    <p class="troca">Já tem conta? <a href="index.php?controller=site&amp;action=login<?= $voltar ? '&amp;voltar=' . $voltar : '' ?>">Entrar</a></p>
  </form>
</main>
<?php require __DIR__ . '/site_rodape.php'; ?>
