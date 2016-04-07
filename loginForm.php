<!-- Login form on a seperate page--!>
<?php
include('header.php');
if($_SERVER['QUERY_STRING'] != ""){
	$error = $_GET['error'];
	echo $error;
	}
	
	$error1 = $_SERVER['QUERY_STRING'];




?>

</head>
<body>

<?php include('navbar.php'); ?>


<div class = "container">

	<div class="wrapper">
		<form action = "login.php" method = "post" name="Login_Form" class="form-signin">  
     
		    <h3 class="form-signin-heading">Sign in to FeedMe</h3>
			  <hr class="colorgraph"><br>
			  
			  <?php if($_SERVER['QUERY_STRING'] == "error") : ?>
			  <div class="alert alert-danger">
			  <p>Invalid username or password!</p></div>
			  <? elseif($_SERVER['QUERY_STRING'] == "signedup"): ?>
			  <div class="alert alert-success">
			  <p>You have successfully signed up! You may now login</p></div>
			  <?php endif; ?>		
			  
			  <div class = "form-group">
			  <input type="text" class="form-control" name="username" placeholder="Username" required="" autofocus="" />
			  </div>
			  
			  <div class = "form-group">
			  <input type="password" class="form-control" name="password" placeholder="Password" required=""/> 
			  <span class="help-block">Not registered?&nbsp;&nbsp;<a href="signup.php">Sign up!</a></span>
			  </div>
			  
			  <div class="form-group">				 
			  <input type = "submit" class="btn btn-primary btn-block" name = "submit" value = "Login" >
			  </div> 	
			  
			  		
		</form>			
	</div>
</div>