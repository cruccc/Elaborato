// Accordion 
function myAccFunc() {
  	var x = document.getElementById("demoAcc");
  	if (x.className.indexOf("w3-show") == -1) {
    	x.className += " w3-show";
  	} else {
    	x.className = x.className.replace(" w3-show", "");
  	}
}

// Click on the "Jeans" link on page load to open the accordion for demo purposes
document.getElementById("myBtn").click();


// Open and close sidebar
function w3_open() {
  	document.getElementById("mySidebar").style.display = "block";
  	document.getElementById("myOverlay").style.display = "block";
}
 
function w3_close() {
  	document.getElementById("mySidebar").style.display = "none";
  	document.getElementById("myOverlay").style.display = "none";
}

function controlloAjaxEmail(path){//funzione che richiede al server il controllo della mail
	var xhttp1 = new XMLHttpRequest();//istanzio l'oggetto che permette di gestire le richieste ajax
	var flag = true;//flag che indica se il controllo è andato a buon fine
	var messaggio;//stringa che contiene il messaggio da stampare nel caso di errore

	flag = controlloEmail();

	if(flag==0){//se il campo ha passato il primo controllo
		xhttp1.onreadystatechange = function() {//funzione che gestisce la lo stato della risposta e la risposta
			if (this.readyState == 4 && this.status == 200) {//controllo che lo stato della risposta sia 4 e che sia stata elaborata con successo
				if(this.responseText == "0"){//controllo se la risposta del server è '0' (controllo superato con successo)
					controlloGiusto("email1");//se il controllo è stato superato con successo rendo verde il campo
					controlloGiusto("email2");
				}else{//altrimenti la risposta del server è '1' (controllo non superato)
					controlloSbagliato("email1");//se il controllo non è stato superato con successo rendo rosso il campo e stampo un messaggio di errore
					controlloSbagliato("email2");
					document.getElementById("msgemail1").innerHTML = "Questa email è già utilizzata in un altro account";
				}
			}
		};
	  	xhttp1.open("POST", path+"controlliAjax.php", true);//imposto il tipo di richiesta(POST o GET e sincrona(false) o asincrona(true)) e la pagina da interrogare
	  	xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");//imposto l'header della richiesta in POST
	  	xhttp1.send("nomeDato=Email&valDato="+document.getElementById("email1").value);//invio la richiesta passando due parametri. 'nomeDato' indica di che campo si vuole fare il controllo('Username' o 'Email') e 'valDato' è il valore scelto dall'utente per quel campo.
	}else{
		if(flag==1){
			messaggio = "L'email non è nel formato richiesto (es. prova@gmail.com)";
		}else{
			messaggio = "Le due email non coincidono";
			controlloSbagliato("email2");
		}
		controlloSbagliato("email1");//se il campo non supera il primo controllo rendo rosso il campo e stampo un messaggio di errore
		document.getElementById("msgemail1").innerHTML = messaggio;
	}
}

function controlloEmail(){//funzione che controlla il formato della mail
    // verifico se è un indirizzo valido
    if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById("email1").value))){
		return 1;//email !ok
    }else if(document.getElementById("email1").value!=document.getElementById("email2").value){
    	return 2;//email !ok
    }else{
		return 0;//email ok
    }
}

function controlloPassword(){//funzione che controlla il formato della pasword e che le due password(password e conferma password) coincidano
	if (!(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test(document.getElementById("password1").value))){//controllo che il formato della password sia giusto
		controlloSbagliato("password1");//se non è giusto rendo rosso il campo e stampo un messaggio di errore
		controlloSbagliato("password2");
		document.getElementById("msgpassword1").innerHTML = "Deve contenere almeno una lettera maiuscola, una lettera minuscola e un numero, deve essere di almeno 8 caratteri";
		return false;
    }else if(document.getElementById("password1").value != document.getElementById("password2").value){//controllo che le due password(password e conferma password) coincidano
    	controlloSbagliato("password1");//se non coincidono rendo rosso il campo e stampo un messaggio di errore
    	controlloSbagliato("password2");
    	document.getElementById("msgpassword1").innerHTML = "Le password non coincidono";
		return false;
    }
	controlloGiusto("password1");//se sono stati superati tutti i controlli rendo verdi i campi
	controlloGiusto("password2");
	return true;
}

