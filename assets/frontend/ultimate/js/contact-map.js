// Adresse à géocoder
const address = 'Umm Hurair 2';

let map = L.map('map').setView([0, 0], 2); // Vue globale en attendant les coordonnées

// Charger les tuiles OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Fonction asynchrone pour obtenir les coordonnées de l'adresse et afficher le marqueur
async function initMap() {
    try {
        // Utiliser le proxy PHP pour éviter le problème CORS
        const url = `${base_url}assets/frontend/ultimate/js/proxy.php?address=${encodeURIComponent(address)}`;
       

        const response = await fetch(url);
        const data = await response.json();

        if (data.length > 0) {
            const latitude = data[0].lat;
            const longitude = data[0].lon;

            // Centrer la carte sur les coordonnées obtenues
            map.setView([latitude, longitude], 15);

            // Ajouter un marqueur à l'adresse avec un pop-up
            L.marker([latitude, longitude]).addTo(map)
                .bindPopup(address)
                .openPopup();
        } else {
            console.error("Adresse introuvable.");
            document.getElementById("map").innerHTML = "<p>Adresse introuvable. Veuillez vérifier l'adresse.</p>";
        }
    } catch (error) {
        console.error("Erreur de géocodage :", error);
    }
}

// Initialiser la carte lorsque le DOM est chargé
document.addEventListener("DOMContentLoaded", initMap);
