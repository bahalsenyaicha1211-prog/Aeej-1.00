const cartes = document.querySelectorAll('.cartes li');
const galerie = document.getElementById('galerie');
const boutonPrecedent = document.getElementById('precedent');
const boutonSuivant = document.getElementById('suivant');

let active = Math.floor(cartes.length / 2); // carte centrale
let autoRotateInterval = null;
let isPaused = false;
let mouseX = 0;
let mouseY = 0;
let rotationX = 0;
let rotationY = 0;
let targetRotationX = 0;
let targetRotationY = 0;

// Fonction pour mettre à jour l'affichage des cartes
function afficherCarte() {
  cartes.forEach((carte, i) => {
    const offset = i - active;
    const absOffset = Math.abs(offset);

    // Opacité et z-index
    carte.style.opacity = absOffset > 3 ? 0 : 1 - absOffset * 0.25;
    carte.style.zIndex = 100 - absOffset;

    // Ajouter/supprimer la classe active
    if (offset === 0) {
      carte.classList.add('active');
    } else {
      carte.classList.remove('active');
    }

    // Calcul des transformations de base
    const translateX = offset * 220;
    const translateZ = -Math.min(absOffset * 80, 240);
    const scale = Math.max(1 - absOffset * 0.15, 0.55);
    const rotateY = offset * -18;

    // Ajouter l'effet 3D basé sur la position de la souris (seulement pour la carte centrale)
    let finalRotateX = 0;
    let finalRotateY = rotateY;
    
    if (offset === 0) {
      finalRotateX = targetRotationX;
      finalRotateY = rotateY + targetRotationY;
    }

    carte.style.transform = `translate(-50%, -50%) 
                             translateX(${translateX}px) 
                             translateZ(${translateZ}px) 
                             scale(${scale}) 
                             rotateY(${finalRotateY}deg)
                             rotateX(${finalRotateX}deg)`;
  });
}

// Fonction pour démarrer la rotation automatique
function demarrerRotationAuto() {
  if (autoRotateInterval) {
    clearInterval(autoRotateInterval);
  }
  
  autoRotateInterval = setInterval(() => {
    if (!isPaused) {
      active = (active + 1) % cartes.length;
      afficherCarte();
    }
  }, 3000);
}

// Fonction pour mettre en pause la rotation automatique
function pauseRotationAuto() {
  isPaused = true;
  clearTimeout(window.resumeTimeout);
  window.resumeTimeout = setTimeout(() => {
    isPaused = false;
  }, 5000); // Reprendre après 5 secondes d'inactivité
}

// Navigation manuelle
function carteSuivante() {
  active = (active + 1) % cartes.length;
  afficherCarte();
  pauseRotationAuto();
}

function cartePrecedente() {
  active = (active - 1 + cartes.length) % cartes.length;
  afficherCarte();
  pauseRotationAuto();
}

// Gestion des événements de navigation
boutonPrecedent.addEventListener('click', cartePrecedente);
boutonSuivant.addEventListener('click', carteSuivante);

// Effet 3D réactif à la souris
galerie.addEventListener('mousemove', (e) => {
  const rect = galerie.getBoundingClientRect();
  const centerX = rect.left + rect.width / 2;
  const centerY = rect.top + rect.height / 2;
  
  mouseX = e.clientX - centerX;
  mouseY = e.clientY - centerY;
  
  // Calculer les rotations cibles (limitées pour un effet subtil)
  targetRotationY = (mouseX / rect.width) * 15; // Max 15 degrés
  targetRotationX = -(mouseY / rect.height) * 10; // Max 10 degrés
  
  afficherCarte();
  pauseRotationAuto();
});

// Réinitialiser la rotation quand la souris quitte la galerie
galerie.addEventListener('mouseleave', () => {
  targetRotationX = 0;
  targetRotationY = 0;
  afficherCarte();
});

// Navigation au clavier
document.addEventListener('keydown', (e) => {
  if (e.key === 'ArrowLeft') {
    cartePrecedente();
  } else if (e.key === 'ArrowRight') {
    carteSuivante();
  }
});

// Gestion du redimensionnement
let resizeTimeout;
window.addEventListener('resize', () => {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(() => {
    afficherCarte();
  }, 250);
});

// Clic sur une carte pour la centrer
cartes.forEach((carte, index) => {
  carte.addEventListener('click', () => {
    if (index !== active) {
      active = index;
      afficherCarte();
      pauseRotationAuto();
    }
  });
});

// Affichage initial
afficherCarte();

// Démarrer la rotation automatique
demarrerRotationAuto();

// Mise à jour de l'année dans le pied de page
const spanAnnee = document.getElementById('annee-actuelle');
if (spanAnnee) {
  spanAnnee.textContent = new Date().getFullYear();
}
