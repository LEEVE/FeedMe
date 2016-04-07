<!-- Displays all orders placed by the user, and its details --!>
<?php
include('header.php');

//userid of logged in customer
$logged_in_user = $_SESSION['login_userID'];

date_default_timezone_set('Australia/Melbourne');
$cur_time = date("Y-m-d H:i:s");
$orderid = $_POST['orderid'];

//when customer confirms order is delivered
$sql4 = "UPDATE DeliveryOrder
		 SET OrderComplete = 1, TimeDelivered = '$cur_time'
		 WHERE OrderID = '$orderid'";
$query4 = mysqli_query($con, $sql4);

//all orders and its details placed by customer 
$sql = "SELECT * FROM DeliveryOrder INNER JOIN Address
		ON DeliveryOrder.RestaurantAddressID=Address.AddressID
		WHERE CustomerID = '$logged_in_user'
		ORDER BY OrderID DESC";



$result_array = array();
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
    	$row_array = array();    	
		array_push($row_array, $row["RestaurantName"]);
		array_push($row_array, $row["AddressString"]);

		array_push($row_array, $row["Price"]);
		//array_push($row_array, $row["TimeOrdered"]);
		array_push($row_array, $row["AcceptTimeLimit"]);
		$driverID = $row["DriverID"];
		$orderaccept = $row["OrderAccept"];
		array_push($row_array, $row["OrderAccept"]);
		array_push($row_array, $row["OrderComplete"]);
		array_push($row_array,$row["OrderID"]);
		array_push($row_array, $row["OrderDetails"]);
		if($orderaccept == "1"){
			$sql1 = "SELECT * FROM User WHERE UserID = '$driverID'";
			$result1= mysqli_query($con, $sql1);
			$row1 = mysqli_fetch_assoc($result1);
			$driverId = $row1['UserID'];
			$fname = $row1['FirstName'];
			$lname = $row1['LastName'];
			$phone = $row1['Phone'];
			array_push($row_array, $fname);
			array_push($row_array, $lname);
			array_push($row_array, $phone);
		}
		
		array_push($result_array, $row_array);
		} 
	 
}
	    	      
?>
<script src="JS/timer.js" type="text/javascript"></script>	
</head>
	
<body>
	
<?php
include('navbar.php');
?>
		
<?php $i = 0; foreach($result_array as $row): ?>
	<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<div class="well">
		<h3><?php echo $row[0]; ?></h3>
	
		<table class="table">
		<tr>
			<td>Restaurant Address:</td>
			<td><?php echo $row[1]; ?></td>
		</tr>
	
		<tr>
			<td>Price:</td>
			<td>$<?php echo $row[2]; ?></td>
		</tr>
		<tr>
			<td>Order Details:</td>
			<td><?php echo $row[7]; ?></td>
		</tr>
		<?php if($row[4] == "1") : ?>
			<tr>
				<td>Accepted by</td>
				<td><p class="text-success"><?php echo $row[8]; ?>&nbsp;<?php echo $row[9]; ?></p></td>
			</tr>
			<tr>
				<td>Contact them at :</td>
				<td><p class="text-success"><?php echo $row[10]; ?></p></td>
			</tr>
			<tr>
				<td>Order Status:</td>
				<?php if($row[5] == "1") : ?>
					<td><p class="text-success">Completed</p></td>
					</tr>
				<?php else : ?>
					<td><p class="text-info">On its way</p></td>
					<input type="hidden" name="orderid" value="<?php echo $row[6]; ?>">
					<td><input type="submit" value = "Click here when your order is delivered" class="btn btn-success"></td>
					</tr>
			<?php endif; ?>
		<?php else : ?>
			<?php 
				date_default_timezone_set('Australia/Melbourne'); 
				if(time() > strtotime($row[3])) : ?>
			<tr>
				<td>Order Status:</td>
				<td><p class="text-danger">Expired</p></td>
			</tr>
			<?php else : ?>
				<tr>
					<td>Expires in: </td>
					<td>
					<?php 
						date_default_timezone_set('Australia/Melbourne');
						$exp = strtotime(sprintf("-%s hours %s minutes %s seconds",date("H"),date("i"),date("s")),strtotime($row[3]));
					?>
					<input type="hidden" value="<?php echo date("d",strtotime($row[3]));?>" id="<?php echo day,$i ;?>">
					<input type="hidden" value="<?php echo date("H",strtotime($row[3]));?>" id="<?php echo hr,$i ;?>">
					<input type="hidden" value="<?php echo date("i",strtotime($row[3]));?>" id="<?php echo min,$i ;?>">
					<input type="hidden" value="<?php echo date("s",strtotime($row[3]));?>" id="<?php echo sec,$i ;?>">
					<div id="<?php echo timer,$i ;?>">
						<?php echo $row[3] ;?>
						<script type="text/javascript">
							var i = "<?php echo $i; ?>";
							startTimer(i,document.querySelector('#timer'+ i));
						</script>
						</td>
					</div>
				<td></td>
			</tr>
			<?php endif; ?>
		<?php endif; ?>
		</table>
	</div>
	</form>
	<?php $i += 1; ?>
	<?php endforeach; ?>