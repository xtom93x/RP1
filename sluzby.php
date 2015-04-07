<?php
session_start();
include("_funkcie.php");
hlavicka("Služby"); 
if (isset($_SESSION['user'])) {  
  include("osobny.php");
}else include("login.php");
  echo "<div class='hlavny'><main>\n";
  if (substr($_SESSION['user']['admin'],3,1)){?>
    
  <?php
  }else{
    vypis_moje_sluzby($_SESSION['user']['id_user']);
    ?>
    <script type='text/javascript' src='_js/kalendar_user.js'></script>
    <div id=kalendar_box></div>
    <script>
      zobrazKalendar(m,r);
    </script>
    <?php
    if (isset($_POST['datum'])){
      ?>
      <script>
        aktual_d=<?php echo date('j',strtotime($_POST['datum']));?>;
        aktual_m=<?php echo date('n',strtotime($_POST['datum']));?>;
        aktual_r=<?php echo date('Y',strtotime($_POST['datum']));?>;
        d=aktual_d;
        m=aktual_m;
        r=aktual_r;
        zobrazKalendar(m,r);
      </script>
      <?php
      //vypis_sluzby($_POST['datum'],0);
    }else{
      //vypis_sluzby(date('Y-m-d'),0);
    }    
  }
  echo "</main></div>";  
  paticka();
?>