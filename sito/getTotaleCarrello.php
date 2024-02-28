<?php

	$query = "SELECT A.Prezzo, C.Quantita FROM RigheCarrello C INNER JOIN Alimenti A ON C.CodAlimento = A.CodAlimento WHERE C.EmailUtente = '".$emailCarrello."'";

	$carrello = mysqli_query($conn, $query);

	$nRows = mysqli_num_rows($carrello);//salvo il numero di righe della risposta

	$totale = 0;

	if($nRows>0){//controllo che la ricerca sia andata a buon fine
		for($i=0;$i<$nRows;$i++){
			$totCarrello = mysqli_fetch_assoc($carrello);
			$totale = $totale + ($totCarrello['A.Prezzo']*$totCarrello['C.Quantita']);
		}
	}

	return $totale;

?>