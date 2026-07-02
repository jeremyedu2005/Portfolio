//code généré par IA 100% par CLAUDE 
//Mais attention certain code d'exemple donné par le professeur ont été utilisés comme le chargement du csv  pour cr�er un graphique en baton,camembert  et un seul que j'ai recherch� sur internet pour le graphique radar tous.
//Tous les graphiques ont �t� adapt� par l'IA avec les codes donn�s par le professeur gr�ce aux url//

/* // --- CHARGEMENT DU CSV ---
    d3.csv("./data/barometre.csv", function(error, data) {
        if (error) {
            console.log("Erreur chargement CSV : " + error);
            return;
        }
*/
// et ce qui permet d'afficher le graphique camembert.

// Attendre que le DOM soit entièrement chargé avant d'exécuter le script
window.addEventListener("DOMContentLoaded", init, false); //code donné par le professeur sur le PDF

// Catégories AFFICHÉES dans la légende (après regroupement)
var categoriesAffichees = [
    "En voiture",                                // Catégorie voiture
    "Transports en commun (train inclus)",       // TC + train regroupés
    "Vélo ou à pied",                            // Mobilités douces
    "Deux-roues motorisé"                        // Scooter, moto
];

// Tableau des couleurs associées à chaque catégorie
var couleursTransport = ['#e41a1c', '#377eb8', '#4daf4a', '#984ea3'];

// NOUVEAU : Couleurs pour l'opinion climatique
var couleursOpinion = ['#2ca02c', '#d62728'];

// === AJOUT POUR LE RADAR CHART ===
const tranchesAge = [
    "15-17 ans",
    "18-24 ans",
    "25-34 ans",
    "35-49 ans",
    "50-64 ans",
    "65 ans et +"
];

// Fonction principale appelée au chargement de la page
function init() {

    // Sélection de l'élément HTML servant d'infobulle
    var tooltip = d3.select("#infobulle");

    // --- CHARGEMENT DU CSV ---
    d3.csv("./data/barometre.csv", function(error, data) {

        // --- AJOUT POUR LE PROFESSEUR : GESTION DE L'ÉCRAN DE CHARGEMENT ---
        d3.select("#chargement-page").style("display", "none"); 

        if (error) {
            console.log("Erreur chargement CSV : " + error);
            return;
        }

        // ✅ CORRECTION : Vérification si le CSV est vide
        if (!data || data.length === 0) {
            console.error("❌ Aucune donnée trouvée dans le CSV !");
            return;
        }

        console.log("CSV Chargé. Nombre de lignes : " + data.length);

        // --- GESTION DE LA CARTE ---
        d3.selectAll(".land")
            .on("mouseover", function(d) {
                tooltip.style("opacity", 1);
                tooltip.html(d3.select(this).attr("title"));
                
                // EFFET DE SURVOLE POUR AFFICHER LES GRAPHIQUES
                var nomSurvole = d3.select(this).attr("title");
                genererGraphiquesPourDepartement(nomSurvole, data);
                
                // Retirer l'état actif pour que le survol soit fluide
                d3.selectAll(".land").classed("actif", false);
            })
            .on("mousemove", function() {
                tooltip
                    .style("left", (d3.event.pageX + 15) + "px")
                    .style("top", (d3.event.pageY - 15) + "px");
            })
            .on("mouseout", function() {
                tooltip.style("opacity", 0);
            })
            .on("click", function() {
                d3.selectAll(".land").classed("actif", false);
                d3.select(this).classed("actif", true);
                var nomDepartement = d3.select(this).attr("title");
                genererGraphiquesPourDepartement(nomDepartement, data);
            });
    });
}

