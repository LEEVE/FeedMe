<!-- Home page of the website. Logged in and not logged in users can view this page
Customers can view if a driver is available to deliver to their address
Drivers can view if there are any orders they can accept  --!>
<?php
$title = "FeedMe";
include('header.php');
?>
<script>
//smooth scrolling between div's
$(document).ready(function(){
	$('a[href^="#"]').on('click',function (e) {
	    e.preventDefault();

	    var target = this.hash;
	    var $target = $(target);

	    $('html, body').stop().animate({
	        'scrollTop': $target.offset().top
	    }, 900, 'swing', function () {
	        window.location.hash = target;
	    });
	});
});
</script>
<script src="JS/home.js" type="text/javascript"></script>
</head>

<body>
<!-- background image --!>
<div class="image" id = "top">
</div>
<?php 
include ('navbar.php');
?>
<div class="heading" id="top">
<h1>On Demand, 24-7</h1>
<h3>Get Food delivered from Anywhere at Anytime !</h3>
<br/>
<a href="#homecontent" class="btn btn-info col-lg-4 col-lg-offset-4" role="button">Get started</a>
</div> 

<!-- Order, Deliver Tabs with Google Address Autocomplete --!>
<div class="homecontent" id="homecontent">
<div class = "col-lg-2 col-lg-offset-5">
<ul class="nav nav-pills" id="myTab">
  <li class="active"><a href="#order" data-toggle="tab" aria-expanded="true">Order</a></li>
  <li class=""><a href="#deliver" data-toggle="tab" aria-expanded="false">Deliver</a></li>
</ul>
</div>

<br/><br/><br/><br/>

<!--Content to be displayed when one of the tabs is selected-->
<div class = "tab-content">

<!--Displayed when the 'Order' tab is selected (default)-->
	<div class = "tab-pane fade in active" id = "order">
	<!--The actual form-->
		<form class = "form-horizontal" name = "myForm" action = "orderform.php" method = "post">
			<div class = "col-lg-4 col-lg-offset-4">
				<div id="step1" class="steps">
					<img src = "Images/Step1.png"></img>
					<p><b>Enter your Delivery Address</b></p>
				</div>
				<div class = "form-group">
				<input class = "form-control" name="UserAdd" id="UserLocn" type="text" placeholder="Enter your address"> 
				<input type="hidden" id="userLat" name="userLat"><input type="hidden" id="userLng" name="userLng">
				</div>
			</div>
			<!-- Dynamic content retrived from home-ajax.php --!>
			<div id="txtHint"></div>
		<br/><br/><br/>
	</div>
	
	
<!--Displayed when the 'Deliver' tab is selected-->
	</form>
	<div class = "tab-pane fade" id = "deliver">
	<!--The actual form-->
		<form class = "form-horizontal" name = "myForm" action = "availableorders.php" method = "post">
			<div class = "col-lg-4 col-lg-offset-4">
				<div id="step1" class="steps">
					<img src = "Images/Step1.png"></img>
					<p><b>Enter your Address and Radius (in km)</b></p>
				</div>
				<div class = "form-group">
					<input class = "form-control" name = "DriverAddress" id="DriverLocn" type="text" placeholder="Enter your location"> 
					<input type="hidden" id="driverLat" name="driverLat"><input type="hidden" id="driverLng" name="driverLng"> 
				</div>
				<div class = "form-group input-group">
					<input class = "form-control" type="text" name="DriverRadius" id ="radius" placeholder="Enter your radius">
					<span class="input-group-addon">km</span>
				</div>
			</div>
			<!-- Driver cannot view orders until logged in --!>
			<?php if($_SESSION['login_user'] == "") : ?>
			<a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-success col-lg-4 col-lg-offset-4" role="button">Let's go</a>
			<?php else : ?>
			<div class="col-lg-4 col-lg-offset-4">
			<input class = "form-control btn btn-success" type = "submit" value = "Let's go">
			</div>
			<?php endif; ?>
		</form>

		<br/><br/><br/>
	</div>
</div>
</body>
