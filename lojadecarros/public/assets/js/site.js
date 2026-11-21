const reduz = matchMedia('(prefers-reduced-motion: reduce)').matches;
const suave = reduz ? 'auto' : 'smooth';
const brl = v => v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

// Aviso rápido no pé da tela
const aviso = texto => {
  let el = document.querySelector('.toast');
  if (!el) {
    el = document.createElement('div');
    el.className = 'toast';
    el.setAttribute('role', 'status');
    document.body.append(el);
  }
  el.textContent = texto;
  el.classList.add('on');
  clearTimeout(el.temporizador);
  el.temporizador = setTimeout(() => el.classList.remove('on'), 2400);
};

// Cabeçalho ganha fundo ao rolar
const topo = document.querySelector('.topo');
const rolar = () => topo.classList.toggle('solido', scrollY > 30);
rolar();
addEventListener('scroll', rolar, { passive: true });

// Elementos entram suavemente quando aparecem na tela
const itens = document.querySelectorAll('.reveal');
if (reduz || !('IntersectionObserver' in window)) {
  itens.forEach(i => i.classList.add('visto'));
} else {
  const obs = new IntersectionObserver(es => es.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('visto'); obs.unobserve(e.target); }
  }), { threshold: .15 });
  itens.forEach(i => obs.observe(i));
}

// Números que sobem até o valor final (preços e números do "Sobre nós")
const subir = (el, fim, formato, duracao = 1600) => {
  const inicio = performance.now();
  const passo = agora => {
    const p = Math.min((agora - inicio) / duracao, 1);
    el.textContent = formato(fim * (1 - Math.pow(1 - p, 3)));
    if (p < 1) requestAnimationFrame(passo);
  };
  requestAnimationFrame(passo);
};
document.querySelectorAll('[data-valor]').forEach(el => {
  const fim = parseFloat(el.dataset.valor);
  if (!reduz && fim) subir(el, fim, brl);
});
document.querySelectorAll('[data-contar]').forEach(el => {
  const fim = parseInt(el.dataset.contar, 10);
  if (reduz || !fim) return;
  el.textContent = '0';
  new IntersectionObserver(([e], o) => {
    if (e.isIntersecting) { o.disconnect(); subir(el, fim, v => Math.round(v)); }
  }, { threshold: .6 }).observe(el);
});

// Carrossel de carros: setas, contador, barra de progresso e avanço automático
const trilho = document.querySelector('.trilho');
if (trilho) {
  const slides = [...trilho.children];
  const contador = document.querySelector('.contador');
  const barra = document.querySelector('.progresso i');
  const dois = n => String(n).padStart(2, '0');
  const passo = () => slides.length > 1 ? slides[1].offsetLeft - slides[0].offsetLeft : 0;
  const noFim = () => trilho.scrollLeft + trilho.clientWidth >= trilho.scrollWidth - 4;

  const atualiza = () => {
    const i = noFim() ? slides.length - 1 : Math.round(trilho.scrollLeft / (passo() || 1));
    if (contador) contador.textContent = `${dois(i + 1)} / ${dois(slides.length)}`;
    if (barra) barra.style.setProperty('--p', (i + 1) / slides.length);
  };
  const ir = dir => {
    if (dir > 0 && noFim()) trilho.scrollTo({ left: 0, behavior: suave });
    else if (dir < 0 && trilho.scrollLeft < 4) trilho.scrollTo({ left: trilho.scrollWidth, behavior: suave });
    else trilho.scrollBy({ left: dir * passo(), behavior: suave });
  };
  document.querySelectorAll('.seta').forEach(b => b.addEventListener('click', () => ir(+b.dataset.dir)));
  trilho.addEventListener('scroll', atualiza, { passive: true });
  atualiza();

  // Avança sozinho a cada 5 s, só com o carrossel visível e sem mouse ou foco em cima
  let timer;
  const parar = () => clearInterval(timer);
  const iniciar = () => { parar(); if (!reduz && slides.length > 1) timer = setInterval(() => ir(1), 5000); };
  ['mouseenter', 'focusin', 'touchstart'].forEach(ev => trilho.addEventListener(ev, parar, { passive: true }));
  ['mouseleave', 'focusout'].forEach(ev => trilho.addEventListener(ev, iniciar));
  new IntersectionObserver(([e]) => e.isIntersecting ? iniciar() : parar()).observe(trilho);
}

