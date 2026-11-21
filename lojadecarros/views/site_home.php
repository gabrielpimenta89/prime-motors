<?php
$titulo   = 'Montclair Motors';
$destaque = $carros[0] ?? null;
$semFoto  = !$destaque || empty($destaque['imagem']);
$slides   = array_slice($carros, 0, 8);
$total    = count($carros);
$marcas   = count(array_unique(array_column($carros, 'marca')));
require __DIR__ . '/site_topo.php';
?>
<main>

<section class="hero<?= $semFoto ? ' sem-foto' : '' ?>">
  <div class="hero-brilho"></div>
  <?php if ($destaque): ?><span class="hero-marca" aria-hidden="true" style="--n:<?= function_exists('mb_strlen') ? mb_strlen($destaque['marca']) : strlen($destaque['marca']) ?>"><?= site_e($destaque['marca']) ?></span><?php endif; ?>
  <div class="hero-texto">
    <h1><?= site_e($destaque ? site_nome($destaque) : 'Montclair Motors') ?></h1>
    <p class="hero-preco"<?= $destaque ? ' data-valor="' . (float)$destaque['preco'] . '"' : '' ?>><?= site_e($destaque ? site_preco($destaque['preco']) : 'Novos carros chegam em breve.') ?></p>
    <?php if ($destaque): ?>
      <a class="btn" href="index.php?controller=site&amp;action=detalhes&amp;id=<?= (int)$destaque['id_carro'] ?>">Ver detalhes</a>
    <?php endif; ?>
  </div>
  <?php if (!$semFoto): ?>
    <div class="hero-foto"><img src="<?= site_e($destaque['imagem']) ?>" alt="<?= site_e(site_nome($destaque)) ?>"></div>
  <?php endif; ?>
</section>

<?php if ($slides): ?>
<section class="destaques" aria-label="Carros em destaque">
  <div class="destaques-topo reveal">
    <h2>Em destaque</h2>
    <?php if (count($slides) > 1): ?>
      <div class="controles">
        <span class="contador">01 / <?= sprintf('%02d', count($slides)) ?></span>
        <button class="seta" type="button" data-dir="-1" aria-label="Carro anterior"><svg viewBox="0 0 24 24"><path d="m15 6-6 6 6 6"></path></svg></button>
        <button class="seta" type="button" data-dir="1" aria-label="Próximo carro"><svg viewBox="0 0 24 24"><path d="m9 6 6 6-6 6"></path></svg></button>
      </div>
    <?php endif; ?>
  </div>
  <div class="trilho reveal" tabindex="0" aria-label="Carros em destaque">
    <?php foreach ($slides as $c): ?>
      <a class="slide" href="index.php?controller=site&amp;action=detalhes&amp;id=<?= (int)$c['id_carro'] ?>">
        <div class="foto" style="view-transition-name:foto-<?= (int)$c['id_carro'] ?>">
          <?php if (!empty($c['imagem'])): ?><img src="<?= site_e($c['imagem']) ?>" alt="<?= site_e(site_nome($c)) ?>" loading="lazy"><?php endif; ?>
        </div>
        <div class="slide-info">
          <div>
            <h3><?= site_e(site_nome($c)) ?></h3>
            <dl class="mini">
              <?php foreach (array_slice(site_specs($c), 0, 2, true) as $k => $v): ?>
                <div><dt><?= site_e($k) ?></dt><dd><?= site_e($v) ?></dd></div>
              <?php endforeach; ?>
            </dl>
          </div>
          <p class="preco"><?= site_e(site_preco($c['preco'])) ?></p>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
  <?php if (count($slides) > 1): ?><div class="progresso" aria-hidden="true"><i></i></div><?php endif; ?>
  <div class="destaques-rodape reveal"><a class="btn" href="index.php?controller=site&amp;action=carros">Ver todos os carros</a></div>
</section>
<?php endif; ?>

<section class="sobre" id="sobre">
  <div class="sobre-grade">
    <div class="reveal">
      <h2>Sobre nós</h2>
      <p class="sobre-lide">Excelência em movimento.</p>
    </div>
    <div class="reveal" style="--i:1">
      <p>A Montclair Motors reúne carros selecionados para quem busca desempenho, conforto e sofisticação. Cada veículo aparece aqui com preço, especificações e fotos, para você decidir com clareza.</p>
      <p>Crie sua conta, marque interesse nos carros de que mais gostar e nossa equipe acompanha o seu interesse.</p>
      <div class="valores">
        <div><strong>Seleção criteriosa</strong><span>Cada carro é escolhido antes de chegar ao site.</span></div>
        <div><strong>Transparência</strong><span>Preço e especificações sempre à vista.</span></div>
        <div><strong>Atendimento próximo</strong><span>Uma equipe pronta para ajudar você.</span></div>
      </div>
      <?php if ($total): ?>
        <div class="numeros">
          <div><strong data-contar="<?= $total ?>"><?= $total ?></strong><span><?= $total === 1 ? 'carro disponível' : 'carros disponíveis' ?></span></div>
          <div><strong data-contar="<?= $marcas ?>"><?= $marcas ?></strong><span><?= $marcas === 1 ? 'marca' : 'marcas' ?></span></div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

</main>
<?php require __DIR__ . '/site_rodape.php'; ?>