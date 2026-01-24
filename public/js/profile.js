document.addEventListener("DOMContentLoaded", () => {

  const input = document.querySelector("[data-photo-input]");
  const preview = document.querySelector("[data-photo-preview]");
  const remove = document.querySelector("[data-remove-photo]");

  if (input && preview) {
    input.addEventListener("change", e => {
      const file = e.target.files[0];
      if (!file) return;
      preview.src = URL.createObjectURL(file);
    });
  }

  if (remove) {
    remove.addEventListener("change", () => {
      if (remove.checked && !confirm("Supprimer la photo actuelle ?")) {
        remove.checked = false;
      }
    });
  }

});
