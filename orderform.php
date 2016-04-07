<!-- Displays a form required to place an order. Also processes the input of that form and inserts data in the database --!>

<?php
$title = "Place Order";
include('header.php');
include('navbar.php');

//check if logged in
$userid = $_SESSION['login_userID'];
$priceErr = "Approximate Price is required";
$orderErr = "Order details are required";
$timeErr = "Time Limit is Required";

$userAdd = $userLat = $userLng = $restName = $restAdd = $restLat = $restLng = $price = $time = $order = $des = "";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$userAdd = $_POST['UserAdd'];
	$userLat = $_POST['userLat'];
	$userLng = $_POST['userLng'];	
	$restName = $_POST['RestName'];
	$restAdd = $_POST['RestAdd'];
	$restLat = $_POST['restLat'];
	$restLng = $_POST['restLng'];
	if(!(empty($_POST['price']))){
		$price = test_input($_POST['price']);
		$priceErr = "";
	}
	if(!(empty($_POST['orderDetails']))){
		$order = test_input($_POST['orderDetails']);
		$orderErr = "";
	}
	if(!(empty($_POST['time']))){
		$time = test_input($_POST['time']);
		$timeErr = "";
	}
	$des = test_input($_POST['description']);
	if($priceErr == "" && $timeErr == "" && $orderErr == ""){
		//echo $userid;
		$sql1 = "SELECT AddressID FROM Address WHERE AddressString = '$userAdd'";
		$query1 = mysqli_query($con,$sql1);
		$user_address_id = 0;
		if(mysqli_num_rows($query1) > 0){
			$row = mysqli_fetch_assoc($query1);
			$user_address_id = $row['AddressID'];
			//echo $user_address_id; 
		}
		if(!$user_address_id){
			$sql2 = "INSERT INTO Address VALUES(DEFAULT,'$userAdd','$userLat','$userLng')";
			$query2 = mysqli_query($con,$sql2);
			$user_address_id = mysqli_insert_id($con);
			//echo $user_address_id;
			$sql3 = "INSERT INTO UserAddress VALUES('$userid','$user_address_id',0,1)";
			$query3 = mysqli_query($con,$sql3);
		}
		else{
			$sql4 = "SELECT IS_CURRENT FROM UserAddress WHERE UserID = '$userid' AND AddressID = '$user_address_id'";
			$query4 = mysqli_query($con,$sql4);
			$row = mysqli_fetch_assoc($query4);
			$current = $row['IS_CURRENT'];
			//echo $current;
			if(!$current){
				$sql5 = "UPDATE UserAddress SET IS_CURRENT = 1 WHERE UserID = '$userid' AND AddressID = '$user_address_id'";
				$query5 = mysqli_query($con,$sql5);
			
			}
		
		}
		$sql6 = "SELECT AddressID FROM Address WHERE AddressString = '$restAdd'";
		$query6 = mysqli_query($con,$sql6);
		$rest_address_id = 0;
		if(mysqli_num_rows($query6) > 0){
			$row2 = mysqli_fetch_assoc($query6);
			$rest_address_id = $row2['AddressID'];
			//echo $rest_address_id;
		}
		if(!$rest_address_id){
			$sql7 = "INSERT INTO Address VALUES(DEFAULT,'$restAdd','$restLat','$restLng')";
			$query7 = mysqli_query($con,$sql7);
			$rest_address_id = mysqli_insert_id($con);
			//echo $rest_address_id;
		}
		date_default_timezone_set('Australia/Melbourne');
		$endTime = date("Y-m-d H:i:s",strtotime(sprintf('+%s minutes',$time)));
		$sql8 = "INSERT INTO DeliveryOrder VALUES(DEFAULT,'$userid','$user_address_id',NULL,'$restName','$rest_address_id','$price','$endTime',0,0,NULL,'$order','$des')";
		$query8 = mysqli_query($con,$sql8);
	}
	
}

if($query8){
	header("location: myorders.php");
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

?>

<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<fieldset>
  <legend class="text-center">Order Details</legend>
  
  <div class="form-group">
    <label class = "control-label col-lg-4">Your Address</label>
    <div class="col-lg-4">
      <input type="text" readonly="readonly" class="form-control" name="UserAdd" value="<?php echo $userAdd; ?>">
      <input type="hidden" name="userLat" value="<?php echo $userLat; ?>">
      <input type="hidden" name="userLng" value="<?php echo $userLng; ?>">
    </div>
  </div>
  
  <div class="form-group">
    <label class = "control-label col-lg-4">Restaurant Name</label>
    <div class="col-lg-4">
      <input type="text" readonly="readonly" class="form-control" name="RestName" value="<?php echo $restName; ?>">
    </div>
</div>

<div class="form-group">
    <label class = "control-label col-lg-4">Restaurant Address</label>
    <div class="col-lg-4">
      <input type="text" readonly="readonly" class="form-control" name="RestAdd" value="<?php echo $restAdd; ?>">
      <input type="hidden" name="restLat" value="<?php echo $restLat; ?>">
      <input type="hidden" name="restLng" value="<?php echo $restLng; ?>">
    </div>
</div>


<div class="form-group">
    <label class = "control-label col-lg-4">Expected Price</label>
    <div class="col-lg-4">
		<div class="input-group"> 
		  <span class="input-group-addon">$</span>  
		  <input type="number" class="form-control" name="price" min="0" value="<?php echo $price; ?>">
		</div>  
	    <span class="error"> <?php echo $priceErr;?></span>
	    <span class="help-block">Your driver will pay for the food at the restaurant. You will have to pay them the exact amount of the food + a delivery charge of $7 at your doorstep. In case of any problems, contact FeedMe customer service at 1800-345-567.</span>
    </div>
</div>

<div class="form-group">
    <label class = "control-label col-lg-4">Acceptance Time limit</label>
    <div class="col-lg-4">
    	<div class="input-group">
      <input type="number" class="form-control" name="time" min = "1" placeholder = "Time limit in Minutes" value="<?php echo $time; ?>">
      <span class="input-group-addon">mins</span> 
      </div>
      <span class="error"> <?php echo $timeErr;?></span>
      <span class="help-block">Your order request will expire in these many minutes if not accepted by someone.</span>
    </div>
</div>

<div class="form-group">
    <label class = "control-label col-lg-4">Order Details</label>
    <div class="col-lg-4">
    	<textarea class="form-control" rows="3" name="orderDetails" placeholder="Food item, Quantity &#13;&#10;(Cheeseburger, 2)"><?php echo $order; ?></textarea> 
    	<span class="error"> <?php echo $orderErr;?></span>   
    </div>
</div>

<div class="form-group">
    <label class = "control-label col-lg-4">Description</label>
    <div class="col-lg-4">
    	<textarea class="form-control" rows="3" name="description" placeholder="Special instructions for the driver (if any)"><?php echo $des; ?></textarea>    
    </div>
</div>

<div class="form-group">
  <div class="col-lg-8 col-lg-offset-4">
	<input type="submit" value = "Submit" id = "sub" class="btn btn-primary">
  </div>  
</div> 


