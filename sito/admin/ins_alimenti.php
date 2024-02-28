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
			echo '<script>alert("Errore nell\'accesso al database");location.replace("ins_alimenti.php");</script>';
		}else{
			$uploadOk = 1;
			$nome = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['nomeAli']));
			$prezzo = $_POST['prezzo'];

			if(is_numeric($prezzo)==false){
				$uploadOk = 0;
				echo '<script>alert("Il prezzo deve essere un valore numerico con massimo due cifre decimali");location.replace("ins_alimenti.php");</script>';
			}

			$categoria = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['categoria']));

			/****************************CONTROLLO DELL'IMMAGINE**********************************/
			$date = date('Y-m-d_H-i-s');
			$img = "../".$configSito['cartellaAlimenti'].DIRECTORY_SEPARATOR.$nome.$date.".jpg";

			if(file_exists($img)){	//se il file esiste già, quindi c'è un articolo con lo stesso nome
				$uploadOk = 0;	//il file non va bene
				echo '<script>alert("Esiste già un alimento con lo stesso nome");location.replace("ins_alimenti.php");</script>';
			}

			$check = getimagesize($_FILES["immagine"]["tmp_name"]);
			if($check === false){
				echo '<script>alert("Il file non è un immagine");location.replace("ins_alimenti.php");</script>';
				$uploadOk = 0;
			}

			//ottengo il tipo di file inviato (estensione)
			$tipoFile = strtolower(pathinfo($_FILES["immagine"]["name"],PATHINFO_EXTENSION));

			if($_FILES["immagine"]["size"]>$configSito['maxGrandezzaImg']){//controllo che il file non sia più grande della grandezza prestabilita
				$uploadOk = 0;	//il file non va bene
				echo '<script>alert("L immagine deve essere più piccola di '.($configSito['maxGrandezzaImg']/1000).'kB");location.replace("ins_alimenti.php");</script>';
			}

			if($uploadOk==1){	//se i file vanno bene
				if("../".$configSito['cartellaAlimenti']){
					if (move_uploaded_file($_FILES["immagine"]["tmp_name"],$img)){
						$query = "INSERT INTO Alimenti (NomeAlimento,Prezzo,Categoria,PathImmagine,Cancellato) 
								VALUES ('".$nome."', '".$prezzo."', '".$categoria."', '".$nome.$date.".".$tipoFile."', '0');";
						$result1 = mysqli_query($conn,$query);
						
						if(!$result1){
							echo '<script>alert("Errore: '.mysqli_error($conn).'"); location.replace("ins_alimenti.php");</script>';
						}else{
							echo '<script>alert("Inserimento avvenuto con successo!"); location.replace("ins_alimenti.php");</script>';
						}
					}else{
						unlink($img);
					}
				}else{
					echo '<script>alert("La cartella \''.$configSito['cartellaAlimenti'].'\' non esiste"); location.replace("ins_alimenti.php");</script>';
				}
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
	          	<form action="ins_alimenti.php" enctype="multipart/form-data" method="post">

	            	<h1 style="margin-bottom: 30px">Inserisci un nuovo alimento</h1>
            		<div class="row">

              			<div class="col-sm-5">
                			<div class="w3-container">
                  				<label for="nomeAli"><p>Nome:</p></label>
                			</div>
              			</div>
              			<div class="col-sm-7">
                			<div class="w3-container">
                  				<input type="text" name="nomeAli" id="nomeAli" maxlength="100" required><br><br>
                			</div>
              			</div>

            		</div>
            		<div class="row">

              			<div class="col-sm-5">
                			<div class="w3-container">
                  				<label for="prezzo"><p>Prezzo:</p></label>
                			</div>
              			</div>
              			<div class="col-sm-7">
                			<div class="w3-container">
                  				<input type="number" step="0.01" name="prezzo" id="prezzo" required><br><br>
                			</div>
              			</div>

            		</div>
            		<div class="row">

              			<div class="col-sm-5">
                			<div class="w3-container">
                  				<label for="categoria"><p>Categoria:</p></label>
                			</div>
              			</div>
              			<div class="col-sm-7">
                			<div class="w3-container">
                				<select id="categoria" name="categoria" style="width: 196px;height: 28px;">
									<?php
										$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);
										if(!$conn){
											echo '<option value="errore" selected disabled><p>Errore di accesso al database</p></option>';
										}else{

											$query = "SELECT NomeCategoria FROM Categorie WHERE Cancellato = 0 ORDER BY NomeCategoria";

											$result3 = mysqli_query($conn, $query);

											$nRows = mysqli_num_rows($result3);//salvo il numero di righe della risposta

											if($nRows>0){

												echo '<option value="" selected disabled hidden>Scegli categoria</option>';

												for($i=0;$i<$nRows;$i++){//scorro le righe della selezione
													$categoria = mysqli_fetch_assoc($result3);
													echo '<option value="'.$categoria["NomeCategoria"].'"><p>'.$categoria["NomeCategoria"].'</p></option>';
												}

											}else{
												echo '<option value="errore" selected disabled><p>Nessuna categoria trovata</p></option>';
											}
										}
										mysqli_close($conn);
									?>
								</select><br><br>
                			</div>
              			</div>

            		</div>
            		<div class="row">

              			<div class="col-sm-5">
                			<div class="w3-container">
                  				<label for="immagine"><p>Immagine dell'alimento:</p></label>
                			</div>
              			</div>
              			<div class="col-sm-7">
                			<div class="w3-container">
                  				<input type="file" name="immagine" id="immagine" required><br><br>
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