// Fonction principale de génération des graphiques
function genererGraphiquesPourDepartement(dept, allData) {

    // Afficher le panneau contenant les graphiques
    document.getElementById("panneau-graphiques").style.display = "block";

    // Mettre à jour le titre
    d3.select("#titre-departement").text("Corrélation : " + dept);

    // 1. Filtrer les données
    var dataDept = allData.filter(function(d) {
        return d["Département"] === dept;
    });

    if (dataDept.length === 0) { 
        d3.select("#titre-departement").text(dept + " : Pas de données"); 
        d3.select("#chart-opinion").html(""); 
        d3.select("#chart-transport").html(""); 
        d3.select("#chart-age-opinion").html(""); 
        return;
    }

    // 2. COMPTAGE DES DONNÉES
    var compteurTransport = [0, 0, 0, 0]; 
    var compteurOpinion = [0, 0]; 

    dataDept.forEach(function(d) {
        // --- PARTIE TRANSPORT ---
        var transport = d["s22. Mode de transport"]; 
        var indexT = -1;
        
        // ✅ CORRECTION : Parenthèses pour éviter les erreurs de logique avec ||
        if (transport === "En voiture (même si vous n'êtes pas le conducteur)") { 
            indexT = 0; 
        } 
        else if (transport === "En transports en commun urbains (dont métro)" || 
                 transport === "En train") { 
            indexT = 1; 
        }
        else if (transport === "En vélo ou à pied") { 
            indexT = 2; 
        }
        else if (transport === "En deux-roues motorisé") { 
            indexT = 3; 
        }

        if (indexT !== -1) { 
            compteurTransport[indexT]++; 
        }

        // --- PARTIE OPINION CC (q5) ---
        // ✅ CORRECTION : Utilisation de toLowerCase() pour éviter les problèmes de casse
        var opinion = d["q5. Certitude/hypothèse impact effet de serre"];
        if (opinion && opinion.toLowerCase().indexOf("certitude") !== -1) { 
            compteurOpinion[0]++; 
        }
        else if (opinion && opinion.toLowerCase().indexOf("hypothèse") !== -1) { 
            compteurOpinion[1]++; 
        }
    });

    // 3. CALCUL DES TOTAUX
    var totalT = d3.sum(compteurTransport);
    var totalO = d3.sum(compteurOpinion);

    // 4. CALCUL DES POURCENTAGES
    var pourcentagesTransport = calculerPourcentages(compteurTransport, totalT);
    var pourcentagesOpinion = calculerPourcentages(compteurOpinion, totalO);

    console.log("Transport - Compteurs:", compteurTransport, "Pourcentages:", pourcentagesTransport);
    console.log("Opinion - Compteurs:", compteurOpinion, "Pourcentages:", pourcentagesOpinion);

    // 5. DESSIN DES GRAPHIQUES
    dessinerCamembert("#chart-transport", compteurTransport, pourcentagesTransport);
    dessinerBarChart("#chart-opinion", compteurOpinion, pourcentagesOpinion);

    // ────────────────────────────────────────────────────────────────
    //                  RADAR : Répartition des âges
    // ────────────────────────────────────────────────────────────────


    // pour faire un le radar j'ai demandé à l'ia de prendre exemple de ça mais je ne sais plus sur quel url je l'ai pris.
/*

    var options = {
          series: [{
          name: 'Series 1',
          data: [80, 50, 30, 40, 100, 20],
        }, {
          name: 'Series 2',
          data: [20, 30, 40, 80, 20, 80],
        }, {
          name: 'Series 3',
          data: [44, 76, 78, 13, 43, 10],
        }],
          chart: {
          height: 350,
          type: 'radar',
          dropShadow: {
            enabled: true,
            blur: 1,
            left: 1,
            top: 1
          }
        },
        title: {
          text: 'Radar Chart - Multi Series'
        },
        stroke: {
          width: 2
        },
        fill: {
          opacity: 0.1
        },
        markers: {
          size: 0
        },
        yaxis: {
          stepSize: 20
        },
        xaxis: {
          categories: ['2011', '2012', '2013', '2014', '2015', '2016']
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

*/

    d3.select("#chart-age-opinion").html(""); // On vide l'ancien graphique

    // Comptage par tranche d'âge
    let counts = {};
    tranchesAge.forEach(age => {
        counts[age] = 0;
    });

    let totalAge = 0;
    dataDept.forEach(d => {
        let age = d["S2. âge"]?.trim();
        if (age && tranchesAge.includes(age)) {
            counts[age]++;
            totalAge++;
        }
    });

    // Préparation d'une seule série en POURCENTAGE
    let series = [
        {
            name: "Répartition des âges",
            data: tranchesAge.map(age => {
                return totalAge > 0 ? Math.round((counts[age] / totalAge) * 100) : 0;
            })
        }
    ];

    // Configuration du radar ApexCharts (simplifié pour une seule série)
    const options = {
        series: series,
        chart: {
            height: 340,
            type: 'radar',
            dropShadow: { enabled: true, blur: 1, left: 1, top: 1 },
            toolbar: { show: false }
        },
        title: {
            text: `Répartition des âges – ${dept}`,
            align: 'center',
            style: { fontSize: '14px', fontWeight: 'bold' }
        },
        stroke: { width: 2 },
        fill: { opacity: 0.2 },
        markers: { size: 0 },
        yaxis: {
            min: 0,
            max: 100,
            tickAmount: 5,
            labels: { formatter: val => val + "%" }
        },
        xaxis: {
            categories: tranchesAge,
            labels: { style: { fontSize: '11px' } }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center'
        },
        tooltip: {
            y: { formatter: val => val + "%" }
        }
    };

    // Création et rendu du graphique
    try {
        const chart = new ApexCharts(document.querySelector("#chart-age-opinion"), options);
        chart.render();
    } catch (error) {
        console.error("Erreur radar chart:", error);
        d3.select("#chart-age-opinion").html("<p style='color:red; text-align:center; padding:20px;'>Erreur de chargement du graphique radar</p>");
    }
}



