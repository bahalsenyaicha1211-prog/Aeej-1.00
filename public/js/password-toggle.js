document.addEventListener("DOMContentLoaded", () => {
  const EYE = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>';
  const EYE_OFF = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.8 21.8 0 0 1 5.06-6.06M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.8 21.8 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

  document.querySelectorAll('input[type="password"]').forEach((input) => {
    if (input.dataset.pwToggleApplied) return;
    input.dataset.pwToggleApplied = "1";

    const wrapper = document.createElement("div");
    wrapper.style.position = "relative";
    wrapper.style.width = input.style.width || "100%";

    input.parentNode.insertBefore(wrapper, input);
    wrapper.appendChild(input);

    input.style.paddingRight = "42px";
    input.style.boxSizing = "border-box";

    const btn = document.createElement("button");
    btn.type = "button";
    btn.setAttribute("aria-label", "Afficher le mot de passe");
    // width/height/margin figes explicitement : le CSS global du site (ex. "button { width:100% }")
    // s'appliquerait sinon a ce bouton et etirerait l'icone sur toute la largeur du champ.
    btn.style.cssText =
      "position:absolute; right:8px; top:50%; transform:translateY(-50%); width:26px; height:26px; min-width:26px; max-width:26px; margin:0; display:inline-flex; align-items:center; justify-content:center; background:none; border:none; cursor:pointer; padding:0; line-height:0; opacity:0.65; color:inherit;";
    btn.innerHTML = EYE;

    btn.addEventListener("click", () => {
      const hidden = input.type === "password";
      input.type = hidden ? "text" : "password";
      btn.innerHTML = hidden ? EYE_OFF : EYE;
      btn.setAttribute("aria-label", hidden ? "Masquer le mot de passe" : "Afficher le mot de passe");
    });

    wrapper.appendChild(btn);
  });
});