// Nossos carros: busca, filtros, faixa de preço, ordenação e coração de interesse
const grade = document.querySelector('.grade[data-csrf]');
if (grade) {
  const cards = [...grade.querySelectorAll('.carro')];
  const busca = document.getElementById('busca');
  const faixa = document.getElementById('preco-max');
  const ordem = document.getElementById('ordem');
  const vazio = grade.querySelector('.sem-resultado');
  const contagem = document.getElementById('contagem');
  const textoInicial = contagem ? contagem.textContent : '';
  const limpa = t => t.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
  const escolha = { marca: '', comb: '' };

  const aplicar = () => {
    const q = busca ? limpa(busca.value) : '';
    const teto = faixa ? +faixa.value : Infinity;
    if (faixa) document.getElementById('preco-txt').textContent = brl(teto);
    let n = 0;
    cards.forEach(c => {
      const d = c.dataset;
      const ok = limpa(d.nome).includes(q)
        && (!escolha.marca || d.marca === escolha.marca)
        && (!escolha.comb || d.comb === escolha.comb)
        && +d.preco <= teto;
      c.hidden = !ok;
      if (ok) n++;
    });
    const [campo, sentido] = (ordem ? ordem.value : 'recente').split(':');
    const chave = c => campo === 'recente' ? +c.dataset.i : +c.dataset[campo];
    cards.sort((a, b) => (chave(a) - chave(b)) * (sentido === 'desc' ? -1 : 1)).forEach(c => grade.append(c));
    if (vazio) vazio.hidden = n > 0 || !cards.length;
    if (contagem) contagem.textContent = n === cards.length ? textoInicial : `${n} ${n === 1 ? 'carro encontrado' : 'carros encontrados'}`;
  };
  const limpar = () => {
    if (busca) busca.value = '';
    if (faixa) faixa.value = faixa.max;
    if (ordem) ordem.value = 'recente';
    escolha.marca = escolha.comb = '';
    document.querySelectorAll('.grupo').forEach(g => g.querySelectorAll('.chip').forEach((b, i) => b.setAttribute('aria-pressed', i === 0)));
    aplicar();
  };

  if (busca) busca.addEventListener('input', aplicar);
  if (faixa) faixa.addEventListener('input', aplicar);
  if (ordem) ordem.addEventListener('change', aplicar);
  document.querySelectorAll('.grupo').forEach(g => g.addEventListener('click', e => {
    const b = e.target.closest('.chip');
    if (!b) return;
    g.querySelectorAll('.chip').forEach(x => x.setAttribute('aria-pressed', x === b));
    escolha[g.dataset.grupo] = b.dataset.valor;
    aplicar();
  }));
  const botaoLimpar = grade.querySelector('.limpar');
  if (botaoLimpar) botaoLimpar.addEventListener('click', limpar);
  aplicar();

  // Coração: marca ou desmarca o interesse sem sair da lista
  grade.addEventListener('click', async e => {
    const b = e.target.closest('.coracao');
    if (!b) return;
    if (grade.dataset.logado !== '1') {
      location.href = `index.php?controller=site&action=login&voltar=${b.dataset.id}`;
      return;
    }
    const marcar = b.getAttribute('aria-pressed') !== 'true';
    const corpo = new FormData();
    corpo.append('csrf', grade.dataset.csrf);
    corpo.append('id_carro', b.dataset.id);
    try {
      const r = await fetch(`index.php?controller=site&action=${marcar ? 'interesse' : 'removerInteresse'}`,
        { method: 'POST', body: corpo, headers: { 'X-Requested-With': 'fetch' } });
      if (!r.ok) throw new Error(r.status);
      b.setAttribute('aria-pressed', marcar);
      b.classList.remove('pulso');
      void b.offsetWidth;
      b.classList.add('pulso');
      aviso(marcar ? 'Interesse registrado' : 'Removido dos seus interesses');
    } catch {
      aviso('Não foi possível salvar agora. Tente de novo.');
    }
  });
}

