<?php
session_start();
include("_funkcie.php");

hlavicka("O stránke");
if (isset($_SESSION['user'])){
  include('osobny.php');
}else{
  include('login.php');
}

?>
<div class=hlavny><main>
  <h1>O stránke</h1>
  <p style='text-align:left;'>
  Táto webová stránka (aplikácia) slúži na administráciu miništrantskej súťaže na Trnávke.<br>
  Aplikácia je určená predovšetkým administrátorom miništrantskej súťaže, ktorými sú
rehoľníci Seliazínov dona Bosca a starší miništranti. Druhou, ale početnejšou
skupinou sú samotní miništranti ako účastníci súťaže. Väčšina obsahu aplikácie je
chránená prihlasovacími menami a heslami, ale obsahuje aj stránky dostupné pre
verejnosť.
  </p>
  <p>
  Témou miništrantskej súťaže na Trnávke na rok 2014/2015 sú Boží bojovníci a preto
je aplikácia zasadená do stredovekej tématiky (organizácia stránok je riešená ako
jednotlivé miestnosti na Hrade Krista Kráľa). Miništranti budú získavať základné body za
účasť a miništrovanie na bohoslužbách. Bonusové body získajú za služby, ktoré si budú môcť
zapísať pomocou aplikácie a za správne odpovede v kvízoch. Do celkového skóre sa bude
pripočítavať maximálne toľko bonusových bodov, ako polovica bodov získaných za
miništrovanie na omšiach. Vďaka týmto bodom budú postupovať v tabuľke najlepších
miništrantov a získavať vyššie tituly (levely). Na konci školského roka budú ohodnotení traja
najlepší miništranti cenami a titulmi Lord (1. miesto), Barón (2. miesto) a Zeman (3. miesto).
  </p>
  <p>
  Webová aplikácia sa skladá z verejných častí (časť hlavnej stránky, rytierská sála -
tabuľka súťažiacich, knižnica - archív článkov), častí určených pre súťažiacich (hlavná
stránka, zápis služieb, kvízy) a častí, kam má prístup iba administrátor (pridávanie
súťažiacich, úprava bodového stavu, vytváranie kvízov atď.).
  </p>
  <ul>
  Časti stránky a ich funkcie:
    <li><strong>Hlavná stránka:</strong><br> Na hlavnej stránke sa bude prihlasovací formulár a okno s najnovším príbehov, oznamom
a odkazom na stránku s pravidlami súťaže. Zobrazená bude taktiež tabuľka súťažiacich a ich
bodový stav. Tabuľka bude len informačná, jednoduchá, usporiadaná od najlepších po
najhorších.<br>
Prihlásení používatelia budú vidieť svoj podrobný bodový stav, titul a fotku pod ktorou
bude odkaz na stránku profilu, kde si bude používateľ môcť zmeniť prihlasovacie údaje a
fotku.<br>
Okno s týmto obsahom bude po prihlásení viditeľné na každej stránke. Na každej stránke
sa bude taktiež dať odhlásiť.</li>
    <li><strong>Rytierska sála:</strong><br> V "rytierskej sále" bude graficky vyspelejšia tabuľka súťažiacich, ktorá bude obsahovať
aj obrázky k titulom, fotky súťažiacich a bude sa dať usporiadať podľa mena, priezviska a
bodov.</li>
    <li><strong>Cvičisko:</strong><br> "Cvičisko" bude rozdelené na dve časti.
V prvej sa budú spravovať služby. Súťažiaci si budú môcť listovať v termínoch služieb a
prihlasovať sa na ne. Na vrchu stránky bude tabuľka služieb na ktoré sa používateľ už
prihlásil a tu si ich bude aj môcť odhlásiť. Každý súťažiaci sa bude môcť prihlásiť na
maximálne 3 služby naraz.<br>
Administrátori budú vidieť aj služby, ktoré už prebehli a podľa prezenčky zo sakristie v
kostole budú môcť pridávať body. Keď každý z miništrantov obdrží body za službu, alebo
bude označený ako "dezertér" (neprišiel na bohoslužbu), termín sa sám odstráni. Termíny
služieb, ktoré ešte neprebehli bude administrátor môcť upraviť, prípadne zrušiť.<br>
V druhej časti "cvičiska" budú kvízy. Súťažiaci budú vidieť, ktoré kvízy ešte
nezodpovedali a zodpovedať na ne. Otázky môžu byť dvoch typov - s možnosťami, ktoré
bude opravovať samotná aplikácia a otázky typu "vlastnými slovami", ktoré bude musieť
administrátor ohodnotiť.<br>
Administrátori budú v tejto časti môcť vytvárať kvízy, otázky a prideľovať im možné
odpovede, prípadne typ odpovede a predovšetkým opravovať odpovede súťažiacich.</li> 
    <li><strong>Kancelária správcu:</strong><br> Táto časť bude prístupná len administrátorom. "Správca" tu bude môcť prihlasovať
nových súťažiacich, meniť ich údaje (pre prípad chybného zadania) a bodový stav, pridávať
nové tituly a termíny služieb.</li>
    <li><strong>Knižnica:</strong><br> Knižnica bude plniť funkciu archívu príbehov z hlavnej strany a oznamov. Tu taktiež
bude môcť administrátor pridávať nové príbehy a oznamy.</li>
    <li><strong>Trónna sála:</strong><br> V trónnej sále sú zverejnené pravidlá a vypísaná tabuľka aktuálnych hodností.</li> 
  </ul>
  <a href='špecifikácia.pdf'>Špecifikácia (.pdf)</a>
</main></div>
<?php
paticka();

?>