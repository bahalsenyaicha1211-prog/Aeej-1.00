/**
 * Import d'images en masse : le navigateur envoie chaque fichier directement à
 * Cloudinary (en parallèle), puis le formulaire est soumis avec seulement les
 * URLs obtenues. Évite de faire transiter des dizaines de fichiers par PHP
 * (limite max_file_uploads, timeout, envoi séquentiel).
 *
 * Activation : <form data-bulk-upload
 *                    data-cloud-name="..." data-upload-preset="..." data-folder="...">
 *                ... <input type="file" name="images[]" multiple> ...
 *              </form>
 *
 * Sans JS (ou en cas d'échec réseau total), le <input type="file"> normal reste
 * en place : l'ancien traitement serveur via `images[]` continue de fonctionner.
 */
(() => {
  const CONCURRENCY = 5;

  document.querySelectorAll('form[data-bulk-upload]').forEach((form) => {
    const fileInput = form.querySelector('input[type="file"]');
    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
    if (!fileInput) return;

    const cloudName = form.dataset.cloudName;
    const preset = form.dataset.uploadPreset;
    const folder = form.dataset.folder || '';
    if (!cloudName || !preset) return; // config absente → on laisse l'envoi serveur

    // Zone de progression
    const progress = document.createElement('div');
    progress.className = 'bulk-progress';
    progress.hidden = true;
    progress.innerHTML =
      '<div class="bulk-progress__bar"><span></span></div><div class="bulk-progress__text"></div>';
    fileInput.insertAdjacentElement('afterend', progress);
    const bar = progress.querySelector('.bulk-progress__bar span');
    const text = progress.querySelector('.bulk-progress__text');

    let uploading = false;

    form.addEventListener('submit', (e) => {
      if (uploading) { e.preventDefault(); return; }

      const files = Array.from(fileInput.files || []);
      if (files.length === 0) return; // rien à envoyer côté client → laisse le serveur gérer

      e.preventDefault();
      uploading = true;
      if (submitBtn) { submitBtn.disabled = true; }
      progress.hidden = false;

      let done = 0;
      const urls = [];
      const failed = [];
      const setProgress = () => {
        const pct = Math.round((done / files.length) * 100);
        bar.style.width = pct + '%';
        text.textContent = `Envoi ${done} / ${files.length}` + (failed.length ? ` — ${failed.length} échec(s)` : '');
      };
      setProgress();

      const uploadOne = async (file, attempt = 1) => {
        const fd = new FormData();
        fd.append('file', file);
        fd.append('upload_preset', preset);
        if (folder) fd.append('folder', folder);
        try {
          const res = await fetch(`https://api.cloudinary.com/v1_1/${cloudName}/image/upload`, {
            method: 'POST',
            body: fd,
          });
          if (!res.ok) throw new Error('HTTP ' + res.status);
          const data = await res.json();
          if (!data.secure_url) throw new Error('réponse sans secure_url');
          urls.push(data.secure_url);
        } catch (err) {
          if (attempt < 2) return uploadOne(file, attempt + 1);
          failed.push(file.name);
        } finally {
          done++;
          setProgress();
        }
      };

      // Pool de CONCURRENCY uploads en parallèle
      const queue = files.slice();
      const worker = async () => {
        while (queue.length) {
          await uploadOne(queue.shift());
        }
      };

      Promise.all(Array.from({ length: Math.min(CONCURRENCY, files.length) }, worker)).then(() => {
        if (urls.length === 0) {
          uploading = false;
          if (submitBtn) submitBtn.disabled = false;
          text.textContent = 'Aucune image n’a pu être envoyée. Vérifiez votre connexion et réessayez.';
          return;
        }

        if (failed.length) {
          text.textContent = `${urls.length} image(s) prête(s). ${failed.length} échec(s) ignoré(s).`;
        }

        // On retire les fichiers du champ pour ne pas les renvoyer au serveur,
        // et on injecte les URLs obtenues.
        fileInput.disabled = true;
        urls.forEach((url) => {
          const hidden = document.createElement('input');
          hidden.type = 'hidden';
          hidden.name = 'image_urls[]';
          hidden.value = url;
          form.appendChild(hidden);
        });

        uploading = false;
        form.submit();
      });
    });
  });
})();
