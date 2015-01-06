<?php
session_start();
include("_funkcie.php");

hlavicka("Upravenie príbehu");
if (isset($_SESSION['user'])) {  
  include("osobny.php");
}else include("login.php"); 
if (isset($_SESSION['user']) && substr($_SESSION['user']['admin'],5,1)){
  if (isset($_GET['id']) && $pribeh=daj_pribeh($_GET['id'])){
    if(isset($_POST['uprav']) && isset($_POST['nazov']) && spravny_text($_POST['nazov'])
       && isset($_POST['text']) && spravny_text($_POST['text']) && uprav_pribeh($pribeh['id_pribeh'],$_POST['nazov'],$_POST['text'])){
      ?>
      <div class='hlavny'><main>
        <h2>Podarilo sa upraviť príbeh.</h2>
      </main></div>
      <?php     
    }else{
?>
    <div class='hlavny'><main>
      <form method=post><table>
        <tr><td><label for=nazov>Názov</label></td>
          <td><input type=text size=30 maxlength=255 id=nazov name=nazov value='<?php if (isset($_POST['nazov'])) echo $_POST['nazov']; else echo $pribeh['nazov']?>' required></td></tr>
        <tr><td><label for=text>Obsah</label></td>
          <td><textarea name=text id=text cols=50 rows=20 required><?php if (isset($_POST['text'])) echo $_POST['text']; else echo $pribeh['text']?></textarea></td></tr>
        <tr><td></td><td><input type=submit name=uprav value='Uprav príbeh'></td></tr>
      </table></form>
    </main></div>
<?php
    }
  }else{
  ?>
    <div class='hlavny'><main>
      <h2>Príbeh sa nenašiel.</h2>
    </main></div>
  <?php
  }
}else include('no_entry.php');
paticka();

?>