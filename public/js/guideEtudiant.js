document.addEventListener('DOMContentLoaded', () => {
  const panels = document.querySelectorAll('[data-panel]');
  const sideLinks = document.querySelectorAll('.side-link');
  const quickCards = document.querySelectorAll('.quick-card');
  const chips = document.querySelectorAll('.chip');

  function activate(targetId) {
    // panels
    panels.forEach(p => p.classList.toggle('is-active', p.id === targetId));

    // side links
    sideLinks.forEach(b => b.classList.toggle('is-active', b.dataset.target === targetId));

    // chips
    chips.forEach(c => c.classList.toggle('is-active', c.dataset.target === targetId));

    // scroll to content
    const anchor = document.getElementById('guide-content');
    if (anchor) anchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  sideLinks.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.target)));
  chips.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.target)));
  quickCards.forEach(card => card.addEventListener('click', () => activate(card.dataset.target)));

  // default
  activate('integration');
});
