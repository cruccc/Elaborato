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

	if(isset($_POST['canc'])){
		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

		if(!$conn){
			echo '<script>alert("Errore nell\'accesso al database");location.replace("elenco_classi.php");</script>';
		}else{

			$classe = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['classe']));

			echo '<script>
					if(confirm("Sei sicuro di voler cancellare la classe \''.$classe.'\'?")){
						location.replace("elenco_classi.php?classe='.$classe.'");
					}else{
						location.replace("elenco_classi.php");
					}
				</script>';
			
			mysqli_close($conn);
		}
	}elseif(isset($_GET['classe'])){

		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

		if(!$conn){
			echo '<script>alert("Errore nell\'accesso al database");location.replace("elenco_classi.php");</script>';
		}else{

			$classe = htmlspecialchars(mysqli_real_escape_string($conn,$_GET['classe']));

			$query = "UPDATE Classi SET Cancellato = 1 WHERE NomeClasse = '".$classe."'";

			$result2 = mysqli_query($conn,$query);

			if(!$result2){
				echo '<script>alert("C\'è stato un errore nella cancellazione della classe \''.$classe.'\'");location.replace("elenco_classi.php");</script>';
			}else{
				echo '<script>alert("Eliminazione avvenuta con successo!");location.replace("elenco_classi.php");</script>';
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
	      		<h1 style="margin-bottom: 30px">Elenco delle classi</h1>
	      		<?php 
	      			$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

					if(!$conn){
						echo '<p>Errore nell\'accesso al database</p>';
					}else{

						$query = "SELECT * FROM Classi WHERE Cancellato = 0";

						$result3 = mysqli_query($conn,$query);

						$nRows = mysqli_num_rows($result3);

						if($nRows==0){
							echo '<p>La tabella Classi è vuota, Nessun risultato</p>';
						}else{ ?>

							<div class="table-responsive">
				          		<table class="table table-striped table-hover">
				          			<thead>
				          				<tr>
				          					<th>Classe</th>
				          					<th>Aula</th>
				          					<th>Operazioni</th>
				          				</tr>
				          			</thead>
				          			<tbody>
				          				<?php
				          					for($i = 0;$i<$nRows;$i++){
				          						$classe = mysqli_fetch_assoc($result3);
				          						echo '<tr>
							          					<td>'.$classe['NomeClasse'].'</td>
							          					<td>'.$classe['Aula'].'</td>
							          					<td>
							          						<form action="elenco_classi.php" method="post">
							          						<input type="text" name="classe" value="'.$classe['NomeClasse'].'" hidden readonly>
							          						<button type="submit" class="canc-button" name="canc"><i class="fas fa-trash-alt"></i>    Cancella</button>
							          						</form>
							          					</td>
							          				</tr>';
				          					}
				          				?>
				          			</tbody>
				          		</table>
				          	</div>

						<?php }
						
						mysqli_close($conn);
					}
	      		?>
	      	</div>

	      	<!-- End page content -->
	    </div>
  	</body>
</html>