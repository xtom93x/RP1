<?php
session_start();
include("_funkcie.php");
hlavicka("Úprava profilu");
if (isset($_SESSION['user'])){
  if (isset($_POST['zmen_username'])){ 
    if (isset($_POST['meno']) && spravny_text($_POST['meno'])
        && zmen_username($_SESSION['user']['id_user'],$_POST['meno'])){
      obnov_user();
      $hlaska="<p><strong>Prihlasovacie meno bolo zmenené.</strong></p>\n";
    }
  }
  elseif (isset($_POST['zmen_heslo'])){
    if (isset($_POST['heslo_old']) && spravny_text($_POST['heslo_old'])
        && over_identitu($_SESSION['user']['id_user'],$_POST['heslo_old']) 
        && isset($_POST['heslo']) && spravny_text($_POST['heslo'])
        && isset($_POST['heslo_rp']) && spravny_text($_POST['heslo_rp'])
        && $_POST['heslo']==$_POST['heslo_rp'] && zmen_heslo($_SESSION['user']['id_user'],$_POST['heslo'])){
      obnov_user();
      $hlaska="<p><strong>Prihlasovacie heslo bolo zmenené.</strong></p>\n";           
    }
  }
  elseif (isset($_POST['zmen_profilovku'])){
    if (isset($_FILES['profilovka']) && spravny_obrazok($_FILES['profilovka'],1000000) 
        && zmen_profilovku($_SESSION['user']['id_user'],$_FILES['profilovka'])){
      obnov_user();
      $hlaska="<p><strong>Profilovka bola zmenená.</strong></p>\n";    
    }
  }  
  include("osobny.php"); 
  ?>
  <div class='hlavny'><main>
    <?php if (isset($hlaska)) echo $hlaska;?>
    <h2><?php echo $_SESSION['user']['krstne']." ".$_SESSION['user']['priezvisko'];?></h2>
    <table>
      <form method=post>
        <tr><td><label for=meno>Prihlasovacie meno:</label></td>
        <td><input type=text size=30 id=meno name=meno value= <?php if (isset($_POST['meno'])) echo $_POST['meno']; else echo $_SESSION['user']['meno'];?> </td></tr>
        <tr><td></td><td><input type=submit name=zmen_username value="Zmeniť prihlasovacie meno"></td></tr>
      </form>
      <form method=post>
        <tr><td><label for=heslo_old>Staré heslo:</label></td>
        <td><input type=password size=30 id=heslo_old name=heslo_old></td></tr>
        <tr><td><label for=heslo>Nové heslo:</label></td>
        <td><input type=password size=30 id=heslo name=heslo></td></tr>
        <tr><td><label for=heslo_rp>Zopakuj nové heslo:</label></td>
        <td><input type=password size=30 id=heslo_rp name=heslo_rp></td></tr>
        <tr><td></td><td><input type=submit name=zmen_heslo value="Zmeniť heslo"></td></tr>
      </form>
      <form method="post" enctype="multipart/form-data">
        <tr><td><label for=profilovka>Profilovka (max.1MB):</label></td>
        <td><input type="file" name="profilovka" id="profilovka" accept='image/jpg,image/jpeg,image/gif,image/png'></td></tr>
        <tr><td></td><td><input type=submit name=zmen_profilovku value="Zmeniť profilovku"></td></tr>
      </form>
    </table>
  </main></div>
<?php
}else{
  include("login.php"); 
  include('no_entry.php');
}
paticka();
?>