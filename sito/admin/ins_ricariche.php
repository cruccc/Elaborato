<?php

	session_start();
  	/*if(isset($_SESSION['permessi'])){
    	if(($_SESSION['permessi']==0)||($_SESSION['permessi']==1)){
    		header("Location: ../login.php");
    	}
  	}else{
    	header("Location: ../login.php");
  	}*/

	require_once '../configDB.php';
	$configSito = require '../configSito.php';

	if(isset($_POST['invia'])){

		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

		if(!$conn){
			echo '<script>alert("Errore nell\'accesso al database");location.replace("ins_ricariche.php");</script>';
		}else{
			$emailUtente = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['emailUtente']));
			$descrizione = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['descrizione']));
			$importo = $_POST['importo'];

			if(is_numeric($importo)){
				$query = "INSERT INTO Ricariche (EmailUtente,Descrizione,Importo,DataOra,EmailOperatore) 
						VALUES ('".$emailUtente."', '".$descrizione."', '".$importo."', '".date('Y-m-d H:i:s')."', '".$_SESSION['email']."');";
				$result1 = mysqli_query($conn,$query);
				
				if(!$result1){
					echo '<script>alert("Errore: '.mysqli_error($conn).'"); location.replace("ins_ricariche.php");</script>';
				}else{
					echo '<script>alert("Inserimento avvenuto con successo!"); location.replace("ins_ricariche.php");</script>';
				}
			}else{
				echo '<script>alert("L\'importo deve essere un valore numerico con massimo due cifre decimali");location.replace("ins_ricariche.php");</script>';
			}
			mysqli_close($conn);
		}
	}
?>
<!DOCTYPE html>
<html lang="it">
  	<head>
    	<?php require_once 'head.php';?>
  	</head>
  	<body class="w3-light-grey">

	    <?php require_once 'sidebar.php';?>

	    <!-- !PAGE CONTENT! -->
	    <div class="w3-main" style="margin-left:300px;margin-top:43px;">

	      	<?php require_once 'header.php';?>

	      	<div class="container-fluid">
	          	<form action="ins_ricariche.php" enctype="multipart/form-data" method="post">

	            	<h1 style="margin-bottom: 30px">Inserisci una nuova ricarica</h1>
            		<div class="row">

              			<div class="col-sm-5">
                			<div class="w3-container">
                  				<label for="emailUtente"><p>Email Utente:</p></label>
                			</div>
              			</div>
              			<div class="col-sm-7">
                			<div class="w3-container">
                  				<input list="emailUtenti" type="text" name="emailUtente" id="emailUtente" maxlength="150">
								<datalist id="emailUtenti">
									<?php
										$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);
										if(!$conn){
											echo '<option value="errore" selected disabled><p>Errore di accesso al database</p></option>';
										}else{

											$query = "SELECT Email FROM Utenti ORDER BY Email";

											$result3 = mysqli_query($conn, $query);

											$nRows = mysqli_num_rows($result3);//salvo il numero di righe della risposta

											if($nRows>0){

												for($i=0;$i<$nRows;$i++){//scorro le righe della selezione
													$utente = mysqli_fetch_assoc($result3);
													echo '<option value="'.$utente["Email"].'"><p>'.$utente["Email"].'</p></option>';
												}

											}else{
												echo '<option value="errore" selected disabled><p>Nessuna categoria trovata</p></option>';
											}
										}
										mysqli_close($conn);
									?>
								</datalist><br><br>
                			</div>
              			</div>

            		</div>
            		<div class="row">

              			<div class="col-sm-5">
                			<div class="w3-container">
                  				<label for="importo"><p>Importo(€):</p></label>
                			</div>
              			</div>
              			<div class="col-sm-7">
                			<div class="w3-container">
                  				<input list="importi" type="number" step="0.01" name="importo" id="importo" required>
                  				<datalist id="importi">
                  					<option value="5">
                  					<option value="10">
                  					<option value="15">
                  					<option value="20">
                  					<option value="25">
                  					<option value="50">
                  					<option value="100">
                  				</datalist><br><br>
                			</div>
              			</div>

            		</div>
            		<div class="row">

              			<div class="col-sm-5">
                			<div class="w3-container">
                  				<label for="descrizione"><p>Descrizione:</p></label>
                			</div>
              			</div>
              			<div class="col-sm-7">
                			<div class="w3-container">
                  				<input list="descrizioni" type="text" name="descrizione" id="descrizione" maxlength="100" required>
                  				<datalist id="descrizioni">
                  					<option value="Contanti">
                  					<option value="Carta">
                  					<option value="Bollettino">
                  				</datalist><br><br>
                			</div>
              			</div>

            		</div>
            		<div class="row">

              			<div class="col-sm-12">
                			<div class="w3-container">
                  				<h5 style="text-align: center; margin-top: 10px"><input class="submit-button" style="width: 140px" type="submit" name="invia" id="invia" value="Inserisci"></h5>
                			</div>
              			</div>

            		</div>
	        	</form>
	      	</div>

	      	<!-- End page content -->
	    </div>
  	</body>
</html>