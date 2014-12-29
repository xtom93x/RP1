<?php
session_start();
include("_funkcie.php");
hlavicka("Knižnica"); 
if (isset($_SESSION['user'])) {  
  include("osobny.php");
}else include("login.php");
  echo "<div class='hlavny'><main>\n";
  
  if (isset($_GET['page_pr'])){
    if (isset($_POST['zmaz_pr'])){
      zmaz_pribeh($_POST['id']);
    }  
    vypis_pribehy($_GET['page_pr']);
  } else if (isset($_GET['page_oz'])){
    if (isset($_POST['zmaz_oz'])){
      zmaz_oznam($_POST['id']);
    }
    vypis_oznamy($_GET['page_oz']);
  }
  else {?>
    <a href='kniznica.php?page_pr=1'><div class='entry_left'>
      <figure><img class='img_medium' src="obr/ikona_pribehy.png" alt="Príbehy"></figure>
    </div></a>
    <a href='kniznica.php?page_oz=1'><div class='entry_right'>
      <figure><img class='img_medium' src="obr/ikona_oznamy.png" alt="Oznamy"></figure>
    </div></a>
  <?php
  }
  echo "</main></div>";  
  paticka();
?>