<?php

	session_start();
	
	$_SESSION['email'] = null;
	$_SESSION['logged'] = false;
	$_SESSION['permessi'] = 0;
	session_destroy();

	header("Location: login.php");

?>