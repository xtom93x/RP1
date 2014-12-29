<?php
echo "<div class='osobny'><aside>\n";
 
if ($_SESSION['user']['admin']) {
  echo "<h1>Vitaj správca ".$_SESSION['user']['krstne'].".</h1>\n";
}
else echo "<h1>".$_SESSION["user"]["krstne"]." ".$_SESSION["user"]["priezvisko"]."</h1>\n";

echo "<figure><img class='img_small' src='";
if ($_SESSION['user']['profilovka']){
  echo $_SESSION['user']['profilovka'];
} else echo "obr/user.png";
echo "'></figure>\n";
echo "<a href=uprav_profil.php>Upraviť profil</a>\n";

echo "<p>Hodnosť: <strong>".$_SESSION['user']['level']['nazov']."</strong></p>\n";

echo "<p>Body za miništrovanie: ".$_SESSION['user']['body_minis']."</p>\n";

echo "<p>Bonusové body: ".$_SESSION['user']['body_bonus']."</p>\n";
if ($_SESSION['user']['body_bonus']-($_SESSION['user']['body_minis']/2)>0)
  echo "<p class='text_alarm'>Bonusové body mimo limit: ".($_SESSION['user']['body_bonus']-($_SESSION['user']['body_minis']/2))."</p>\n";
else echo "<p>Všetky bonusové body sú v limite. :)</p>\n";

echo "<p><strong>Celkovo bodov: ".$_SESSION['user']['body']."</strong></p>\n";

echo "<figure><a href='obr/".$_SESSION['user']['level']['obrazok']."'>
  <img class='img_small' src='obr/".$_SESSION['user']['level']['obrazok_mini']."' alt='".$_SESSION['user']['level']['nazov']."'></a></figure>\n";
echo "</aside></div>\n";
?>