/**
 * Page Contact : apparition au défilement pour le héro, les cartes de
 * l'équipe, la barre latérale et le formulaire.
 */
(() => {
  const obs = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
      if (e.isIntersecting) {
        e.target.classList.add('is-in');
        obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.reveal').forEach((el) => obs.observe(el));
})();
