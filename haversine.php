<!-- Calculates distance between two latitude,longitude points
Assumes earth's radius = 6371Km --!>
<?php

function haversine($lat1, $lon1, $lat2, $lon2){
	
	$deltaLat = deg2rad($lat2 - $lat1);
	$deltaLong = deg2rad($lon2 - $lon1);
	
	$a = pow(sin($deltaLat/2), 2) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * pow(sin($deltaLong/2), 2));
	$c = 2 * atan2(sqrt($a),sqrt(1-$a));
	
	return $c * 6371;
}
?>