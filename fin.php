<?php
session_start();

/* Paramètres */
$bdd_fichier = 'labyrinthe.db';	//Fichier de la base de données
$type = 'vide';			//Type de couloir à lister
    

$sqlite = new SQLite3($bdd_fichier); 		//On ouvre le fichier de la base de données
    
/* Instruction SQL pour récupérer la liste des pieces adjacentes à la pièce paramétrée */
$sql = 'SELECT couloir.id, couloir.type FROM couloir WHERE type=:type';
    
    
/* Préparation de la requete et de ses paramètres */
$requete = $sqlite -> prepare($sql);
$requete -> bindValue(':type', $type, SQLITE3_TEXT);
    
$result = $requete -> execute();    //Execution de la requête et récupération du résultat

$db = new SQLite3('labyrinthe.db'); // connexion à la base de données

$score = $_SESSION['score'] ?? 0; //nombre de déplacements faits par le joueur

echo "<!DOCTYPE html>\n";
echo "<html lang=\"fr\">\n";
echo "<head>\n";
echo "    <meta charset=\"UTF-8\">\n";
echo "    <title>Victoire</title>\n";
//styles CSS
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
echo "a#rcmc { 
        background: #11a21dff;
        padding: .5rem 1rem;
        margin-top: 1rem;
        text-decoration: none; }\n"; 
echo "</style>\n";
echo "</head>\n";
echo "<body>\n";

echo "<h1>Tu as trouvé la sortie ! Bien joué !!!</h1>\n"; //message de félicitations

echo "<p>Score : $score</p>\n"; //affiche le score final du joueur

echo "<a id='rcmc'href=\"index.php\">Recommencer</a>\n"; //permet au joueur de recommencer la partie si il le souhaite

echo "</body>\n";
echo "</html>\n";

$sqlite -> close();			//On ferme bien le fichier de la base de données avant de terminer!

?>
