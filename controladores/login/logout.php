<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session. 
session_unset();
session_destroy();
 
// Redirect to login page
header("location: ../../vistas/login/loginYregistro.php");
exit;
?>