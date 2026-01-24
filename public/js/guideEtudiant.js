
    // Fonction pour afficher le contenu correspondant
    function showContent(sectionId) {
    // Masquer tous les contenus
    const contents = document.querySelectorAll('.content');
    contents.forEach(content => {
        content.style.display = 'none';
    });

    // Afficher uniquement la section demandée
    const activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.style.display = 'block';
        // Défilement fluide jusqu’à la section affichée
        activeSection.scrollIntoView({ behavior: 'smooth' });
    }
    }

    // Par défaut : masquer tout sauf la première section
    window.addEventListener('DOMContentLoaded', () => {
    const allSections = document.querySelectorAll('.content');
    allSections.forEach((sec, index) => {
        sec.style.display = index === 0 ? 'block' : 'none';
    });
    });
