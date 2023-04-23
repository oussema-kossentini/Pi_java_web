// Variables globales
let ville = distance = ""

window.onload = () => {
    // On initialise la carte et on la centre sur Paris
    let carte = L.map('map').setView([48.852969, 2.349903], 13);
                
    // On charge les "tuiles"
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        // Il est toujours bien de laisser le lien vers la source des données
        attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        minZoom: 1,
        maxZoom: 20,
        name: 'tiles' // permettra de ne pas supprimer cette couche
    }).addTo(carte);
} 
