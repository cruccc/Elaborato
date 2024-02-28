<?php

	session_start();
	require_once 'configDB.php';

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
		<div class="w3-main" style="margin-left:250px">

		  	<!-- navbar -->
			<?php require "navbar.php";?>

		  	<!-- Image header -->
		  	<div class="w3-display-container w3-container">
		    	<img src="/w3images/jeans.jpg" alt="Jeans" style="width:100%">
		    	<div class="w3-display-topleft w3-text-white" style="padding:24px 48px">
		      		<h1 class="w3-jumbo w3-hide-small">New arrivals</h1>
		      		<h1 class="w3-hide-large w3-hide-medium">New arrivals</h1>
		      		<h1 class="w3-hide-small">COLLECTION 2016</h1>
		      		<p><a href="#jeans" class="w3-button w3-black w3-padding-large w3-large">SHOP NOW</a></p>
		    	</div>
		  	</div>

		  	<div class="w3-container w3-text-grey" id="jeans">
		    	<p>8 items</p>
		  	</div>

		  	<!-- Product grid -->
		  	<div class="w3-row w3-grayscale">
		    	<div class="w3-col l3 s6">
		      		<div class="w3-container">
		        		<img src="/w3images/jeans1.jpg" style="width:100%">
		        		<p>Ripped Skinny Jeans<br><b>$24.99</b></p>
		      		</div>
		      		<div class="w3-container">
		        		<img src="/w3images/jeans2.jpg" style="width:100%">
		        		<p>Mega Ripped Jeans<br><b>$19.99</b></p>
		      		</div>
		    	</div>

		    	<div class="w3-col l3 s6">
		      		<div class="w3-container">
		        		<div class="w3-display-container">
		          			<img src="/w3images/jeans2.jpg" style="width:100%">
		          			<span class="w3-tag w3-display-topleft">New</span>
		          			<div class="w3-display-middle w3-display-hover">
		            			<button class="w3-button w3-black">Buy now <i class="fa fa-shopping-cart"></i></button>
		          			</div>
		        		</div>
		        		<p>Mega Ripped Jeans<br><b>$19.99</b></p>
		      		</div>
			      	<div class="w3-container">
			        	<img src="/w3images/jeans3.jpg" style="width:100%">
			        	<p>Washed Skinny Jeans<br><b>$20.50</b></p>
			      	</div>
		    	</div>

		    	<div class="w3-col l3 s6">
		      		<div class="w3-container">
		        		<img src="/w3images/jeans3.jpg" style="width:100%">
		        		<p>Washed Skinny Jeans<br><b>$20.50</b></p>
		      		</div>
		      		<div class="w3-container">
		        		<div class="w3-display-container">
		          			<img src="/w3images/jeans4.jpg" style="width:100%">
		          			<span class="w3-tag w3-display-topleft">Sale</span>
		          			<div class="w3-display-middle w3-display-hover">
		            			<button class="w3-button w3-black">Buy now <i class="fa fa-shopping-cart"></i></button>
		          			</div>
		        		</div>
		        		<p>Vintage Skinny Jeans<br><b class="w3-text-red">$14.99</b></p>
		      		</div>
		    	</div>

		    	<div class="w3-col l3 s6">
		      		<div class="w3-container">
		        		<img src="/w3images/jeans4.jpg" style="width:100%">
		        		<p>Vintage Skinny Jeans<br><b>$14.99</b></p>
		      		</div>
		      		<div class="w3-container">
		        		<img src="/w3images/jeans1.jpg" style="width:100%">
		        			<p>Ripped Skinny Jeans<br><b>$24.99</b></p>
		      		</div>
		    	</div>
		  	</div>
		  
		  	<!-- Footer -->
		  	<?php require "footer.php";?>

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