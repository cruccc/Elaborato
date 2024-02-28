<?php
	if(isset($_POST['cancella'])){
		if(file_exists($_POST['path'])){
			if(is_dir($_POST['path'])){
				if(!rmdir($_POST['path'])) {
					echo '<script>alert("Errore nella cancellazione della cartella, path: '.$_POST['path'].'"); location.replace("tool_cancella.php");</script>';
				}else{
					echo '<script>alert("Cancellazione della cartella avvenuta con successo, path: '.$_POST['path'].'"); location.replace("tool_cancella.php");</script>';
				}
			}else{
				if(!unlink($_POST['path'])) {
					echo '<script>alert("Errore nella cancellazione del file, path: '.$_POST['path'].'"); location.replace("tool_cancella.php");</script>';
				}else{
					echo '<script>alert("Cancellazione del file avvenuta con successo, path: '.$_POST['path'].'"); location.replace("tool_cancella.php");</script>';
				}
			}
		}else{
			echo '<script>alert("Il file o cartella non esiste, path: '.$_POST['path'].'"); location.replace("tool_cancella.php");</script>';
		}
	}
?>

<form method="post" enctype="multipart/form-data">
	<label for="path">Inserire il path del file/cartella da cancellare</label>
	<input type="text" name="path" id="path" required>
	<input type="submit" name="cancella" id="cancella" value="Cancella">
</form>