<?php
	
	session_start();
	if((!isset($_SESSION['email'])||isset($_SESSION['logged'])||!isset($_SESSION['codice_attivazione']))&&!isset($_GET['codice_attivazione'])&&!isset($_GET['email'])){
		header("Location: index.php");
	}

	if(isset($_GET['codice_attivazione'])&&isset($_GET['email'])){

		require_once 'configDB.php';

		$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

		if(!$conn){
			echo '<script>alert("Errore nell\'accesso al database");</script>';
		}else{

			$codice_attivazione = htmlspecialchars(mysqli_real_escape_string($conn,$_GET['codice_attivazione']));
			$email = htmlspecialchars(mysqli_real_escape_string($conn,$_GET['email']));
			
			$query = "UPDATE Utenti
						SET Stato = 1
						WHERE Email = '".$email."' AND CodAttivazione = '".$codice_attivazione."'";
			$result = mysqli_query($conn,$query);

			if($result){
				echo '<script>alert("Attivazione dell\'account completata"); location.replace("index.php");</script>';
				session_destroy();
				session_start();
				$_SESSION['email'] = $email;
				$_SESSION['logged'] = true;
				echo '<script>alert("Attivazione dell\'account completata"); location.replace("index.php");</script>';
			}else{
				echo '<script>alert("Errore nell\'attivazione dell\'account"); location.replace("index.php");</script>';
			}
		}

		mysqli_close($conn);

	}else{

	    $destinatario = $_SESSION['email'];//email del destinatario
	    $nomeDestinatario = $_SESSION['nome'];//(opzionale) compare il nome del destinatario nell'email
		$codice_attivazione = $_SESSION['codice_attivazione'];

		//richiamo le classi per l'invio della mail
	    require 'phpmailer/PHPMailerAutoload.php';
	    require 'phpmailer/class.phpmailer.php';
	    require 'phpmailer/class.smtp.php';

	    $configSito = require 'configSito.php';    

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
	    $mail->Body = 'Ciao '.$nomeDestinatario.',<br>clicca il link per confermare la tua mail<br>
	                  <a href="'.$configSito['indirizzo'].'/confirmationEmail.php?codice_attivazione='.$codice_attivazione.'&email='.$destinatario.'">Conferma la tua email</a>';

	    if(!$mail->send()){
	        //errore nell'invio
	        echo '<script>alert("Qualcosa è andato storto. Riprova più tardi!"); location.replace("register.php");</script>';
	    }else{
	        //email inviata
	        echo '<script>alert("E\' stata inviata una mail per la conferma dell\'account a '.$destinatario.'"); location.replace("register.php");</script>';
	        $mex = "E' stata inviata una mail per la conferma dell'account a ".$destinatario;
	    }
	
	}

?>