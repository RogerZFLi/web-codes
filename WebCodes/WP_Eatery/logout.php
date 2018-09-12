<?php 
session_start();
$sid=session_id();

session_destroy();
unset($_SESSION);
header("Location: userlogin.php"); 
?>