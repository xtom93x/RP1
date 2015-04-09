<?php
include('_conDB.php');
date_default_timezone_set('UTC');


function daj_level($body){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT * FROM levels WHERE body<='.$body.' ORDER BY body DESC LIMIT 1',$link)){
      return mysql_fetch_assoc($result);
    }
    else {
      error("Dopit sa nepodaril.");
      return False;
    }
  } else return False;
}

function daj_level_vsetko($id_lvl){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT * FROM levels WHERE id_lvl='.$id_lvl,$link)){
      return mysql_fetch_assoc($result);
    }
  } 
  return False;  
}

function daj_najnovsi_oznam(){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT * FROM oznamy WHERE active=1 ORDER BY datum DESC LIMIT 1',$link)){
      if (!mysql_num_rows($result)) return False;
      $row=mysql_fetch_assoc($result);
      return $row;
    } else {
      error('Nepodarilo sa načítať najnovší oznam.');
      return False;
    }
  } else return False;
}

function daj_najnovsi_pribeh(){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT * FROM pribehy ORDER BY datum DESC LIMIT 1',$link)){
      if (!mysql_num_rows($result)) return False;
      $row=mysql_fetch_assoc($result);
      $row['datum']=date('j.n.Y',strtotime($row['datum']));
      return $row;
    } else {
      error('Nepodarilo sa načítať príbeh.');
      return False;
    }
  } else return False;
}

function daj_oznam($id_oznam){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT * FROM oznamy WHERE id_oznam='.$id_oznam,$link)){
      return mysql_fetch_assoc($result);
    }
  }
  error('Oznam sa nenašiel.');
  return false;
}

function daj_pribeh($id_pribeh){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT * FROM pribehy WHERE id_pribeh='.$id_pribeh,$link)){
      return mysql_fetch_assoc($result);
    }
  }
  error('Príbeh sa nenašiel.');
  return false;
}

function daj_user($id_user){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT * FROM users WHERE id_user='.$id_user,$link)){
      return mysql_fetch_assoc($result);
    }
  }
  error('Bojovník sa nenašiel.');
  return false;
}

function daj_uvodne_score(){
  $link=conDB();
  $result_m=mysql_query('SELECT body, krstne, priezvisko FROM users WHERE visible=1 ORDER BY body DESC',$link);
  echo "<div class='score_uvod'>\n";
  echo "<h2>Najlepší bojovníci</h2>\n";
  echo "<table border=1>\n";
  echo "<tr><th>Meno</th><th>Body</th><th>Hodnosť</th></tr>\n";
  while ($row=mysql_fetch_assoc($result_m)) {
    $level=daj_level($row['body']);
    echo "<tr><td>".$row['krstne']." ".$row['priezvisko']."</td>\n
      <td>".$row['body']."</td>\n  
      <td>".$level['nazov']."</td></tr>\n";
      }          
  echo "</table>\n";
}

function error($text){
  echo "<script>alert('".$text."')</script>\n";
}

function hlavicka($nadpis) {
  ?>
  <!DOCTYPE html>
  <html>
  <head>
  <meta charset="utf-8">
  <title><?php echo $nadpis; ?></title>
  <link href="styly.css" rel="stylesheet">
  </head>

  <body>
  <div class='hlavicka'><header>
  <div class='lava_veza'></div>
  <div class='prava_veza'></div>
  <h1><?php echo $nadpis; ?></h1>
  <?php
  if (isset($_SESSION['user'])){
  ?>
  <form method="post" action="index.php">
    <strong>Prihlásený <?php echo $_SESSION['user']['meno']?> </strong>
    <input type="submit" name="odhlas" value="Odhlás">  
  </form>
  <?php
  }
  include('navigacia.php');
  ?>
  </header></div>      
  <?php
}

function je_zapisany_na_termin($id_user,$id_termin){
  $link=conDB();
  if ($result=mysql_query('SELECT count(*) as pocet FROM sluzby WHERE id_user='.$id_user.' AND id_termin='.$id_termin,$link)){
    $row=mysql_fetch_assoc($result);
    return $row['pocet']>0;
  }
  return -1;
}

function obnov_user(){
  if (!isset($_SESSION['user']['id_user'])){
    error('Nie si prihlásený');
    return false;
  }
  if ($link=conDB()){
    $sql="SELECT * FROM users WHERE id_user='".$_SESSION['user']['id_user']."' LIMIT 1";
    $result=mysql_query($sql, $link);
    if ($result){
      if (mysql_num_rows($result)==1) {
        $row=mysql_fetch_assoc($result);
        $row['level']=daj_level($row['body']);
        $_SESSION['user']=$row;
        return True;
      } else {
        error("Chybné meno alebo heslo.");
        return False;
      }
    } else {
        error("Nepodarilo sa prihlásiť. Skúste znova.");
        return False;
    }
  }  
}

function odhlas(){
  session_unset('user');
}

function odhlas_sluzbu($id_sluzba,$id_termin){
  $link=conDB();
  if (mysql_query('DELETE FROM sluzby WHERE id_sluzba='.$id_sluzba,$link)){
    echo ":)";
    if ($result=mysql_query('SELECT id_perm_termin FROM termins WHERE id_termin='.$id_termin,$link)){
      $row=mysql_fetch_assoc($result);
      if ($row['id_perm_termin']!=null && pocet_zapisanych_na_termin($id_termin)==0){
        mysql_query('DELETE FROM termins WHERE id_termin='.$id_termin,$link);
      }
    }
    return true;
  }
  return false;
}

function over_identitu($id_user,$heslo){
  if ($link=conDB()){
    if($result=mysql_query("SELECT id_user FROM users WHERE id_user=".$id_user." AND heslo='".md5(addslashes(strip_tags(trim($heslo))))."' LIMIT 1",$link)){
      return mysql_fetch_assoc($result);
    }else{
      error('Nepodarilo sa overiť identitu.');
      return false;
    }
  }
}

function paticka(){?>
  <div class='paticka'><footer>
    <em>vytvoril:<strong>Tomáš Žitňanský</strong></em>
  </footer></div>
  </body>
  </html>
<?php
}

