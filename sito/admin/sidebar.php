<?php session_start();?>
<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
  <a class="link-white" href="../index.php"><span class="w3-bar-item w3-right" style="text-transform: uppercase;"><?php $configSito = require '../configSito.php'; echo $configSito['nome'];?></span></a>
</div>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
  <?php if(isset($_SESSION['permessi'])){
    if($_SESSION['permessi']>0){?>
      <div class="w3-container w3-row">
        <div class="w3-col s12 w3-bar">
          <span>Welcome, <strong><?php echo $_SESSION['nome'];?></strong></span><br>
          <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
          <a href="account.php" class="w3-bar-item w3-button"><i class="fa fa-user"></i></a>
          <a href="logout.php" class="w3-bar-item w3-button"><i class="fas fa-sign-out-alt"></i></a>
        </div>
      </div>
    <?php }
  }?>
  <hr>
  <div class="w3-container">
    <h5>Dashboard</h5>
  </div>
  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    <a href="ins_ricariche.php" class="w3-bar-item w3-button w3-padding"><i class="fas fa-money-bill-wave"></i>  Effettua una ricarica</a>
    <a href="register.php" class="w3-bar-item w3-button w3-padding"><i class="fas fa-user-plus"></i>  Registra Operatore</a>
    <a href="ins_alimenti.php" class="w3-bar-item w3-button w3-padding"><i class="fas fa-hamburger"></i>  Aggiungi un Alimento</a>
    <a href="elenco_alimenti.php" class="w3-bar-item w3-button w3-padding"><i class="fas fa-list-ul indentato"></i>  Elenco Alimenti</a>
    <a href="ins_classi.php" class="w3-bar-item w3-button w3-padding"><i class="fas fa-graduation-cap"></i>  Aggiungi una Classe</a>
    <a href="elenco_classi.php" class="w3-bar-item w3-button w3-padding"><i class="fas fa-list-ul indentato"></i>  Elenco Classi</a>
    <a href="ins_categorie.php" class="w3-bar-item w3-button w3-padding"><i class="fas fa-utensils"></i>  Aggiungi una Categoria</a>
    <a href="elenco_categorie.php" class="w3-bar-item w3-button w3-padding"><i class="fas fa-list-ul indentato"></i>  Elenco Categorie</a>
    <a href="ins_momenti.php" class="w3-bar-item w3-button w3-padding"><i class="far fa-clock"></i>  Aggiungi un Momento</a>
    <a href="elenco_momenti.php" class="w3-bar-item w3-button w3-padding"><i class="fas fa-list-ul indentato"></i>  Elenco Momenti</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i>  Settings</a><br><br>
  </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>