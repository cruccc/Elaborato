<?php
	//////////////////////////////IMPOSTAZIONI DATABASE////////////////////////////////
	$dbhost = "localhost";
	$dbusername = "quintab1920sql";
	$dbpassword = "4dYzfexdHEDI3vya";
	$dbname = "quintab1920sql_Cruciani";
	//controllo che i dati siano corretti
	$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);
	if(!$conn){
		echo '<script>alert("Errore di accesso al database");</script>';
	}
	mysqli_close($conn);

?>