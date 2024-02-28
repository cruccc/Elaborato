<?php

	session_start();
	if(isset($_SESSION['logged'])){//controllo se l'utente è registrato dato che solamente ad un utente non registrato è consentito registrarsi
		if($_SESSION['logged']){
			header("Location:index.php");//se l'utente è registrato lo rimando alla pagina principale
		}
	}else{
		if(isset($_SESSION['permessi'])){
	    	if($_SESSION['permessi']!=0){
	    		header("Location: index.php");
	    	}
	  	}
	}
	require_once 'configDB.php';

	if(isset($_POST['invia'])){

		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

  		if($conn){

			$email = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['email']));
			$password = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['password']));

  			$query = "SELECT Email, Password, Stato FROM Utenti WHERE Email = '".$email."'";

  			$login = mysqli_query($conn, $query);

  			if(mysqli_num_rows($login)==0){//controllo che la ricerca sia andata a buon fine
  				$query = "SELECT Email, Password, Nome, Permessi FROM Operatori WHERE Email = '".$email."'";

  				$login1 = mysqli_query($conn, $query);

  				if(mysqli_num_rows($login1)==0){//controllo che la ricerca sia andata a buon fine
  					echo '<script>alert("Username o password errati"); location.replace("login.php");</script>';
  				}else{
  					$account = mysqli_fetch_assoc($login1);

  					if($account['Permessi']<1){
  						echo '<script>alert("Questo account deve essere confermato tramite la mail di conferma che abbiamo inviato"); location.replace("login.php");</script>';
  					}else{
  						if(!password_verify($password, $account['Password'])){
							echo '<script>alert("Username o password errati"); location.replace("login.php");</script>';
						}else{
							session_destroy();
							session_start();
							$_SESSION['email'] = $email;
							$_SESSION['nome'] = $account['Nome'];
							$_SESSION['permessi'] = $account['Permessi'];
							header("Location: admin/index.php");
						}
  					}
  				}
  			}else{

				$account = mysqli_fetch_assoc($login);

				if($account['Stato']!=1){
					echo '<script>alert("Questo account deve essere confermato tramite la mail di conferma che abbiamo inviato"); location.replace("login.php");</script>';
				}else{
					if(!password_verify($password, $account['Password'])){
						echo '<script>alert("Username o password errati"); location.replace("login.php");</script>';
					}else{
						$_SESSION['email'] = $email;
						$_SESSION['logged'] = true;
						header("Location: index.php");
					}
				}
  			}
  			mysqli_close($conn);
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
		<div class="w3-main spazio-sotto" style="margin-left:250px">

		  	<!-- Push down content on small screens -->
			<div class="w3-hide-large" style="margin-top:83px"></div>

		  	<div class="container-fluid">
			  	<form action="login.php" method="post">

		  			<h1 style="margin-bottom: 30px">Accedi</h1>
					<div class="container-form">
						<div class="row">

							<div class="col-sm-5">
								<div class="w3-container">
									<label for="email"><p>Email:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="email" name="email" id="email" maxlength="150" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="prova@esempio.com" required><br><br>
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-5">
								<div class="w3-container">
									<label for="password"><p>Password:</p></label>
								</div>
							</div>
							<div class="col-sm-7">
					      		<div class="w3-container">
									<input type="password" name="password" id="password" maxlength="50" required><br><br>
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-12">
					      		<div class="w3-container">
									<h5 style="text-align: center; margin-top: 10px"><input class="submit-button" style="width: 120px" type="submit" name="invia" id="invia" value="Accedi"></h5>
								</div>
							</div>

						</div>
						<div class="row">

							<div class="col-sm-12">
					      		<div class="w3-container" style="text-align: right;">
									<hr>
									<p>Non hai un account?<br><a href="register.php">Registrati qui</a></p>
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