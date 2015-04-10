<?php
session_start();
include("_funkcie.php");
hlavicka("Služby"); 
if (isset($_SESSION['user'])) {  
  include("osobny.php");
}else include("login.php");
  echo "<div class='hlavny'><main>\n";
  if (substr($_SESSION['user']['admin'],3,1)){
    if (isset($_POST['zmaz_termin']) && isset($_POST['id'])){
      if (zmaz_spec_termin($_POST['id'],1)){
        $hlaska="Termín odstránený.";
      }else{
        $hlaska="Nepodarilo sa odstrániť termín.";
      }
    }else if (isset($_POST['zapis_body_z_terminu']) && isset($_POST['pocet_users']) && isset($_POST['id_termin'])){
      if($result=mysql_query('SELECT datum FROM termins WHERE id_termin='.$_POST['id_termin'],conDB())){
        $termin=mysql_fetch_assoc($result);
        $datum=$termin['datum'];
      }else{
        $datum="";
      }
      $hlaska="Pridané za termín ".date('j.n.Y',strtotime($datum))." :<br>";
      for ($i=1;$i<=$_POST['pocet_users'];$i++){ 
        if (isset($_POST['id_user'.$i]) && isset($_POST['body_bonus'.$i])
            && ($_POST['body_bonus'.$i]*1)>0 && isset($_POST['id_sluzba'.$i])){
          $body_minis=0; 
          $body_bonus=0; 
          if (isset($_POST['body_bonus'.$i])) $body_bonus=$_POST['body_bonus'.$i];
          $ok_hlaska=pridaj_body($_POST['id_user'.$i],$body_minis,$body_bonus);
          if ($ok_hlaska!="" && $ok_hlaska!="Nenašiel sa bojovník s ID ".$_POST['id_user'.$i]){
            odhlas_sluzbu($_POST['id_sluzba'.$i],$_POST['id_termin']);
          }
          if ($i>1) $hlaska.="<br>";
          $hlaska.=$ok_hlaska;
        }
      }
      if ($result=mysql_query('SELECT count(*) AS pocet FROM termins WHERE id_termin='.$_POST['id_termin'],conDB())){
        $row=mysql_fetch_assoc($result);
        if($row['pocet']==0)
          $hlaska.="<br>Všetky služby boli obodované a termín sa zmazal.";
      }
    }
    if (isset($hlaska)) echo "<p>".$hlaska."</p>\n";
    if (isset($_GET['page'])){
      vypis_sluzby_admin($_GET['page']);
    }else vypis_sluzby_admin();
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