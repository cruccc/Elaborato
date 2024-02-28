<?php 
	require_once 'configDB.php';//pagina contenente le informazioni necessarie alla connessione al DB
	$configSito = require 'configSito.php';
	session_start();

	if((isset($_POST['codice'])||isset($codice))&&isset($_SESSION['email'])&&(isset($agg)||isset($_POST['agg']))) {//controllo che siano stati passati i valori tramite POST della richiesta ajax
		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);
		if($conn){
			$codiceAgg = null;
			if(isset($_POST['codice'])){
				$codiceAgg = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['codice']));
			}else{
				$codiceAgg = $codice;
			}
			
			//scrivo la query
			$query = "SELECT Quantita FROM RigheCarrello WHERE EmailUtente = '".$_SESSION['email']."' AND CodAlimento = '".$codiceAgg."'";

			//eseguo la query
			$result2 = mysqli_query($conn,$query);
			
			//controllo l'esito della query
			if(mysqli_num_rows($result2)==1){//se è stato trovato una riga con lo stesso campo invio '1' al client (errore)

				$quantita = mysqli_fetch_assoc($result2);

				$agg1 = null;
				if(isset($_POST['agg'])){
					$agg1 = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['agg']));
				}else{
					$agg1 = $agg;
				}

				if(($quantita['Quantita']+$agg1)<=$configSito['maxNumAlimenti']){
					if(($quantita['Quantita']+$agg1)>0){
						//scrivo la query
						$query = "UPDATE RigheCarrello SET Quantita = '".($quantita['Quantita']+$agg1)."' WHERE EmailUtente = '".$_SESSION['email']."' AND CodAlimento = '".$codiceAgg."'";

						//eseguo la query
						$result3 = mysqli_query($conn,$query);

						if($result3){
							echo '0';
						}else{
							echo '1e';
						}
					}else{
						//scrivo la query
						$query = "DELETE FROM RigheCarrello WHERE EmailUtente = '".$_SESSION['email']."' AND CodAlimento = '".$codiceAgg."'";

						//eseguo la query
						$result4 = mysqli_query($conn,$query);

						if($result4){
							echo '0';
						}else{
							echo '1';
						}
					}
				}else{
					echo '3';
				}
				
			}else{//se non è stata trovata una riga con lo stesso campo invio '0' al client (ok)
				echo '1';
			}
			mysqli_close($conn);//chiudo la connessione al db
		}else{
			echo '1';
		}
	}else{
		echo '1';
	}
?>