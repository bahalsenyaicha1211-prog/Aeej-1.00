/**
 * Zone d'upload d'image moderne (« cliquer ou glisser »).
 *
 * <div class="imgup" data-imgup data-cloud-name data-upload-preset data-folder
 *      data-name="logo" [data-multiple]>
 *   <input type="hidden" name="logo_url">        (simple uniquement)
 *   <div class="imgup__zone">…</div>
 *   <input class="imgup__file" type="file" name="logo" hidden>   (fallback serveur)
 *   <button class="imgup__change">…</button>
 * </div>
 *
 * Le fichier part directement vers Cloudinary ; on ne renvoie que l'URL.
 * En cas d'échec, on rebascule sur l'<input type="file"> (envoi serveur).
 */
(() => {
  const CONCURRENCY = 5;
  const MAX_BYTES = 4 * 1024 * 1024;
  const OK_TYPES = ['image/png', 'image/jpeg', 'image/webp'];

  document.querySelectorAll('[data-imgup]').forEach((root) => {
    const zone = root.querySelector('.imgup__zone');
    const fileInput = root.querySelector('.imgup__file');
    const preview = root.querySelector('.imgup__preview');
    const empty = root.querySelector('.imgup__empty');
    const bar = root.querySelector('.imgup__bar');
    const barSpan = root.querySelector('.imgup__bar span');
    const status = root.querySelector('.imgup__status');
    const changeBtn = root.querySelector('.imgup__change');
    if (!zone || !fileInput) return;

    const cloud = root.dataset.cloudName;
    const preset = root.dataset.uploadPreset;
    const folder = root.dataset.folder || '';
    const name = root.dataset.name || 'image';
    const multiple = root.hasAttribute('data-multiple');
    const hidden = multiple ? null : root.querySelector('input[type="hidden"][name="' + name + '_url"]');
    const form = root.closest('form');

    let busy = false;

    const say = (msg, err) => {
      status.hidden = !msg;
      status.textContent = msg || '';
      status.style.color = err ? '#fb7185' : '#94a3b8';
    };
    const setBar = (pct) => {
      bar.hidden = pct >= 100 || pct <= 0;
      barSpan.style.width = pct + '%';
    };
    const showPreview = (src) => {
      if (multiple || !preview) return;
      preview.src = src;
      preview.hidden = false;
      if (empty) empty.hidden = true;
      if (changeBtn) changeBtn.hidden = false;
    };

    // --- ouverture du sélecteur ---
    zone.addEventListener('click', () => { if (!busy) fileInput.click(); });
    zone.addEventListener('keydown', (e) => {
      if ((e.key === 'Enter' || e.key === ' ') && !busy) { e.preventDefault(); fileInput.click(); }
    });
    if (changeBtn) changeBtn.addEventListener('click', () => { if (!busy) fileInput.click(); });

    // --- glisser-déposer ---
    ['dragenter', 'dragover'].forEach((ev) =>
      zone.addEventListener(ev, (e) => { e.preventDefault(); root.classList.add('is-dragover'); }));
    ['dragleave', 'dragend', 'drop'].forEach((ev) =>
      zone.addEventListener(ev, () => root.classList.remove('is-dragover')));
    zone.addEventListener('drop', (e) => {
      e.preventDefault();
      if (busy) return;
      handle(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', () => handle(fileInput.files));

    function keepForFallback(files) {
      // remet les fichiers dans l'input pour que l'envoi serveur fonctionne
      try {
        const dt = new DataTransfer();
        files.forEach((f) => dt.items.add(f));
        fileInput.files = dt.files;
        fileInput.disabled = false;
      } catch (_) { /* navigateurs anciens : tant pis */ }
    }

    async function uploadOne(file, attempt = 1) {
      const fd = new FormData();
      fd.append('file', file);
      fd.append('upload_preset', preset);
      if (folder) fd.append('folder', folder);
      try {
        const res = await fetch(`https://api.cloudinary.com/v1_1/${cloud}/image/upload`, { method: 'POST', body: fd });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        if (!data.secure_url) throw new Error('no secure_url');
        return data.secure_url;
      } catch (err) {
        if (attempt < 2) return uploadOne(file, attempt + 1);
        return null;
      }
    }

    async function handle(fileList) {
      let files = Array.from(fileList || []).filter((f) => {
        if (!OK_TYPES.includes(f.type)) return false;
        if (f.size > MAX_BYTES) { say(`« ${f.name} » dépasse 4 Mo.`, true); return false; }
        return true;
      });
      if (!multiple) files = files.slice(0, 1);
      if (files.length === 0) return;

      if (!cloud || !preset) { keepForFallback(files); say('Prêt à envoyer.'); if (files[0]) showPreview(URL.createObjectURL(files[0])); return; }

      busy = true;
      root.classList.add('is-busy');
      if (files[0]) showPreview(URL.createObjectURL(files[0]));
      say(multiple ? `Envoi de ${files.length} image(s)…` : 'Envoi…');
      setBar(8);

      const urls = [];
      let done = 0;
      const queue = files.slice();
      const worker = async () => {
        while (queue.length) {
          const f = queue.shift();
          const url = await uploadOne(f);
          if (url) urls.push(url);
          done++;
          setBar(Math.round((done / files.length) * 100));
        }
      };
      await Promise.all(Array.from({ length: Math.min(CONCURRENCY, files.length) }, worker));

      busy = false;
      root.classList.remove('is-busy');
      setBar(0);

      if (urls.length === 0) {
        say('Envoi impossible. Réessayez ou enregistrez : l’image partira avec le formulaire.', true);
        keepForFallback(files);
        return;
      }

      // Succès : on renseigne l'URL et on neutralise l'input fichier.
      fileInput.value = '';
      fileInput.disabled = true;
      fileInput.required = false;

      if (multiple) {
        root.querySelectorAll('input[name="image_urls[]"]').forEach((n) => n.remove());
        urls.forEach((url) => {
          const inp = document.createElement('input');
          inp.type = 'hidden'; inp.name = 'image_urls[]'; inp.value = url;
          root.appendChild(inp);
        });
        say(`${urls.length} image(s) prête(s).`);
      } else {
        if (hidden) hidden.value = urls[0];
        showPreview(urls[0]);
        say('Image prête.');
      }
    }

    // Sécurité : empêcher l'envoi du formulaire pendant un upload en cours.
    if (form) {
      form.addEventListener('submit', (e) => {
        if (busy) { e.preventDefault(); say('Patientez, envoi en cours…'); }
      });
    }
  });
})();
