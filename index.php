<?php
session_start();
include("_funkcie.php");
if (isset($_POST['meno']) && isset($_POST['heslo']) && 
    spravny_text($_POST['meno']) && spravny_text($_POST['heslo']) &&
    $user=prihlas($_POST['meno'],$_POST['heslo'])){ 
  $_SESSION['user']=$user;
}
else if (isset($_POST['odhlas'])) odhlas();
if (!isset($_SESSION['user'])) {
  hlavicka("Vstupná hala");
  include("login.php"); 
  ?>
  <div class='hlavny'><main>
    <h1>Vitaj!</h1>
    <p>Stojíte vo Vstupnej hale hradu Krista Kráľa. Ak ste členom jeho Gardy, 
      tak sa prihláste u veliteľa stráže a môžete sa voľne pohybovať po celom hrade. 
      Ak ste len náhodný okoloidúci, tak sa môžete pozrieť do 
      <a href=skore.php>Rytierskej</a> alebo <a href=tron.php>Trónnej sály</a> alebo do
      <a href=kniznica.php>Knižnice</a>.
    </p>
  </main></div>
<?php
  paticka();
} else {
  $_SESSION['user']['level']=daj_level($_SESSION['user']['body']); 
  hlavicka("Domov");
  include("osobny.php");
  ?>
  <div class='hlavny'><main>
    <div class='odkaz_pravidla'>
      <a href='tron.php'>Pravidlá</a>
    </div>
  <?php
  
  if ($oznam=daj_najnovsi_oznam()){
    echo "<div class='uvodny_oznam'><article>\n";
    echo "<h2>".$oznam['nadpis']."</h2>\n";
    echo "<p>".$oznam['text']."</p>\n";
    echo "<p class='low_prior'>".date('j.n.Y',strtotime($oznam['datum']))."</p>\n";
    echo "</article></div>\n";
  }
  
  echo "<div class='uvodny_pribeh'><article>\n";
  if ($pribeh=daj_najnovsi_pribeh()){
    echo "<h2>".$pribeh['nazov']."</h2>\n";
    echo "<p>".$pribeh['text']."</p>\n";
    echo "<p class='low_prior'>".$pribeh['datum']."</p>\n";
  } else{
    echo "<p class='text_alarm'>Nepodarilo sa načítať príbeh.</p>\n";
  }
  echo "</article></div>\n";
                         
  daj_uvodne_score();
  echo "</main></div>\n";  
  paticka();
}
?>