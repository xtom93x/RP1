<?php
session_start();
include("_funkcie.php");

hlavicka("Upravenie oznamu");
if (isset($_SESSION['user'])) {  
  include("osobny.php");
}else include("login.php"); 
if (isset($_SESSION['user']) && substr($_SESSION['user']['admin'],6,1)){
  if (isset($_GET['id']) && $oznam=daj_oznam($_GET['id'])){
    if(isset($_POST['uprav']) && isset($_POST['nadpis']) && spravny_text($_POST['nadpis'])
       && isset($_POST['text']) && spravny_text($_POST['text']) && isset($_POST['active']) 
       && uprav_oznam($oznam['id_oznam'],$_POST['nadpis'],$_POST['text'],$_POST['active'])){
      ?>
      <div class='hlavny'><main>
        <h2>Podarilo sa upraviť oznam.</h2>
      </main></div>
      <?php     
    }else{
?>
    <div class='hlavny'><main>
      <form method=post><table>
        <tr><td><label for=nadpis>Nadpis</label></td>
          <td><input type=text size=30 maxlength=255 id=nadpis name=nadpis value='<?php if (isset($_POST['nadpis'])) echo $_POST['nadpis']; else echo $oznam['nadpis']?>'></td></tr>
        <tr><td><label for=text>Obsah</label></td>
          <td><textarea name=text id=text cols=50 rows=20><?php if (isset($_POST['text'])) echo $_POST['text']; else echo $oznam['text']?></textarea></td></tr>
        <tr><td><label>Aktíny (aktuálny)</label></td>
          <td><input type="radio" name="active" id="active_yes" value=1<?php if ((isset($_POST['active']) && $_POST["active"])||(!isset($_POST['active']) && $oznam['active'])) echo ' checked'; ?>> <label for="active_yes">áno</label></td></tr>
          <tr><td></td><td><input type="radio" name="active" id="active_no" value=0<?php if ((isset($_POST['active']) && !$_POST["active"])||(!isset($_POST['active']) && !$oznam['active'])) echo ' checked'; ?>> <label for="active_no">nie</label></td></tr>
        <tr><td></td><td><input type=submit name=uprav value='Uprav oznam'></td></tr>
      </table></form>
    </main></div>
<?php
    }
  }else{
  ?>
    <div class='hlavny'><main>
      <h2>Oznam sa nenašiel.</h2>
    </main></div>
  <?php
  }
}else include('no_entry.php');
paticka();
?>