<?php

	session_start();
	if(isset($_SESSION['logged'])){
		if(!$_SESSION['logged']){
			header("Location: login.php");
		}
	}else{
		header("Location: login.php");
	}
	require_once 'configDB.php';

	$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

	if(!$conn){
		echo '<a href="#" class="w3-bar-item w3-button">Errore di accesso al database</a>';
	}else{

		$query = "SELECT Nome,Cognome,NomeClasse,DataNascita FROM Utenti WHERE Email = '".$_SESSION['email']."'";

		$accountResult = mysqli_query($conn, $query);

		$nRows = mysqli_num_rows($accountResult);//salvo il numero di righe della risposta

		if($nRows!=1){//controllo che la ricerca sia andata a buon fine
			echo '<script>alert("Account non trovato: '.mysqli_error($conn).'"); location.replace("index.php");</script>';
		}else{

			$account = mysqli_fetch_assoc($accountResult);

			$query = "SELECT SUM(Importo) AS TotRicariche FROM Ricariche WHERE EmailUtente = '".$_SESSION['email']."'";

			$ricariche = mysqli_query($conn, $query);

			$nRows = mysqli_num_rows($ricariche);//salvo il numero di righe della risposta

			if($nRows=1){//controllo che la ricerca sia andata a buon fine
				$totRicariche = mysqli_fetch_assoc($ricariche);
			}else{
				$totRicariche['TotRicariche'] = 0;
			}

			$query = "SELECT SUM(ImportoTot) AS TotOrdini FROM RigheOrdini R INNER JOIN Ordini O ON R.CodOrdine = O.CodOrdine WHERE O.EmailUtente = '".$_SESSION['email']."'";

			$ordini = mysqli_query($conn, $query);

			$nRows = mysqli_num_rows($ordini);//salvo il numero di righe della risposta

			if($nRows=1){//controllo che la ricerca sia andata a buon fine
				$totOrdini = mysqli_fetch_assoc($ordini);
			}else{
				$totOrdini['TotOrdini'] = 0;
			}

			$emailSaldo = $_SESSION['email']; $saldo = require 'getSaldo.php';

		}
		mysqli_close($conn);
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
		<div class="w3-main spazio-sotto" style="margin-left:250px">

		  	<!-- navbar -->
			<?php $navbar = "Account"; require "navbar.php";?>

		  	<div class="container-fluid">
			  	<form action="account.php" method="post">

		  			<h1 style="margin-bottom: 30px">Il tuo Account</h1>
					<div class="container-form" style="border-bottom: none;">
						<div class="row">

							<div class="col-sm-5">
								<div class="w3-container">
									<label for="email1"><p>Email:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="email" name="email1" id="email1" maxlength="150" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="prova@esempio.com" onkeyup="controlloAjaxEmail('email1');" <?php echo 'value="'.$_SESSION['email'].'"';?> readonly required><br><small id="msgemail1"></small><br>
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-5">
					      		<div class="w3-container">
									<label for="nome"><p>Nome:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="text" name="nome" id="nome" maxlength="50" onkeyup="controlloNome();" <?php echo 'value="'.$account['Nome'].'"';?> readonly required><br><small id="msgnome"></small><br>
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-5">
					      		<div class="w3-container">
									<label for="cognome"><p>Cognome:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="text" name="cognome" id="cognome" maxlength="50" onkeyup="controlloCognome();" <?php echo 'value="'.$account['Cognome'].'"';?> readonly required><br><small id="msgcognome"></small><br>
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-5">
					      		<div class="w3-container">
									<label for="classe"><p>Classe:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="text" name="classeDisplay" id="classeDisplay" <?php echo 'value="'.$account['NomeClasse'].'"';?> readonly required><br><br>
								</div>
							</div>
							<div class="col-sm-7" hidden>
					      		<div class="w3-container">
									<select id="classe" name="classe" style="width: 196px;height: 28px;" required>
										<?php
											$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);
											if(!$conn){
												echo '<option value="errore"><p>Errore di accesso al database</p></option>';
											}else{

												$query = "SELECT NomeClasse FROM Classi ORDER BY NomeClasse";

												$result3 = mysqli_query($conn, $query);

												$nRows = mysqli_num_rows($result3);//salvo il numero di righe della risposta

												if($nRows>0){

													for($i=0;$i<$nRows;$i++){//scorro le righe della selezione
														$classi = mysqli_fetch_assoc($result3);
														if($classi['NomeClasse']!=$account['NomeClasse']){
															echo '<option value="'.$classi["NomeClasse"].'"><p>'.$classi["NomeClasse"].'</p></option>';
														}else{
															echo '<option value="'.$classi["NomeClasse"].'" selected><p>'.$classi["NomeClasse"].'</p></option>';
														}
													}

												}else{
													echo '<option value="errore"><p>Nessuna classe trovata</p></option>';
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
									<label for="dataNascita"><p>Data di Nascita:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="text" name="dataDisplay" id="dataDisplay" <?php echo 'value="'.$account['DataNascita'].'"';?> readonly required><br><br>
								</div>
							</div>
							<div class="col-sm-7" hidden>
					      		<div class="w3-container">
									<input type="date" name="dataNascita" id="dataNascita" maxlength="50" style="width: 196px" <?php echo 'value="'.$account['DataNascita'].'"';?> readonly required><br><small id="msgdataNascita"></small><br>
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-5">
					      		<div class="w3-container">
									<label for="saldo"><p>Saldo:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="text" name="saldo" id="saldo" <?php echo 'value="'.$saldo.'"';?> readonly required><br><br>
								</div>
							</div>

						</div>
					</div>
				</form>

				<form action="logout.php" method="post">
					<div class="container-form" style="border-top: none;">
						<div class="row">
							<div class="col-sm-12">
					      		<div class="w3-container">
									<h5 style="text-align: center;"><input class="submit-button" style="width: 100px" type="submit" name="invia" id="invia" value="Esci"></h5>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>

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