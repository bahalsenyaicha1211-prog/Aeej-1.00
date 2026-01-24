document.addEventListener("DOMContentLoaded", () => {
  const btn = document.querySelector("[data-toggle-password]");
  const input = document.getElementById("password");

  if (!btn || !input) return;

  btn.addEventListener("click", () => {
    const isHidden = input.getAttribute("type") === "password";
    input.setAttribute("type", isHidden ? "text" : "password");
    btn.textContent = isHidden ? "Masquer" : "Afficher";
  });
});
