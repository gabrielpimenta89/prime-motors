<?php
$titulo = 'Nossos carros | Montclair Motors';
$total  = count($carros);
require __DIR__ . '/site_topo.php';

$marcas = array_values(array_unique(array_column($carros, 'marca')));
$combs  = array_values(array_unique(array_filter(array_column($carros, 'combustivel'))));
$precos = array_map('floatval', array_column($carros, 'preco'));
$minimo = $precos ? floor(min($precos) / 1000) * 1000 : 0;
$maximo = $precos ? ceil(max($precos) / 1000) * 1000 : 0;
?>
<main>

<section class="pagina-topo">
  <h1>Nossos carros</h1>
  <p id="contagem"><?= $total ? ($total === 1 ? '1 carro disponível' : $total . ' carros disponíveis') : 'Novos carros chegam em breve.' ?></p>
  <?php if ($total): ?>
    <div class="filtros">
      <input id="busca" type="search" placeholder="Buscar modelo" aria-label="Buscar modelo">
      <?php if ($total > 1): ?>
        <?php foreach (['marca' => $marcas, 'comb' => $combs] as $grupo => $valores): if (count($valores) < 2) continue; ?>
          <div class="grupo" data-grupo="<?= $grupo ?>" role="group" aria-label="<?= $grupo === 'marca' ? 'Marca' : 'Combustível' ?>">
            <button type="button" class="chip" aria-pressed="true" data-valor=""><?= $grupo === 'marca' ? 'Todas' : 'Todos' ?></button>
            <?php foreach ($valores as $v): ?><button type="button" class="chip" aria-pressed="false" data-valor="<?= site_e($v) ?>"><?= site_e($v) ?></button><?php endforeach; ?>
          </div>
        <?php endforeach; ?>
        <div class="filtros-linha">
          <label class="faixa"><span>Preço até <b id="preco-txt"></b></span>
            <input type="range" id="preco-max" min="<?= $minimo ?>" max="<?= $maximo ?>" step="1000" value="<?= $maximo ?>">
          </label>
          <label class="ordem"><span>Ordenar por</span>
            <select id="ordem">
              <option value="recente">Mais recentes</option>
              <option value="preco:asc">Menor preço</option>
              <option value="preco:desc">Maior preço</option>
              <option value="ano:desc">Mais novos</option>
              <option value="km:asc">Menor quilometragem</option>
            </select>
          </label>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</section>

<section class="catalogo catalogo-pagina">
  <div class="grade" data-csrf="<?= site_csrf() ?>" data-logado="<?= $logado ? 1 : 0 ?>">
    <?php foreach ($carros as $i => $c): $id = (int)$c['id_carro']; $marcado = in_array($id, $marcados, true); ?>
      <article class="carro reveal" style="--i:<?= $i % 3 ?>" data-i="<?= $i ?>"
               data-nome="<?= site_e(site_nome($c)) ?>" data-marca="<?= site_e($c['marca']) ?>" data-comb="<?= site_e($c['combustivel']) ?>"
               data-preco="<?= (float)$c['preco'] ?>" data-ano="<?= (int)$c['ano'] ?>" data-km="<?= (int)$c['km'] ?>">
        <a class="carro-link" href="index.php?controller=site&amp;action=detalhes&amp;id=<?= $id ?>">
          <div class="foto" style="view-transition-name:foto-<?= $id ?>">
            <?php if (!empty($c['imagem'])): ?><img src="<?= site_e($c['imagem']) ?>" alt="<?= site_e(site_nome($c)) ?>" loading="lazy"><?php endif; ?>
          </div>
          <h3><?= site_e(site_nome($c)) ?></h3>
          <dl class="mini">
            <?php foreach (array_slice(site_specs($c), 0, 3, true) as $k => $v): ?>
              <div><dt><?= site_e($k) ?></dt><dd><?= site_e($v) ?></dd></div>
            <?php endforeach; ?>
          </dl>
          <p class="preco"><?= site_e(site_preco($c['preco'])) ?></p>
        </a>
        <button type="button" class="coracao" data-id="<?= $id ?>" aria-pressed="<?= $marcado ? 'true' : 'false' ?>" aria-label="Tenho interesse neste carro">
          <svg viewBox="0 0 24 24"><path d="M12 21s-7-4.4-9-9a5 5 0 0 1 9-3 5 5 0 0 1 9 3c-2 4.6-9 9-9 9z"></path></svg>
        </button>
      </article>
    <?php endforeach; ?>
    <?php if (!$carros): ?><p class="vazio">Nenhum carro disponível no momento.</p><?php endif; ?>
    <p class="vazio sem-resultado" hidden>Nenhum carro encontrado com esses filtros. <button type="button" class="limpar">Limpar filtros</button></p>
  </div>
</section>

</main>
<?php require __DIR__ . '/site_rodape.php'; ?>