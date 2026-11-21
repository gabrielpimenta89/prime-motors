<?php
$logado = isset($_SESSION['cliente_id']);   // cliente logado no site
$equipe = isset($_SESSION['usuario_id']);   // funcionário logado no painel
$primeiro = fn(string $nome): string => explode(' ', trim($nome))[0];
// número de versão = data de alteração do arquivo: o navegador baixa de novo sempre que o arquivo muda
$versao = fn(string $arq): int => (int)@filemtime(__DIR__ . '/../' . $arq);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= site_e($titulo ?? 'Montclair Motors') ?></title>
  <link rel="icon" type="image/png" href="public/assets/css/favicon.png">
  <link rel="stylesheet" href="public/assets/css/site.css?v=<?= $versao('public/assets/css/site.css') ?>">
  <script>
    // Marca a página como "com JavaScript" e abre a cortina azul se a página anterior a fechou
    (function (h) {
      h.classList.add('js');
      try { if (sessionStorage.getItem('cortina')) { h.classList.add('cortina-abre'); sessionStorage.removeItem('cortina'); } } catch (e) {}
    })(document.documentElement);
  </script>
  <script src="public/assets/js/site.js?v=<?= $versao('public/assets/js/site.js') ?>" defer></script>
</head>
<body>
<header class="topo">
  <a class="marca" href="index.php?controller=site&amp;action=home">Montclair<span>Motors</span></a>
  <nav>
    <a href="index.php?controller=site&amp;action=carros">Carros</a>
    <a class="sec" href="index.php?controller=site&amp;action=home#sobre">Sobre nós</a>
    <?php if ($logado): ?>
      <a href="index.php?controller=site&amp;action=interesses">Meus interesses</a>
      <span class="ola">Olá, <?= site_e($primeiro($_SESSION['cliente_nome'])) ?></span>
      <a href="index.php?controller=site&amp;action=sair">Sair</a>
    <?php elseif ($equipe): ?>
      <span class="ola">Olá, <?= site_e($primeiro($_SESSION['nome'] ?? 'equipe')) ?></span>
      <a href="index.php?controller=auth&amp;action=dashboard">Painel da loja</a>
      <a href="index.php?controller=auth&amp;action=logout">Sair</a>
    <?php else: ?>
      <details class="entrar">
        <summary>Entrar</summary>
        <div class="entrar-menu">
          <a href="index.php?controller=site&amp;action=login"><strong>Área do cliente</strong><span>Entre ou crie sua conta</span></a>
          <a href="index.php?controller=auth&amp;action=form"><strong>Área do funcionário</strong><span>Painel da loja</span></a>
        </div>
      </details>
      <a class="btn mini-btn" href="index.php?controller=site&amp;action=cadastro">Criar conta</a>
    <?php endif; ?>
  </nav>
</header>