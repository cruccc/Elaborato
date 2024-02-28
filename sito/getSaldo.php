<?php
	
	$query = "SELECT SUM(Importo) AS TotRicariche FROM Ricariche WHERE EmailUtente = '".$emailSaldo."'";

	$ricariche = mysqli_query($conn, $query);

	$nRows = mysqli_num_rows($ricariche);//salvo il numero di righe della risposta

	if($nRows=1){//controllo che la ricerca sia andata a buon fine
		$totRicariche = mysqli_fetch_assoc($ricariche);
	}else{
		$totRicariche['TotRicariche'] = 0;
	}

	$query = "SELECT SUM(ImportoTot) AS TotOrdini FROM RigheOrdini R INNER JOIN Ordini O ON R.CodOrdine = O.CodOrdine WHERE O.EmailUtente = '".$emailSaldo."'";

	$ordini = mysqli_query($conn, $query);

	$nRows = mysqli_num_rows($ordini);//salvo il numero di righe della risposta

	if($nRows=1){//controllo che la ricerca sia andata a buon fine
		$totOrdini = mysqli_fetch_assoc($ordini);
	}else{
		$totOrdini['TotOrdini'] = 0;
	}

	return number_format($totRicariche['TotRicariche'] - $totOrdini['TotOrdini'],2);

?>