<?php
session_start();
include("_funkcie.php");
hlavicka("Kancelária správcu");
if (isset($_SESSION['user'])){
  include('osobny.php');
  if (substr($_SESSION['user']['admin'],1,1)){
    ?>
    <div class=hlavny><main>
    <?php
    if (isset($_POST['zapis_body']) && isset($_POST['pocet_users'])){
      $hlaska="Pridané:<br>";
      for ($i=1;$i<=$_POST['pocet_users'];$i++){
        if (isset($_POST['id_user'.$i]) && ((isset($_POST['body_minis'.$i])
            && ($_POST['body_minis'.$i]*1)!=0 ) || (isset($_POST['body_bonus'.$i])
            && ($_POST['body_bonus'.$i]*1)!=0))){
          $body_minis=0; 
          if (isset($_POST['body_minis'.$i])) $body_minis=$_POST['body_minis'.$i]; 
          $body_bonus=0; 
          if (isset($_POST['body_bonus'.$i])) $body_bonus=$_POST['body_bonus'.$i];
          $ok_hlaska=pridaj_body($_POST['id_user'.$i],$body_minis,$body_bonus);
          if ($i>1) $hlaska.="<br>";
          $hlaska.=$ok_hlaska;
        }
      }
    }
    if (isset($hlaska)) echo "<p>".$hlaska."</p>";
    vypis_admin_body();
  }else include('no_entry.php');
}else {
  include('login.php');
  include('no_entry.php');
}  
paticka();
?>