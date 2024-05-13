let date = new Date();
let mois = date.getMonth();
let annee = date.getFullYear();
let infoContainer = document.getElementById("info-container");
let dernierJourClique = null;
let nombreAbsences = 0;
let nombreConges = 0;
let nombreHeuresSup = 0;
const jour = document.querySelector(".calendrier-dates");
const prenexIcons = document.querySelectorAll(".calendrier-nav span");
const joursCalendrier = document.querySelectorAll('.day'); 
const jourJ = document.querySelector(".date-actuelle-calendrier");
// Tableau des noms des mois
const lesMois = [
"Janvier",
"Février",
"Mars",
"Avril",
"Mai",
"Juin",
"Juillet",
"Aout",
"Septembre",
"Octobre",
"Novembre",
"Décembre"
];
function getHeuresSupByMonthAndYear(month, year) {
let totalHeuresSup = 0;
heuresSupData.forEach(heureSup => {
    const date = new Date(heureSup.date);
    if (date.getMonth() === month && date.getFullYear() === year) {
        if (heureSup.nbHeures !== null && heureSup.nbHeures !== undefined && heureSup.nbHeures !== '') {
            const [heures, minutes] = heureSup.nbHeures.split(':').map(Number);
                if (!isNaN(heures) && !isNaN(minutes)) {
                const dureeHeuresSup = heures + minutes / 60;
                totalHeuresSup += dureeHeuresSup;
            }
        }
    }
});
// Convertir totalHeuresSup en chaîne de caractères au format HH:MM
const heures = Math.floor(totalHeuresSup);
const minutes = Math.round((totalHeuresSup - heures) * 60);
return `${heures.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
}
// Fonction pour générer le calendrier
const manipulation = () => { 
// Récupérer le premier jour du mois
let jourUn = new Date(annee, mois, 1).getDay();
jourUn = jourUn === 0 ? 6 : jourUn - 1;
// Récupérer la derniere date du mois
let derniereDate = new Date(annee, mois + 1, 0).getDate();
let dernierJour = new Date(annee, mois, derniereDate).getDay();
let derniereDateDuMois = new Date(annee, mois, 0).getDate();
let lit = "";
//-----INSTAURER NOTIFS SUR CHAQUES JOURS DE LES ABSENCES DANS LE CALNDRIER-----
// Créer un objet pour stocker les informations sur les jours d'absence
const joursAbsence = {};
// Remplir l'objet avec les jours d'absence
absencesData.forEach(absence => {
    const dateDebut = new Date(absence.dateDebutAt);
    const dateFin = new Date(absence.dateFinAt);
    //Boucler sur chaque jour d'absence et stocker l'information
    for (let currentDate = dateDebut; currentDate <= dateFin; currentDate.setDate(currentDate.getDate() + 1)) {
        const key = `${currentDate.getFullYear()}-${(currentDate.getMonth() + 1).toString().padStart(2, '0')}-${currentDate.getDate().toString().padStart(2, '0')}`;
        joursAbsence[key] = true;
    }
});
//-------------------CONGE-----------
// Créer un objet pour stocker les informations sur les jours de congé
const joursConge = {};
// Remplir l'objet avec les jours de congé
congeData.forEach(conge => {
    // console.log("Conge.UserID:", conge.userId);
    const dateDebut = new Date(conge.dateDebutAt);
    const dateFin = new Date(conge.dateFinAt);
    // Boucler sur chaque jour de congé et stocker l'information
    for (let currentDate = dateDebut; currentDate <= dateFin; currentDate.setDate(currentDate.getDate() + 1)) {
        const key = `${currentDate.getFullYear()}-${(currentDate.getMonth() + 1).toString().padStart(2, '0')}-${currentDate.getDate().toString().padStart(2, '0')}`;
        joursConge[key] = true;
    }
});
//----------HEURES SUPP.-----------
// Créer un objet pour stocker les informations sur les heures Supp.
const joursHeuresSup = {};
heuresSupData.forEach((heuresSup, i) => { // Ajouter l'index i ici
    const date = new Date(heuresSup.date);
    const key = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
    joursHeuresSup[key] = joursHeuresSup[key] || [];
    joursHeuresSup[key].push(heuresSup.nbHeures);
});
// Boucler sur chaque jour du mois
for (let i = 1; i <= derniereDate + jourUn; i++) {
    let jourCourant = i - jourUn;
    const key = `${annee}-${(mois + 1).toString().padStart(2, '0')}-${jourCourant.toString().padStart(2, '0')}`;
    if (joursHeuresSup.hasOwnProperty(key)) {
        joursHeuresSup[key].forEach((heuresSup, i) => {
            if (typeof heuresSup === 'string') {
                const [heures, minutes] = heuresSup.split(':').map(Number);
                nombreHeuresSup += heures + minutes / 60;
            } else {
                nombreHeuresSup += heuresSup;
            }
        });
    }
}
// Convertir nombreHeuresSup en chaîne de caractères au format HH:MM
const heures = Math.floor(nombreHeuresSup);
const minutes = Math.round((nombreHeuresSup - heures) * 60);
const heuresSupFormatees = `${heures.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
heuresSupData.forEach((heuresSup, i) => { // Ajouter l'index i ici
    const dateDebut = new Date(heuresSup.date);
    const heureDepartNormale = new Date(dateDebut.getFullYear(), dateDebut.getMonth(), dateDebut.getDate(), 17, 0, 0); // Définir l'heure de début des heures supplémentaires à 17:00
    // Vérifier si heuresSup.duree est défini
    if (heuresSup.duree) {
        // Créer un nouvel objet Date représentant la fin des heures normales (heure de départ réelle)
        const heureFinNormale = new Date(dateDebut.getFullYear(), dateDebut.getMonth(), dateDebut.getDate(), ...heuresSup.duree.split(':').map(Number));
        // Calculer la durée des heures supplémentaires en millisecondes
        const dureeHeuresSup = heureFinNormale - heureDepartNormale;
        // Stockez la durée en millisecondes pour la date spécifiée dans heuresSup.date
        const key = `${dateDebut.getFullYear()}-${(dateDebut.getMonth() + 1).toString().padStart(2, '0')}-${dateDebut.getDate().toString().padStart(2, '0')}`;
        joursHeuresSup[key].push(dureeHeuresSup);
    }
});
//----------------------------------
// Réinitialiser les compteurs à zéro à chaque nouvelle génération du calendrier
nombreAbsences = 0;
nombreConges = 0;
nombreHeuresSup = 0;
for (let i = 1; i <= derniereDate + jourUn; i++) {
let jourCourant = i - jourUn;
let ajd = jourCourant === date.getDate() && mois === new Date().getMonth() && annee === new Date().getFullYear() ? "active" : "";
let estJourAbsence = joursAbsence[`${annee}-${(mois + 1).toString().padStart(2, '0')}-${jourCourant.toString().padStart(2, '0')}`];
let estJourConge = joursConge[`${annee}-${(mois + 1).toString().padStart(2, '0')}-${jourCourant.toString().padStart(2, '0')}`];
let estJourHeuresSup = joursHeuresSup[`${annee}-${(mois + 1).toString().padStart(2, '0')}-${jourCourant.toString().padStart(2, '0')}`];
//------Affichage des notifs dans la case de la date - -------
let classes = `day ${ajd}`;
    if (estJourAbsence) {
        classes += ' with-absences';
        nombreAbsences++;
    }
    if (estJourConge) {
        classes += ' with-conges';
        nombreConges++;
    }
    if (estJourHeuresSup) {
        classes += ' with-heuresSup';
        // Additionner les durées d'heures supplémentaires pour le jour courant
        const dureesHeuresSup = estJourHeuresSup;
        dureesHeuresSup.forEach(duree => {
            if (typeof duree === 'string') { // utiliser "duree" à la place de "heuresSup"
                const [heures, minutes] = duree.split(':').map(Number);
                nombreHeuresSup += heures + minutes / 60;
            } else {
                nombreHeuresSup += duree;
            }
        });
    }
    // Ajouter l'attribut de données (data-semaine) avec le numéro de semaine
    const numeroSemaine = getWeekNumber(new Date(annee, mois, jourCourant));
    lit += `<li class="${classes}" data-semaine="${numeroSemaine}">${jourCourant > 0 ? jourCourant : ""}<div class="absence-bar"></div><div class="conge-bar"></div><div class="heuresSup-bar"></div></li>`;
}
// Mettre à jour les nombres dans le HTML
document.getElementById('nombre-absences').textContent = nombreAbsences;
document.getElementById('nombre-conges').textContent = nombreConges;
document.getElementById('nombre-heuresSup').textContent = getHeuresSupByMonthAndYear(mois, annee);
//---- FIN DU CODE POUR INSTAUREE LES NOTIFS -----
// Mettre à jour le HTML de l'élément jour avec les jours du mois actuel
jour.innerHTML = lit;
function getWeekNumber(date) {
    // Définir le premier jour de la semaine (0 pour dimanche, 1 pour lundi, etc.)
    const firstDayOfWeek = 1; // Lundi
    // Trouver le premier jour de l'année
    const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
    // Calculer le nombre de jours écoulés depuis le premier jour de l'année
    const pastDaysOfYear = (date - firstDayOfYear) / 86400000;
    // Trouver le jour de la semaine du premier jour de l'année
    let firstDayOfYearDay = firstDayOfYear.getDay();
    if (firstDayOfYearDay === 0) {
        firstDayOfYearDay = 7; // Dimanche est 0, donc 7 pour commencer à partir de lundi
    }
    // Calculer le décalage pour le premier jour de la semaine
    const diff = firstDayOfWeek - firstDayOfYearDay;
    // Calculer le numéro de semaine
    return Math.ceil((pastDaysOfYear + diff + 1) / 7);
}
// Fonction pour générer les semaines du mois
function genererSemainesDuMois() {
    const premierJourDuMois = new Date(annee, mois, 1);
    const dernierJourDuMois = new Date(annee, mois + 1, -1);
    const premierJour = premierJourDuMois.getDate() - premierJourDuMois.getDay() + (premierJourDuMois.getDay() === 0 ? -6 : 1);
    const dernierJour = dernierJourDuMois.getDate() - dernierJourDuMois.getDay() + 7;
    const semaines = [];
    for (let jour = premierJour; jour <= dernierJour; jour += 7) {
        const semaine = [];
        for (let i = 0; i < 7; i++) {
            const date = new Date(annee, mois, jour + i);
            semaine.push(date);
        }
        semaines.push(semaine);
    }
    return semaines;
}
// Fonction pour générer les numéros de semaine
function genererNumerosSemaine() {
    const semaines = genererSemainesDuMois();
    return semaines.map((semaine, index) => {
        const numeroSemaine = getWeekNumber(semaine[0]);
        return `<li class="numero-semaine" data-semaine="${numeroSemaine}">${numeroSemaine}</li>`;
    });
}
function reinitialiserSemaineSelectionnee() {
    const semainePrecedente = document.querySelector('.week-highlight');
    if (semainePrecedente) {
        semainePrecedente.classList.remove('week-highlight');
    }
    const joursSemaine = document.querySelectorAll('.day.week-highlight');
    joursSemaine.forEach(jour => {
        jour.classList.remove('week-highlight');
    });
}
// Générer les numéros de semaine et les ajouter à la page
const numeroSemaineContainer = document.querySelector(".numero-semaine-list");
numeroSemaineContainer.innerHTML = genererNumerosSemaine().join('');
// Ajouter un gestionnaire d'événements au conteneur de la liste des numéros de semaine
numeroSemaineContainer.addEventListener('click', (event) => {
    // Vérifier si l'élément cliqué est un élément de numéro de semaine
    if (event.target.classList.contains('numero-semaine')) {
        // Récupérer le numéro de semaine associé à l'élément cliqué
        const numeroSemaine = event.target.dataset.semaine;
        // Sélectionner tous les jours du calendrier qui ont le même numéro de semaine
        const joursCorrespondants = document.querySelectorAll(`.day[data-semaine="${numeroSemaine}"]`);
        // Mettre en surbrillance les jours correspondants dans le calendrier
        joursCorrespondants.forEach(jour => {
            jour.classList.add('week-highlight'); // Ajouter la classe pour mettre en surbrillance
        });       
        // Réinitialiser la semaine sélectionnée
        reinitialiserSemaineSelectionnee();
    }
});
// Ajouter un gestionnaire d'événements pour le clic sur un numéro de semaine
numeroSemaineContainer.addEventListener('click', (event) => {
    const numeroSemaineClique = event.target.dataset.semaine;
    if (numeroSemaineClique) {
        // Supprimer la classe de surbrillance de la semaine précédemment sélectionnée
        const semainePrecedente = document.querySelector('.week-highlight');
        if (semainePrecedente) {
            semainePrecedente.classList.remove('week-highlight');
        }
        // Ajouter la classe de surbrillance à la semaine sélectionnée
        const joursSemaine = document.querySelectorAll(`.day[data-semaine="${numeroSemaineClique}"]`);
        joursSemaine.forEach(jour => {
            jour.classList.add('week-highlight');
        });
    }
});
// Boucle pour ajouter les premieres dates des prochains mois
for (let i = dernierJour; i > 6; i--) {
lit += `<li class="inactive">i${
i - dernierJour + 1
    } </li>`;
}
// Mettre à jour le texte de l'élément de la date actuelle
// avec le format mois année
jourJ.innerText = `${lesMois[mois]
} ${annee}`;
// Sélectionner tous les éléments de jour du calendrier
const joursCalendrier = document.querySelectorAll('.day');
// Ajouter un gestionnaire d'événements pour le survol
joursCalendrier.forEach(jour => {
    jour.addEventListener('mouseenter', () => {
        if (jour !== dernierJourClique && !jour.classList.contains('week-highlight')) {
            jour.style.backgroundColor = 'lightgray';
        }
    });
});
// Ajouter un gestionnaire d'événements pour la sortie du survol
joursCalendrier.forEach(jour => {
    jour.addEventListener('mouseleave', () => {
        if (jour !== dernierJourClique && !jour.classList.contains('week-highlight')) {
            jour.style.backgroundColor = '';
            jour.style.fontSize = 'small';
        }
    });
});
// Ajouter un gestionnaire d'événements pour le clic
joursCalendrier.forEach(jour => {
    jour.addEventListener('click', () => { 
// Changer la couleur de fond au clic
        jour.style.backgroundColor = 'lightblue';
        jour.style.fontSize= 'xx-large';
        jour.style.boxShadow= 'rgba(0, 0, 0, 0.17) 0px -23px 25px 0px inset, rgba(0, 0, 0, 0.15) 0px -36px 30px 0px inset, rgba(0, 0, 0, 0.1) 0px -79px 40px 0px inset, rgba(0, 0, 0, 0.06) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px';
// Mettre à jour le dernier jour cliqué
    if (dernierJourClique) {
        dernierJourClique.style.backgroundColor = '';
        dernierJourClique.style.fontSize = 'inherit';
        dernierJourClique.style.boxShadow = 'none';
}
dernierJourClique = jour;
// Réinitialiser les valeurs du champ de saisie de l'employé et des cases à cocher
const employeeNameInput = document.getElementById('employeeName');
const showAbsencesCheckbox = document.getElementById('show-absences');
const showCongesCheckbox = document.getElementById('show-conges');
const showHeuresSupCheckbox = document.getElementById('show-heures-sup');
if (employeeNameInput && showAbsencesCheckbox && showCongesCheckbox && showHeuresSupCheckbox) {
    employeeNameInput.value = '';
    showAbsencesCheckbox.checked = true;
    showCongesCheckbox.checked = true;
    showHeuresSupCheckbox.checked = true;
}
// Ajouter le texte d'information dans infoContainer
const infoText = `Evénements pour le ${
jour.textContent
}-${
mois + 1
}-${annee}: Votre texte ici`;
const infoParagraph = document.createElement("p");
infoParagraph.textContent = infoText;
infoContainer.appendChild(infoParagraph);
});
});
// Ajouter un gestionnaire d'événements pour le clic sur un jour du calendrier
joursCalendrier.forEach( jour => {
jour.addEventListener('click', () => {
// Récupérer la date cliquée au format jour-mois-année
const jourClique = parseInt(jour.innerText);
const moisClique = mois + 1; // Ajouter 1 car les mois sont indexés à partir de 0
const dateClique = new Date(annee,mois,jourClique)
const formattedDateClique = `${annee}-${moisClique.toString().padStart(2, '0')}-${jourClique.toString().padStart(2, '0')}`;
// Convertir la date cliquée en objet Date JavaScript
const dateCliqueObj = new Date(dateClique);
// Filtrer les absences du jour
const absencesDuJour = absencesData.filter(absence => {
const dateDebut = new Date(absence.dateDebutAt);
const dateFin = new Date(absence.dateFinAt);
const formattedDateDebut = dateDebut.toISOString().split("T")[0];
const formattedDateFin = dateFin.toISOString().split("T")[0];
return formattedDateClique >= formattedDateDebut && formattedDateClique <= formattedDateFin;
});
// Filtrer les congés du jour
const congesDuJour = congeData.filter(conge => {
const dateDebut = new Date(conge.dateDebutAt);
const dateFin = new Date(conge.dateFinAt);
const formattedDateDebut = dateDebut.toISOString().split("T")[0];
const formattedDateFin = dateFin.toISOString().split("T")[0];
return formattedDateClique >= formattedDateDebut && formattedDateClique <= formattedDateFin;
});
// Filtrer les heures supp. pour le jour cliqué
const heuresSupDuJour = heuresSupData.filter(heuresSup => {
const dateH = new Date(heuresSup.date);
return (
dateH.getFullYear() === dateClique.getFullYear() &&
dateH.getMonth() === dateClique.getMonth() &&
dateH.getDate() === dateClique.getDate()
);
});
// Afficher les absences et les congés du jour dans l'élément d'information
afficherEvenementsDuJour(absencesDuJour, congesDuJour, heuresSupDuJour);

showAbsencesCheckbox.addEventListener('change', afficherEvenements);
showCongesCheckbox.addEventListener('change', afficherEvenements);
showHeuresSupCheckbox.addEventListener('change', afficherEvenements);
employeeNameInput.addEventListener('input', afficherEvenementsId);

const filterButton = document.getElementById('filterButton');

filterButton.addEventListener('click', afficherEvenements);

function afficherEvenementsId() {
    const showAbsences = showAbsencesCheckbox.checked;
    const showConges = showCongesCheckbox.checked;
    const showHeuresSup = showHeuresSupCheckbox.checked;
    const employeeName = employeeNameInput.value.trim().toLowerCase();
    const filteredAbsences = showAbsences ? absencesDuJour.filter(absence => absence.employe.nom.toLowerCase().includes(employeeName)) : [];
    const filteredConges = showConges ? congesDuJour.filter(conge => conge.employe.nom.toLowerCase().includes(employeeName)) : [];
    const filteredHeuresSup = showHeuresSup ? heuresSupDuJour.filter(heuresSup => heuresSup.employe.nom.toLowerCase().includes(employeeName)) : [];
    afficherEvenementsDuJour(filteredAbsences, filteredConges, filteredHeuresSup);
    // Mettre à jour les compteurs
    const compteurEvenementsAbsences = document.getElementById('compteur-absences');
    compteurEvenementsAbsences.textContent = filteredAbsences.length;
    const compteurEvenementsConges = document.getElementById('compteur-conges');
    compteurEvenementsConges.textContent = filteredConges.length;
    const compteurEvenementsHeuresSup = document.getElementById('compteur-heuresSup');
    compteurEvenementsHeuresSup.textContent = filteredHeuresSup.length;
}
function afficherEvenements() {
    const showAbsences = showAbsencesCheckbox.checked;
    const showConges = showCongesCheckbox.checked;
    const showHeuresSup = showHeuresSupCheckbox.checked;
    const filteredAbsences = showAbsences ? absencesDuJour.filter(absence => absence) : [];
    const filteredConges = showConges ? congesDuJour.filter(conge => conge) : [];
    const filteredHeuresSup = showHeuresSup ? heuresSupDuJour.filter(heuresSup => heuresSup) : [];
    afficherEvenementsDuJour(filteredAbsences, filteredConges, filteredHeuresSup);
}
//Mise en place des compteurs
// Réinitialiser le nombre total d'événements au clic d'un jour
totalEvenementsClicJourAbsences = 0;
totalEvenementsClicJourConges = 0;
totalEvenementsClicJour = 0;
// Ajouter le nombre d'événements du jour au nombre total
totalEvenementsClicJourAbsences += absencesDuJour.length;
totalEvenementsClicJourConges += congesDuJour.length;
totalEvenementsClicJour += heuresSupDuJour.length;
// Afficher le nombre total d'événements dans le compteur
const compteurEvenementsAbsences = document.getElementById('compteur-absences');
compteurEvenementsAbsences.textContent = totalEvenementsClicJourAbsences;
const compteurEvenementsConges = document.getElementById('compteur-conges');
compteurEvenementsConges.textContent = totalEvenementsClicJourConges;
const compteurEvenementsHeuresSup = document.getElementById('compteur-heuresSup');
compteurEvenementsHeuresSup.textContent = totalEvenementsClicJour;
});
});
function afficherEvenementsDuJour(absences, conges, heuresSup) {
// Effacer le contenu précédent de l'élément d'information
infoContainer.innerHTML = '';
// Afficher les événements filtrés
if (absences.length === 0 && conges.length === 0 && heuresSup.length === 0) {
    // Aucun événement trouvé, afficher un message
    infoContainer.innerHTML = '<h3 class="text-center m-5">Aucun évènement</h3>';
} else {
    let nombreAbsencesTotal = 0;
    let nombreCongesTotal = 0;
    let nombreHeuresSupTotal = 0;

    if (absences.length > 0) {
    let htmlContentAbsences = '<table class="table">';
    htmlContentAbsences += '<thead><tr><th>Employé</th><th>Du</th><th>Au</th><th>Statut</th><th>Motif</th></tr></thead>';
    htmlContentAbsences += '<tbody>';
    htmlContentAbsences += `<h5><span class="absence"> Absences </span></h5>`;
    // Afficher les absences du jour
    absences.forEach(absence => {
        const formattedDateDebut = formatDate(absence.dateDebutAt);
        const formattedDateFin = formatDate(absence.dateFinAt);
        htmlContentAbsences += '<tr>'
        htmlContentAbsences += `<td>${absence.employe.nom}</td>`;
        htmlContentAbsences += `<td>${formattedDateDebut}</td>`;
        htmlContentAbsences += `<td>${formattedDateFin}</td>`;
        htmlContentAbsences += `<td><span class="badge bg-${absence.statut ? 'success' : 'danger'}">${absence.statut ? 'Justifié' : 'Non justifié'}</span></td>`;
        // if (absence.statut === '0') {
        // htmlContentAbsences += `<td><span class="badge bg-success">Justifié</span></td>`;
        // } else {
        // htmlContentAbsences += `<td><span class="badge bg-danger">Non justifié</span></td>`;
        // }
        htmlContentAbsences += `<td>${absence.motif}</td>`;
        htmlContentAbsences += '</tr>';
        nombreAbsencesTotal++; // Incrémenter le nombre total d'absences
    });
    // Ajouter une ligne pour le nombre total d'absences
    htmlContentAbsences += `<tr><td colspan="5" class="text-end"><strong>Nombre total d'absences : </strong>${nombreAbsencesTotal}</td></tr>`;
    htmlContentAbsences += '</tbody>';
    htmlContentAbsences += '</table>';
    infoContainer.innerHTML += htmlContentAbsences;
    }
    if (conges.length > 0) {
    let htmlContentConges = '<table class="table">';
    htmlContentConges += '<thead><tr><th>Employé</th><th>Du</th><th>Au</th><th>Statut</th><th>Type</th></tr></thead>';
    htmlContentConges += '<tbody>';
    htmlContentConges += `<h5><span class="conge"> Congé </span></h5>`;
    // Afficher les congés du jour
    conges.forEach(conge => {
        const formattedDateDebut = formatDate(conge.dateDebutAt);
        const formattedDateFin = formatDate(conge.dateFinAt);
        htmlContentConges += '<tr>'
        htmlContentConges += `<td>${conge.employe.nom}</td>`;
        htmlContentConges += `<td>${formattedDateDebut}</td>`;
        htmlContentConges += `<td>${formattedDateFin}</td>`;
        htmlContentConges += `<td><span class="badge bg-${conge.statut ? 'success' : 'danger'}">${conge.statut ? 'Justifié' : 'Non justifié'}</span></td>`;
        htmlContentConges += `<td>${conge.typeConge}</td>`;
        htmlContentConges += '</tr>';
        nombreCongesTotal++; // Incrémenter le nombre total de congés
    });
    // Ajouter une ligne pour le nombre total de congés
    htmlContentConges += `<tr><td colspan="5" class="text-end"><strong>Nombre total de congés : </strong>${nombreCongesTotal}</td></tr>`;
    htmlContentConges += '</tbody>';
    htmlContentConges += '</table>';
    infoContainer.innerHTML += htmlContentConges;
    }
    if (heuresSup.length > 0) {
    let htmlContentHeuresSup = '<table class="table">';
    htmlContentHeuresSup += '<thead><tr><th>Employé</th><th>Date</th><th>H.depart</th><th>Total d\'heures</th></tr></thead>';
    htmlContentHeuresSup += '<tbody>';
    htmlContentHeuresSup += `<h5><span class="heuresSup"> Heures Supp. </span></h5>`;
    // Afficher les congés du jour
    heuresSup.forEach(heuresSup => {
        const formattedDateH = formatDate(heuresSup.date, true);
        htmlContentHeuresSup += '<tr>'
        htmlContentHeuresSup += `<td>${heuresSup.employe.nom}</td>`;
        htmlContentHeuresSup += `<td>${formattedDateH}</td>`;
        htmlContentHeuresSup += `<td>${heuresSup.heureDepart}</td>`;
        htmlContentHeuresSup += `<td>${heuresSup.nbHeures}</td>`;
        htmlContentHeuresSup += '</tr>';
        // Ajouter le total d'heures supplémentaires
        const [heures, minutes] = heuresSup.nbHeures.split(':').map(Number);
        nombreHeuresSupTotal += heures + minutes / 60;
    });
    // Ajouter une ligne pour le nombre total d'heures supplémentaires
    const heuresTotal = Math.floor(nombreHeuresSupTotal);
    const minutesTotal = Math.round((nombreHeuresSupTotal - heuresTotal) * 60);
    htmlContentHeuresSup += `<tr><td colspan="4" class="text-end"><strong>Total d'heures supplémentaires : </strong>${heuresTotal}h ${minutesTotal}min</td></tr>`;
    htmlContentHeuresSup += '</tbody>';
    htmlContentHeuresSup += '</table>';
    infoContainer.innerHTML += htmlContentHeuresSup;
    }

    function formatDate(dateString, includeTime = false) {
    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear().toString().slice(-2);
    let formattedDate = `${day}/${month}/${year}`;
    return formattedDate;
    }
}
}
const employeeNameInput = document.getElementById("employeeName");
const afficherEvenementsMoisButton = document.getElementById("afficherEvenementsMois");
afficherEvenementsMoisButton.addEventListener("click", () => {
    const employeeName = employeeNameInput.value.trim().toLowerCase();
    afficherEvenementsDuMois(absencesData, congeData, heuresSupData, mois, annee, employeeName);
});
function afficherEvenementsDuMois(absences, conges, heuresSup, moisActuel, anneeActuelle, employeeName) {
const showAbsences = showAbsencesCheckbox.checked;
const showConges = showCongesCheckbox.checked;
const showHeuresSup = showHeuresSupCheckbox.checked;
// Filtrer les absences du mois en fonction de l'employé sélectionné et de l'état de la checkbox
const absencesMoisFiltrées = absences.filter(absence => {
    const dateDebut = new Date(absence.dateDebutAt);
    const dateFin = new Date(absence.dateFinAt);
    return (
    (dateDebut.getMonth() === moisActuel && dateDebut.getFullYear() === anneeActuelle) ||
    (dateFin.getMonth() === moisActuel && dateFin.getFullYear() === anneeActuelle)
    ) && absence.employe.nom.toLowerCase().includes(employeeName) && showAbsences;
});
// Filtrer les congés du mois en fonction de l'employé sélectionné et de l'état de la checkbox
const congesMoisFiltrées = conges.filter(conge => {
    const dateDebut = new Date(conge.dateDebutAt);
    const dateFin = new Date(conge.dateFinAt);
    return (
    (dateDebut.getMonth() === moisActuel && dateDebut.getFullYear() === anneeActuelle) ||
    (dateFin.getMonth() === moisActuel && dateFin.getFullYear() === anneeActuelle)
    ) && conge.employe.nom.toLowerCase().includes(employeeName) && showConges;
});
// Filtrer les heures supplémentaires du mois en fonction de l'employé sélectionné et de l'état de la checkbox
const heuresSupMoisFiltrées = heuresSup.filter(heureSup => {
const dateHeureSup = new Date(heureSup.date);
    return (
    dateHeureSup.getMonth() === moisActuel && dateHeureSup.getFullYear() === anneeActuelle
    ) && heureSup.employe.nom.toLowerCase().includes(employeeName) && showHeuresSup;
});
// Afficher les événements filtrés dans l'info-container
afficherEvenementsDuJour(absencesMoisFiltrées, congesMoisFiltrées, heuresSupMoisFiltrées);
}
numeroSemaineContainer.addEventListener("click", (event) => {
  if (event.target.classList.contains("numero-semaine")) {
    const numeroSemaine = event.target.dataset.semaine;
    afficherEvenementsSemaine(numeroSemaine);
  }
});
function afficherEvenementsSemaine(numeroSemaine) {
    const employeeName = employeeNameInput.value.trim().toLowerCase();
    const showAbsences = showAbsencesCheckbox.checked;
    const showConges = showCongesCheckbox.checked;
    const showHeuresSup = showHeuresSupCheckbox.checked;
    // Calculer la date de début et de fin de la semaine sélectionnée
    const semaines = genererSemainesDuMois();
    const semaineSelectionnee = semaines.find((semaine) => {
      return getWeekNumber(semaine[0]) === parseInt(numeroSemaine);
    });
    const dateDebutSemaine = semaineSelectionnee[0];
    const dateFinSemaine = new Date(semaineSelectionnee[6].getFullYear(), semaineSelectionnee[6].getMonth(), semaineSelectionnee[6].getDate() + 1);  
    // Filtrer les absences de la semaine en fonction de l'employé sélectionné et de l'état de la checkbox
    const absencesSemaine = absencesData.filter((absence) => {
    const dateDebut = new Date(absence.dateDebutAt);
    const dateFin = new Date(absence.dateFinAt);
      return (dateDebut >= dateDebutSemaine && dateFin < dateFinSemaine) && absence.employe.nom.toLowerCase().includes(employeeName) && showAbsences;
    });
  
    // Filtrer les congés de la semaine en fonction de l'employé sélectionné et de l'état de la checkbox
    const congesSemaine = congeData.filter((conge) => {
    const dateDebut = new Date(conge.dateDebutAt);
    const dateFin = new Date(conge.dateFinAt); 
      return (dateDebut >= dateDebutSemaine && dateFin < dateFinSemaine) && conge.employe.nom.toLowerCase().includes(employeeName) && showConges;
    });
  
    // Filtrer les heures supplémentaires de la semaine en fonction de l'employé sélectionné et de l'état de la checkbox
    const heuresSupSemaine = heuresSupData.filter((heureSup) => {
    const date = new Date(heureSup.date); 
      return (date >= dateDebutSemaine && date < dateFinSemaine) && heureSup.employe.nom.toLowerCase().includes(employeeName) && showHeuresSup;
    });
    // Afficher les événements filtrés dans l'info-container
    afficherEvenementsDuJour(absencesSemaine, congesSemaine, heuresSupSemaine);
  }    
};
manipulation();
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
manipulation();
});
});
date = new Date(annee, mois, date.getDate());
// Récupérer l'élément select pour le mois
const moisSelect = document.getElementById("mois-select");
for (let i = 0; i < lesMois.length; i++) {
const option = document.createElement("option");
option.value = i;
option.textContent = lesMois[i];
moisSelect.appendChild(option);
}
// Sélectionner le mois actuel par défaut
moisSelect.value = mois; // Ajouter 1 car les mois sont indexés à partir de 0
// Récupérer l'élément select pour l'année
const anneeSelect = document.getElementById("annee-select");
// Générer dynamiquement les options d'années (de deux années avant l'année actuelle jusqu'à deux années après)
const anneeActuelle = new Date().getFullYear();
const anneeDebut = anneeActuelle - 2;
const anneeFin = anneeActuelle + 10;
for (let annee = anneeDebut; annee <= anneeFin; annee++) {
const option = document.createElement("option");
option.value = annee;
option.textContent = annee;
anneeSelect.appendChild(option);
}
// moisSelect.value = mois + 1;
anneeSelect.value = annee;
// Ajouter un gestionnaire d'événements pour le changement de sélection du mois
moisSelect.addEventListener("change", () => {
mois = parseInt(moisSelect.value);
manipulation();
});
// Ajouter un gestionnaire d'événements pour le changement de sélection de l'année
anneeSelect.addEventListener("change", () => {
annee = parseInt(anneeSelect.value);
manipulation();
});
// Vérifier si l'élément existe avant de définir la variable
if (document.getElementById('employeeName')) {
    const employeeNameInput = document.getElementById('employeeName');
    if (document.getElementById('mois-en-cours-btn')) {
    const moisEnCoursBtn = document.getElementById("mois-en-cours-btn");
        moisEnCoursBtn.addEventListener("click", () => {
        // Réinitialiser les filtres
        employeeNameInput.value = '';
        showAbsencesCheckbox.checked = true;
        showCongesCheckbox.checked = true;
        showHeuresSupCheckbox.checked = true;
        // Réinitialiser l'affichage dans l'info-container
        infoContainer.innerHTML = '';
        // Mettre à jour le mois et l'année
        mois = new Date().getMonth();
        annee = new Date().getFullYear();
        moisSelect.value = mois;
        anneeSelect.value = annee;
        manipulation();
    });
    }
}
function filtrerEvenements() {
    const showAbsences = document.getElementById('show-absences').checked;
    const showConges = document.getElementById('show-conges').checked;
    const showHeuresSup = document.getElementById('show-heures-sup').checked;
    const absencesBars = document.querySelectorAll('.absence-bar');
    const congesBars = document.querySelectorAll('.conge-bar');
    const heuresSupBars = document.querySelectorAll('.heuresSup-bar');
    absencesBars.forEach(bar => {
        if (showAbsences) {
            bar.style.display = 'block';
        } else {
            bar.style.display = 'none';
        }
    });
    congesBars.forEach(bar => {
        if (showConges) {
            bar.style.display = 'block';
        } else {
            bar.style.display = 'none';
        }
    });
    heuresSupBars.forEach(bar => {
        if (showHeuresSup) {
            bar.style.display = 'block';
        } else {
            bar.style.display = 'none';
        }
    })
}
// Ajouter des écouteurs d'événements pour les changements dans les options de filtrage
const showAbsencesCheckbox = document.getElementById('show-absences');
const showCongesCheckbox = document.getElementById('show-conges');
const showHeuresSupCheckbox = document.getElementById('show-heures-sup');
showAbsencesCheckbox.addEventListener('change', filtrerEvenements);
showCongesCheckbox.addEventListener('change', filtrerEvenements);
showHeuresSupCheckbox.addEventListener('change', filtrerEvenements);

// Vérifier si l'élément existe avant d'ajouter l'événement d'écoute
if (document.getElementById('employeeName')) {
// Ajouter un événement pour filtrer les compteurs lorsqu'un nom d'employé est saisi
const employeeNameInput = document.getElementById('employeeName');
employeeNameInput.addEventListener('input', function() {
    // Récupérer le nom de l'employé saisi dans le champ d'entrée
    const employeeName = employeeNameInput.value.trim().toLowerCase();
    // Récupérer le mois et l'année actuels
    const currentDate = new Date();
    const moisEnCours = currentDate.getMonth(); // Mois en cours (de 0 à 11)
    const anneeEnCours = currentDate.getFullYear();
    // Mettre à jour les compteurs avec le nom de l'employé filtré
    mettreAJourCompteursFiltres(absencesData, congeData, heuresSupData, employeeName, moisEnCours, anneeEnCours);
});
}
