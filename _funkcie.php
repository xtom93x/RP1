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

function daj_najnovsi_oznam(){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT * FROM oznamy WHERE active=1 ORDER BY datum DESC LIMIT 1',$link)){
      return mysql_fetch_assoc($result);
    } else {
      error('Nepodarilo sa načítať najnovší oznam.');
      return False;
    }
  } else return False;
}

function daj_najnovsi_pribeh(){
  if ($link=conDB()){
    if ($result=mysql_query('SELECT * FROM pribehy ORDER BY datum DESC LIMIT 1',$link)){
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
          <p><?php echo $row['text'];?></p>
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
          <p><?php echo $row['text'];?></p>
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
  $result_m=mysql_query('SELECT body, krstne, priezvisko, profilovka FROM users WHERE visible=1 ORDER BY '.$by.' '.$diverg,$link);
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

function zmaz_oznam($id_oznam){
  if ($link=conDB()){
    if ($result=mysql_query('DELETE FROM oznamy WHERE id_oznam='.$id_oznam,$link)){
      return True;
    }      
  }
  error('Nepodarilo sa zmazať oznam.');
  return False;
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