(() => {
  // ===== TEXTES HERO =====
  const diapo = document.getElementById('texte-sur-image');
  const textes = [
    "Bienvenue sur l’AEEJ",
    "Association des étudiants étrangers à Jendouba",
    "Une communauté pour réussir ensemble"
  ];
  let i = 0;

  function afficherTexte() {
    if (!diapo) return;
    diapo.style.opacity = "0";
    diapo.style.transform = "translateY(6px)";
    setTimeout(() => {
      diapo.textContent = textes[i];
      diapo.style.opacity = "1";
      diapo.style.transform = "translateY(0)";
      i = (i + 1) % textes.length;
    }, 220);
  }

  // ===== SLIDER =====
  const slides = document.querySelectorAll('.slide');
  let index = 0;

  function changeSlide() {
    if (!slides.length) return;
    slides[index].classList.remove('active');
    index = (index + 1) % slides.length;
    slides[index].classList.add('active');
  }

  // ===== COUNTERS =====
  function animateCounter(el, target, duration = 1400) {
    let start = 0;
    const steps = Math.max(1, Math.floor(duration / 16));
    const inc = target / steps;

    const timer = setInterval(() => {
      start += inc;
      if (start >= target) {
        el.textContent = target;
        clearInterval(timer);
      } else {
        el.textContent = Math.floor(start);
      }
    }, 16);
  }

  function initStatsAnimation() {
    const statNumbers = document.querySelectorAll('.stat-number');
    if (!statNumbers.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;

        const el = entry.target;
        if (el.dataset.animated) return;

        const target = parseInt(el.getAttribute('data-target') || '0', 10);
        el.dataset.animated = "1";
        animateCounter(el, isNaN(target) ? 0 : target);

        observer.unobserve(el);
      });
    }, { threshold: 0.25 });

    statNumbers.forEach(el => observer.observe(el));
  }

  document.addEventListener('DOMContentLoaded', () => {
    // transitions texte
    if (diapo) {
      diapo.style.transition = "opacity 220ms ease, transform 220ms ease";
      afficherTexte();
      setInterval(afficherTexte, 4200);
    }

    // slider
    setInterval(changeSlide, 4200);

    // compteurs
    initStatsAnimation();
  });
})();
