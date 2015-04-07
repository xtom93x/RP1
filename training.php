<?php
session_start();
include("_funkcie.php");
hlavicka("Cvičisko"); 
if (isset($_SESSION['user'])) {  
  include("osobny.php");
}else include("login.php");
  echo "<div class='hlavny'><main>\n";
  {?>
    <a href='sluzby.php'><div class='entry_left'>
      <figure><img class='img_small' src="obr/ikona_kalendar.png" alt="Služby"></figure>
      <h1>Služby</h1>
    </div></a>
    <a href='testy.php'><div class='entry_right'>
      <figure><img class='img_small' src="obr/ikona_kalendar.png" alt="Testy"></figure>
      <h1>Testy</h1>
    </div></a>
  <?php
  }
  echo "</main></div>";  
  paticka();
?>