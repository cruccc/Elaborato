<?php

	session_start();
	if(isset($_SESSION['permessi'])){//controllo se l'utente è registrato dato che solamente ad un utente non registrato è consentito registrarsi
		header("Location:index.php");//se l'utente è registrato lo rimando alla pagina principale
	}
	require_once '../configDB.php';

	if(isset($_POST['invia'])){

		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

  		if($conn){

			$email = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['email']));
			$password = htmlspecialchars(mysqli_real_escape_string($conn,$_POST['password']));

  			$query = "SELECT Email, Password, Permessi FROM Operatori WHERE Email = '".$email."'";

  			$login = mysqli_query($conn, $query);

  			if(mysqli_num_rows($login)==0){//controllo che la ricerca sia andata a buon fine
  				echo '<script>alert("Username o password errati"); location.replace("login.php");</script>';
  			}else{

				$account = mysqli_fetch_assoc($login);

				if($account['Permessi']!=1){
					echo '<script>alert("Questo account deve essere confermato tramite la mail di conferma che abbiamo inviato"); location.replace("login.php");</script>';
				}else{
					if(!password_verify($password, $account['Password'])){
						echo '<script>alert("Username o password errati"); location.replace("login.php");</script>';
					}else{
						$_SESSION['email'] = $email;
						$_SESSION['permessi'] = $account['Permessi'];
						header("Location: index.php");
					}
				}
  			}
  			mysqli_close($conn);
  		}
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
			  	<form action="login.php" method="post">

		  			<h1 style="margin-bottom: 30px">Accedi</h1>
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

				</form>
			</div>

	      	<!-- End page content -->
	    </div>
  	</body>
</html>