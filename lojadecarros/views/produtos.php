<?php
$editar = $editar ?? null;
$produtos = $produtos ?? [];
?>

<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Carros</title>
<link rel="icon" type="image/png" sizes="512x512" href="public/assets/css/favicon.png">

<link rel="stylesheet" href="public/assets/css/produtos.css">
</head>

<body>

<div class="header">
<div class="container header-inner">
<div>
<img src="public/assets/css/logoprimemotors-removebg-preview.png"
     class="logo-header">
<span class="badge">Carros</span>
</div>

<div class="user">
Olá, <strong><?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?></strong>
<a class="btn btn-ghost" href="index.php?controller=auth&action=logout">Sair</a>
</div>
</div>
</div>

<div class="container grid">

<!-- FORM -->
<div class="card">
<h2><?= $editar ? "Editar Carro #".$editar['id_carro'] : "Cadastrar Carro" ?></h2>

<form method="post" action="index.php?controller=produto&action=salvar" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $editar['id_carro'] ?? 0 ?>">

<div class="form-group">
<label>Marca</label>
<input class="input" type="text" name="marca" required
value="<?= $editar['marca'] ?? '' ?>">
</div>

<div class="form-group">
<label>Modelo</label>
<input class="input" type="text" name="modelo" required
value="<?= $editar['modelo'] ?? '' ?>">
</div>

<div class="form-group">
<label>Ano</label>
<input class="input" type="number" name="ano" required
value="<?= $editar['ano'] ?? '' ?>">
</div>

<div class="form-group">
<label>Cor</label>
<input class="input" type="text" name="cor"
value="<?= $editar['cor'] ?? '' ?>">
</div>

<div class="form-group">
<label>Combustível</label>
<input class="input" type="text" name="combustivel"
value="<?= $editar['combustivel'] ?? '' ?>">
</div>

<div class="form-group">
<label>KM</label>
<input class="input" type="number" name="km"
value="<?= $editar['km'] ?? '' ?>">
</div>

<div class="form-group">
<label>Nº Chassi</label>
<input class="input" type="text" name="n_chassi"
value="<?= $editar['n_chassi'] ?? '' ?>">
</div>

<div class="form-group">
  <label>Imagem do Carro</label>
  <input class="input" type="file" name="imagem" id="imagem" accept="image/*">
</div>

<img id="previewImagem" style="width:120px; display:none; margin-top:10px;">

<!-- NOVO CAMPO -->
<div class="form-group">
<label>Preço (R$)</label>
<input
    class="input"
    type="number"
    name="preco"
    step="0.01"
    min="0"
    required
    value="<?= $editar['preco'] ?? '' ?>">
</div>

<div class="actions">
<button class="btn btn-primary" type="submit">Salvar</button>
<a class="btn" href="index.php?controller=produto&action=index">Limpar</a>
</div>

</form>
</div>

<!-- LISTA -->
<div class="card">
<h2>Lista de Carros</h2>

<table class="table">
<thead>
<tr>
<th>ID</th>
<th>Marca</th>
<th>Modelo</th>
<th>Ano</th>
<th>Cor</th>
<th>KM</th>
<th>Preço</th>
<th>Ações</th>
<th>Imagem</th>
</tr>
</thead>

<tbody>
<?php foreach ($produtos as $p): ?>
<tr>
<td><?= $p['id_carro'] ?></td>
<td><?= htmlspecialchars($p['marca']) ?></td>
<td><?= htmlspecialchars($p['modelo']) ?></td>
<td><?= $p['ano'] ?></td>
<td><?= htmlspecialchars($p['cor']) ?></td>
<td><?= number_format($p['km'], 0, ',', '.') ?></td>
<td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
<td>
  <a class="btn"
  href="index.php?controller=produto&action=index&id=<?= $p['id_carro'] ?>">
  Editar
  </a>

  <a class="btn btn-danger"
  href="index.php?controller=produto&action=deletar&id=<?= $p['id_carro'] ?>"
  onclick="return confirm('Deletar este carro?')">
  Excluir
  </a>
</td>

<td>
  <?php if (!empty($p['imagem'])): ?>
    <img src="<?= $p['imagem'] ?>" width="80">
  <?php endif; ?>
</td>

<?php endforeach; ?>
</tbody>

</table>
</div>

</div>

</body>
</html>

<script>
document.getElementById("imagem").addEventListener("change", function(e) {
  const file = e.target.files[0];

  if (file) {
    const reader = new FileReader();

    reader.onload = function(ev) {
      const img = document.getElementById("previewImagem");
      img.src = ev.target.result;
      img.style.display = "block";
    };

    reader.readAsDataURL(file);
  }
});
</script>