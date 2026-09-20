/**
 * Filtre instantané pour les listes admin/trésorerie : transforme un
 * <form data-live-search="#resultsId"> classique (GET + bouton "Rechercher")
 * en filtre à la volée — on tape, la liste se met à jour après un court
 * débounce, sans rechargement de page ni clic.
 *
 * Contrat serveur : le contrôleur doit, sur la même route GET, renvoyer
 * uniquement le fragment "résultats" (table + pagination, PAS le layout)
 * quand la requête porte l'en-tête X-Requested-With: XMLHttpRequest.
 *
 * - Le formulaire et son bouton "submit" restent 100% fonctionnels sans JS
 *   (progressive enhancement) : le bouton est juste masqué une fois le JS actif.
 * - Les liens de pagination à l'intérieur du conteneur cible, ainsi que tout
 *   lien "Réinitialiser" marqué data-live-search-link="#resultsId" ailleurs
 *   sur la page, sont interceptés et passent aussi par l'AJAX.
 * - L'URL est mise à jour (history.replaceState) pour rester partageable/
 *   rafraîchissable, sans polluer l'historique à chaque frappe.
 */
(() => {
  const DEBOUNCE_MS = 300;

  function paramsFromForm(form) {
    const params = new URLSearchParams(new FormData(form));
    for (const key of [...params.keys()]) {
      if (params.get(key) === '') params.delete(key);
    }
    params.delete('page');
    return params;
  }

  function buildUrl(base, params) {
    const qs = params.toString();
    return qs ? `${base}?${qs}` : base;
  }

  function syncFormsFromUrl(url) {
    const params = new URL(url, window.location.origin).searchParams;
    document.querySelectorAll('form[data-live-search]').forEach((form) => {
      Array.from(form.elements).forEach((el) => {
        if (!el.name || el.type === 'submit' || el.type === 'hidden') return;
        el.value = params.get(el.name) ?? '';
      });
    });
  }

  function navigate(target, url) {
    target.dataset.liveSearchController && target._lsAbort?.abort();
    const controller = new AbortController();
    target._lsAbort = controller;

    target.classList.add('is-loading');
    return fetch(url, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      signal: controller.signal,
    })
      .then((res) => (res.ok ? res.text() : Promise.reject(res.status)))
      .then((html) => {
        target.innerHTML = html;
        history.replaceState(null, '', url);
        syncFormsFromUrl(url);
      })
      .catch((err) => {
        if (err?.name !== 'AbortError') {
          // Échec réseau/serveur : on laisse le contenu précédent affiché
          // plutôt que de casser la page.
          console.error('live-search:', err);
        }
      })
      .finally(() => target.classList.remove('is-loading'));
  }

  function init(form) {
    const target = document.querySelector(form.dataset.liveSearch);
    if (!target) return;

    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
    if (submitBtn) submitBtn.hidden = true;

    let timer = null;
    const trigger = () => {
      clearTimeout(timer);
      timer = setTimeout(() => {
        navigate(target, buildUrl(form.action, paramsFromForm(form)));
      }, DEBOUNCE_MS);
    };

    form.addEventListener('input', trigger);
    form.addEventListener('change', trigger);
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      clearTimeout(timer);
      navigate(target, buildUrl(form.action, paramsFromForm(form)));
    });

    target.addEventListener('click', (e) => {
      const link = e.target.closest('a[href]');
      if (!link || link.origin !== window.location.origin) return;
      e.preventDefault();
      navigate(target, link.href);
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }

  document.addEventListener('click', (e) => {
    const link = e.target.closest('[data-live-search-link]');
    if (!link) return;
    const target = document.querySelector(link.dataset.liveSearchLink);
    if (!target) return;
    e.preventDefault();
    navigate(target, link.href);
  });

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-live-search]').forEach(init);
  });
})();
