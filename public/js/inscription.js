document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form.grid");
  if (!form) return;

  const matricule = document.getElementById("matricule");
  const telephone = document.getElementById("telephone");
  const submitBtn = document.getElementById("btn-submit");

  // Matricule -> uppercase + sans espaces
  if (matricule) {
    matricule.addEventListener("input", () => {
      matricule.value = matricule.value.replace(/\s+/g, "").toUpperCase();
    });
  }

  // Téléphone -> garder + et chiffres (simple)
  if (telephone) {
    telephone.addEventListener("input", () => {
      let v = telephone.value;
      v = v.replace(/[^\d+]/g, "");         // supprime tout sauf chiffres et +
      v = v.replace(/\+(?=.)/g, (m, idx) => (idx === 0 ? "+" : "")); // un seul + au début
      telephone.value = v;
    });
  }

  // Petite validation front + anti double-submit
  form.addEventListener("submit", (e) => {
    // Laisse Laravel gérer la validation, mais on bloque si champs required vides.
    if (!form.checkValidity()) {
      e.preventDefault();
      form.reportValidity();
      return;
    }

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.style.opacity = "0.75";
      submitBtn.querySelector("span") && (submitBtn.querySelector("span").textContent = "Création en cours...");
    }
  });
});
