<?php
	
	session_start();
	if((!isset($_SESSION['email'])||!isset($_SESSION['password']))&&!isset($_GET['password'])&&!isset($_GET['email'])){
		header("Location: index.php");
	}

	if(isset($_GET['password'])&&isset($_GET['email'])){

		require_once '../configDB.php';

		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

		if(!$conn){
			echo '<script>alert("Errore nell\'accesso al database");</script>';
		}else{

			$password = htmlspecialchars(mysqli_real_escape_string($conn,$_GET['password']));
			$email = htmlspecialchars(mysqli_real_escape_string($conn,$_GET['email']));
			
			$query = "SELECT Password, Nome FROM Operatori WHERE Email = '".$email."' AND Permessi = 0";
			$result1 = mysqli_query($conn,$query);

			if(mysqli_num_rows($result1)>0){
				$account = mysqli_fetch_assoc($result1);
				if(password_verify($password, $account['Password'])){
					$query = "UPDATE Operatori
								SET Permessi = 1
								WHERE Email = '".$email."'";
					$result2 = mysqli_query($conn,$query);

					if($result2){
						echo '<script>alert("Attivazione dell\'account completata"); location.replace("index.php");</script>';
						session_destroy();
						session_start();
						$_SESSION['email'] = $email;
						$_SESSION['nome'] = $account['Nome'];
						$_SESSION['permessi'] = 1;
						echo '<script>alert("Attivazione dell\'account completata"); location.replace("index.php");</script>';
					}else{
						echo '<script>alert("Errore nell\'attivazione dell\'account"); location.replace("index.php");</script>';
					}
				}else{
					echo '<script>alert("Errore nell\'attivazione dell\'account"); location.replace("index.php");</script>';
				}
			}else{
				echo '<script>alert("Errore nell\'attivazione dell\'account"); location.replace("index.php");</script>';
			}
		}

		mysqli_close($conn);

	}else{

	    $destinatario = $_SESSION['email'];//email del destinatario
	    $nomeDestinatario = $_SESSION['nome'];//(opzionale) compare il nome del destinatario nell'email
		$password = $_SESSION['password'];

		//richiamo le classi per l'invio della mail
	    require '../phpmailer/PHPMailerAutoload.php';
	    require '../phpmailer/class.phpmailer.php';
	    require '../phpmailer/class.smtp.php';

	    $configSito = require '../configSito.php';    

	    $mail = new PHPMailer;//istanzio un oggetto di classe PHPMailer

	    //impostazioni protocollo SMTP
	    $mail->isSMTP(); 							
	    $mail->Host = $configSito['serverSMTP']; 				        // SMTP server
	    $mail->SMTPAuth = true;                                         //imposto l'autenticazione SMTP 						                    
	    $mail->Username = $configSito['email']; 	                    // email del mittente
	    $mail->Password = $configSito['password']; 				        // passoword dell'account del mittente
	    $mail->Port = 587;                                              //specifico la porta smtp in relazione al protocollo
	    $mail->SMTPSecure = 'tls';                                      //specifico il protocollo da usare
	    $mail->setFrom($configSito['email'],$configSito['nome']);   	//imposto il mittente e il nome
	    $mail->addAddress($destinatario,$nomeDestinatario);             //aggiungo l'email del destinatario	
	                    

	    //impostazioni dell'email
	    $mail->isHTML(true);                                            //invio l'email in formato HTML
	    $mail->Subject = "Conferma Email";			                    //imposto l'oggetto della mail
	                                                                    //imposto il corpo dell'email
	    $mail->Body = 'Ciao '.$nomeDestinatario.',<br>Le tue credenziali da operatore sono:<br><br>Username: \''.$destinatario.'\'<br>Password: \''.$password.'\'<br><br>clicca il link sottostante per confermare la tua mail e attivare il tuo account da operatore<br>
	                  <a href="'.$configSito['indirizzo'].'/admin/confirmationEmail.php?password='.$password.'&email='.$destinatario.'">Conferma la tua email</a>';

	    if(!$mail->send()){
	        //errore nell'invio
	        echo '<script>alert("Qualcosa è andato storto. Riprova più tardi!"); location.replace("register.php");</script>';
	    }else{
	        //email inviata
	        echo '<script>alert("E\' stata inviata una mail per la conferma dell\'account a '.$destinatario.'"); location.replace("register.php");</script>';
	    }
	
	}

?>