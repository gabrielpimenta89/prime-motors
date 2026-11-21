<?php
$grupos = $grupos ?? [];
$links = [
    ['auth', 'dashboard', 'Dashboard', '<path d="M3 11 12 3l9 8"></path><path d="M5 10v10h14V10"></path>'],
    ['produto', 'index', 'Carros', '<path d="M5 16h14l-1.5-5h-11z"></path><circle cx="8" cy="17.5" r="1.5"></circle><circle cx="16" cy="17.5" r="1.5"></circle>'],
    ['entrada', 'index', 'Entradas', '<path d="M12 3v12"></path><path d="m7 10 5 5 5-5"></path><path d="M4 20h16"></path>'],
    ['venda', 'index', 'Vendas', '<circle cx="9" cy="20" r="1.5"></circle><circle cx="18" cy="20" r="1.5"></circle><path d="M3 3h2l2.6 12.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.5L21 8H6"></path>'],
    ['relatorio', 'index', 'Relatórios', '<path d="M5 20v-9"></path><path d="M12 20V4"></path><path d="M19 20v-6"></path><path d="M3 20h18"></path>'],
];
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Interesses</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" sizes="512x512" href="public/assets/css/favicon.png">
<link rel="stylesheet" href="public/assets/css/produtos.css">
<style>
.table.interesses{min-width:0}
.cabeca-carro{display:flex;align-items:center;flex-wrap:wrap;gap:14px;margin-bottom:16px}
.cabeca-carro h2{margin:0}
</style>
</head>
<body>

<div class="fundo" aria-hidden="true"></div>

<div class="header">
<div class="container header-inner">
<div class="brand">
<img src="public/assets/css/logoprimemotors-removebg-preview.png" alt="Montclair Motors" class="logo-header">
<span class="badge">Interesses</span>
</div>

<div class="user">
Olá, <strong><?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?></strong>
<a class="btn btn-ghost" href="index.php?controller=auth&action=logout">Sair</a>
</div>
</div>
</div>

<div class="container nav-wrap">
<nav class="card nav-card" aria-label="Ir para outras páginas">
<h2>Ir para</h2>
<div class="nav-links">
<?php foreach ($links as [$ctrl, $acao, $nome, $icone]): ?>
<a href="index.php?controller=<?= $ctrl ?>&action=<?= $acao ?>">
<span class="nav-icon" aria-hidden="true"><svg class="ico" viewBox="0 0 24 24"><?= $icone ?></svg></span>
<span class="nav-titulo"><?= $nome ?></span>
<svg class="ico nav-seta" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"></path><path d="m13 6 6 6-6 6"></path></svg>
</a>
<?php endforeach; ?>
</div>
</nav>
</div>

<div class="container" style="padding-top:24px;padding-bottom:36px">

<?php if (!$grupos): ?>
<div class="card">
<h2>Interesses dos clientes</h2>
<p>Nenhum cliente demonstrou interesse em um carro ainda.</p>
</div>
<?php endif; ?>

<?php foreach ($grupos as $linhas): $c = $linhas[0]; $n = count($linhas); ?>
<div class="card" style="margin-bottom:20px">
<div class="cabeca-carro">
<?php if (!empty($c['imagem'])): ?><img class="thumb" src="<?= htmlspecialchars($c['imagem']) ?>" width="80" alt="Foto do carro"><?php endif; ?>
<h2><?= htmlspecialchars($c['marca'] . ' ' . $c['modelo']) ?></h2>
<span class="badge"><?= $n === 1 ? '1 interessado' : $n . ' interessados' ?></span>
<?php if ((int)$c['vendido'] === 1): ?><span class="badge">Vendido</span><?php endif; ?>
</div>

<div class="table-wrap">
<table class="table interesses">
<thead>
<tr><th>Cliente</th><th>E-mail</th><th>Celular</th><th>Data</th></tr>
</thead>
<tbody>
<?php foreach ($linhas as $l): ?>
<tr>
<td><?= htmlspecialchars($l['nome']) ?></td>
<td><?= htmlspecialchars($l['email'] ?? '') ?></td>
<td><?= htmlspecialchars($l['celular'] ?? '—') ?></td>
<td><?= date('d/m/Y H:i', strtotime($l['data_registro'])) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
<?php endforeach; ?>

</div>
</body>
</html>