// ---  FONCTION DE POURCENTAGE ---
function calculerPourcentages(compteurs, total) {
    if (total === 0) {
        return compteurs.map(function() { return 0; });
    }
    
    var pourcentagesExacts = compteurs.map(function(count) { 
        return (count / total) * 100; 
    });
    
    var pourcentagesArrondis = pourcentagesExacts.map(function(p) { 
        return Math.floor(p); 
    });
    
    var somme = pourcentagesArrondis.reduce(function(acc, val) { 
        return acc + val; 
    }, 0);

    var difference = 100 - somme; 
    
    if (difference > 0) {
        var decimales = pourcentagesExacts.map(function(p, i) { 
            return { index: i, decimale: p - Math.floor(p) }; 
        });
        
        decimales.sort(function(a, b) { 
            return b.decimale - a.decimale; 
        });
        
        for (var i = 0; i < difference; i++) { 
            pourcentagesArrondis[decimales[i].index]++; 
        }
    }
    
    return pourcentagesArrondis;
}





// Code adapté de l'exemple du professeur pour l'opinion climatique
// Afin de faire le graphique en bâton j'ai demandé à l'IA de s'inspirer de ce code via l'url qui nous a été donné: https://www.tutorialsteacher.com/d3js/create-bar-chart-using-d3js
/*
    <style>
        .bar {
            fill: steelblue;
        }
    </style>
    <script src="https://d3js.org/d3.v4.min.js"></script>
<body>
<svg width="600" height="500"></svg>
<script>
    var svg = d3.select("svg"),
        margin = 200,
        width = svg.attr("width") - margin,
        height = svg.attr("height") - margin
    svg.append("text")
       .attr("transform", "translate(100,0)")
       .attr("x", 50)
       .attr("y", 50)
       .attr("font-size", "24px")
       .text("XYZ Foods Stock Price")
    var xScale = d3.scaleBand().range([0, width]).padding(0.4),
        yScale = d3.scaleLinear().range([height, 0]);
    var g = svg.append("g")
               .attr("transform", "translate(" + 100 + "," + 100 + ")");
    d3.csv("XYZ.csv", function(error, data) {
        if (error) {
            throw error;
        }
        xScale.domain(data.map(function(d) { return d.year; }));
        yScale.domain([0, d3.max(data, function(d) { return d.value; })]);
        g.append("g")
         .attr("transform", "translate(0," + height + ")")
         .call(d3.axisBottom(xScale))
         .append("text")
         .attr("y", height - 250)
         .attr("x", width - 100)
         .attr("text-anchor", "end")
         .attr("stroke", "black")
         .text("Year");
        g.append("g")
         .call(d3.axisLeft(yScale).tickFormat(function(d){
             return "$" + d;
         })
         .ticks(10))
         .append("text")
         .attr("transform", "rotate(-90)")
         .attr("y", 6)
         .attr("dy", "-5.1em")
         .attr("text-anchor", "end")
         .attr("stroke", "black")
         .text("Stock Price");
        g.selectAll(".bar")
         .data(data)
         .enter().append("rect")
         .attr("class", "bar")
         .attr("x", function(d) { return xScale(d.year); })
         .attr("y", function(d) { return yScale(d.value); })
         .attr("width", xScale.bandwidth())
         .attr("height", function(d) { return height - yScale(d.value); });
    });
</script>
*/
function dessinerBarChart(containerId, compteurs, pourcentages) {
    
    // Suppression de l'ancien graphique
    d3.select(containerId).html("");
    
    // Création du SVG (comme dans l'exemple du professeur)
    // ✅ AMÉLIORATION : Hauteur augmentée pour laisser place aux labels
    var svg = d3.select(containerId).append("svg")
        .attr("width", 280)
        .attr("height", 270);
    
    // Marges (comme dans l'exemple du professeur)
    // ✅ AMÉLIORATION : Marge du bas augmentée pour les labels
    var margin = 50;
    var marginBottom = 70;
    var width = svg.attr("width") - margin;
    var height = svg.attr("height") - marginBottom;
    
    // Titre du graphique (comme dans l'exemple du professeur)
    svg.append("text")
       .attr("transform", "translate(50,0)")
       .attr("x", 20)
       .attr("y", 20)
       .attr("font-size", "14px")
       .attr("font-weight", "bold")
       .text("Opinion Climat (%)")
    
    // Échelles X et Y (comme dans l'exemple du professeur)
    var xScale = d3.scaleBand().range([0, width]).padding(0.4);
    var yScale = d3.scaleLinear().range([height, 0]);
    
    // Groupe principal avec translation (comme dans l'exemple du professeur)
    var g = svg.append("g")
               .attr("transform", "translate(" + 50 + "," + 40 + ")");
    
    // Préparation des données (comme dans l'exemple du professeur avec data.map)
    // ✅ AMÉLIORATION : Labels raccourcis pour meilleure lisibilité
    var data = [
        {label: "Certitude", value: pourcentages[0]},
        {label: "Hypothèse", value: pourcentages[1]}
    ];
    
    // Domaines des échelles (comme dans l'exemple du professeur)
    xScale.domain(data.map(function(d) { return d.label; }));
    yScale.domain([0, 100]); // 0 à 100% pour les pourcentages
    
    // Axe X en bas (comme dans l'exemple du professeur)
    // ✅ AMÉLIORATION : Labels SANS rotation, avec plus d'espace
    g.append("g")
     .attr("transform", "translate(0," + height + ")")
     .call(d3.axisBottom(xScale))
     .selectAll("text")
     .style("text-anchor", "middle")
     .attr("font-size", "12px")
     .attr("font-weight", "bold");
    
    // Ajout du label de l'axe X (plus bas pour laisser de l'espace)
    g.append("text")
     .attr("y", height + 55)
     .attr("x", width / 2)
     .attr("text-anchor", "middle")
     .attr("stroke", "black")
     .attr("font-size", "10px")
     .text("Type d'opinion");
    
    // Axe Y à gauche (comme dans l'exemple du professeur)
    g.append("g")
     .call(d3.axisLeft(yScale).tickFormat(function(d){
         return d + "%";  // Affichage en pourcentage (comme dans l'exemple)
     })
     .ticks(5))  // 5 graduations
     .append("text")
     .attr("transform", "rotate(-90)")
     .attr("y", -35)
     .attr("dy", "0em")
     .attr("text-anchor", "middle")
     .attr("stroke", "black")
     .attr("font-size", "10px")
     .text("Pourcentage");
    
    // Dessin des barres (EXACTEMENT comme dans l'exemple du professeur)
    g.selectAll(".bar")
     .data(data)
     .enter().append("rect")
     .attr("class", "bar")
     .attr("x", function(d) { return xScale(d.label); })
     .attr("y", function(d) { return yScale(d.value); })
     .attr("width", xScale.bandwidth())
     .attr("height", function(d) { return height - yScale(d.value); })
     .attr("fill", function(d, i) { return couleursOpinion[i]; });
    
    // Affichage des valeurs au-dessus des barres (ajout pour plus de clarté)
    g.selectAll(".value-label")
     .data(data)
     .enter().append("text")
     .attr("class", "value-label")
     .attr("x", function(d) { return xScale(d.label) + xScale.bandwidth() / 2; })
     .attr("y", function(d) { return yScale(d.value) - 5; })
     .attr("text-anchor", "middle")
     .attr("font-size", "12px")
     .attr("font-weight", "bold")
     .text(function(d) { return d.value + "%"; });
}