function pocet_mojich_sluzieb($id_user){
  if ($link=conDB()){
    if ($result=mysql_query("SELECT count(*) as pocet FROM sluzby,termins WHERE sluzby.id_user=".$id_user." AND 
                            sluzby.id_termin=termins.id_termin AND (termins.datum>CURDATE() OR (termins.datum=CURDATE() AND termins.cas>CURTIME())) 
                            ORDER BY termins.datum,termins.cas",$link)){
      $row=mysql_fetch_assoc($result);
      return $row['pocet'];    
    }  
  }
  return PHP_INT_MAX();
}

function pocet_zapisanych_na_termin($id){
  if ($link=conDB()){
    if ($result=mysql_query("SELECT count(*) AS pocet FROM sluzby WHERE id_termin=".$id,$link)){
      $row=mysql_fetch_assoc($result);
      return $row['pocet'];
    }
  }
  return 0;
}

function pridaj_body($id_user,$body_minis=0,$body_bonus=0){
  $hlaska="";
  if ($body_minis) $hlaska.=" +".$body_minis." bodov";
  if ($body_bonus) $hlaska.=" +".$body_bonus." bonusových bodov";
  if($link=conDB()){
    if($result=mysql_query('SELECT priezvisko,krstne,body_minis,body_bonus FROM users WHERE id_user='.$id_user,$link)){
      $row=mysql_fetch_assoc($result);
      $name=$row['priezvisko'].' '.$row['krstne'];
      $body_minis+=$row['body_minis'];
      $body_bonus+=$row['body_bonus'];
      if ($body_minis!=0 && $body_minis/2>=$body_bonus) $body=$body_minis+$body_bonus;
      else $body=round($body_minis*1.5);
    }else return "Nenašiel sa bojovník s ID ".$id_user;
    mysql_free_result($result);
    if ($result=mysql_query('UPDATE users SET body_minis='.$body_minis.', body_bonus='.$body_bonus.',
    body='.$body.' WHERE id_user='.$id_user,$link)){
      return $name.$hlaska;
    }
  }
}

function pridaj_oznam($nadpis,$text){
  if ($link=conDB()){
    if ($result=mysql_query('INSERT INTO oznamy SET nadpis="'.addslashes(strip_tags(trim($nadpis))).'", 
        text="'.addslashes(strip_tags(trim($text))).'", datum="'.date('Y-m-d').'", active=1')){
      return True;    
    }
  }
  error('Nepodarilo sa pridať oznam.');
  return False;
}

function pridaj_perm_termin($den,$cas,$max_pocet,$poznamka){
  if ($link=conDB()){
    if ($result=mysql_query('INSERT INTO permanent_termins SET den='.$den.', cas="'.$cas.'", max_pocet='.$max_pocet.', 
        poznamka="'.addslashes(strip_tags(trim($poznamka))).'"',$link)){
      return True;    
    }
  }
  error('Nepodarilo sa pridať permanentný termín.');
  return False;
}

function pridaj_pribeh($nazov,$text){
  if ($link=conDB()){
    if ($result=mysql_query('INSERT INTO pribehy SET nazov="'.addslashes(strip_tags(trim($nazov))).'", 
        text="'.addslashes(strip_tags(trim($text))).'", datum="'.date('Y-m-d').'"')){
      return True;    
    }
  }
  error('Nepodarilo sa pridať príbeh.');
  return False;
}

function pridaj_spec_termin($datum,$cas,$max_pocet,$poznamka){
  echo $datum;
  if ($link=conDB()){
    if ($result=mysql_query('INSERT INTO termins SET datum="'.$datum.'", cas="'.$cas.'", max_pocet='.$max_pocet.', 
        poznamka="'.addslashes(strip_tags(trim($poznamka))).'"',$link)){
      return True;    
    }
  }
  error('Nepodarilo sa pridať špeciálny termín.');
  return False;
}

function pridaj_user($meno,$krstne,$priezvisko,$heslo,$admin,$visible){
  if($link=conDB()){
    if ($result=mysql_query('SELECT count(id_user) as pocet FROM users WHERE meno="'.addslashes(strip_tags(trim($meno))).'"',$link)){
      $row=mysql_fetch_assoc($result);
      if ($row['pocet']>0){
        error('Prihlasovacie meno "'.addslashes(strip_tags(trim($meno))).'" je už obsadené.');
        return false;
      }
    }else{
      error('Nepodarilo sa pridať bojovníka.');
      return False;
    }
    mysql_free_result($result);
    if ($result=mysql_query("INSERT INTO users SET meno='".addslashes(strip_tags(trim($meno)))."',
        krstne='".addslashes(strip_tags(trim($krstne)))."',
        priezvisko='".addslashes(strip_tags(trim($priezvisko)))."',
        heslo='".md5(addslashes(strip_tags(trim($heslo))))."',
        admin='".$admin."', visible=".$visible,$link)) return True;
  }
  error('Nepodarilo sa pridať bojovníka.');
  return False;
}

function pridaj_level($nazov,$body,$obrazok,$obrazok_mini){
  if($link=conDB()){
    if ($result=mysql_query('INSERT INTO levels SET nazov="'.addslashes(strip_tags(trim($nazov))).'", body='.$body.',
        obrazok="'.$obrazok['name'].'", obrazok_mini="'.$obrazok_mini['name'].'"',$link)){
      if (!move_uploaded_file($obrazok['tmp_name'],"obr/".$obrazok['name'])){ 
          error('Nastala chyba pri presune obrázka.');
      }
      if (!move_uploaded_file($obrazok_mini['tmp_name'],"obr/".$obrazok_mini['name'])){ 
          error('Nastala chyba pri presune miniatúry.');
      }
      return True;
    }
  }
  error('Nepodarilo sa pridať titul.');
  return False;
}

function prihlas($meno,$heslo){
  if ($link=conDB()){
    $sql="SELECT * FROM users WHERE meno='".addslashes(strip_tags(trim($meno)))."' AND heslo='".md5(addslashes(strip_tags(trim($heslo))))."' LIMIT 1";
    $result=mysql_query($sql, $link);
    if ($result){
      if (mysql_num_rows($result)==1) {
        $row=mysql_fetch_assoc($result);
        return $row;
      } else {
        error("Chybné meno alebo heslo.");
        return False;
      }
    } else {
        error("Nepodarilo sa prihlásiť. Skúste znova.");
        return False;
    }
  }
}

function spravny_obrazok($file,$maxsize=0){
  if($file['error']==0) { 
    if (is_uploaded_file($file['tmp_name'])){
      if (!$maxsize || $file['size']<=$maxsize){
        return True;
      }else{
        error('Zvolený obrázok je príliž veľký.');
        return False;
      }
    }
  }
  error("Chyba pri načítaní obrázka.");
  return False;
}

function spravny_text($t){
  $t = addslashes(strip_tags(trim($t)));
  if (strlen($t)>0) return True;
  else {
  return False;}
}

function uprav_oznam($id_oznam,$nadpis,$text,$active){
  if ($link=conDB()){
    if ($result=mysql_query('UPDATE oznamy SET nadpis="'.addslashes(strip_tags(trim($nadpis))).'", 
        text="'.addslashes(strip_tags(trim($text))).'", active='.$active.' WHERE id_oznam='.$id_oznam,$link)){
      return True;    
    }
  }
  error('Nepodarilo sa upraviť oznam.');
  return False;
}

function uprav_perm_termin($id,$den,$cas,$max_pocet,$poznamka,$rem){
  if ($link=conDB()){
    if ($result=mysql_query('UPDATE permanent_termins SET den='.$den.', cas="'.$cas.'", max_pocet='.$max_pocet.', 
        poznamka="'.addslashes(strip_tags(trim($poznamka))).'" WHERE id_perm_termin='.$id,$link)){ 
      if ($rem){
        if (mysql_query('DELETE FROM termins WHERE (datum>CURDATE() or (datum=CURDATE() and cas>CURTIME())) and id_perm_termin='.$id,$link)){
          return true;
        }
      }else{
        return True;
      }    
    }
  }
  error('Nepodarilo sa upraviť permanentný termín.');
  return False;
}

function uprav_pribeh($id_pribeh,$nazov,$text){
  if ($link=conDB()){
    if ($result=mysql_query('UPDATE pribehy SET nazov="'.addslashes(strip_tags(trim($nazov))).'", 
        text="'.addslashes(strip_tags(trim($text))).'" WHERE id_pribeh='.$id_pribeh,$link)){
      return True;    
    }
  }
  error('Nepodarilo sa upraviť príbeh.');
  return False;
}

function uprav_spec_termin($id,$datum,$cas,$max_pocet,$poznamka){
  if ($link=conDB()){
    if ($result=mysql_query('UPDATE termins SET datum="'.$datum.'", cas="'.$cas.'", max_pocet='.$max_pocet.', 
        poznamka="'.addslashes(strip_tags(trim($poznamka))).'" WHERE id_termin='.$id,$link)){
      return True;    
    }
  }
  error('Nepodarilo sa upraviť špeciálny termín.');
  return False;
}

function vrat_perm_termin($id){
  if($link=conDB()){
    if($result=mysql_query('SELECT * FROM permanent_termins WHERE id_perm_termin='.$_GET['id'].' LIMIT 1',$link)){
      if(mysql_num_rows($result)==1){
        return mysql_fetch_assoc($result);
      }
    }
  }
  return false;  
}

function vrat_spec_termin($id){
  if($link=conDB()){
    if($result=mysql_query('SELECT * FROM termins WHERE id_termin='.$_GET['id'].' LIMIT 1',$link)){
      if(mysql_num_rows($result)==1){
        return mysql_fetch_assoc($result);
      }
    }
  }
  return false;  
}

function vypis_admin_body(){
  if ($link=conDB()){
    if ($result=mysql_query("SELECT * FROM users ORDER BY priezvisko, meno ASC",$link)){
      ?>
      <table>
      <form method='post'>
      <tr><th>Priezvisko a meno</th><th>Body za ministrovanie</th><th>Bonusové body</th><th>Body v limite</th></tr>
      <?php
      $pocet=0;
      while ($row=mysql_fetch_assoc($result)){
        $pocet+=1;
        ?>
        <tr>
          <input type=hidden name='id_user<?php echo $pocet?>' value=<?php echo $row['id_user'];?>>
          <td><?php echo $row['priezvisko'].' '.$row['krstne']?></td>
          <td>
            <label for='body_minis<?php echo $pocet?>'><?php echo $row['body_minis']?> +</label>
            <input id='body_minis<?php echo $pocet?>' name='body_minis<?php echo $pocet?>' type=text size=10 value=0>
          </td>
          <td>
            <label for='body_bonus<?php echo $pocet?>'><?php echo $row['body_bonus']?> +</label>
            <input id='body_bonus<?php echo $pocet?>' name='body_bonus<?php echo $pocet?>' type=text size=10 value=0>
          </td>
          <td>
            <label><?php echo $row['body']?></label> 
          </td>
        </tr>
        <?php
      }
      ?>
        <tr>
          <input type=hidden name='pocet_users' value=<?php echo $pocet;?>>
          <td><input type=submit name=zapis_body value='Zapíš všetky body'></td>
          <td></td><td></td>
        </tr>
      </form>
      </table>
      <?php
    }
  }
}

function vypis_admin_users(){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT * FROM users',$link)){
      ?>
      <table border=1>
        <tr><th>Meno a priezvisko</th><th>Prihlasovacie meno</th><th>Admin</th><th>Profilovka</th><th></th></tr>
      <?php
      while ($row=mysql_fetch_assoc($result)){
      ?>
      <tr><td><?php echo $row['krstne']." ".$row['priezvisko'];?></td>
        <td><p>
        <?php 
          echo $row['meno']; 
          if(!$row['visible']) echo "</p><p class=low_prior>Neviditeľný";
        ?>
        </p></td>
        <td>
        <?php
        if ($row['admin']){
          ?><ul><?php
          $prava=array('spravovanie bojovníkov','spravovanie bodov','spravovanie hodností','spravovanie služieb','spravovanie kvízov','spravovanie príbehov','spravovanie oznamov');
          for ($i=0;$i<7;$i++){
            if (substr($row['admin'],$i,1)) echo '<li>'.$prava[$i].'</li>';
          }
          ?></ul><?php
        }else echo "Nie";
        ?>
        </td>
        <td><figure><img class=img_small alt='Profilovka' src='<?php if ($row['profilovka']) echo $row['profilovka']; else echo "obr/user.png";?>'></figure></td>
        <td><a href='uprav_user.php?id=<?php echo $row['id_user'];?>'>Uprav údaje</a><br>
          <form method=post>
            <input type=hidden name='id_user' value=<?php echo $row['id_user'];?>>
            <input type=submit name='zmaz_user' value='Zmaž bojovníka' onclick='potvrd_zmaz_user(event);'>
          </form>
        </td>
      </tr>
      <?php  
      }
      echo "</table>\n";
    }
  }
}

function vypis_admin_levels(){
  if ($link=conDB()){
    if($result=mysql_query('SELECT * FROM levels ORDER BY body ASC',$link)){
      ?>
      <table border=1>
      <tr><th>Hodnosť</th><th>Bodová hranica</th><th>Obrázok</th><th>Obrázok miniatúra</th><th></th></tr>
      <?php
      while ($row=mysql_fetch_assoc($result)){
        ?>
        <tr id='<?php echo $row['id_lvl']?>'>
          <td><?php echo $row['nazov'];?></td><td><?php echo $row['body'];?></td>
          <td>
            <a href='obr/<?php echo $row['obrazok'];?>'>
              <img class=img_small src='obr/<?php echo $row['obrazok'];?>' alt='<?php echo $row['nazov'];?>'>
            </a>
          </td>
          <td>
            <a href='obr/<?php echo $row['obrazok_mini'];?>'>
              <img class=img_small src='obr/<?php echo $row['obrazok_mini'];?>' alt='<?php echo $row['nazov'];?>'>
            </a>
          </td>
          <td>
            <a href='uprav_level.php?id=<?php echo $row['id_lvl']?>'>Uprav titul</a>
            <form method=post>
              <input type=hidden name='id_level' value=<?php echo $row['id_lvl'];?>>
              <input type=submit name='zmaz_level' value='Zmaž titul' onclick='potvrd_zmaz_level(event);'>
            </form>
          </td>
        </tr>
        <?php
      }
      ?>
      </table>
      <?php
    }
  }
}

function vypis_hodnosti_pravidla(){
  if ($link=conDB()){
    if($result=mysql_query('SELECT * FROM levels ORDER BY body ASC',$link)){
      ?>
      <table border=1>
      <tr><th>Hodnosť</th><th>Bodová hranica</th><th>Obrázok</th></tr>
      <?php
      while ($row=mysql_fetch_assoc($result)){
        ?>
        <tr id='<?php echo $row['id_lvl']?>'><td><?php echo $row['nazov'];?></td><td><?php echo $row['body'];?></td>
          <td>
            <a href='obr/<?php echo $row['obrazok'];?>'>
              <img class=img_small src='obr/<?php echo $row['obrazok_mini'];?>' alt='<?php echo $row['nazov'];?>'>
            </a>
          </td></tr>
        <?php
      }
      ?>
      </table>
      <?php
    }
  }
}

function vypis_moje_sluzby($id_user){
  if ($link=conDB()){
    if($result=mysql_query("SELECT * FROM sluzby,termins WHERE sluzby.id_user=".$id_user." AND 
                            sluzby.id_termin=termins.id_termin AND (termins.datum>CURDATE() OR (termins.datum=CURDATE() AND termins.cas>CURTIME())) 
                            ORDER BY termins.datum,termins.cas",$link)){
      if (mysql_num_rows($result)){
        ?>
        <table border=1>
          <tr><th>Dátum</th><th>Čas</th><th>Počet voľných miest</th><th>Poznámka</th><th></th></tr>
        <?php 
        while ($row=mysql_fetch_assoc($result)){
          ?>
          <tr>
            <td><?php echo date('j.n.Y',strtotime($row['datum']))?></td>
            <td><?php echo date('H:i',strtotime($row['cas']))?></td>
            <td><?php echo ($row['max_pocet']-pocet_zapisanych_na_termin($row['id_termin']))."/".$row['max_pocet']?></td>
            <td><?php echo $row['poznamka']?></td>
            <td><form method=post>
              <input type=hidden name=id_sluzba value=<?php echo $row['id_sluzba']?> >
              <input type=hidden name=id_termin value=<?php echo $row['id_termin']?> >            
              <input type=submit name=odhlas_sluzbu value='Odhlás službu'>
            </form></td>
          </tr>
          <?php
        }
        ?>
        </table>
        <?php   
      }else{
        echo "<h1>Nemáš zapísané žiadne služby.</h1>\n";
      }
      if (3-mysql_num_rows($result)>1){
        echo "<p>Môžeš si ešte zapísať ".(3-mysql_num_rows($result))." služby.</p>\n";
      }else if (3-mysql_num_rows($result)==1){
        echo "<p>Môžeš si ešte zapísať 1 službu.</p>\n";  
      }else{
        echo "<p class=text_alarm>Nemôžeš si už zapísať ďalšie služby.</p>\n";
      }
    }
  }
}

function vypis_oznamy($page){
  $pocet_na_stranke=5;
  ?> <div class='oznamy_box'><section> <?php
  if ($link=conDB()){
    if ($result=mysql_query('SELECT count(id_oznam) as pocet FROM oznamy',$link)){
      $row=mysql_fetch_assoc($result);
      if ($row['pocet']>$pocet_na_stranke){ 
        ?> <div class='pages'><nav>
           <a href='kniznica.php?page_oz=1'>1.</a> <?php
        if ($page>4) echo "...";
        for ($i= (($page>3) ? $page-2 : 2);($i<=ceil($row["pocet"]/$pocet_na_stranke))and($i<=$page+2);$i++){
          echo "<a href='kniznica.php?page_oz=".$i."'>".$i.".</a>\n";
        }
        $last_page=ceil($row["pocet"]/$pocet_na_stranke);
        if ($last_page>$page+2){
          if ($last_page>$page+3) echo "...";
          echo "<a href='kniznica.php?page_oz=".$last_page."'>".$last_page.".</a>\n"; 
        } 
        ?> </nav></div> <?php
      }else if (!$row['pocet']){
        ?> <h2 class="text_alarm">Nenašli sa žiadne oznamy.</h2> <?php
      } 
    }
    if ($result=mysql_query('SELECT * FROM oznamy ORDER BY datum LIMIT '.$pocet_na_stranke*($page-1).','.$pocet_na_stranke,$link)){
      while($row=mysql_fetch_assoc($result)){?>
        <div class='oznam'><article>
          <h2><?php echo $row['nadpis'];?></h2>
          <p><?php echo str_replace("\n", "<br>", $row['text']);?></p>
          <?php
          if ($row['active']) {
            echo "<p class='text_alarm'>Aktuálny</p>\n";
          }else{
            echo "<p class='low_prior'>Neaktuálny</p>\n";
          }
          echo "<p class='low_prior'>".date('j.n.Y',strtotime($row['datum']))."</p>\n";
          if (isset($_SESSION['user']) && substr($_SESSION['user']['admin'],6,1)){
          ?>
            <a href='uprav_oznam.php?id=<?php echo $row['id_oznam'];?>'>Upraviť oznam</a><br>
            <form method='post'>
              <input type=hidden name=id value=<?php echo $row['id_oznam'];?>>
              <input type=submit name='zmaz_oz' value='Zmaž oznam'>
            </form>
          <?php
          }
          ?>
        </article></div>
        <?php
      }
      if (isset($_SESSION['user']) && substr($_SESSION['user']['admin'],6,1)) echo "<a href=pridaj_oznam.php>Pridaj oznam</a><br>\n";
    }
  }
  ?> </section></div> <?php
}

function vypis_perm_temins($page){
  ?>
  <script>
    function potvrd_zmaz_perm_termin(e,term){
      var con=confirm('Naozaj chceš zmazať permanentný termín "'+term+'" ?\nSpolu s termínom sa zmažú aj všetky zapísané služby na daný termín.');
      if (!con){
        e.preventDefault();
      }
    }
  </script>
  <?php
  $pocet_na_stranke=5;
  ?> 
  <div class='termins_box'><section>
  <h1>Permanentné termíny</h1>
  <?php
  if ($link=conDB()){
    if ($result=mysql_query('SELECT count(id_perm_termin) as pocet FROM permanent_termins',$link)){
      $row=mysql_fetch_assoc($result);
      if ($row['pocet']>$pocet_na_stranke){
        ?> <div class='pages'><nav>
           <a href='admin_termins.php?page_perm=1'>1.</a> <?php
        if ($page>4) echo "...";
        for ($i= ($page>3) ? $page-2 : 2;($i<=ceil($row["pocet"]/$pocet_na_stranke))and($i<=$page+2);$i++){
          echo "<a href='admin_termins.php?page_perm=".$i."'>".$i.".</a>\n";
        }
        $last_page=ceil($row["pocet"]/$pocet_na_stranke);
        if ($last_page>$page+2){
          if ($last_page>$page+3) echo "...";
          echo "<a href='admin_termins.php?page_perm=".$last_page."'>".$last_page.".</a>\n"; 
        } 
        ?> </nav></div> <?php
      }else if (!$row['pocet']){
        ?> <h2 class="text_alarm">Nenašli sa žiadne permanentné termíny.</h2> <?php
      }
    }
    if ($result=mysql_query('SELECT * FROM permanent_termins ORDER BY den,cas LIMIT '.$pocet_na_stranke*($page-1).','.$pocet_na_stranke,$link)){
      $dni=array('','Nedeľa','Pondelok','Utorok','Streda','Štvrtok','Piatok','Sobota');
      ?><table border='1'><tr><th>Deň</th><th>Čas</th><th>Počet miest</th><th>Poznámka</th><th></th></tr><?php
      while($row=mysql_fetch_assoc($result)){?>
        <tr>
          <td><?php echo $dni[$row['den']]?></td>
          <td><?php echo date('H:i',strtotime($row['cas']))?></td>
          <td><?php echo $row['max_pocet']?></td>
          <td><?php echo $row['poznamka']?></td>
          <td>
            <a href='uprav_perm_termin.php?id=<?php echo $row['id_perm_termin']?>'>Uprav termín</a>
            <form method='post'>
              <input type=hidden name='id' id='id' value=<?php echo $row['id_perm_termin']?>>
              <input type=submit name=zmaz_perm id=zmaz_perm value='Zmaž termín' onclick='potvrd_zmaz_perm_termin(event,"<?php echo $dni[$row['den']]." o ".date("H:i",strtotime($row['cas']))?>");'>
            </form>  
          </td>
        </tr>
        <?php
      }
      ?></table><?php
    }
  }
  ?>
  </section></div>
  <?php
}

function vypis_pribehy($page){
  $pocet_na_stranke=3;
  ?> <div class='pribehy_box'><section> <?php
  if ($link=conDB()){
    if ($result=mysql_query('SELECT count(id_pribeh) as pocet FROM pribehy',$link)){
      $row=mysql_fetch_assoc($result);
      if ($row['pocet']>$pocet_na_stranke){
        ?> <div class='pages'><nav>
           <a href='kniznica.php?page_pr=1'>1.</a> <?php
        if ($page>4) echo "...";
        for ($i= ($page>3) ? $page-2 : 2;($i<=ceil($row["pocet"]/$pocet_na_stranke))and($i<=$page+2);$i++){
          echo "<a href='kniznica.php?page_pr=".$i."'>".$i.".</a>\n";
        }
        $last_page=ceil($row["pocet"]/$pocet_na_stranke);
        if ($last_page>$page+2){
          if ($last_page>$page+3) echo "...";
          echo "<a href='kniznica.php?page_pr=".$last_page."'>".$last_page.".</a>\n"; 
        } 
        ?> </nav></div> <?php
      }else if (!$row['pocet']){
        ?> <h2 class="text_alarm">Nenašli sa žiadne príbehy.</h2> <?php
      }
    }
    if ($result=mysql_query('SELECT * FROM pribehy ORDER BY datum LIMIT '.$pocet_na_stranke*($page-1).','.$pocet_na_stranke,$link)){
      while($row=mysql_fetch_assoc($result)){?>
        <div class='oznam'><article>
          <h2><?php echo $row['nazov'];?></h2>
          <p><?php echo str_replace("\n", "<br>", $row['text']);?></p>
          <p class='low_prior'><?php echo date('j.n.Y',strtotime($row['datum']));?></p>
          <?php
          if (isset($_SESSION['user']) && substr($_SESSION['user']['admin'],5,1)){
          ?>
            <a href='uprav_pribeh.php?id=<?php echo $row['id_pribeh'];?>'>Upraviť príbeh</a><br>
            <form method='post'>
              <input type=hidden name=id value=<?php echo $row['id_pribeh'];?>>
              <input type=submit name='zmaz_pr' value='Zmaž príbeh'>
            </form>
          <?php
          }
          ?>
        </article></div>
        <?php
      }
      if (isset($_SESSION['user']) && substr($_SESSION['user']['admin'],5,1)) echo "<a href=pridaj_pribeh.php>Pridaj príbeh</a><br>\n";
    }
  }
  ?> </section></div> <?php
}

function vypis_score($by,$diverg){
  $link=conDB();
  if ($diverg) $diverg='DESC';
  else $diverg='ASC';
  if($result_m=mysql_query('SELECT body, krstne, priezvisko, profilovka FROM users WHERE visible=1 ORDER BY '.$by.' '.$diverg,$link)){
  ?>
  <table class=zoznam border=1>
  <tr><th><a href='score.php?by=krstne<?php if ($by=='krstne' && $diverg=='ASC') echo "&diverg=true"?>'>Meno</a>
   a <a href='score.php?by=priezvisko<?php if ($by=='priezvisko' &&$diverg=='ASC') echo "&diverg=true"?>'>priezvisko</a></th>
      <th><a href='score.php?by=body<?php if ($by=='body' &&$diverg=='ASC') echo "&diverg=true"?>'>Body</a></th><th>Hodnosť</th></tr>
  <?php
  while ($row=mysql_fetch_assoc($result_m)) {
    $level=daj_level($row['body']);
    if ($row['profilovka']) {
      $profilovka=$row['profilovka'];
    }else $profilovka='obr/user.png';
    echo "<tr><td><figure><img alt='".$row['krstne']." ".$row['priezvisko']."' 
            src='".$profilovka."' class=img_small>
            <figcaption>".$row['krstne']." ".$row['priezvisko']."</figcaption></figure></td>
      <td>".$row['body']."</td>  
      <td><figure><a href='tron.php#".$level['id_lvl']."'>
        <img alt='".$level['nazov']."' class='img_small' src='obr/".$level['obrazok_mini']."'>
        <figcaption>".$level['nazov']."</figcaption></a></figure></td></tr>\n";
  } 
  echo "</table>\n";
  }
}

function vypis_sluzby($datum){
  if (date('Y-m-d')>$datum){
    echo "<h2 class=text_alarm>Nepovolený dátum.</h2>\n";
    return;
  }
  $link=conDB();
  if ($result=mysql_query("SELECT perms.cas AS cas, perms.id_perm_termin AS id_perm_termin, termins.datum AS datum,
                           termins.id_termin AS id_termin, perms.max_pocet AS max_pocet, perms.poznamka AS poznamka 
                           FROM (SELECT * FROM permanent_termins WHERE den=DAYOFWEEK('$datum')) AS perms 
                           LEFT JOIN termins ON (perms.id_perm_termin=termins.id_perm_termin AND termins.datum='".$datum."') UNION
                           SELECT termins.cas AS cas, null AS id_perm_termin, termins.datum AS datum,
                           termins.id_termin AS id_termin, termins.max_pocet AS max_pocet, termins.poznamka AS poznamka 
                           FROM termins WHERE datum='$datum' AND termins.id_perm_termin is NULL ORDER BY cas",$link)){
    if (mysql_num_rows($result)<1){
      echo "<h2>Nenašli sa žiadne termíny služieb.</h2>\n";
      return;
    } 
    ?>
    <hr>
    <table border=1>
      <tr><th>Dátum</th><th>Čas</th><th>Počet voľných miest</th><th>Poznámka</th><th></th></tr>
    <?php
    while ($row=mysql_fetch_assoc($result)){
    ?> 
      <tr>
        <td><?php echo date('j.n.Y',strtotime($datum))?></td>
        <td><?php echo $row['cas']?></td>
        <td><?php if ($row['id_termin']==null) echo $row['max_pocet']."/".$row['max_pocet']; else echo ($row['max_pocet']-pocet_zapisanych_na_termin($row['id_termin']))."/".$row['max_pocet']?></td>
        <td><?php echo $row['poznamka']?></td>
        <td>
        <?php
          $pocet=pocet_mojich_sluzieb($_SESSION['user']['id_user']);
          if ($pocet<3 && ($row['id_termin']==null || (pocet_zapisanych_na_termin($row['id_termin'])<$row['max_pocet'] && !je_zapisany_na_termin($_SESSION['user']['id_user'],$row['id_termin'])))){
          ?>
          <form method=post>
            <input type=hidden name=id_termin value=<?php echo $row['id_termin']?>>
            <input type=hidden name=id_perm_termin value=<?php echo $row['id_perm_termin']?>>
            <input type=hidden name=datum value='<?php echo $datum?>'>
            <input type=submit name=zapis_sluzbu value='Zapíš službu'>
          </form>
        <?php
        }
        ?>
        </td>
      </tr>
    <?php
    }
    ?></table><?php
  }
}

function vypis_spec_temins($page){
  ?>
  <script>
    function potvrd_zmaz_spec_termin(e,term){
      var con=confirm('Naozaj chceš zmazať špeciálny termín "'+term+'" ?');
      if (!con){
        e.preventDefault();
      }
    }
  </script>
  <?php
  $pocet_na_stranke=5;
  ?> 
  <div class='termins_box'><section>
  <h1>Špeciálne termíny</h1>
  <?php
  if ($link=conDB()){
    if ($result=mysql_query('SELECT count(id_termin) as pocet FROM termins WHERE id_perm_termin IS NULL',$link)){
      $row=mysql_fetch_assoc($result);
      if ($row['pocet']>$pocet_na_stranke){
        ?> <div class='pages'><nav>
           <a href='admin_termins.php?page_spec=1'>1.</a> <?php
        if ($page>4) echo "...";
        for ($i= ($page>3) ? $page-2 : 2;($i<=ceil($row["pocet"]/$pocet_na_stranke))and($i<=$page+2);$i++){
          echo "<a href='admin_termins.php?page_spec=".$i."'>".$i.".</a>\n";
        }
        $last_page=ceil($row["pocet"]/$pocet_na_stranke);
        if ($last_page>$page+2){
          if ($last_page>$page+3) echo "...";
          echo "<a href='admin_termins.php?page_spec=".$last_page."'>".$last_page.".</a>\n"; 
        } 
        ?> </nav></div> <?php
      }else if (!$row['pocet']){
        ?> <h2 class="text_alarm">Nenašli sa žiadne špeciálne termíny.</h2> <?php
      }
    }
    if ($result=mysql_query('SELECT * FROM termins WHERE id_perm_termin IS NULL ORDER BY datum,cas LIMIT '.$pocet_na_stranke*($page-1).','.$pocet_na_stranke,$link)){
      ?><table border='1'><tr><th>Dátum</th><th>Čas</th><th>Počet miest</th><th>Poznámka</th><th></th></tr><?php
      while($row=mysql_fetch_assoc($result)){?>
        <tr>
          <td><?php echo date('j.n.Y',strtotime($row['datum']))?></td>
          <td><?php echo date('H:i',strtotime($row['cas']))?></td>
          <td><?php echo $row['max_pocet']?></td>
          <td><?php echo $row['poznamka']?></td>
          <td>
            <a href='uprav_spec_termin.php?id=<?php echo $row['id_termin']?>'>Uprav termín</a>
            <form method='post'>
              <input type=hidden name='id' id='id' value=<?php echo $row['id_termin']?>>
              <input type=submit name=zmaz_spec id=zmaz_spec value='Zmaž termín' onclick='potvrd_zmaz_spec_termin(event,"<?php echo date('j.n.Y',strtotime($row['datum']))." o ".date("H:i",strtotime($row['cas']))?>");'>
            </form>  
          </td>
        </tr>
        <?php
      }
      ?></table><?php
    }
  }
  ?>
  </section></div>
  <?php
}

function zapis_sluzbu($id_user,$id_termin,$id_perm_termin,$datum){
  if ($id_termin==null){
    $link=conDB();
    if ($result=mysql_query("SELECT * FROM permanent_termins WHERE id_perm_termin=$id_perm_termin",$link)){
      $row=mysql_fetch_assoc($result);
      if ($result=mysql_query("INSERT INTO termins SET id_perm_termin=".$id_perm_termin.", cas='".$row['cas']."', datum='".$datum."', 
                               max_pocet=".$row['max_pocet'].", poznamka='".$row['poznamka']."'",$link)){
        if($result=mysql_query('SELECT LAST_INSERT_ID() as id',$link)){
          $row=mysql_fetch_assoc($result);
          $id_termin=$row['id'];
          if(mysql_query("INSERT INTO sluzby SET id_user=".$id_user.", id_termin=".$id_termin,$link)){
            return true;
          }
        }                         
      }
    }  
    return false;
  }
  $link=conDB();
  if ($result=mysql_query("SELECT * FROM termins WHERE id_termin=".$id_termin,$link)){
    if ($row=mysql_fetch_assoc($result)){
      if((pocet_zapisanych_na_termin($id_termin)<$row['max_pocet']) &&
         !je_zapisany_na_termin($id_user,$id_termin) &&
         mysql_query("INSERT INTO sluzby SET id_user=".$id_user.", id_termin=".$id_termin,$link)){
        return true;
      }
    }  
  }
  return false; 
}

function zmaz_level($id_lvl){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT obrazok,obrazok_mini FROM levels WHERE id_lvl='.$id_lvl,$link)){
      $row=mysql_fetch_assoc($result);
      $obrazok=$row['obrazok'];
      $obrazok_mini=$row['obrazok_mini'];
      mysql_free_result($result);
      if ($result=mysql_query('DELETE FROM levels WHERE id_lvl='.$id_lvl,$link)){
        unlink("obr/".$obrazok); 
        unlink("obr/".$obrazok_mini); 
        return True;
      }  
    }
  }
  error('Nepodarilo sa zmazať titul.');
  return False;  
}

function zmaz_oznam($id_oznam){
  if ($link=conDB()){
    if ($result=mysql_query('DELETE FROM oznamy WHERE id_oznam='.$id_oznam,$link)){
      return True;
    }      
  }
  error('Nepodarilo sa zmazať oznam.');
  return False;
}

function zmaz_perm_termin($id){
  if ($link=conDB()){
    if (mysql_query('DELETE FROM termins WHERE (datum>CURDATE() or (datum=CURDATE() and cas>CURTIME())) and id_perm_termin='.$id,$link)&& 
        mysql_query('DELETE FROM permanent_termins WHERE id_perm_termin='.$id,$link)){
      return true;
    }
  }
  error('Nepodarilo sa zmazať permanentný termín.');
  return false;
}

function zmaz_spec_termin($id){
  if ($link=conDB()){
    if (mysql_query('DELETE FROM termins WHERE id_termin='.$id,$link)){
      return true;
    }
  }
  error('Nepodarilo sa zmazať špeciálny termín.');
  return false;
}

function zmaz_profilovku($id_user){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT profilovka FROM users WHERE id_user='.$id_user,$link)){
      $row=mysql_fetch_assoc($result);
      $profilovka=$row['profilovka'];
      mysql_free_result($result);
      if ($result=mysql_query('UPDATE users SET profilovka=0 WHERE id_user='.$id_user,$link)){
        unlink($profilovka); 
        return True;
      }  
    }
  }
  error('Nepodarilo sa zmazať profilovku.');
  return False;
}

function zmaz_pribeh($id_pribeh){
  if ($link=conDB()){
    if ($result=mysql_query('DELETE FROM pribehy WHERE id_pribeh='.$id_pribeh,$link)){
      return True;
    }      
  }
  error('Nepodarilo sa zmazať príbeh.');
  return False;
}

function zmaz_user($id_user){
  if ($link=conDB()){
    if ($result=mysql_query('DELETE FROM users WHERE id_user='.$id_user,$link)){
      return True;
    }      
  }
  error('Nepodarilo sa zmazať bojovníka.');
  return False;
}

function zmen_heslo($id_user,$heslo){
  if ($link=conDB()){
    if ($result=mysql_query('UPDATE users SET heslo="'.md5(addslashes(strip_tags(trim($heslo)))).'" 
        WHERE id_user='.$id_user,$link)){
      return True;    
    }
  }
  error('Nepodarilo sa zmeniť heslo.');
  return False;
} 

function zmen_obrazok_titulu($id_lvl,$file,$type="obrazok"){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT '.$type.' FROM levels WHERE id_lvl='.$id_lvl,$link)){
      $row=mysql_fetch_assoc($result);
      $stary_obrazok=$row[$type];
      mysql_free_result($result);
      if ($result=mysql_query('UPDATE levels SET '.$type.'="'.$file['name'].'" WHERE id_lvl='.$id_lvl,$link)){ 
        if (!move_uploaded_file($file['tmp_name'],"obr/".$file['name'])){ 
          if ($type=="obrazok") error('Nastala chyba pri presune obrázka.');
          else if ($type=="obrazok_mini") error('Nastala chyba pri presune miniatúry.');
        }else{
          if ($stary_obrazok && $stary_obrazok!="obr/".$file['name'] 
              && file_exists($stary_obrazok)) unlink($stary_obrazok);
          return True;
        }
      }  
    }
  }
  if ($type=="obrazok") error('Nepodarilo sa zmeniť obrázok titulu.');
  else if ($type=="obrazok_mini") error('Nepodarilo sa zmeniť miniatúru titulu.');
  return False;
}

function zmen_profilovku($id_user,$file){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT profilovka FROM users WHERE id_user='.$id_user,$link)){
      $row=mysql_fetch_assoc($result);
      $stara_profilovka=$row['profilovka'];
      mysql_free_result($result);
      if ($result=mysql_query('UPDATE users SET profilovka="obr/profilovky/'.$file['name'].'" WHERE id_user='.$id_user,$link)){ 
        if (!move_uploaded_file($file['tmp_name'],"obr/profilovky/".$file['name'])){ 
          error('Nastala chyba pri presune profilovky.'); 
        }else{
          if ($stara_profilovka && $stara_profilovka!="obr/profilovky/".$file['name'] 
              && file_exists($stara_profilovka)) unlink($stara_profilovka);
          return True;
        }
      }  
    }
  }
  error('Nepodarilo sa zmeniť profilovku.');
  return False;
}

function zmen_udaje_titulu($id_lvl,$nazov,$body){
  if ($link=conDB()){
    if ($result=mysql_query("UPDATE levels SET nazov='".addslashes(strip_tags(trim($nazov)))."',
    body=".$body." WHERE id_lvl=".$id_lvl,$link)){
      return True;
    }
  }
  error('Nepodarilo sa upraviť údaje titulu.');
  return False;
}

function zmen_user_udaje($id_user,$meno,$krstne,$priezvisko,$admin){
  if ($link=conDB()){
    $result=mysql_query('SELECT count(id_user) as pocet FROM users WHERE meno="'.addslashes(strip_tags(trim($meno))).'"
    and id_user!='.$id_user,$link);
    if (!$result){
      error('Nepodarilo sa zmeniť údaje.');
      return False;  
    }
    $row=mysql_fetch_assoc($result);
    if ($row['pocet']>0){
      error('Prihlasovacie meno "'.addslashes(strip_tags(trim($meno))).'" je už obsadené.');
      return false;
    }
    mysql_free_result($result);
    if ($result=mysql_query('UPDATE users SET meno="'.addslashes(strip_tags(trim($meno))).'",
        krstne="'.addslashes(strip_tags(trim($krstne))).'",
        priezvisko="'.addslashes(strip_tags(trim($priezvisko))).'",
        admin="'.addslashes(strip_tags(trim($admin))).'"  
        WHERE id_user='.$id_user,$link)){
      return True;    
    }
  }
  error('Nepodarilo sa zmeniť údaje.');
  return False;
}

function zmen_username($id_user,$meno){
  if ($link=conDB()){
    $result=mysql_query('SELECT count(id_user) as pocet FROM users WHERE meno="'.addslashes(strip_tags(trim($meno))).'"',$link);
    if (!$result){
      error('Nepodarilo sa zmeniť prihlasovacie meno.');
      return False;  
    }
    $row=mysql_fetch_assoc($result);
    if ($row['pocet']>0){
      error('Prihlasovacie meno "'.addslashes(strip_tags(trim($meno))).'" je už obsadené.');
      return false;
    }
    mysql_free_result($result);
    if ($result=mysql_query('UPDATE users SET meno="'.addslashes(strip_tags(trim($meno))).'" 
        WHERE id_user='.$id_user,$link)){
      return True;    
    }
  }
  error('Nepodarilo sa zmeniť prihlasovacie meno.');
  return False;
} 



?>