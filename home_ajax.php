
<?php
include("header.php");
include("haversine.php");

//Customer delivery address latitude and longitude, gotten from Google's address autocompete
$user_lat = htmlspecialchars($_GET["lat"]);
$user_long = htmlspecialchars($_GET["lon"]);

//select all drivers
$sql1 = "SELECT DriverID,Latitude,Longitude,Radius 
			FROM Driver NATURAL JOIN User NATURAL JOIN UserAddress NATURAL JOIN Address 
				WHERE IS_DEFAULT = 1";

$result1 = mysqli_query($con,$sql1);
if (!$result1){
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
}
else{
	$driver_array = array();
	while($row = mysqli_fetch_assoc($result1)){			
		$driver_id = $row['DriverID'];
		$driver_lat = $row['Latitude'];
		$driver_long = $row['Longitude'];
		$radius = $row['Radius'];
		
		$dist = haversine($user_lat, $user_long, $driver_lat, $driver_long);
		
		if($dist <= $radius){
			array_push($driver_array, array($driver_id, $driver_lat, $driver_long, $radius));
		}
	}
}


echo '<div class = "col-lg-4 col-lg-offset-4">';
		
if (empty($driver_array)) {
	echo '<div class = "form-group">
		  <p class="text-danger">There are no drivers available to deliver to your location right now :(</p>
		  <p class="text-danger">Please check back later or choose another suburb.</p>
		  <p class="text-danger">You can still place an order request but there is a chance that no driver will accept it before it times out</p>
		  </div>';
}
echo sprintf('<input type = "hidden" name="num_driver" value="%s">',count($driver_array));
for($i=0; $i < count($driver_array); $i++){
	for($j = 0; $j < count($driver_array[0]); $j++){
	echo sprintf('<input type = "hidden" id = "drivers" name = "drivers[%s][%s]" value = "%s">', 
				  $i, $j, $driver_array[$i][$j]);			  
	}
}
echo '	<br/><br/><br/></div>
		<div class = "col-lg-3 col-lg-offset-3">
		<div id = "step2" class="steps">
			<img src = "Images/Step2.png"></img>
			<p><b>Pick a Restaurant</b></p>
		</div>
			<div class = "form-group" id="rest"><input class = "form-control" type="text" readonly="readonly" name = "RestName" id="RestName" placeholder="Restaurant Name" onchange="stoppedTyping()"></div>
		</div>
		<div class = "col-lg-3">
			<div class = "form-group"><input class = "form-control" type="text" readonly="readonly" name = "RestAdd" id="RestLocn" placeholder="Restaurant Address">
			<input type="hidden" name="restLat" id="restLat"><input type="hidden" name="restLng" id="restLng"></div>
		</div>
		<div class = "col-lg-8 col-lg-offset-2">
		<input id="search" class="controls" type="text" placeholder="   Search by name/address/cuisine/type ... etc  ">
		<div class = "form-group" id="map" style="width:100%;height:500px;"></div>
		</div>
		<div id="hide">
		<input type="text" name="flag" autofocus onfocus="initializeMap()">
		</div>';

if ($_SESSION['login_user'] == ""){
	echo '<a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-success col-lg-4 col-lg-offset-4" role="button">Let\'s Go</a>';
}
else{
	echo '<div class = "col-lg-4 col-lg-offset-4">
		  <input class = "form-control btn btn-success" type = "submit" id="submit" value = "Let\'s go"></div>
		  <br/><br/>';
}
echo   '</div>
		</form>';			
?>