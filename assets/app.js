let date = new Date();
let mois = date.getMonth();
let annee = date.getFullYear();
let infoContainer = document.getElementById("info-container")
let dernierJourClique = null; // Variable pour stocker le dernier jour cliqué

const jour = document.querySelector(".calendrier-dates");
const jourJ = document.querySelector(".date-actuelle-calendrier");
const prenexIcons = document.querySelectorAll(".calendrier-nav span");

// Tableau des noms des mois 
const lesMois = [
  "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre"
];

// Fonction pour générer le calendrier
const mainpulation = () => {
  // Récupérer le premier jour du mois
  let jourUn = new Date(annee, mois, 1).getDay();
  jourUn = (jourUn === 0) ? 6 : jourUn - 1;
  // Récupérer la derniere date du mois
  let derniereDate = new Date(annee, mois + 1, 0).getDate();
  let dernierJour = new Date(annee, mois, derniereDate).getDay();
  let derniereDateDuMois = new Date(annee, mois, 0).getDate();
  let lit = "";

  // Boucles pour ajouter la dernière date du mois précédent
  for (let i = jourUn; i > 0; i--) {
    lit += `<li class="inactive">${derniereDateDuMois - i + 1}</li>`;
  }

  // Boucle pour ajouter les dates du mois actuel
  for (let i = 1; i <= derniereDate; i++) {
    // Vérifier si la date actuelle est correcte
    let ajd = i === date.getDate() && mois === new Date().getMonth() && annee === new Date().getFullYear() ? "active" : "";
    // Ajouter les jours au contenu HTML de l'élément jour
    lit += `<li class="day ${ajd}">${i}</li>`;
  }

  // Mettre à jour le HTML de l'élément jour avec les jours du mois actuel
  jour.innerHTML = lit;

  // Boucle pour ajouter les premieres dates des prochains mois
  for (let i = dernierJour; i > 6; i--) {
    lit += `<li class="inactive">i${i - dernierJour + 1} </li>`;
  }

  // Mettre à jour le texte de l'élément de la date actuelle 
  // avec le format mois année
  jourJ.innerText = `${lesMois[mois]} ${annee}`;

  // Sélectionner tous les éléments de jour du calendrier
  const joursCalendrier = document.querySelectorAll('.day');

  // Ajouter un gestionnaire d'événements pour le survol
  joursCalendrier.forEach(jour => {
    jour.addEventListener('mouseenter', () => {
      if (jour !== dernierJourClique) {
        jour.style.backgroundColor = 'lightgray';
      }
    });
  });

  // Ajouter un gestionnaire d'événements pour la sortie du survol
  joursCalendrier.forEach(jour => {
    jour.addEventListener('mouseleave', () => {
      if (jour !== dernierJourClique) {
        jour.style.backgroundColor = '';
      }
    });
  });

  // Ajouter un gestionnaire d'événements pour le clic
  joursCalendrier.forEach(jour => {
    jour.addEventListener('click', () => {
      // Changer la couleur de fond au clic
      jour.style.backgroundColor = 'lightblue';

      // Mettre à jour le dernier jour cliqué
      if (dernierJourClique && dernierJourClique !== jour) {
        dernierJourClique.style.backgroundColor = ''; // Réinitialiser la couleur du dernier jour cliqué
      }
      dernierJourClique = jour; // Mettre à jour le dernier jour cliqué

      // Récupérer l'année et le mois associés à cette case
      const clickedYear = annee;
      const clickedMonth = mois;

      // Appeler la fonction pour récupérer les absences pour cette case spécifique
      getAbsencesForMonth(clickedYear, clickedMonth);
    });
  });
};

mainpulation();

// Ajouter un click event pour chaque icone
prenexIcons.forEach(icon => {
  icon.addEventListener("click", () => {
    mois = icon.id === "calendrier-prev" ? mois - 1 : mois + 1;
    if (mois < 0) {
      mois = 11;
      annee--;
    } else if (mois > 11) {
      mois = 0;
      annee++;
    }
    date = new Date(annee, mois, date.getDate());
    mainpulation();
  });
});

// Récupérer les absences pour un mois spécifique
function getAbsencesForMonth(year, month) {
  // Construire la date au format 'YYYY-MM-DD' pour le premier jour du mois
  const firstDayOfMonth = new Date(year, month, 1).toISOString().split('T')[0];

  // Envoyer une requête AJAX à l'action 'get_absences' dans votre contrôleur Symfony
  // Assurez-vous d'adapter l'URL de la requête à votre environnement
  fetch(`/get_absences?date=${firstDayOfMonth}`)
    .then(response => response.json())
    .then(data => {
      // Manipuler les données des absences récupérées
      console.log(data); // Vous pouvez traiter les données ici et les afficher sur le calendrier
    })
    .catch(error => console.error('Error fetching absences:', error));
}
