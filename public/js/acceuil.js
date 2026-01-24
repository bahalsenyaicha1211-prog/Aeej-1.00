

const LARAVEL_BACKEND_URL = 'http://localhost'; // 
// Tableau des textes à afficher
let i = 0;
const diapo = document.getElementById('texte-sur-image');

const textes = [
  "Bienvenue sur l'AEEJ",
  "Association des étudiants étrangers à Jendouba",
  "Votre passerelle vers une expérience étudiante enrichissante à Jendouba"
];

function afficherTexte() {
  diapo.textContent = textes[i];
  diapo.classList.remove('slide-in'); // retire l'animation précédente
  void diapo.offsetWidth; // astuce pour forcer le redémarrage de l’animation
  diapo.classList.add('slide-in'); // relance l’animation
  i = (i + 1) % textes.length;
}

afficherTexte();
setInterval(afficherTexte, 4000);


// --- Gestion du slider d'images ---
const slides = document.querySelectorAll('.slide');
let index = 0;

function changeSlide() {
  slides[index].classList.remove('active');
  index = (index + 1) % slides.length;
  slides[index].classList.add('active');
}

// Démarrage des animations
afficherTexte();
setInterval(afficherTexte, 4000);
setInterval(changeSlide, 4000);

// ============================================
// ANIMATION DES COMPTEURS STATISTIQUES
// ============================================

/**
 * Fonction pour animer les compteurs numériques
 * Les nombres s'incrémentent progressivement jusqu'à leur valeur cible
 */
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16); // 60 FPS
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            // Formater le nombre (pas de décimales pour les entiers)
            element.textContent = Math.floor(start);
        }
    }, 16);
}

/**
 * Observer pour déclencher l'animation quand la section devient visible
 * Utilise l'Intersection Observer API pour une meilleure performance
 */
function initStatsAnimation() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    // Options pour l'Intersection Observer
    const observerOptions = {
        threshold: 0.3, // Déclenche quand 30% de l'élément est visible
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumber = entry.target;
                const target = parseInt(statNumber.getAttribute('data-target'));
                
                // Vérifier si l'animation n'a pas déjà été déclenchée
                if (!statNumber.classList.contains('animated')) {
                    statNumber.classList.add('animated');
                    animateCounter(statNumber, target);
                }
                
                // Arrêter d'observer cet élément après l'animation
                observer.unobserve(statNumber);
            }
        });
    }, observerOptions);

    // Observer chaque compteur
    statNumbers.forEach(stat => {
        observer.observe(stat);
    });
}

// Initialiser l'animation des statistiques quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    initStatsAnimation();
});

// Réinitialiser les compteurs si nécessaire (pour le rafraîchissement de page)
window.addEventListener('load', () => {
    // Petite pause pour s'assurer que tout est chargé
    setTimeout(initStatsAnimation, 100);
});
