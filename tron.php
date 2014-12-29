<?php
session_start();
include("_funkcie.php");

hlavicka("Trónna sála");
if (isset($_SESSION['user'])){
  include('osobny.php');
}else{
  include('login.php');
}

?>
<div class=hlavny><main>
  <h1>Pravidlá</h1>
  <figure><img src='obr/jesus_throne.jpg' class=img_medium></figure>
  <p style='text-align:left;'>
  Vitajte chlapci na hrade Krista Kráľa!

  Dopočuli sme sa, že sa chcete stať miništrantami - Božími bojovníkmi. Teda dobre, 
  príjmame vás do našich radov, ale nezabúdajte, že miništrovanie je veľká česť, 
  slúžime pri bohoslužbách samému Kráľovi kráľov, preto sa musíme všetci správať slušne a dôstojne. 

  Ako v každej garde, aj v tejto sa sami musíte vypracovať na najvyššie posty.<br> 
  Ako na to:
  </p>
  <ul>
    <li>za každú omšu na ktorej budeš miništrovať získaš 5 bodov (nezabudni sa zapísať 
      do tabuľky v sakristii)</li>
    <li>za účasť a aktivitu na stretkách môžeš získať bonusové body</li>
    <li>keďže ide predovšetkým o miništrantskú súťaž, bonusové body môžu tvoriť maximálne 
      jednu tretinu zo všetkých tvojich bodov<br>
      (napr. máš 100 bodov za miništrovanie a 80 bonusových bodov ---> 150 skóre a 30 bonusových bodov 
      čaká až budeš mať viac bodov za miništrovanie :) )</li>
    <li>každý bojovník (miništrant) dostane na prvom stretku prihlasovacie meno a heslo, pomocou 
      ktorého bude mať prístup do hradu na stránke súťaže 
      <a href="www.ministranti.besaba.com">www.ministranti.besaba.com</a></li>
    <li>na stránke si každý bude môcť zapísať služby, kedy a na ktorú omšu príde miništrovať a 
      tak získať viac bodov za omšu</li>
    <li>na stránke sa taktiež budú objavovať aj zaujímavé kvízi za ktoré sa bude dať získať bonusové body</li>
    <li>za každým, keď dosiahnete určitú hranicu bodov, získate nový titul, ktorý uvidíte v 
      tabulke najlepších miništrantov na stránke súťaže</li>
    <li>na konci súťaže najlepší 3 získajú výnimočný titul Lord (1. miesto), Barón (2.miesto), 
      alebo Zeman (3. miesto)</li>
    <li>možnosť priebežnej zmeny pravidiel si vyhradzujú organizátori súťaže :)</li>
    <li>pamätajme, že chodíme miništrovať hlavne kôli Bohu a súťaž je až na 2. mieste :)</li>
  </ul>
  <?php vypis_hodnosti_pravidla();?>
</main></div>
<?php
paticka();

?>