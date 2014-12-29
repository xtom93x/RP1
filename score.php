<?php
session_start();
include("_funkcie.php");
hlavicka("Rytierská sála");
if (isset($_SESSION['user'])) include('osobny.php');
else include('login.php');
echo "<div class='hlavny'><main>\n";
$diverg=false;
$by='body';
if (isset($_GET['by'])) $by=$_GET['by'];
else {
  $diverg=true;
}
if (isset($_GET['diverg'])) $diverg=$_GET['diverg'];
vypis_score($by,$diverg);
echo "</main></div>\n"; 
paticka();  
?>