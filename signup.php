 <!-- Sign up form. Also processes inputs from the form and inserts data in the database --!>
 
 <?php

$title = "Enter details";
include('header.php');

// define variables and set to empty values
$firstnameErr = $lastnameErr = $emailErr = $usernameErr = $passwordErr = $phoneErr = "";
$firstname = $lastname = $email = $username = $password = $phone = "";

$address = $lat = $long = "";
$addressErr = "";

$driver = $radius = "";
$driverErr = $radiusErr = "";

//Pre populate form if query string is not empty i.e says edit
if ($_SERVER["QUERY_STRING"] != ""){
  $logged_in_user = $_SESSION['login_user'];

  $result = mysqli_query($con, "SELECT * FROM User Where UserName = '$logged_in_user'");
  $row = mysqli_fetch_row($result);

  $firstname = $row[1]; $lastname = $row[2]; $email = $row[5]; $phone = $row[6];
  
  $result = mysqli_query($con, "SELECT AddressString FROM Address NATURAL JOIN UserAddress NATURAL JOIN User 
  Where UserName = '$logged_in_user'");
  $row = mysqli_fetch_row($result);
	
  $address = $row[0];  	
  
}


if ($_SERVER["REQUEST_METHOD"] == "POST"){

   if (empty($_POST["username"])) {
     $usernameErr = "User Name is required";
   } else {
   		$username = test_input($_POST["username"]);
        //Check if the entered username is already in the database or not
   		$sql = "SELECT * FROM User WHERE UserName = '$username'";
   		$query = mysqli_query($con,$sql);
   		$num_rows = mysqli_num_rows($query);
   		if($num_rows > 0){
       		$usernameErr = "Sorry, that User Name is already taken";
    	}   
   }

   if (empty($_POST["password"]) ) {
     $passwordErr = "Password is required";
   } else if($_POST["password"] != $_POST["password2"]){
   	  $passwordErr = "Passwords don't match!";
   	}else{	
     $password = test_input($_POST["password"]);
   }

   if (empty($_POST["firstname"])) {
     $firstnameErr ="First Name is required";
   } else {
     $firstname = test_input($_POST["firstname"]);
     // check if name only contains letters and whitespace
     if (!preg_match("/^[a-zA-Z ]*$/",$firstname)) {
       $firstnameErr = "Only letters and white space allowed"; 
     }
   }
   
    if (empty($_POST["lastname"])) {
     $lastnameErr = "Last Name is required";
   } else {
     $lastname = test_input($_POST["lastname"]);
     // check if name only contains letters and whitespace
     if (!preg_match("/^[a-zA-Z ]*$/",$lastname)) {
       $lastnameErr = "Only letters and white space allowed"; 
     }
   }
   
   if (empty($_POST["email"])) {
     $emailErr = "Email is required";
   } else {
     $email = test_input($_POST["email"]);
     // check if e-mail address is well-formed
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $emailErr = "Invalid email format"; 
     }
   }
   
    if (empty($_POST["phone"])) {
     $phoneErr = "Phone Number is required";
   } 
   else if(!is_numeric($_POST["phone"])){
   	 $phoneErr = "Please enter a valid phone number";
   }else{	
     $phone = test_input($_POST["phone"]);
   } 
   
    if (empty($_POST["address"])) {
     $addressErr = "Address is required";
   } else {
     $address = test_input($_POST["address"]);
     $lat = $_POST["cityLat"];
     $long = $_POST["cityLng"];
   }
    
    if(empty($_POST["driver"])){
    	$driverErr = "Please select Yes/No";
    }else{
    	$driver = $_POST["driver"];
    	if($driver == "yes"){
    		if(empty($_POST["radius"])){
    			$radiusErr = "Please enter a radius";
    		}
    		else{
    			$radius = test_input($_POST["radius"]);
    		}
    	}
    	else{
    		$radius = 0;
    	}
    }


//Insert if there was no query string i.e new user trying to sign up
if($_SESSION["QUERY_STRING"] == ""){

  //SQL queries to perform the insert operations
  $sql1 = "INSERT INTO User Values (DEFAULT, '$firstname', '$lastname', '$username', PASSWORD('$password'), 
  '$email','$phone')";
  $sql2 = "INSERT INTO Address Values (DEFAULT, '$address', '$lat', '$long')";

  
  //Insert ONLY if there were no errors anywhere
  if ($firstnameErr == "" && $lastnameErr == "" && $usernameErr == "" && $passwordErr == "" 
  			&& $emailErr == "" && $phoneErr == "" && $addressErr == ""){
    $query1 = mysqli_query($con, $sql1);
    if (!$query1){
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }else{ 
    	$UserID = mysqli_insert_id($con);
    	$sql4 = "INSERT INTO Customer Values ('$UserID')";
    	$query4 = mysqli_query($con,$sql4);
  		if(!$query4){
  			echo "Error: " . $sql . "<br>" . mysqli_error($con);
  		}
  		
  		if($radius){
  			$sql5 = "INSERT INTO Driver Values ('$UserID', '$radius')";
  			$query5 = mysqli_query($con, $sql5);
  			if(!$query5){
  				echo "Error: " . $sql . "<br>" . mysqli_error($con);
  			}
  		}
    }
    
    $query2 = mysqli_query($con, $sql2);
    if (!$query2){
    echo "Error: " . $sql2 . "<br>" . mysqli_error($con);
    }else{
    $AddressID = mysqli_insert_id($con);
    }
    //Insert only if the above 2 queries ran succesfully
  	if($query1 && $query2){
  		$sql3 = "INSERT INTO UserAddress Values ('$UserID', '$AddressID', 1,1)";
		$query3 = mysqli_query($con, $sql3);
	 	if (!$query3){
	   		echo "Error: " . $sql3 . "<br>" . mysqli_error($con);
	 	}
  	}
  }
}else{
//Otherwise update the existing records

  //Update only if there were no errors
  if ($firstnameErr == "" && $lastnameErr == "" && $usernameErr == "" && $passwordErr == "" && 
  $emailErr == "" && $phoneErr == ""){
  
  	$sql1 = "UPDATE User
  			 SET FirstName = '$firstname', LastName = '$lastname', Email = '$email', Phone = '$phone'
  			 WHERE UserName = '$logged_in_user'";
    $query1 = mysqli_query($con, $sql1);
    if (!$query1){
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }	
  }	
  $result = mysqli_query("SELECT UserID From User Where UserName = '$logged_in_user'");
  $row = mysqli_fetch_row($result); 
  $UserID = $row[0];
  
  $result1 = mysqli_query("UPDATE UserAddress 
  						   SET IS_DEFAULT = 0
  						   WHERE UserID = '$UserID' AND IS_DEFAULT = 1");
  
  $sql2 = "INSERT INTO Address Values (DEFAULT, '$address', '$lat', '$long')";
  if($addressErr == ""){
    $query2 = mysqli_query($con, $sql2);
    if (!$query2){
    echo "Error: " . $sql2 . "<br>" . mysqli_error($con);
    }else{
    $AddressID = mysqli_insert_id($con);
    }
  }
  $sql3 = "INSERT INTO UserAddress Values ('$UserID', '$AddressID', 1,1)";
  //Insert only if the above 2 queries ran succesfully
  if($query1 && $query2){
	 $query3 = mysqli_query($con, $sql3);
	 if (!$query3){
	   	echo "Error: " . $sql3 . "<br>" . mysqli_error($con);
	 }
  }	
} 			 

  

  //Redirect to login (home) page if everything was inserted successfully 
  //doesnt work
if($query1 && $query2 && $query3){
    header("location: loginForm.php?signedup");
  }  
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}


