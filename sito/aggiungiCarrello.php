<?php 
	require_once 'configDB.php';//pagina contenente le informazioni necessarie alla connessione al DB
	$configSito = require 'configSito.php';
	session_start();

	if(isset($_POST['codice'])&&isset($_SESSION['email'])) {//controllo che siano stati passati i valori tramite POST della richiesta ajax
		//effettuo la connessione al DB. 'nomeDato' indica di che campo si vuole fare il controllo('Username' o 'Email') che coincide con il nome della relativa colonna del database e 'valDato' è il valore scelto dall'utente per quel campo
		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

		if($conn){//controllo che la connessione sia andata a buon fine

			//prevenzione di attacchi XSS e SQL Injection
			$codice = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['codice']));

			//scrivo la query
			$query = "INSERT INTO RigheCarrello (EmailUtente,CodAlimento,Quantita) 
						VALUES ('".$_SESSION['email']."', '".$codice."', 1);";//'nomeDato' indica la colonna interessata e 'valDato' il valore scelto dall'utente
			
			//eseguo la query
			$result1 = mysqli_query($conn,$query);
			
			//controllo l'esito della query
			if(!$result1){

				$agg = 1; require 'aggiornaCarrello.php';

			}else{
				echo '0';
			}
			mysqli_close($conn);//chiudo la connessione al db
		}else{
			echo '1';
		}
	}else{
		if(!isset($_SESSION['email'])){
			echo '2';
		}
	}
?>