<?php
	session_start();
	if(isset($_SESSION['logged'])){//controllo se l'utente è registrato dato che solamente ad un utente non registrato è consentito registrarsi
		if($_SESSION['logged']){
			header("Location:index.php");//se l'utente è registrato lo rimando alla pagina principale
		}
	}

	require_once 'configDB.php';

	if(isset($_POST['invia'])){

		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

		if(!$conn){
			echo '<script>alert("Errore nell\'accesso al database");</script>';
		}else{
			$password1 = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['password1']));
			$password2 = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['password2']));
			$email1 = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['email1']));
			$email2 = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['email2']));
			$nome = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['nome']));
			$cognome = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['cognome']));
			$dataNascita = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['dataNascita']));
			$classe = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['classe']));

			
			$flag = controllaEmail($email1,$email2,$dbhost,$dbusername,$dbpassword,$dbname);
			if($flag==0){
				if(controllaPassword($password1,$password2)==0){
					$password1 = password_hash($password1, PASSWORD_DEFAULT);
					$nome = strtoupper($nome);
					$cognome = strtoupper($cognome);
					$codice_attivazione = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
					$query = "INSERT INTO Utenti (Email,Nome,Cognome,DataNascita,Password,NomeClasse,Stato,CodAttivazione) 
							VALUES ('".$email1."', '".$nome."', '".$cognome."', '".$dataNascita."', '".$password1."', '".$classe."', '0', '".$codice_attivazione."');";
					$result1 = mysqli_query($conn,$query);
					
					if(!$result1){
						echo '<script>alert("Errore: '.mysqli_error($conn).'"); location.replace("register.php");</script>';
					}else{
						$_SESSION['email'] = $email1;
						$_SESSION['nome'] = $nome;
						$_SESSION['codice_attivazione'] = $codice_attivazione;
						header("Location: confirmationEmail.php");
					}
				}
			}elseif ($flag==5) {
				$_SESSION['email'] = $email1;
				$_SESSION['nome'] = $nome;
				echo '<script>
						if(confirm("Questa mail è già utilizzata in un account in stato di conferma,\nvuoi reinviare la mail di conferma?"){
							location.replace("confirmationEmail.php");
						}else{
							location.replace("register.php");
						}
					</script>';
			}
			
			mysqli_close($conn);
		}
	}

	function controllaPassword($password1,$password2){
		if($password1==$password2){
			if(strlen($password1)>=8){
				if(strlen($password1)<=50){
					return 0;
				}else{
					echo '<script>alert("La password inserita è troppo lunga (max 50 caratteri)"); location.replace("register.php");</script>';
					return 3;
				}
			}else{
				echo '<script>alert("La password inserita è troppo corta (min 8 caratteri): '.$password1.'"); location.replace("register.php");</script>';
				return 2;
			}
		}else{
			echo '<script>alert("Le due password inserite non coincidono"); location.replace("register.php");</script>';
			return 1;
		}
	}

	function controllaEmail($email1,$email2,$dbhost,$dbusername,$dbpassword,$dbname){
		if($email1==$email2){
			if(strlen($email1)>=5){
				if(strlen($email1)<=150){
					$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

			  		if($conn){

			  			$query = "SELECT Stato FROM Utenti WHERE Email = '".$email1."'";

			  			$controlloEmail = mysqli_query($conn, $query);

			  			if(mysqli_num_rows($controlloEmail)<1){//controllo che la ricerca sia andata a buon fine
			  				return 0;
			  			}else{

							$account = mysqli_fetch_assoc($controlloEmail);

							if($account['Stato']==0){
								return 5;
							}else{
								echo '<script>alert("Esiste già un account con questa email"); location.replace("regsiter.php");</script>';
								return 4;
							}

			  			}
			  			mysqli_close($conn);
			  		}
				}else{
					echo '<script>alert("L\'email inserita è troppo lunga (max 50 caratteri)"); location.replace("register.php");</script>';
					return 3;
				}
			}else{
				echo '<script>alert("L\'email inserita è troppo corta (min 8 caratteri)"); location.replace("register.php");</script>';
				return 2;
			}
		}else{
			echo '<script>alert("Le due email inserite non coincidono"); location.replace("register.php");</script>';
			return 1;
		}
		return 6;
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

			<!-- Push down content on small screens -->
			<div class="w3-hide-large" style="margin-top:83px"></div>

		  	<div class="container-fluid">
			  	<form action="register.php" method="post">

		  			<h1 style="margin-bottom: 30px">Registrati</h1>
					<div class="container-form">
						<div class="row">

							<div class="col-sm-5">
								<div class="w3-container">
									<label for="email1"><p>Email:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="email" name="email1" id="email1" maxlength="150" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="prova@esempio.com" onkeyup="controlloAjaxEmail('');" required><br><small id="msgemail1"></small><br>
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-5">
					      		<div class="w3-container">
									<label for="email2"><p>Conferma Email:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="email" name="email2" id="email2" maxlength="150" onkeyup="controlloAjaxEmail('');" required><br><br>
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-5">
					      		<div class="w3-container">
									<label for="password1"><p>Password:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="password" name="password1" id="password1" maxlength="50" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Deve contenere almeno una lettera maiuscola, una lettera minuscola e un numero, deve essere di almeno 8 caratteri" onkeyup="controlloPassword();" required><br><small id="msgpassword1"></small><br><!--viene richiamata la funzione controlloPassword() utilizzando l'evento onkeyup che si attiva ogni volta che l'utente rilascia un tasto della tastiera-->
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-5">
					      		<div class="w3-container">
									<label for="password2"><p>Conferma Password:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="password" name="password2" id="password2" maxlength="50" onkeyup="controlloPassword();" required><br><br><!--viene richiamata la funzione controlloPassword() utilizzando l'evento onkeyup che si attiva ogni volta che l'utente rilascia un tasto della tastiera-->
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
									<input type="text" name="nome" id="nome" maxlength="50" onkeyup="controlloNome();" required><br><small id="msgnome"></small><br>
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
									<input type="text" name="cognome" id="cognome" maxlength="50" onkeyup="controlloCognome();" required><br><small id="msgcognome"></small><br>
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
									<select id="classe" name="classe" style="width: 196px;height: 28px;">
										<?php
											$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);
											if(!$conn){
												echo '<option value="errore"><p>Errore di accesso al database</p></option>';
											}else{

												$query = "SELECT NomeClasse FROM Classi ORDER BY NomeClasse";

												$result3 = mysqli_query($conn, $query);

												$nRows = mysqli_num_rows($result3);//salvo il numero di righe della risposta

												if($nRows>0){

													echo '<option value="" selected disabled hidden>Scegli classe</option>';

													for($i=0;$i<$nRows;$i++){//scorro le righe della selezione
														$classe = mysqli_fetch_assoc($result3);
														echo '<option value="'.$classe["NomeClasse"].'"><p>'.$classe["NomeClasse"].'</p></option>';
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
									<input type="date" name="dataNascita" id="dataNascita" maxlength="50" style="width: 196px" required><br><small id="msgdataNascita"></small><br>
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-12">
					      		<div class="w3-container">
									<h5 style="text-align: center; margin-top: 10px"><input class="submit-button" style="width: 140px" type="submit" name="invia" id="invia" value="Registrati" onclick="controlloSubmitUtenti()"></h5>
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-12">
					      		<div class="w3-container" style="text-align: right;">
									<hr>
									<p>Hai già un account?<br><a href="login.php">Accedi qui</a></p>
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