function controlloNome(){//funzione che controlla il formato del nome
	if((document.getElementById("nome").value.length<1)||(document.getElementById("nome").value.length>50)){
    	controlloSbagliato("nome");
		document.getElementById("msgnome").innerHTML = "Il nome non è nel formato richiesto(1-50 caratteri)";
		return false;//nome !ok
	}
	controlloGiusto("nome");
	return true;//nome ok
}

function controlloCognome(){//funzione che controlla il formato del nome
	if((document.getElementById("cognome").value.length<1)||(document.getElementById("cognome").value.length>50)){
    	controlloSbagliato("cognome");
		document.getElementById("msgcognome").innerHTML = "Il cognome non è nel formato richiesto(1-50 caratteri)";
		return false;//cognome !ok
	}
	controlloGiusto("cognome");
	return true;//cognome ok
}

function controlloData(){//funzione che controlla il formato della data
	if((document.getElementById("dataNascita").value<"01/01/1900")||(document.getElementById("dataNascita").value>"31/12/2021")){
    	controlloSbagliato("dataNascita");
		document.getElementById("msgdataNascita").innerHTML = "Questa non è una data valida";
		return false;//data !ok
	}
	controlloGiusto("dataNascita");
	return true;//data ok
}

function controlloSubmit(){
	if(document.getElementById("msgpassword1")!=null){
		if((document.getElementById("msgemail1").innerHTML=="")&&(document.getElementById("msgcognome").innerHTML=="")&&(document.getElementById("msgpassword1").innerHTML=="")&&(document.getElementById("msgnome").innerHTML=="")&&(document.getElementById("msgdataNascita").innerHTML=="")){
			document.getElementById("invia").disabled = false;
		}
	}else{
		if((document.getElementById("msgemail1").innerHTML=="")&&(document.getElementById("msgcognome").innerHTML=="")&&(document.getElementById("msgnome").innerHTML=="")){
			document.getElementById("invia").disabled = false;
		}
	}
}

function controlloGiusto(idTag){//funzione che passatogli un id di un campo lo rende verde, riabilita nel caso fosse stato disabilitato il pulsante di invio e cancella eventuali messaggi di errore
	document.getElementById(idTag).style.borderColor = "green";
	document.getElementById(idTag).style.background = "#a3ffac";
	if(document.getElementById("msg"+idTag)!=null) document.getElementById("msg"+idTag).innerHTML = "";
	controlloSubmit();
}

function controlloSbagliato(idTag){//funzione che passatogli un id di un campo lo rende rosso e disabilita il pulsante di invio
	document.getElementById(idTag).style.borderColor = "red";
	document.getElementById(idTag).style.background = "#ffc8c8";
	document.getElementById("invia").disabled = true;
}

function aggiungiCarrello(codice){
	var xhttp2 = new XMLHttpRequest();//istanzio l'oggetto che permette di gestire le richieste ajax

		xhttp2.onreadystatechange = function() {//funzione che gestisce la lo stato della risposta e la risposta
			if (this.readyState == 4 && this.status == 200) {//controllo che lo stato della risposta sia 4 e che sia stata elaborata con successo
				var messaggio;
				switch(this.responseText){
					case '0': messaggio = "Articolo aggiunto al carrello"; break;
					case '1': messaggio = "Errore nell'aggiunta dell'articolo al carrello"; break;
					case '2': messaggio = "Devi accedere per aggiungere articoli al carrello"; break;
				}
				alert(messaggio);
				if(this.responseText == '2'){
					location.replace("login.php");
				}
			}
		};
	  	xhttp2.open("POST", "aggiungiCarrello.php", true);//imposto il tipo di richiesta(POST o GET e sincrona(false) o asincrona(true)) e la pagina da interrogare
	  	xhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");//imposto l'header della richiesta in POST
	  	xhttp2.send("codice="+codice);//invio la richiesta passando due parametri. 'nomeDato' indica di che campo si vuole fare il controllo('Username' o 'Email') e 'valDato' è il valore scelto dall'utente per quel campo.
}