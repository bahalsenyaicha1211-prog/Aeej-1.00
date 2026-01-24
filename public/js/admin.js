(function () {
  const drawer = document.querySelector('[data-admin-drawer]');
  const openBtn = document.querySelector('[data-admin-drawer-open]');
  const closeBtns = document.querySelectorAll('[data-admin-drawer-close]');

  if (!drawer || !openBtn) return;

  function open() {
    drawer.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }
  function close() {
    drawer.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  openBtn.addEventListener('click', open);
  closeBtns.forEach(btn => btn.addEventListener('click', close));

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') close();
  });
})();
