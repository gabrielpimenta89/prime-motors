<?php $titulo = 'Entrar | Montclair Motors'; require __DIR__ . '/site_topo.php'; ?>
<main class="auth">
  <form class="auth-card" method="post" action="index.php?controller=site&amp;action=login">
    <h1>Entrar</h1>
    <p><?= $voltar ? 'Entre para registrar seu interesse neste carro.' : 'Acesse sua conta Montclair.' ?></p>
    <?php if ($erro): ?><div class="aviso" role="alert"><?= site_e($erro) ?></div><?php endif; ?>
    <input type="hidden" name="csrf" value="<?= site_csrf() ?>">
    <input type="hidden" name="voltar" value="<?= $voltar ?>">
    <label class="campo"><span>E-mail</span><input type="email" name="email" required autocomplete="email" autofocus></label>
    <label class="campo"><span>Senha</span><input type="password" name="senha" required autocomplete="current-password"></label>
    <button class="btn">Entrar</button>
    <p class="troca">Ainda não tem conta? <a href="index.php?controller=site&amp;action=cadastro<?= $voltar ? '&amp;voltar=' . $voltar : '' ?>">Criar conta</a></p>
    <p class="troca">É funcionário? <a href="index.php?controller=auth&amp;action=form">Área do funcionário</a></p>
  </form>
</main>
<?php require __DIR__ . '/site_rodape.php'; ?>