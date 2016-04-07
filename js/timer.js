//Shows countdown of an order's expiry time
	function startTimer(index,display){
	var target_day = document.getElementById('day'+index).value;
	var target_hr = document.getElementById('hr'+index).value;
	var target_min = document.getElementById('min'+index).value;
	var target_sec = document.getElementById('sec'+index).value;
 	// variables for time units
	var days,hours, minutes, seconds;
 	// update the tag with id "countdown" every 1 second
	setInterval(function () {
 		// find the amount of "seconds" between now and target
    	var current = new Date();
    	var current_day = current.getDate();
    	var current_sec = current.getSeconds();
    	var current_min = current.getMinutes();
    	var current_hr = current.getHours();
    	var seconds_left = (target_day * 86400 - current_day * 86400) + (target_hr * 3600 - current_hr * 3600) + (target_min * 60 - current_min * 60) + (target_sec - current_sec);
    	days = parseInt(seconds_left/86400);
    	seconds_left = seconds_left % 86400;
    	hours = parseInt(seconds_left/3600);
    	seconds_left = seconds_left % 3600;
    	minutes = parseInt(seconds_left/60);
    	seconds_left = seconds_left % 60;
    	seconds = parseInt(seconds_left);
     	display.textContent = days + " : " + hours + " : " + minutes +" : " + seconds + "     (days : hours : minutes : seconds)";
 
	}, 1000);
}