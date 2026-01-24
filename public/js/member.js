// public/js/member.js
(() => {
  const openBtn = document.querySelector('[data-drawer-open]');
  const drawer = document.querySelector('[data-drawer]');
  const closeBtn = document.querySelector('[data-drawer-close]');
  const backdrop = document.querySelector('[data-drawer-backdrop]');

  function openDrawer() {
    if (!drawer) return;
    drawer.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    if (!drawer) return;
    drawer.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  openBtn?.addEventListener('click', openDrawer);
  closeBtn?.addEventListener('click', closeDrawer);
  backdrop?.addEventListener('click', closeDrawer);

  // Optional: ESC to close
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDrawer();
  });
})();
