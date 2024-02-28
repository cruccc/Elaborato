<?php 
	require_once 'configDB.php';//pagina contenente le informazioni necessarie alla connessione al DB

	if(isset($_POST['nomeDato'])&&isset($_POST['valDato'])) {//controllo che siano stati passati i valori tramite POST della richiesta ajax
		//effettuo la connessione al DB. 'nomeDato' indica di che campo si vuole fare il controllo('Username' o 'Email') che coincide con il nome della relativa colonna del database e 'valDato' è il valore scelto dall'utente per quel campo
		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

		if($conn){//controllo che la connessione sia andata a buon fine

			//prevenzione di attacchi XSS e SQL Injection
			$nomeDato = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['nomeDato']));
			$valDato = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['valDato']));

			//scrivo la query
			$query = "SELECT ".$nomeDato." FROM Utenti WHERE ".$nomeDato." = '".$valDato."'";//'nomeDato' indica la colonna interessata e 'valDato' il valore scelto dall'utente
			
			//eseguo la query
			$result = mysqli_query($conn,$query);
			
			//controllo l'esito della query
			if(mysqli_num_rows($result)==1){//se è stato trovato una riga con lo stesso campo invio '1' al client (errore)
				echo '1';
			}else{//se non è stata trovata una riga con lo stesso campo
				if($nomeDato=="Email"){//se il campo da cercare è la mail controllo anche la tabella degli operatori
					//scrivo la query
					$query = "SELECT Email FROM Operatori WHERE Email = '".$valDato."'";//'nomeDato' indica la colonna interessata e 'valDato' il valore scelto dall'utente

					//eseguo la query
					$result1 = mysqli_query($conn,$query);
					
					//controllo l'esito della query
					if(mysqli_num_rows($result1)==1){//se è stato trovato una riga con lo stesso campo invio '1' al client (errore)
						echo '1';
					}else{//se non è stata trovata una riga con lo stesso campo invio '0' al client (ok)
						echo '0';
					}
				}else{//se il campo non è la mail invio '0' al client (ok)
					echo '0';
				}
			}
			mysqli_close($conn);//chiudo la connessione al db
		}
	}
?>