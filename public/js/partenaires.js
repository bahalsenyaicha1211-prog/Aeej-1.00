/**
 * Page « Nos partenaires » : le bouton « Lire la suite » n'apparaît que si la
 * description est réellement tronquée, et déplie / replie le texte dans la carte.
 */
(() => {
  const cards = document.querySelectorAll('.pt-card');

  cards.forEach((card) => {
    const desc = card.querySelector('.pt-card__desc');
    const btn = card.querySelector('.pt-card__more');
    if (!desc || !btn) return;

    // Le texte dépasse-t-il la zone tronquée ?
    if (desc.scrollHeight - desc.clientHeight > 3) {
      btn.hidden = false;
    }

    btn.addEventListener('click', () => {
      const open = card.classList.toggle('is-open');
      btn.textContent = open ? 'Réduire' : 'Lire la suite';
    });
  });
})();
