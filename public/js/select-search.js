/**
 * Amélioration progressive des <select> : transforme chaque <select> "normal"
 * de la page en un menu déroulant stylé, avec recherche instantanée pour les
 * listes longues (ex. choix d'un membre).
 *
 * - Le <select> d'origine reste dans le DOM, réellement fonctionnel (masqué
 *   visuellement par une technique "visually-hidden", jamais display:none —
 *   un select `required` caché en display:none casse la validation native).
 * - Le nouveau menu ne fait que piloter select.value puis déclenche un
 *   évènement `change` : tout script déjà branché sur le select continue de
 *   fonctionner sans aucune modification.
 * - Exclus : [multiple], [disabled], [data-no-search].
 * - Un select dont les <option> changent dynamiquement (ex. rempli en JS)
 *   peut se resynchroniser en déclenchant `select.dispatchEvent(new Event('ssel:refresh'))`.
 */
(() => {
  const SEARCH_THRESHOLD = 7;

  function normalize(str) {
    return (str || '')
      .toString()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .toLowerCase()
      .trim();
  }

  function enhance(select) {
    if (select.dataset.sselDone) return;
    select.dataset.sselDone = '1';

    const wrap = document.createElement('div');
    wrap.className = 'ssel';
    select.parentNode.insertBefore(wrap, select);
    wrap.appendChild(select);
    select.classList.add('ssel__native');
    select.setAttribute('tabindex', '-1');
    select.setAttribute('aria-hidden', 'true');

    const trigger = document.createElement('button');
    trigger.type = 'button';
    trigger.className = 'ssel__trigger';
    trigger.setAttribute('aria-haspopup', 'listbox');
    trigger.setAttribute('aria-expanded', 'false');
    trigger.innerHTML =
      '<span class="ssel__label"></span>' +
      '<svg class="ssel__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>';
    wrap.appendChild(trigger);

    const panel = document.createElement('div');
    panel.className = 'ssel__panel';
    panel.hidden = true;
    panel.innerHTML =
      '<div class="ssel__searchWrap" hidden><input type="text" class="ssel__search" placeholder="Rechercher…" autocomplete="off"></div>' +
      '<ul class="ssel__list" role="listbox" tabindex="-1"></ul>' +
      '<div class="ssel__empty" hidden>Aucun résultat</div>';
    wrap.appendChild(panel);

    const labelEl = trigger.querySelector('.ssel__label');
    const searchWrap = panel.querySelector('.ssel__searchWrap');
    const searchInput = panel.querySelector('.ssel__search');
    const list = panel.querySelector('.ssel__list');
    const emptyEl = panel.querySelector('.ssel__empty');

    let items = []; // { value, text, disabled, li }
    let activeIndex = -1;
    let open = false;

    function labelFor(opt) {
      return (opt.textContent || '').trim() || ' ';
    }

    function buildItems() {
      trigger.disabled = select.disabled;
      wrap.classList.toggle('is-disabled', select.disabled);
      if (select.disabled) close();
      list.innerHTML = '';
      items = Array.from(select.options).map((opt, i) => {
        const li = document.createElement('li');
        li.className = 'ssel__opt';
        li.setAttribute('role', 'option');
        li.dataset.index = i;
        li.textContent = labelFor(opt);
        if (opt.disabled) li.classList.add('is-disabled');
        if (opt.selected) li.classList.add('is-selected');
        list.appendChild(li);
        return { opt, li, disabled: opt.disabled, text: labelFor(opt) };
      });
      syncLabel();
    }

    function syncLabel() {
      const opt = select.options[select.selectedIndex];
      labelEl.textContent = opt ? labelFor(opt) : '';
      labelEl.classList.toggle('is-placeholder', !!opt && opt.value === '');
      items.forEach((it) => it.li.classList.toggle('is-selected', it.opt === opt));
    }

    function filter() {
      const q = normalize(searchInput.value);
      let anyVisible = false;
      let firstVisible = -1;
      items.forEach((it, i) => {
        const match = q === '' || normalize(it.text).includes(q);
        it.li.hidden = !match;
        if (match) {
          anyVisible = true;
          if (firstVisible === -1) firstVisible = i;
        }
      });
      emptyEl.hidden = anyVisible;
      setActive(anyVisible ? firstVisible : -1);
    }

    function setActive(index) {
      items.forEach((it) => it.li.classList.remove('is-active'));
      activeIndex = index;
      if (index >= 0 && items[index]) {
        items[index].li.classList.add('is-active');
        items[index].li.scrollIntoView({ block: 'nearest' });
      }
    }

    function choose(index) {
      const it = items[index];
      if (!it || it.disabled) return;
      select.value = it.opt.value;
      select.dispatchEvent(new Event('change', { bubbles: true }));
      select.dispatchEvent(new Event('input', { bubbles: true }));
      syncLabel();
      close();
      trigger.focus();
    }

    function openPanel() {
      if (open || select.disabled) return;
      open = true;
      panel.hidden = false;
      trigger.setAttribute('aria-expanded', 'true');
      wrap.classList.add('is-open');
      const hasSearch = items.length > SEARCH_THRESHOLD;
      searchWrap.hidden = !hasSearch;
      searchInput.value = '';
      filter();
      if (hasSearch) {
        searchInput.focus();
      } else {
        list.focus();
        const selIndex = items.findIndex((it) => it.opt.selected);
        setActive(selIndex >= 0 ? selIndex : 0);
      }
    }

    function close() {
      if (!open) return;
      open = false;
      panel.hidden = true;
      trigger.setAttribute('aria-expanded', 'false');
      wrap.classList.remove('is-open');
    }

    function moveActive(delta) {
      const visible = items.map((it, i) => i).filter((i) => !items[i].li.hidden && !items[i].disabled);
      if (!visible.length) return;
      let pos = visible.indexOf(activeIndex);
      pos = pos === -1 ? 0 : (pos + delta + visible.length) % visible.length;
      setActive(visible[pos]);
    }

    trigger.addEventListener('click', () => (open ? close() : openPanel()));
    trigger.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openPanel();
      }
    });

    list.addEventListener('click', (e) => {
      const li = e.target.closest('.ssel__opt');
      if (!li) return;
      choose(parseInt(li.dataset.index, 10));
    });

    searchInput.addEventListener('input', filter);
    searchInput.addEventListener('keydown', onListKeydown);
    list.addEventListener('keydown', onListKeydown);

    function onListKeydown(e) {
      if (e.key === 'ArrowDown') { e.preventDefault(); moveActive(1); }
      else if (e.key === 'ArrowUp') { e.preventDefault(); moveActive(-1); }
      else if (e.key === 'Enter') { e.preventDefault(); if (activeIndex >= 0) choose(activeIndex); }
      else if (e.key === 'Escape') { e.preventDefault(); close(); trigger.focus(); }
      else if (e.key === 'Tab') { close(); }
    }

    document.addEventListener('click', (e) => {
      if (open && !wrap.contains(e.target)) close();
    });

    select.addEventListener('ssel:refresh', buildItems);

    buildItems();
  }

  function scan(root = document) {
    root.querySelectorAll('select').forEach((select) => {
      if (select.multiple || select.disabled || select.dataset.noSearch !== undefined) return;
      if (select.options.length < 2) return;
      enhance(select);
    });
  }

  document.addEventListener('DOMContentLoaded', () => scan());
})();
