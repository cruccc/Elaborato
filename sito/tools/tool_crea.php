<?php
	if(isset($_POST['crea'])){
		if(!is_dir($_POST['path'])){
			if(mkdir($_POST['path'])) {
				echo '<script>alert("Creazione della cartella avvenuta con successo, path: '.$_POST['path'].'"); location.replace("tool_crea.php");</script>';
			}else{
				echo '<script>alert("Errore nella creazione della cartella, path: '.$_POST['path'].'"); location.replace("tool_crea.php");</script>';
			}
		}else{
			echo '<script>alert("Esiste già una cartella con lo stesso path, path: '.$_POST['path'].'"); location.replace("tool_crea.php");</script>';
		}
	}
?>

<form method="post" action="tool_crea.php">
	<label for="path">Inserire il path della cartella da creare</label>
	<input type="text" name="path" id="path" required>
	<input type="submit" name="crea" id="crea" value="Crea">
</form>