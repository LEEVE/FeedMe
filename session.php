<!-- Creates a session for every user and stores userid in the session--!>
<?php

// Selecting Database
$con = mysqli_connect("mysql6.000webhost.com","a3987683_feedme","welovefood321","a3987683_feedme");
// Check connection

if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


session_start();// Starting Session

// Storing Session
$user_check = $_SESSION['login_user'];

// SQL Query To Fetch Complete Information Of User
$ses_sql = mysqli_query($con, "SELECT UserName, UserID From User where UserName='$user_check'");
$row = mysqli_fetch_array($ses_sql);

$login_session = $row['UserName'];
$login_session_ID = $row['UserID'];

if(!isset($login_session)){
mysqli_close($con); // Closing Connection
header('Location: home.php'); // Redirecting To Home Page
}
?>