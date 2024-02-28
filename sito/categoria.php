<?php

	session_start();
	require_once 'configDB.php';
	$configSito = require 'configSito.php';

	$flag = 1;

	if(isset($_GET['categoria'])){
		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

		if($conn){

			$categoria = htmlspecialchars(mysqli_real_escape_string($conn,$_GET['categoria']));

			$query = "SELECT * FROM Categorie WHERE NomeCategoria = '".$categoria."'";

			$result1 = mysqli_query($conn,$query);

			if(mysqli_num_rows($result1)<1){
				$flag = 0;
			}
			mysqli_close($conn);

		}else{
			$flag = 0;
		}
	}else{
		$flag = 0;
	}

	if($flag==0){
		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

		if($conn){

			$query = "SELECT * FROM Categorie ORDER BY NomeCategoria DESC";

			$result2 = mysqli_query($conn,$query);

			if(mysqli_num_rows($result2)>0){
				$app = mysqli_fetch_assoc($result2);
				$categoria = $app['NomeCategoria'];
				$flag = 1;
			}else{
				$flag = 0;
			}
			mysqli_close($conn);

		}else{
			$flag = 0;
		}
	}

?>

<!DOCTYPE HTML>
<html lang="it">
	<head>
		<?php require "head.php";?>
	</head>

	<body class="w3-content" style="max-width:1200px; padding-top: 40px;">

		<!-- Sidebar/menu -->
		<?php require "sidebar.php";?>

		<!-- !PAGE CONTENT! -->
		<div class="w3-main" style="margin-left:250px">

			<!-- navbar -->
			<?php $navbar = $categoria; require "navbar.php";?>

			<div class="container-fluid">
				<?php 
					if($flag==1){
		      			$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

						if(!$conn){
							echo '<h1>Errore nell\'accesso al database</h1>';
						}else{

							$query = "SELECT CodAlimento,NomeAlimento,Prezzo,PathImmagine FROM Alimenti WHERE Categoria = '".$categoria."'";

							$result3 = mysqli_query($conn,$query);

							$nRows = mysqli_num_rows($result3);

							if($nRows==0){
								echo '<h1>Nessun risultato</h1>';
							}else{
	          					for($i = 0;$i<$nRows;$i++){
	          						$alimento = mysqli_fetch_assoc($result3);
	          						if(($i%4)==0){
	          							echo '<div class="w3-row">';
	          						}
	          						echo '<div class="w3-col l3 s6">
								      		<div class="w3-container">
								        		<div class="w3-display-container">
								          			<img src="'.$configSito['cartellaAlimenti'].DIRECTORY_SEPARATOR.$alimento['PathImmagine'].'" class="immagine">
								          			<div class="w3-display-middle w3-display-hover">
								            			<button class="w3-button w3-black" onclick="aggiungiCarrello(\''.$alimento['CodAlimento'].'\');">Aggiungi al carrello <i class="fa fa-shopping-cart"></i></button>
								          			</div>
								        		</div>
								        		<p>'.$alimento['NomeAlimento'].'<br><b>€'.number_format($alimento['Prezzo'],2).'</b></p>
								      		</div>
								    	</div>';
								    if(($i%4)==3){
								    	echo '</div>';
								    }
	          					}
	          					if(($i%4)!=3){
	          						echo '</div>';
	          					}
	          				}
							
							mysqli_close($conn);
						}
					}else{
						echo '<h1>Errore</h1>';
					}
	      		?>
	      	</div>
		  
		  	<!-- Footer -->
		  	<?php require "footer.php";?>

		  <!-- End page content -->
		</div>

		<!-- Newsletter Modal -->
		<div id="newsletter" class="w3-modal">
		  	<div class="w3-modal-content w3-animate-zoom" style="padding:32px">
		    	<div class="w3-container w3-white w3-center">
		      		<i onclick="document.getElementById('newsletter').style.display='none'" class="fa fa-remove w3-right w3-button w3-transparent w3-xxlarge"></i>
			      	<h2 class="w3-wide">NEWSLETTER</h2>
			      	<p>Join our mailing list to receive updates on new arrivals and special offers.</p>
			      	<p><input class="w3-input w3-border" type="text" placeholder="Enter e-mail"></p>
			      	<button type="button" class="w3-button w3-padding-large w3-red w3-margin-bottom" onclick="document.getElementById('newsletter').style.display='none'">Subscribe</button>
		    	</div>
		  	</div>
		</div>
	</body>
</html>