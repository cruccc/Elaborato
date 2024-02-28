<nav class="w3-sidebar w3-bar-block w3-white w3-collapse w3-top" style="z-index:3;width:250px" id="mySidebar">
	<div class="w3-container w3-display-container w3-padding-16" style="margin-top: 20px">
  	<i onclick="w3_close()" class="fa fa-remove w3-hide-large w3-button w3-display-topright"></i>
  	<a href="index.php" class="link-black"><h3 class="w3-wide"><b><?php $configSito = require 'configSito.php'; echo $configSito['nome'];?></b></h3></a>
	</div>
	<div class="w3-padding-64 w3-large w3-text-grey" style="font-weight:bold">
		<?php

	  		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

	  		if(!$conn){
	  			echo '<a href="#" class="w3-bar-item w3-button">Errore di accesso al database</a>';
	  		}else{

	  			$query = "SELECT NomeCategoria FROM Categorie WHERE Cancellato = 0 ORDER BY NomeCategoria";

	  			$sidebar = mysqli_query($conn, $query);

	  			$nRows = mysqli_num_rows($sidebar);//salvo il numero di righe della risposta

	  			if($nRows<=0){//controllo che la ricerca sia andata a buon fine
	  				echo '<a href="#" class="w3-bar-item w3-button">Nessuna categoria trovata</a>';
	  			}else{

	  				for($i=0;$i<$nRows;$i++){//scorro le righe della selezione
	  					$categoriaSidebar = mysqli_fetch_assoc($sidebar);//salvo le sezioni in un array
	  					echo '<a href="categoria.php?categoria='.$categoriaSidebar['NomeCategoria'].'" class="w3-bar-item w3-button">'.$categoriaSidebar['NomeCategoria'].'</a>';
	  				}

	  			}
	  			mysqli_close($conn);
	  		}
		?>
	</div>
	<a href="#footer" class="w3-bar-item w3-button w3-padding">Contact</a> 
	<a href="javascript:void(0)" class="w3-bar-item w3-button w3-padding" onclick="document.getElementById('newsletter').style.display='block'">Newsletter</a>
	<a href="#footer"  class="w3-bar-item w3-button w3-padding">Subscribe</a>
</nav>

<!-- Top menu on small screens -->
<header class="w3-bar w3-top w3-hide-large w3-black w3-xlarge">
  <div class="w3-bar-item w3-padding-24 w3-wide"><a href="index.php" class="link-white"><?php echo $configSito['nome'];?></a></div>
  <a href="javascript:void(0)" class="w3-bar-item w3-button w3-padding-24 w3-right" onclick="w3_open()"><i class="fa fa-bars"></i></a>
</header>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>