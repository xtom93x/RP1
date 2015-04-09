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
    if (isset($_POST['zapis_sluzbu']) && isset($_POST['id_termin'])
        && isset($_POST['id_perm_termin']) && isset($_POST['datum'])){ 
      if (zapis_sluzbu($_SESSION['user']['id_user'],$_POST['id_termin'],$_POST['id_perm_termin'],$_POST['datum'])){
        echo "<p>Podarilo sa zapísať službu.</p>\n";
      }else{
        error('Nepodarilo sa zapísať službu.\nSkús znova.');
      }    
    }else if (isset($_POST['odhlas_sluzbu']) && isset($_POST['id_sluzba']) && isset($_POST['id_termin'])){
      if (odhlas_sluzbu($_POST['id_sluzba'],$_POST['id_termin'])){
        echo "<p>Podarilo sa odhlásiť službu.</p>\n";
      }else{
        error('Nepodarilo sa odhlásiť službu.\nSkús znova.');
      }
    }
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
      vypis_sluzby($_POST['datum']);
    }else{
      vypis_sluzby(date('Y-m-d'));
    }    
  }
  echo "</main></div>";  
  paticka();
?>