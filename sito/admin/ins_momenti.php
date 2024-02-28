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
			echo '<script>alert("Errore nell\'accesso al database");location.replace("ins_momenti.php");</script>';
		}else{
			$nome = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['nome']));
			$ora = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['ora']));

			$query = "INSERT INTO Momenti (NomeMomento,OraConsegna,Cancellato) 
					VALUES ('".$nome."', '".$ora."', 0);";
			$result1 = mysqli_query($conn,$query);
			
			if(!$result1){
				$query = "UPDATE Momenti SET OraConsegna = '".$ora."', Cancellato = 0 WHERE NomeMomento = '".$nome."' AND Cancellato = 1";
        $result2 = mysqli_query($conn,$query);
        
        if(!$result2){
          echo '<script>alert("Errore: '.mysqli_error($conn).'"); location.replace("ins_momenti.php");</script>';
        }else{
          echo '<script>alert("Inserimento avvenuto con successo!"); location.replace("ins_momenti.php");</script>';
        }
			}else{
				echo '<script>alert("Inserimento avvenuto con successo!"); location.replace("ins_momenti.php");</script>';
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
	          	<form action="ins_momenti.php" method="post">

	            	<h1 style="margin-bottom: 30px">Inserisci un nuovo momento per la consegna</h1>
            		<div class="row">

              			<div class="col-sm-5">
                			<div class="w3-container">
                  				<label for="nome"><p>Nome:</p></label>
                			</div>
              			</div>
              			<div class="col-sm-7">
                			<div class="w3-container">
                  				<input type="text" name="nome" id="nome" maxlength="30" required><br><br>
                			</div>
              			</div>

            		</div>
            		<div class="row">

              			<div class="col-sm-5">
                			<div class="w3-container">
                  				<label for="ora"><p>Orario:</p></label>
                			</div>
              			</div>
              			<div class="col-sm-7">
                			<div class="w3-container">
                  				<input type="time" name="ora" id="ora" required><br><br>
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