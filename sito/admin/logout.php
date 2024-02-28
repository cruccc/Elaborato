<?php

	session_start();
	
	$_SESSION['email'] = null;
	$_SESSION['permessi'] = 0;
	$_SESSION['logged'] = false;
	session_destroy();

	header("Location: ../login.php");

?>