// --- RESTAURATION DE TA FONCTION DE DESSIN DU CAMEMBERT ORIGINALE ---
function dessinerCamembert(containerId, dataValues, pourcentages) {

    // Suppression de l'ancien graphique
    d3.select(containerId).html("");

    // Dimensions du SVG
    var width = 180;
    var height = 180;
    
    // Création du SVG
    var svg = d3.select(containerId).append("svg")
        .attr("width", width)
        .attr("height", height);
        
    // Rayon du camembert
    var radius = Math.min(width, height) / 2;

    // Groupe centré
    var g = svg.append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    // Échelle de couleurs
    var color = d3.scaleOrdinal(couleursTransport);

    // Générateur de camembert
    var pie = d3.pie();

    // Générateur d'arcs
    var arc = d3.arc()
        .innerRadius(0)
        .outerRadius(radius);

    // Création des groupes de tranches
    var arcs = g.selectAll("arc")
        .data(pie(dataValues))
        .enter()
        .append("g")
        .attr("class", "arc");

    // Dessin des tranches
    arcs.append("path")
        .attr("fill", function(d, i) {
            return color(i);
        })
        .attr("d", arc)
        .attr("stroke", "white")
        .style("stroke-width", "2px");

    // Affichage des pourcentages
    arcs.append("text")
        .attr("transform", function(d) {
            return "translate(" + arc.centroid(d) + ")";
        })
        .attr("text-anchor", "middle")
        .attr("font-size", "12px")
        .attr("font-weight", "bold")
        .attr("fill", "white")
        .text(function(d, i) {
            return pourcentages[i] > 0 ? pourcentages[i] + "%" : "";
        });
}

// Code récupéré et adapté puis généré par IA pour dessiner des camemberts avec D3.js
/*
<body>
<svg width="300" height="200"> </svg>
<script>
    var data = [2, 4, 8, 10];
    var svg = d3.select("svg"),
        width = svg.attr("width"),
        height = svg.attr("height"),
        radius = Math.min(width, height) / 2,
        g = svg.append("g").attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");
    var color = d3.scaleOrdinal(['#4daf4a','#377eb8','#ff7f00','#984ea3','#e41a1c']);
    var pie = d3.pie();
    var arc = d3.arc().innerRadius(0).outerRadius(radius);
    var arcs = g.selectAll("arc").data(pie(data)).enter().append("g").attr("class", "arc")
    arcs.append("path").attr("fill", function(d, i) { return color(i); }).attr("d", arc);
</script>
</body>
*/
// Lien donné par le professeur : https://www.tutorialsteacher.com/d3js/create-pie-chart-using-d3js