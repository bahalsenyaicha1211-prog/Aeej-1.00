/**
 * Page « Découvrir Jendouba » :
 *  - diaporamas (héro, médias de section, cartes lieux) : fondu automatique ;
 *  - phrase du héro qui tourne ;
 *  - apparition au défilement ;
 *  - bouton « Afficher plus » sur les cartes lieux quand le texte est tronqué.
 */
(() => {
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- Diaporamas génériques : [data-jdb-show] contient des .jdb-slide ---- */
  document.querySelectorAll('[data-jdb-show]').forEach((box) => {
    const slides = box.querySelectorAll('.jdb-slide');
    if (slides.length < 2) return;

    const dots = box.parentElement?.querySelector('.jdb-dots');
    let i = Math.max(0, [...slides].findIndex((s) => s.classList.contains('is-active')));
    let timer = null;
    const delay = parseInt(box.dataset.jdbShow || '4000', 10);

    const show = (n) => {
      slides[i].classList.remove('is-active');
      if (dots) dots.children[i]?.classList.remove('is-on');
      i = (n + slides.length) % slides.length;
      slides[i].classList.add('is-active');
      if (dots) dots.children[i]?.classList.add('is-on');
    };
    const start = () => { if (!reduce && !timer) timer = setInterval(() => show(i + 1), delay); };
    const stop = () => { clearInterval(timer); timer = null; };

    if (dots) {
      slides.forEach((_, n) => {
        const d = document.createElement('i');
        if (n === i) d.classList.add('is-on');
        d.addEventListener('click', () => { show(n); stop(); start(); });
        dots.appendChild(d);
      });
    }
    box.closest('.jdbPlace, .jdbCard, .jdbHero')?.addEventListener('mouseenter', stop);
    box.closest('.jdbPlace, .jdbCard, .jdbHero')?.addEventListener('mouseleave', start);
    start();
  });

  /* ---- Phrase du héro ---- */
  const heroText = document.querySelector('[data-jdb-text]');
  if (heroText) {
    const phrases = [
      'Au pied des monts de la Kroumirie.',
      'Sur la plaine du Medjerda, grenier de la Tunisie.',
      'À deux pas de Bulla Regia et de Chemtou.',
    ];
    let t = 0;
    const rotate = () => {
      heroText.style.opacity = '0';
      heroText.style.transform = 'translateY(6px)';
      setTimeout(() => {
        heroText.textContent = phrases[t];
        heroText.style.opacity = '1';
        heroText.style.transform = 'translateY(0)';
        t = (t + 1) % phrases.length;
      }, 240);
    };
    rotate();
    if (!reduce) setInterval(rotate, 4200);
  }

  /* ---- Apparition au défilement ---- */
  const obs = new IntersectionObserver((entries) => {
    entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('is-in'); obs.unobserve(e.target); } });
  }, { threshold: 0.15 });
  document.querySelectorAll('.reveal').forEach((el) => obs.observe(el));

  /* ---- « Afficher plus » sur les cartes lieux ---- */
  document.querySelectorAll('.jdbPlace').forEach((card) => {
    const txt = card.querySelector('.jdbPlace__txt');
    const btn = card.querySelector('.jdbPlace__more');
    if (!txt || !btn) return;
    if (txt.scrollHeight - txt.clientHeight > 4) btn.hidden = false;
    btn.addEventListener('click', () => {
      const open = card.classList.toggle('is-open');
      btn.textContent = open ? 'Réduire' : 'Afficher plus';
    });
  });
})();
