const boutonMenu = document.getElementById("bouton-menu");
const menuNavigation = document.getElementById("menu-navigation");
const enTete = document.querySelector("header");

boutonMenu.addEventListener("click", () => {
  boutonMenu.classList.toggle("actif");
  menuNavigation.classList.toggle("actif");
});

window.addEventListener("scroll", () => {
  if (window.scrollY > 50) {
    enTete.classList.add("defile");
  } else {
    enTete.classList.remove("defile");
  }
});

/* Réseau social pas encore actif (ex. LinkedIn, en attendant la création du
   compte) : au lieu d'un lien mort, un petit message temporaire au clic. */
document.querySelectorAll("[data-coming-soon]").forEach((lien) => {
  lien.addEventListener("click", (e) => {
    e.preventDefault();

    const nom = lien.dataset.comingSoon;
    const toast = document.createElement("div");
    toast.textContent = `Notre compte ${nom} arrive bientôt !`;
    toast.style.cssText =
      "position:fixed;left:50%;bottom:30px;transform:translateX(-50%);" +
      "background:#111827;color:#fff;padding:12px 22px;border-radius:999px;" +
      "font-size:14px;font-weight:700;box-shadow:0 10px 30px rgba(0,0,0,.35);" +
      "z-index:9999;opacity:0;transition:opacity .25s ease;";
    document.body.appendChild(toast);

    requestAnimationFrame(() => { toast.style.opacity = "1"; });

    setTimeout(() => {
      toast.style.opacity = "0";
      setTimeout(() => toast.remove(), 300);
    }, 2200);
  });
});
