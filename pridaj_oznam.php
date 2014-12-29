<?php
session_start();
include("_funkcie.php");

hlavicka("Pridávanie oznamu");
if (isset($_SESSION['user'])) {  
  include("osobny.php");
}else include("login.php"); 
if (isset($_SESSION['user']) && substr($_SESSION['user']['admin'],6,1)){
  if(isset($_POST['pridaj']) && isset($_POST['nadpis']) && spravny_text($_POST['nadpis'])
     && isset($_POST['text']) && spravny_text($_POST['text']) && pridaj_oznam($_POST['nadpis'],$_POST['text'])){
    ?>
    <div class='hlavny'><main>
      <h2>Podarilo sa pridať oznam.</h2>
    </main></div>
    <?php     
  }else{
?>
  <div class='hlavny'><main>
    <form method=post><table>
      <tr><td><label for=nadpis>Nadpis</label></td>
        <td><input type=text size=30 maxlength=255 id=nadpis name=nadpis <?php if (isset($_POST['nadpis'])) echo "value='".$_POST['nadpis']."'";?>></td></tr>
      <tr><td><label for=text>Obsah</label></td>
        <td><textarea name=text id=text cols=50 rows=20><?php if (isset($_POST['text'])) echo $_POST['text'];?></textarea></td></tr>
      <tr><td></td><td><input type=submit name=pridaj value='Pridaj oznam'></td></tr>
    </table></form>
  </main></div>
<?php
  }
}else include('no_entry.php');
paticka();
?>