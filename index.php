<?php
session_start();

//ACCUEIL DU JEU DU LABYRINTHE

// reinitialisation du score et des clés
$_SESSION['score'] = 0;
$_SESSION['cles'] = 0;

	//Documentation php pour sqlite : https://www.php.net/manual/en/book.sqlite3.php
	

	/* Paramètres */
	$bdd_fichier = 'labyrinthe.db';	//Fichier de la base de données
	$type = 'vide';			//Type de couloir à lister
	

	$sqlite = new SQLite3($bdd_fichier);		//On ouvre le fichier de la base de données
	
	/* Instruction SQL pour récupérer la liste des pieces adjacentes à la pièce paramétrée */
	$sql = 'SELECT couloir.id, couloir.type FROM couloir WHERE type=:type';
	
	
	/* Préparation de la requete et de ses paramètres */
	$requete = $sqlite -> prepare($sql);	
	$requete -> bindValue(':type', $type, SQLITE3_TEXT);
	
	$result = $requete -> execute();	//Execution de la requête et récupération du résultat

	/* On génère et on affiche notre page HTML avec la liste de nos films */
	echo "<!DOCTYPE html>\n";		//On demande un saut de ligne avec \n, seulement avec " et pas '
	echo "<html lang=\"fr\"><head><meta charset=\"UTF-8\">\n";	//Avec " on est obligé d'échapper les " a afficher avec \
	echo "<title>Labyrinthe</title>\n";
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
	echo "  a.btn { 
			background: #ffffff;
			color: #0f4c81; 
			padding: .5rem 1rem; 
			border-radius: 4px; 
			text-decoration: none; 
			display: inline-block; 
			margin-top: 1rem; }\n";	
	echo "</style>\n";
	echo "</head>\n";
	
	echo "<body>\n";
	echo "<h1>Jeu du Labyrinthe : Trouvez la sortie!</h1>\n";
	echo "<h2>Règle du jeu:</h2>";
	echo "<p>		
				Le but du jeu est simple, il vous suffit de trouver la sortie 
				en vous déplacant dans les couloirs.
			</p>";
	echo "<p>
				Le départ est au couloir 13, il faut se diriger vers le couloir 26 pour sortir du labyrinthe.
				</p>";
	echo "<p>
			Pour vous orienter, vous pouvez vous aider des directions cardinaux Nord (N), Sud (S), Est (E) et Ouest (O) indiquées pour chaque passage.
				</p>";
	echo "<p>
				Attention! Il y a des clés à récupérer pour pouvoir passer dans certaines portes.
				</p>";
	echo "<title>Liste des couloirs</title>\n";
	echo "</head>\n";
	
	echo "<body>\n";

	echo "<a class='btn' href='couloir.php?id=13'>Commencer la partie !</a>\n";
	echo "</body>\n";
	echo "</html>\n";
	
	
	$sqlite -> close();			//On ferme bien le fichier de la base de données avant de terminer!
	
?>