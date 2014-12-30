<?php
session_start();
include("_funkcie.php");
hlavicka("Kancelária správcu");
if (isset($_SESSION['user'])){
  include('osobny.php');
  if (substr($_SESSION['user']['admin'],2,1)){
    ?>
    <div class=hlavny><main>
    <?php
    if (isset($_POST['zmaz_level']) && isset($_POST['id_level'])){
      zmaz_level($_POST['id_level']);
    }
    else if (isset($_POST['nazov']) && spravny_text($_POST['nazov'])
        && isset($_POST['body']) && ($_POST['body']*1)>=0
        && isset($_FILES['obrazok']) && spravny_obrazok($_FILES['obrazok'],1000000)
        && isset($_FILES['obrazok_mini']) && spravny_obrazok($_FILES['obrazok_mini'],1000000)){
      if (pridaj_level($_POST['nazov'],$_POST['body'],$_FILES['obrazok'],$_FILES['obrazok_mini'])) $hlaska='Podarilo sa pridať titul.';    
    }
    if (isset($hlaska)) echo "<h2>".$hlaska."</h2>";
    vypis_admin_levels();
    ?>
      <hr>
      <script type='text/javascript' src='_js/JS.js'></script>
      <form method=post enctype="multipart/form-data">
        <h2>Pridaj titul</h2>
        <table>
        <tr><td class=opis_pole><label for=nazov>Názov:</label></td>
          <td><input type=text size=30 id=nazov name=nazov value='<?php if (isset($_POST['nazov'])) echo $_POST['nazov'];?>' required></td></tr>
        <tr><td class=opis_pole><label for=body>Body:</label></td>
          <td><input type=text size=30 id=body name=body value='<?php if (isset($_POST['body'])) echo $_POST['body'];?>' required></td></tr>
        <tr><td class=opis_pole><label for=obrazok>Obrázok (max.1MB):</label></td>
          <td><input type="file" name="obrazok" id="obrazok" accept='image/jpg,image/jpeg,image/gif,image/png' required></td></tr>
        <tr><td class=opis_pole><label for=obrazok_mini>Obrázok miniatúra (max.1MB):</label></td>
          <td><input type="file" name="obrazok_mini" id="obrazok_mini" accept='image/jpg,image/jpeg,image/gif,image/png' required></td></tr>
        <tr><td></td><td><input type=submit name='pridaj_level' value='Pridaj titul'></td></tr>
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