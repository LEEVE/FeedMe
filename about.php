<!-- Displays company's goals, and information about team members.
Includes a 3D carasouel as per Project Requirements --!>
<?php
$title = "About Us";
include('header.php')
?>
		
<link rel="stylesheet" href="CSS/about.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="JS/about.js"></script>
<script>
<!--

	$(function(){ $(".flipster").flipster({ style: 'carousel', start: 0 }); });

-->
</script>
</head>
<body>
<?php include('navbar.php');?>
<div id="Main-Content">
	<div class="Container">
	<h2 class="text-center">Hungry? We can help!</h3>
	<br/><br/>
<!-- Flipster List -->	
		<div class="flipster">
		  <ul>
			<li>
	  		<img src = "Images/about1.jpg" height="398" width="600">
	  		</li>
			
	  		<li>
	  		<img src = "Images/about2.jpg" height="398" width="600">
	  		</li>
	  		
	  		<li>
	  		<img src = "Images/about3.jpg" height="398" width="600">
	  		</li>
	  		
	  		<li>
	  		<img src = "Images/about4.jpg" height="398" width="600">
	  		</li>
	  		
	  		<li>
	  		<img src = "Images/about5.jpg" height="398" width="600">
	  		</li>
	  		
	  		
		  </ul>
		</div>
<!-- End Flipster List -->
	</div>
	<br/><br/><br/>
	<h3 class="text-center">About Us</h3>
	<h4 class="text-center">At FeedMe, our goal is to develop a community where anyone can get food to 
	anyone from anywhere at any time.</h4>
	<h3 class="text-center">How does it work?</h3>
	<h4 class="text-center">When you give us your address, we check for all the drivers that are
	currently online are willing to deliver to your location. We then provide you with a list of restaurants
	that you can order from. You can either choose from those options or can search by restaurant name, cuisine, dish, type etc.
	When you place an order request, it is made visible to all drivers that satisfy the criteria. The first one to accept it brings your 
	food to you. All you have to do then is sit and relax. In case of a problem, you can always directly contact your delivery guy or 
	contact FeedMe customer service at 1800-345-567.</h4>
	<br/><br/>
	<h3 class="text-center">The Team</h3>
	<br/><br/>
	<div class="row">
		<div class="col-md-4">
			<img src="Images/manan.jpg" class="img-circle" alt="Cinque Terre" width="300" height="250">
			<h2>Manan Ahuja</h2>
			<p>(Project Leader)</p>
			<p>Web, Front-end and Back-end developer</p>
			

		</div>
		
		<div class="col-md-4">
			<img src="Images/hinam.jpg" class="img-circle" alt="Cinque Terre" width="300" height="250">
			<h2>Hinam Mehra</h2>
			<p>Web, Front-end and Back-end developer</p>
		</div>
		
		<div class="col-md-4">
			<img src="Images/sumeet.jpg" class="img-circle" alt="Cinque Terre" width="300" height="250">
			<h2>Sumeet Rajpal</h2>
			<p>Web and Front-end developer</p>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6">
			<img src="Images/devang.jpg" class="img-circle" alt="Cinque Terre" width="300" height="250">
			<h2>Devang Sharma</h2>
			<p>Back-end developer and web designer</p>

		</div>
		<div class="col-md-6">
			<img src="Images/lucas.jpg" class="img-circle" alt="Cinque Terre" width="300" height="250">
			<h2>Lucas Brock</h2>
			<p>Web Developer</p>

		</div>
	</div>
</body>
</html>

