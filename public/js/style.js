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
