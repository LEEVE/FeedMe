<!-- Logs in a User--!>

<?php


session_start(); // Starting Session
$error=''; // Variable To Store Error Message

if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password'])) {
$error = "Username or Password is invalid";
}
else
{
// Define $username and $password
$username=$_POST['username'];
$password=$_POST['password'];


// Establishing Connection with Server by passing server_name, user_id and password as a parameter
// Selecting Database
$con = mysqli_connect("mysql6.000webhost.com","a3987683_feedme","welovefood321","a3987683_feedme");
// Check connection

if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// To protect MySQL injection for Security purpose
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysqli_real_escape_string($con, $username);
$password = mysqli_real_escape_string($con, $password);

// SQL query to fetch information of registerd users and finds user match.
$query = mysqli_query($con, "Select * from User where Password= PASSWORD('$password') AND UserName='$username'");
$rows = mysqli_num_rows($query);
$row = mysqli_fetch_assoc($query);

if ($rows == 1) {
$_SESSION['login_user']=$row['UserName']; // Initializing Session
$_SESSION['login_userID']=$row['UserID'];
header("location: home.php"); // Redirecting To Other Page
} else {
$error = "Username or Password is invalid";
}
mysqli_close($con); // Closing Connection
}
if($error == "Username or Password is invalid"){
	header("location: loginForm.php?error");
	}
}
?>
