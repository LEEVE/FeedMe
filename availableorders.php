<!-- Displays all available orders to drivers --!>
<?php
include('header.php');
include('haversine.php');

$driverID = $_SESSION['login_userID'];
$driver_lat = $_POST['driverLat'];
$driver_lon = $_POST['driverLng'];
$radius = $_POST['DriverRadius'];



$result_array = array();

$acceptID = $_POST['orderid'];
$sql4 = "UPDATE DeliveryOrder
		 SET OrderAccept = 1, DriverID = '$driverID'
		 WHERE OrderID = '$acceptID'";
$query4 = mysqli_query($con, $sql4);
	
	

/*
Get details of all unaccepted orders 
that fall in the logged in driver's radius and display them
*/
$sql = "SELECT * FROM DeliveryOrder 
		WHERE (OrderAccept = 0) OR (OrderAccept = 1 AND DriverID = '$driverID')
		ORDER BY OrderID DESC";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
    	$orderID = $row['OrderID'];
		$restName = $row['RestaurantName']; $price = $row['Price']; $orderTime = $row['TimeOrdered'];
		$timeLimit = $row['AcceptTimeLimit']; $det = $row['OrderDetails']; $des = $row['Description'];
		$accept = $row['OrderAccept'];
		
		//For each unaccepted order, get address details of restaurant
		$sql1 = "SELECT RestaurantAddressID, AddressString, Latitude, Longitude FROM
		 DeliveryOrder INNER JOIN Address 
		 ON DeliveryOrder.RestaurantAddressID = Address.AddressID
		 WHERE OrderID = '$orderID'";
		$result1 = mysqli_query($con, $sql1);
		$row1 = mysqli_fetch_assoc($result1);
		$restAdd = $row1['AddressString']; $restLat = $row1['Latitude']; $restLon = $row1['Longitude'];	
	 	date_default_timezone_set('Australia/Melbourne');
	 	
	 	//For each unaccepted order, that hasnt expired and is within radius, get address details of customer and restaurant
	 	
		if(((haversine($restLat, $restLon, $driver_lat, $driver_lon) <= $radius) && (time() < strtotime($timeLimit))) || $accept == 1 ){	 
		 	$sql2 = "SELECT CustomerAddressID, AddressString, Latitude, Longitude FROM
			 DeliveryOrder INNER JOIN Address 
			 ON DeliveryOrder.CustomerAddressID = Address.AddressID
			 WHERE OrderID = '$orderID'";
			$result2 = mysqli_query($con, $sql2);
			$row2 = mysqli_fetch_assoc($result2);
			$custAdd = $row2['AddressString']; $custLat = $row1['Latitude']; $custLon = $row1['Longitude'];
		
			if((haversine($custLat, $custLon, $driver_lat, $driver_lon) <= $radius) || ($accept == 1)){
				if($accept == 0){
					$submit = "Accept";
					$submit_class = "btn btn-info";
				}
				else{
					$submit = "Accepted by you";
					$submit_class = "btn btn-success";
				}
				array_push($result_array, array($restName, $restAdd, $det, $price, $orderTime, $timeLimit, $des, $orderID, $custAdd,$submit,$submit_class,$accept));	
			}
		}	
    }
}  
		 
?>
<script src="JS/timer.js" type="text/javascript"></script>	

</head>

<body>

<?php
include('navbar.php');
?>
<?php $i = 0;  foreach($result_array as $row): ?>
<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<input type="hidden" name="driverLat" value= "<?php echo $driver_lat; ?>">
<input type="hidden" name="driverLng" value= "<?php echo $driver_lon; ?>">
<input type="hidden" name="DriverRadius" value= "<?php echo $radius; ?>">
<div class="well">
<button type="button" class="close" data-dismiss="alert">x</button>
<h3><?php echo $row[0];?></h3>

<table class="table">
<tr>
<td>Restaurant Address:</td>
<td><?php echo $row[1];?></td>
</tr>

<tr>
<td>Customer Address:</td>
<td><?php echo $row[8];?></td>
</tr>

<tr>
<td>Order Details: </td>
<td><?php echo $row[2];?></td>
</tr>

<tr>
<td>Price:</td>
<td>$<?php echo $row[3];?></td>
</tr>

<tr>
<td>Expires in: </td>
<?php if($row[11] == 0) :?>
<td>
<input type="hidden" value="<?php echo date("d",strtotime($row[5]));?>" id="<?php echo day,$i ;?>">
<input type="hidden" value="<?php echo date("H",strtotime($row[5]));?>" id="<?php echo hr,$i ;?>">
<input type="hidden" value="<?php echo date("i",strtotime($row[5]));?>" id="<?php echo min,$i ;?>">
<input type="hidden" value="<?php echo date("s",strtotime($row[5]));?>" id="<?php echo sec,$i ;?>">
<div id="<?php echo timer,$i ;?>">
<?php echo $row[5] ;?>
<script type="text/javascript">
var i = "<?php echo $i; ?>";
startTimer(i,document.querySelector('#timer'+ i));
</script>
</td>
<?php else : ?>
<td><p class="text-danger">Expired</p></td>
<?php endif; ?>
</tr>

<tr>
<td>Special Wants: </td>
<td><p class="text-info"><?php echo $row[6];?></p></td>
</tr>
</table>

<input type="hidden" name="orderid" value="<?php echo $row[7]; ?>">
<input type="submit" value = "<?php echo $row[9]; ?>" id = "sub" class="<?php echo $row[10]; ?>">
</div>
</form>
<?php endforeach; $i += 1; ?>

	