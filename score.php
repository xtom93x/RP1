<?php
session_start();
include("_funkcie.php");
hlavicka("Rytierská sála");
if (isset($_SESSION['user'])) include('osobny.php');
else include('login.php');
echo "<div class='hlavny'><main>\n";
$diverg=true;
$by='body';
if (isset($_GET['by'])) {
  $by=$_GET['by'];
  $diverg=false;
}
if (isset($_GET['diverg'])) $diverg=true;
vypis_score($by,$diverg);
echo "</main></div>\n"; 
paticka();  
?>