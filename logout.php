<!-- Process user's request to logout--!>
<?php
session_start();
if(session_destroy()) // Destroying All Sessions
{
header("Location: home.php"); // Redirecting To Home Page
}
?>