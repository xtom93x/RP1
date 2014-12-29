<?php
session_start();
include("_funkcie.php");
hlavicka("Úprava údajov bojovníka");
if (isset($_SESSION['user'])){
  include('osobny.php');
  if (substr($_SESSION['user']['admin'],0,1)){
    ?>
    <div class=hlavny><main>
    <?php
    if (isset($_POST['meno']) && spravny_text($_POST['meno'])
        && isset($_POST['krstne']) && spravny_text($_POST['krstne'])
        && isset($_POST['priezvisko']) && spravny_text($_POST['priezvisko'])
        && isset($_POST['admin'])){
      if ($_POST['admin']){
        $admin="";
        for ($i=1;$i<=7;$i++){
          if (isset($_POST['adm_'.$i])) $admin.='1';
          else $admin.='0';
        }
        if ($admin=="0000000") $admin=0;
      }else $admin=0;
      if (isset($_POST['invisible'])) $visible=0;
      else $visible=1;
      if (zmen_user_udaje($_GET['id'],$_POST['meno'],$_POST['krstne'],$_POST['priezvisko'],$admin,$visible)) $hlaska='Podarilo sa upraviť údaje bojovníka.';    
    }
    else if (isset($_POST['heslo']) && spravny_text($_POST['heslo'])
        && isset($_POST['heslo_rp']) && spravny_text($_POST['heslo_rp'])
        && $_POST['heslo']==$_POST['heslo_rp']){
      if (zmen_heslo($_GET['id'],$_POST['heslo'])) $hlaska='Podarilo sa zmeniť heslo.';        
    }
    else if (isset($_POST['zmen_profilovku']) && isset($_FILES['profilovka']) && spravny_obrazok($_FILES['profilovka'],1000000)){
      if (zmen_profilovku($_GET['id'],$_FILES['profilovka'])) $hlaska="Podarilo sa zmeniť profilovku bojovníka.";    
    }
    else if (isset($_POST['zmaz_profilovku']) && zmaz_profilovku($_GET['id'])) $hlaska="Podarilo sa zmazať profilovku bojovníka.";
    if (!isset($_GET['id']) || !$user=daj_user($_GET['id'])){
      ?>
      <h2>Bojovník sa nenašiel.</h2>
      </main></div>
      <?php
    }else{
    if (isset($hlaska)) echo "<h2>".$hlaska."</h2>\n";
    ?>
      <script src='_js/JS.js'></script>
      <h1><?php echo $user['krstne']." ".$user['priezvisko']?></h1>
      <table>
      <form method=post>
        <tr><td class=opis_pole><label for=krstne>Krstné meno:</label></td>
          <td><input type=text size=30 id=krstne name=krstne value='<?php if (isset($_POST['krstne'])) echo $_POST['krstne']; else echo $user['krstne'];?>'></td></tr>
        <tr><td class=opis_pole><label for=priezvisko>Priezvisko:</label></td>
          <td><input type=text size=30 id=priezvisko name=priezvisko value='<?php if (isset($_POST['priezvisko'])) echo $_POST['priezvisko']; else echo $user['priezvisko'];?>'></td></tr>
        <tr><td class=opis_pole><label for=meno>Prihlasovacie meno:</label></td>
          <td><input type=text size=30 id=meno name=meno value='<?php if (isset($_POST['meno'])) echo $_POST['meno']; else echo $user['meno'];?>'></td></tr>
        <tr><td><label>Admin:</label></td>
          <td><ul>
            <li><input type=radio id=admin_yes name=admin value=1 onchange="admin_change();" <?php if ((isset($_POST['admin']) && $_POST['admin']) || (!isset($_POST['admin']) && $user['admin'])) echo "checked";?>><label for=admin_yes>Áno</label></li>
            <li><input type=radio id=admin_yes name=admin value=1 onchange="admin_change();"<?php if ((isset($_POST['admin']) && !$_POST['admin']) || (!isset($_POST['admin']) && !$user['admin'])) echo "checked";?>><label for=admin_no>Nie</label></li>  
          </ul></td>
        </tr>
        <tr id=prava_admin <?php if(!isset($_POST['admin']) && !$user['admin']) echo "style='display:none;'"?>>
          <td class=opis_pole><label>Práva admina:</label></td>
          <td><ul>
            <li>
              <input type='checkbox' name=adm_1 id=adm_1 <?php if ((isset($_POST['admin']) && isset($_POST['adm_1'])) || (!isset($_POST['admin']) && substr($user['admin'],0,1))) echo "checked";?>>
              <label for=adm_1>Spravovanie bojovníkov</label>
            </li>
            <li>
              <input type='checkbox' name=adm_2 id=adm_2 <?php if ((isset($_POST['admin']) && isset($_POST['adm_2'])) || (!isset($_POST['admin']) && substr($user['admin'],1,1))) echo "checked";?>>
              <label for=adm_2>Spravovanie bodov</label>
            </li>
            <li>
              <input type='checkbox' name=adm_3 id=adm_3 <?php if ((isset($_POST['admin']) && isset($_POST['adm_3'])) || (!isset($_POST['admin']) && substr($user['admin'],2,1))) echo "checked";?>>
              <label for=adm_3>Spravovanie hodností</label>
            </li>
            <li>
              <input type='checkbox' name=adm_4 id=adm_4 <?php if ((isset($_POST['admin']) && isset($_POST['adm_4'])) || (!isset($_POST['admin']) && substr($user['admin'],3,1))) echo "checked";?>>
              <label for=adm_4>Spravovanie služieb</label>
            </li>
            <li>
              <input type='checkbox' name=adm_5 id=adm_5 <?php if ((isset($_POST['admin']) && isset($_POST['adm_5'])) || (!isset($_POST['admin']) && substr($user['admin'],4,1))) echo "checked";?>>
              <label for=adm_5>Spravovanie kvízov</label>
            </li>
            <li>
              <input type='checkbox' name=adm_6 id=adm_6 <?php if ((isset($_POST['admin']) && isset($_POST['adm_6'])) || (!isset($_POST['admin']) && substr($user['admin'],5,1))) echo "checked";?>>
              <label for=adm_6>Spravovanie príbehov</label>
            </li>
            <li>
              <input type='checkbox' name=adm_7 id=adm_7 <?php if ((isset($_POST['admin']) && isset($_POST['adm_7'])) || (!isset($_POST['admin']) && substr($user['admin'],6,1))) echo "checked";?>>
              <label for=adm_7>Spravovanie oznamov</label>
            </li>
          </ul></td>
        </tr>
        <tr><td></td><td>
          <input type=checkbox name=invisible id=invisible 
            <?php 
            if (isset($_POST['zmen_user_udaje'])) {
              if (isset($_POST['invisible'])) echo "checked";
            } else if (!$user['visible']) echo "checked";
            ?>>
          <label for=invisible>neviditeľný</label>
          </td></tr>
        <tr><td></td><td><input type=submit name=zmen_user_udaje value="Zmeň údaje bojovníka"></td></tr>
      </form>
      </table>
      <hr>
      <table>  
      <form method=post>
        <tr><td class=opis_pole><label for=heslo>Nové heslo:</label></td>
          <td><input type=password size=30 id=heslo name=heslo></td></tr>
        <tr><td class=opis_pole><label for=heslo_rp>Zopakuj heslo:</label></td>
          <td><input type=password size=30 id=heslo_rp name=heslo_rp></td></tr>
        <tr><td></td><td><input type=submit name=zmen_user_heslo value="Zmeň heslo bojovníka"></td></tr>
      </form>
      </table>
      <?php if($user['profilovka']){?>
      <hr>
      <table> 
      <form method=post>
        <tr><td><label>Profilovka:</label></td>
          <td><figure><img class='img_small' src='<?php if ($user['profilovka']) echo $user['profilovka']; else echo "obr/user.png";?>'></figure></td></tr>
        <tr><td></td><td><input type=submit name=zmaz_profilovku value='Zmaž profilovku bojovníka'></td></tr>
      </form>
      </table>
      <?php } ?>
      <hr>
      <table>
      <form method="post" enctype="multipart/form-data">
        <tr><td><label for=profilovka>Profilovka (max.1MB):</label></td>
        <td><input type="file" name="profilovka" id="profilovka" accept='image/jpg,image/jpeg,image/gif,image/png'></td></tr>
        <tr><td></td><td><input type=submit name=zmen_profilovku value="Zmeniť profilovku bojovníka"></td></tr>
      </form>
      </table>
    </main></div>
    <?php
  }
  }else include('no_entry.php');
}else {
  include('login.php');
  include('no_entry.php');
}  
paticka();
?>