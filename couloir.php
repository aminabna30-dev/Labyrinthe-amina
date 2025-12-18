<?php
session_start();

/* Paramètres */
$db = new SQLite3('labyrinthe.db'); // connexion à la base de données

$idCouloir = intval($_GET['id'] ?? 13);

// initialisation session
if (!isset($_SESSION['cles'])) $_SESSION['cles'] = 0; 
if (!isset($_SESSION['score'])) $_SESSION['score'] = 0;

// infos du couloir actuel
$couloir = $db->querySingle(
    "SELECT * FROM couloir WHERE id = $idCouloir",
    true
);

// si le joueur atteint la sortie, il gagne la partie
if ($couloir['type'] === 'sortie') {
    header("Location: fin.php");
    exit;
}

// permet au joueur d'obtenir une clé
if ($couloir['type'] === 'cle') {
    $_SESSION['cles']++;
}
$_SESSION['score']++;

// position du joueur, nombre de clés et score
echo "<!DOCTYPE html>\n";
echo "<html lang=\"fr\">\n";
echo "<head>\n";
echo "    <meta charset=\"UTF-8\">\n";
echo "    <title>Couloir $idCouloir</title>\n";
// styles CSS
echo "<style>\n";	
echo "body { 
        background-color: #0f4c81;
        color: #f4f7fb;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 2rem; }\n";
echo "h1, h2 { 
        color: #ffffff;
        margin-top: 0; }\n";
echo "p { 
        max-width: 60ch; 
        line-height: 1.5; }\n";	
echo "a.btn { 
        background: #ffffff;
        color: #0f4c81; 
        padding: .5rem 1rem; 
        border-radius: 4px; 
        text-decoration: none; 
        display: inline-block; 
        margin-top: 1rem; }\n";	
echo "a#abd { 
        background: #ff4c4c;
        padding: .5rem 1rem;
        margin-top: 1rem;
        text-decoration: none; }\n"; 
echo "</style>\n";
echo "</head>\n";
echo "<body>\n\n";
echo "<h2>Couloir $idCouloir</h2>\n";
echo "<p>Type : <b>{$couloir['type']}</b></p>\n";
echo "<p>Clés : {$_SESSION['cles']}</p>\n";
echo "<p>Score : {$_SESSION['score']}</p>\n\n";
echo "<hr>\n\n";
echo "<h3>Passages possibles</h3>\n\n";
echo "<ul>\n";

// requête pour passages possibles
$passages = $db->query(
    "SELECT * FROM passage 
     WHERE couloir1 = $idCouloir 
        OR couloir2 = $idCouloir"
);

while ($pass = $passages->fetchArray(SQLITE3_ASSOC)) {

    // déterminer le ou les prochains couloirs
    if ($pass['couloir1'] == $idCouloir) {
    $prochain = $pass['couloir2'];
    $direction = $pass['position1'];
    } else {
    $prochain = $pass['couloir1'];
    $direction = $pass['position2'];
    }

    // si il n'y a pas de clé, le passage de la grille est bloqué
    if ($pass['type'] === 'grille' && $_SESSION['cles'] <= 0) {
        echo "<li>Passage bloqué (clé requise)</li>\n";
        continue;
    }

    // utiliser la clé si ça passe une grille
    if ($pass['type'] === 'grille') {
        $_SESSION['cles']--;
    }

    // permet à l'utilisateur de choisir le prochain couloir
    echo "<li>\n";
    echo "    <a class='btn' href=\"couloir.php?id=$prochain\">\n";
    echo "        Aller vers le couloir $prochain";
    echo " (direction: $direction)";
    echo "\n    </a>\n";
    echo "  </li>\n";
}

echo "</ul>\n\n";
echo "<a id='abd' href=\"index.php\">Abandonner</a>\n\n"; //si l'utilisateur souhaite abandonner la partie
echo "</body>\n";
echo "</html>\n";

$db->close();		//On ferme bien le fichier de la base de données avant de terminer!
//if (!empty($direction)) 
?>