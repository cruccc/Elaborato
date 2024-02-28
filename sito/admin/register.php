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

	if(isset($_POST['invia'])){

		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

		if(!$conn){
			echo '<script>alert("Errore nell\'accesso al database");</script>';
		}else{
			$email1 = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['email1']));
			$email2 = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['email2']));
			$nome = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['nome']));
			$cognome = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['cognome']));
			
			$flag = controllaEmail($email1,$email2,$dbhost,$dbusername,$dbpassword,$dbname);
			if($flag==0){
				$password = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
				$hash = password_hash($password, PASSWORD_BCRYPT);
				$nome = strtoupper($nome);
				$cognome = strtoupper($cognome);
				$query = "INSERT INTO Operatori (Email,Nome,Cognome,Password,Permessi) 
						VALUES ('".$email1."', '".$nome."', '".$cognome."', '".$hash."', '0');";
				$result1 = mysqli_query($conn,$query);
				
				if(!$result1){
					echo '<script>alert("Errore: '.mysqli_error($conn).'"); location.replace("register.php");</script>';
				}else{
					$_SESSION['email'] = $email1;
					$_SESSION['nome'] = $nome;
					$_SESSION['password'] = $password;
					header("Location: confirmationEmail.php");
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

	function controllaEmail($email1,$email2,$dbhost,$dbusername,$dbpassword,$dbname){
		if($email1==$email2){
			if(strlen($email1)>=5){
				if(strlen($email1)<=150){
					$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

			  		if($conn){

			  			$query = "SELECT Stato FROM Utenti WHERE Email = '".$email1."'";

			  			$controlloEmail1 = mysqli_query($conn, $query);

			  			if(mysqli_num_rows($controlloEmail1)<1){//controllo che la ricerca sia andata a buon fine

			  				$query = "SELECT Permessi FROM Operatori WHERE Email = '".$email1."'";

			  				$controlloEmail2 = mysqli_query($conn, $query);

			  				if(mysqli_num_rows($controlloEmail2)<1){//controllo che la ricerca sia andata a buon fine
			  					return 0;
			  				}else{
			  					echo '<script>alert("Esiste già un account operatore con questa email"); location.replace("regsiter.php");</script>';
								return 5;
			  				}
			  			}else{
							echo '<script>alert("Esiste già un account studente con questa email"); location.replace("regsiter.php");</script>';
							return 4;
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
	          	<form action="register.php" method="post">

	            	<h1 style="margin-bottom: 30px">Registra un nuovo operatore</h1>
            		<div class="row">

              			<div class="col-sm-5">
                			<div class="w3-container">
                  				<label for="email1"><p>Email:</p></label>
                			</div>
              			</div>
              			<div class="col-sm-7">
                			<div class="w3-container">
                  				<input type="email" name="email1" id="email1" maxlength="150" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="prova@esempio.com" onkeyup="controlloAjaxEmail('../');" required><br><small id="msgemail1"></small><br>
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
                  				<input type="email" name="email2" id="email2" maxlength="150" onkeyup="controlloAjaxEmail('../');" required><br><br>
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

              			<div class="col-sm-12">
                			<div class="w3-container">
                  				<h5 style="text-align: center; margin-top: 10px"><input class="submit-button" style="width: 140px" type="submit" name="invia" id="invia" value="Registra" onclick="controlloSubmitOperatori()"></h5>
                			</div>
              			</div>

            		</div>
	        	</form>
	      	</div>

	      	<!-- End page content -->
	    </div>
  	</body>
</html>