// Simulador de financiamento (parcelas fixas, tabela Price)
const sim = document.querySelector('.simulador');
if (sim) {
  const preco = parseFloat(sim.dataset.preco);
  const q = nome => sim.querySelector(`[data-sim="${nome}"]`);
  const calcular = () => {
    const pct = +q('entrada').value;
    const entrada = preco * pct / 100;
    const financiado = preco - entrada;
    const n = +sim.querySelector('input[name="parcelas"]:checked').value;
    const i = (parseFloat(q('taxa').value) || 0) / 100;
    const parcela = i ? financiado * i / (1 - Math.pow(1 + i, -n)) : financiado / n;
    q('entrada-pct').textContent = `${pct}%`;
    q('entrada-valor').textContent = brl(entrada);
    q('financiado').textContent = brl(financiado);
    q('parcela').textContent = `${n}x de ${brl(parcela)}`;
    q('total').textContent = brl(entrada + parcela * n);
  };
  sim.addEventListener('input', calcular);
  calcular();
}

// Máscara do CPF no cadastro
const cpf = document.getElementById('cpf');
if (cpf) cpf.addEventListener('input', () => {
  const d = cpf.value.replace(/\D/g, '').slice(0, 11);
  cpf.value = d.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2');
});

// Menu "Entrar": fecha ao clicar fora ou apertar Esc
const entrar = document.querySelector('.entrar');
if (entrar) {
  document.addEventListener('click', e => { if (!entrar.contains(e.target)) entrar.open = false; });
  addEventListener('keydown', e => { if (e.key === 'Escape') entrar.open = false; });
}

// Transição entre páginas: cortina azul (vale para qualquer navegador) e foto que viaja do card até a página do carro
const html = document.documentElement;
let voo = null;
try { voo = JSON.parse(sessionStorage.getItem('voo')); sessionStorage.removeItem('voo'); } catch {}

const alvo = document.querySelector('.detalhe-foto');
if (alvo && voo && voo.href === location.href && !reduz) {
  const f = alvo.getBoundingClientRect();
  alvo.style.animation = 'none';
  alvo.animate([
    { transformOrigin: '0 0', transform: `translate(${voo.x - f.left}px, ${voo.y - f.top}px) scale(${voo.w / f.width}, ${voo.h / f.height})` },
    { transformOrigin: '0 0', transform: 'none' }
  ], { duration: 800, delay: 120, easing: 'cubic-bezier(.2,.7,.2,1)', fill: 'backwards' });
}

if (!reduz) {
  document.addEventListener('click', e => {
    const a = e.target.closest('a[href]');
    if (!a || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    if (a.target || a.hasAttribute('download') || a.origin !== location.origin) return;
    if (a.pathname === location.pathname && a.search === location.search) return;   // só rolagem na mesma página
    e.preventDefault();
    const foto = a.querySelector('.foto');
    try {
      sessionStorage.setItem('cortina', '1');
      if (foto) {
        const r = foto.getBoundingClientRect();
        sessionStorage.setItem('voo', JSON.stringify({ href: a.href, x: r.left, y: r.top, w: r.width, h: r.height }));
      }
    } catch {}
    html.classList.add('cortina-fecha');
    setTimeout(() => { location.href = a.href; }, 350);
  });
  // Voltar pelo histórico pode restaurar a página com a cortina fechada
  addEventListener('pageshow', e => { if (e.persisted) html.classList.remove('cortina-fecha'); });
}