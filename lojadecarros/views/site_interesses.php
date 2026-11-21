<?php $titulo = 'Meus interesses | Montclair Motors'; require __DIR__ . '/site_topo.php'; ?>
<main>
  <section class="pagina-topo">
    <h1>Meus interesses</h1>
    <p><?= $lista ? (count($lista) === 1 ? '1 carro na sua lista' : count($lista) . ' carros na sua lista') : 'Sua lista está vazia.' ?></p>
  </section>
  <section class="catalogo catalogo-pagina">
  <div class="grade">
    <?php foreach ($lista as $i => $c):
      $vendido = (int)$c['vendido'] === 1;
      $tag  = $vendido ? 'div' : 'a';
      $href = $vendido ? '' : ' href="index.php?controller=site&amp;action=detalhes&amp;id=' . (int)$c['id_carro'] . '"';
    ?>
      <article class="carro reveal<?= $vendido ? ' vendido' : '' ?>" style="--i:<?= $i % 3 ?>">
        <<?= $tag ?> class="carro-link"<?= $href ?>>
          <div class="foto" style="view-transition-name:foto-<?= (int)$c['id_carro'] ?>">
            <?php if (!empty($c['imagem'])): ?><img src="<?= site_e($c['imagem']) ?>" alt="<?= site_e(site_nome($c)) ?>" loading="lazy"><?php endif; ?>
            <?php if ($vendido): ?><span class="tag">Vendido</span><?php endif; ?>
          </div>
          <h3><?= site_e(site_nome($c)) ?></h3>
        </<?= $tag ?>>
        <p class="preco"><?= site_e(site_preco($c['preco'])) ?></p>
        <form method="post" action="index.php?controller=site&amp;action=removerInteresse">
          <input type="hidden" name="csrf" value="<?= site_csrf() ?>">
          <input type="hidden" name="id_carro" value="<?= (int)$c['id_carro'] ?>">
          <button class="remover">Remover da lista</button>
        </form>
      </article>
    <?php endforeach; ?>
    <?php if (!$lista): ?><p class="vazio">Você ainda não marcou interesse em nenhum carro. <a class="voltar" href="index.php?controller=site&amp;action=carros">Ver carros</a></p><?php endif; ?>
  </div>
  </section>
</main>
<?php require __DIR__ . '/site_rodape.php'; ?>