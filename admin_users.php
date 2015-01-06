<?php
session_start();
include("_funkcie.php");
hlavicka("Kancelária správcu");
if (isset($_SESSION['user'])){
  include('osobny.php');
  if (substr($_SESSION['user']['admin'],0,1)){
    ?>
    <div class=hlavny><main>
    <?php
    if (isset($_POST['zmaz_user']) && isset($_POST['id_user'])){
      zmaz_user($_POST['id_user']);
    }
    else if (isset($_POST['meno']) && spravny_text($_POST['meno'])
        && isset($_POST['krstne']) && spravny_text($_POST['krstne'])
        && isset($_POST['priezvisko']) && spravny_text($_POST['priezvisko'])
        && isset($_POST['heslo']) && spravny_text($_POST['heslo'])
        && isset($_POST['heslo_rp']) && spravny_text($_POST['heslo_rp'])
        && $_POST['heslo']==$_POST['heslo_rp'] && isset($_POST['admin'])){
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
      if (pridaj_user($_POST['meno'],$_POST['krstne'],$_POST['priezvisko'],$_POST['heslo'],$admin,$visible)) $hlaska='Podarilo sa pridať bojovníka.';    
    }
    if (isset($hlaska)) echo "<h2>".$hlaska."</h2>";
    vypis_admin_users();
    ?>
      <hr>
      <script type='text/javascript' src='_js/JS.js'></script>
      <form method=post>
        <h2>Pridaj bojovníka</h2>
        <table>
        <tr><td class=opis_pole><label for=krstne>Krstné meno:</label></td>
          <td><input type=text size=30 id=krstne name=krstne value='<?php if (isset($_POST['krstne'])) echo $_POST['krstne'];?>' required></td></tr>
        <tr><td class=opis_pole><label for=priezvisko>Priezvisko:</label></td>
          <td><input type=text size=30 id=priezvisko name=priezvisko value='<?php if (isset($_POST['priezvisko'])) echo $_POST['priezvisko'];?>' required></td></tr>
        <tr><td class=opis_pole><label for=meno>Prihlasovacie meno:</label></td>
          <td><input type=text size=30 id=meno name=meno value='<?php if (isset($_POST['meno'])) echo $_POST['meno'];?>' required></td></tr>
        <tr><td class=opis_pole><label for=heslo>Heslo:</label></td>
          <td><input type=password size=30 id=heslo name=heslo required></td></tr>
        <tr><td class=opis_pole><label for=heslo_rp>Zopakuj heslo:</label></td>
          <td><input type=password size=30 id=heslo_rp name=heslo_rp required></td></tr>
        <tr><td><label>Admin:</label></td>
          <td><ul>
            <li><input type=radio id=admin_yes name=admin value=1 onchange="admin_change();" <?php if (!isset($_POST['admin']) || (isset($_POST['admin']) && $_POST['admin'])) echo "checked";?>><label for=admin_yes>Áno</label></li>
            <li><input type=radio id=admin_yes name=admin value=1 onchange="admin_change();"<?php if (isset($_POST['admin']) && !$_POST['admin']) echo "checked";?>><label for=admin_no>Nie</label></li>  
          </ul></td>
        </tr>
        <tr id=prava_admin><td class=opis_pole><label>Práva admina:</label></td>
          <td><ul>
            <li>
              <input type='checkbox' name=adm_1 id=adm_1 <?php if (isset($_POST['adm_1'])) echo "checked";?>>
              <label for=adm_1>Spravovanie bojovníkov</label>
            </li>
            <li>
              <input type='checkbox' name=adm_2 id=adm_2 <?php if (isset($_POST['adm_2'])) echo "checked";?>>
              <label for=adm_2>Spravovanie bodov</label>
            </li>
            <li>
              <input type='checkbox' name=adm_3 id=adm_3 <?php if (isset($_POST['adm_3'])) echo "checked";?>>
              <label for=adm_3>Spravovanie hodností</label>
            </li>
            <li>
              <input type='checkbox' name=adm_4 id=adm_4 <?php if (isset($_POST['adm_4'])) echo "checked";?>>
              <label for=adm_4>Spravovanie služieb</label>
            </li>
            <li>
              <input type='checkbox' name=adm_5 id=adm_5 <?php if (isset($_POST['adm_5'])) echo "checked";?>>
              <label for=adm_5>Spravovanie kvízov</label>
            </li>
            <li>
              <input type='checkbox' name=adm_6 id=adm_6 <?php if (isset($_POST['adm_6'])) echo "checked";?>>
              <label for=adm_6>Spravovanie príbehov</label>
            </li>
            <li>
              <input type='checkbox' name=adm_7 id=adm_7 <?php if (isset($_POST['adm_7'])) echo "checked";?>>
              <label for=adm_7>Spravovanie oznamov</label>
            </li>
          </ul></td>
        </tr>
        <tr><td><label for=invisible>Neviditeľný</label></td>
          <td><input type=checkbox name=invisible id=invisible <?php if (isset($_POST['invisible'])) echo "checked";?>></td></tr> 
        <tr><td></td><td><input type=submit name=pridaj_user value='Pridaj bojovníka'></td></tr>
        </table>
      </form>
    </main></div>
    <?php
  }else include('no_entry.php');
}else {
  include('login.php');
  include('no_entry.php');
}  
paticka();
?>