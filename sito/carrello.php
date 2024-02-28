<?php

	session_start();
	require_once 'configDB.php';
	$configSito = require 'configSito.php';

	if(isset($_SESSION['logged'])){
		if(!$_SESSION['logged']){
			header("Location: login.php");
		}
	}else{
		header("Location: login.php");
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
			<?php $navbar = "Carrello"; require "navbar.php";?>

			<div class="container-fluid">
				<?php 
	      			$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

					if(!$conn){
						echo '<p>Errore nell\'accesso al database</p>';
					}else{

						$query = "SELECT A.CodAlimento, A.NomeAlimento, A.PathImmagine, A.Prezzo, C.Quantita FROM Alimenti A INNER JOIN RigheCarrello C ON A.CodAlimento = C.CodAlimento WHERE C.EmailUtente = '".$_SESSION['email']."'";

						$result3 = mysqli_query($conn,$query);

						$nRows = mysqli_num_rows($result3);

						if($nRows==0){
							echo '<p>Il tuo carrello è vuoto</p>';
						}else{
          					for($i = 0;$i<$nRows;$i++){
          						$alimento = mysqli_fetch_assoc($result3);
          						if(($i%4)==0){
          							echo '<div class="w3-row w3-grayscale">';
          						}
          						echo '<div class="w3-col l3 s6" id="C'.$alimento['CodAlimento'].'">
							      		<div class="w3-container">
							        		<div class="w3-display-container">
							          			<img src="'.$configSito['cartellaAlimenti'].DIRECTORY_SEPARATOR.$alimento['PathImmagine'].'" class="immagine">
							        		</div>
							        		<p>'.$alimento['NomeAlimento'].'<br>
					        					<i class="fas fa-plus carrello-quantita" onclick="aggiornaCarrello(\''.$alimento['CodAlimento'].'\',\'1\')"></i>
					        					<b id="Q'.$alimento['CodAlimento'].'">'.$alimento['Quantita'].'</b>
					        					<i class="fas fa-minus carrello-quantita" onclick="aggiornaCarrello(\''.$alimento['CodAlimento'].'\',\'-1\')"></i> x <b>€</b>
					        					<b id="P'.$alimento['CodAlimento'].'">'.number_format($alimento['Prezzo'],2).'</b>
							        		</p>
							      		</div>
							    	</div>';
							    if(($i%4)==3){
							    	echo '</div>';
							    }
          					}
          					if(($i%4)!=0){
          						echo '</div>';
          					}
          				}
						
						mysqli_close($conn);
					}
	      		?>
	      		<hr>
	      		<h2 class="destra">Totale: <b id="totaleCarrello"><?php $emailCarrello = $_SESSION['email']; $totale = require 'getTotaleCarrello.php'; echo $totale; ?></b></h2>
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