?>

<script>
$(document).ready(function () {
    $(".text").hide();
    $("#yes").click(function () {
        $(".text").show();
    });
    $("#no").click(function () {
        $(".text").hide();
    });
});
</script>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>

<script type="text/javascript">
    function initialize() {
        var input = document.getElementById('addressAutocomplete');
        var options = {
        	componentRestrictions: {country: 'au'}
        };
        
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            document.getElementById('cityLat').value = place.geometry.location.lat();
            document.getElementById('cityLng').value = place.geometry.location.lng();

        });
    }
    google.maps.event.addDomListener(window, 'load', initialize); 
</script>
</head>

<body onload="initialize()">

<?php include ('navbar.php') ?>
<form class = "form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
	<?php if ($_SESSION['login_user'] == "") : ?>
	
   <legend class = "text-center">Please fill in the details below:</legend>
   	
	<div class = "form-group">   	
   	<label class = "control-label col-lg-4">Username</label>
   		<div class = "col-lg-4">
   			<input class = "form-control" type="text" name="username">
   			<span class="error"> <?php echo $usernameErr;?></span>
   		</div>
   	</div>
   
   	<div class = "form-group">   	
   	<label class = "control-label col-lg-4">Password</label>
   		<div class = "col-lg-4">
   			<input class = "form-control" type="password" name="password">
   		</div>
   	</div>
   	
   	<div class = "form-group">   	
   	<label class = "control-label col-lg-4">Confirm password</label>
   		<div class = "col-lg-4">
   			<input class = "form-control" type="password" name="password2">
   			<span class="error"> <?php echo $passwordErr;?></span>
   		</div>
   	</div>   	
  	<br/><br/>
   <?php else : ?>
   
   <legend class = "text-center">Please update your details below:</legend>
   
   <?php endif; ?>
   
   	<div class = "form-group">   	
   	<label class = "control-label col-lg-4">First Name</label>
   		<div class = "col-lg-4">
      		<input class = "form-control" type="text" name="firstname" value="<?php echo $firstname;?>">
      		<span class="error"><?php echo $firstnameErr;?></span>
   		</div>
   	</div>
   	
   	<div class = "form-group">   	
   	<label class = "control-label col-lg-4">Last Name</label>
   		<div class = "col-lg-4">
      		<input class = "form-control" type="text" name="lastname" value="<?php echo $lastname;?>">
      		<span class="error"> <?php echo $lastnameErr;?></span>
   		</div>
   	</div>
   	<br/>

   	<div class = "form-group">   	
   	<label class = "control-label col-lg-4">Email</label>
   		<div class = "col-lg-4">
   			<input class = "form-control" type="text" placeholder="abc@example.com" name="email" value="<?php echo $email;?>">
   			<span class="error"> <?php echo $emailErr;?></span>
   		</div>
   	</div>

   	<div class = "form-group">   	
   	<label class = "control-label col-lg-4">Phone Number</label>
   		<div class = "col-lg-4">
   			<input class = "form-control" type="text" name="phone" value="<?php echo $phone;?>">
   			<span class="error"><?php echo $phoneErr;?></span>
   		</div>
   	</div>


   	<div class = "form-group">   	
   	<label class = "control-label col-lg-4">Address</label>
   		<div class = "col-lg-4">
   			<input class = "form-control" id="addressAutocomplete" type="text" name="address"  
   					value = "<?php echo $address ?>" placeholder="Enter a location" autocomplete="on" runat="server" />  
   			<input type="hidden" id="cityLat" name="cityLat" />
   			<input type="hidden" id="cityLng" name="cityLng" />  
      		<span class="error"><?php echo $addressErr;?></span>
   		</div>
   	</div>
	<br/>

   	<div class = "form-group">   	
   	<label class = "control-label col-lg-4">Would you like to deliver?</label>
   		<div class = "col-lg-4">
   			<div class = "radio"><label>
   				<input type="radio" name="driver" id="yes" value="yes" onClick="driver()">
   				Yes
   			</label></div>	
   			
   			<div class = "radio"><label>
    			<input type="radio" name="driver" id="no" value="no">
    			No
    		</label></div>
    		<br/>
   			<span class="error"><?php echo $driverErr;?></span>
   		</div>
   	</div>

    <div class = "form-group">   	
  		<div class="text">
  			<label class = "control-label col-lg-4">Enter a delivery radius (Can be changed later)</label>
  			<div class = "col-lg-4">
  				<div class="input-group">
        			<input class = "form-control" type="number" name="radius" id="radius" value ="<?php echo $radius;?>">
        			<span class="input-group-addon">km</span>	   
        			<span class=error"><?php echo $radiusErr;?></span>
        		</div>
  			</div>
   		</div>
   	</div> 

    <div class="form-group">
      <div class="col-lg-8 col-lg-offset-4">
        <input type="submit" value = "Submit" class="btn btn-primary">
      </div>  
    </div>  
   
</form>
</body>
</html>

