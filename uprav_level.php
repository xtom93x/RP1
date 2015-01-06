<?php
session_start();
include("_funkcie.php");
hlavicka("Úprava titulu");
if (isset($_SESSION['user'])){
  include('osobny.php');
  if (substr($_SESSION['user']['admin'],2,1)){
    ?>
    <div class=hlavny><main>
    <?php
    if (isset($_POST['nazov']) && spravny_text($_POST['nazov'])
        && isset($_POST['body']) && ($_POST['body']*1)>=0){
      if (zmen_udaje_titulu($_GET['id'],$_POST['nazov'],$_POST['body'])) $hlaska='Podarilo sa upraviť titul.';    
    }
    else if (isset($_POST['zmen_obrazok']) && isset($_FILES['obrazok']) && spravny_obrazok($_FILES['obrazok'],1000000)){
      if (zmen_obrazok_titulu($_GET['id'],$_FILES['obrazok'])) $hlaska="Podarilo sa zmeniť obrázok titulu.";    
    }
    else if (isset($_POST['zmen_obrazok_mini']) && isset($_FILES['obrazok_mini']) && spravny_obrazok($_FILES['obrazok_mini'],1000000)){
      if (zmen_obrazok_titulu($_GET['id'],$_FILES['obrazok_mini'],"obrazok_mini")) $hlaska="Podarilo sa zmeniť miniatúru titulu.";    
    }
    if (!isset($_GET['id']) || !$level=daj_level_vsetko($_GET['id'])){
      ?>
      <h2>Titul sa nenašiel.</h2>
      </main></div>
      <?php
    }else{
    if (isset($hlaska)) echo "<h2>".$hlaska."</h2>\n";
    ?>
      <table>
      <form method=post>
        <tr><td class=opis_pole><label for=nazov>Názov:</label></td>
          <td><input type=text size=30 id=nazov name=nazov value='<?php if (isset($_POST['nazov'])) echo $_POST['nazov']; else echo $level['nazov'];?>' required></td></tr>
        <tr><td class=opis_pole><label for=body>Body:</label></td>
          <td><input type=text size=30 id=body name=body value='<?php if (isset($_POST['body'])) echo $_POST['body']; else echo $level['body'];?>' required></td></tr>
        <tr><td></td><td><input type=submit name=zmen_titul value="Zmeň názov a body"></td></tr>
      </form>
      </table>
      <hr>
      <table> 
      <form method=post enctype="multipart/form-data">
        <tr><td><label>Obrázok:</label></td>
          <td><a href='obr/<?php echo $level['obrazok'];?>'><figure><img class='img_small' src='obr/<?php echo $level['obrazok'];?>'></figure></a></td></tr>
        <tr><td><label for=obrazok>Nový obrázok (max.1MB):</label></td>
          <td><input type="file" name="obrazok" id="obrazok" accept='image/jpg,image/jpeg,image/gif,image/png' required></td></tr>
        <tr><td></td><td><input type=submit name=zmen_obrazok value='Zmen obrázok titulu'></td></tr>
      </form>
      </table>
      <hr>
      <table> 
      <form method=post enctype="multipart/form-data">
        <tr><td><label>Obrázok miniatúra:</label></td>
          <td><a href='obr/<?php echo $level['obrazok_mini'];?>'><figure><img class='img_small' src='obr/<?php echo $level['obrazok_mini'];?>'></figure></a></td></tr>
        <tr><td><label for=obrazok_mini>Nová miniatúra(max.1MB):</label></td>
          <td><input type="file" name="obrazok_mini" id="obrazok_mini" accept='image/jpg,image/jpeg,image/gif,image/png' required></td></tr>
        <tr><td></td><td><input type=submit name=zmen_obrazok_mini value='Zmen obrázok titulu'></td></tr>
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