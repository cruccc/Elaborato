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
			echo '<script>alert("Errore nell\'accesso al database");location.replace("ins_categorie.php");</script>';
		}else{
			$categoria = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['categoria']));

			$query = "INSERT INTO Categorie (NomeCategoria,Cancellato) 
					VALUES ('".$categoria."', 0);";
			$result1 = mysqli_query($conn,$query);
			
			if(!$result1){
				$query = "UPDATE Categorie SET Cancellato = 0 WHERE NomeCategoria = '".$categoria."' AND Cancellato = 1";
				$result2 = mysqli_query($conn,$query);
				
				if(!$result2){
					echo '<script>alert("Errore: '.mysqli_error($conn).'"); location.replace("ins_categorie.php");</script>';
				}else{
					echo '<script>alert("Inserimento avvenuto con successo!"); location.replace("ins_categorie.php");</script>';
				}
			}else{
				echo '<script>alert("Inserimento avvenuto con successo!"); location.replace("ins_categorie.php");</script>';
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
	          	<form action="ins_categorie.php" method="post">

	            	<h1 style="margin-bottom: 30px">Inserisci una nuova categoria di alimenti</h1>
            		<div class="row">

              			<div class="col-sm-5">
                			<div class="w3-container">
                  				<label for="categoria"><p>Nome:</p></label>
                			</div>
              			</div>
              			<div class="col-sm-7">
                			<div class="w3-container">
                  				<input type="text" name="categoria" id="categoria" maxlength="50" required><br><br>
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