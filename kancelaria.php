<?php
session_start();
include("_funkcie.php");
hlavicka("Kancelária správcu");
if (isset($_SESSION['user'])){
  include('osobny.php');
  if ($_SESSION['user']['admin']){     
  ?>
  <div class=hlavny><main>
    <a href='admin_body.php'><div class='entry_left'>
      <figure><img class='img_medium' src="obr/ikona_admin_body.png" alt="Administrácia bodov"></figure>
    </div></a>
    <a href='admin_users.php'><div class='entry_right'>
      <figure><img class='img_medium' src="obr/ikona_admin_users.png" alt="Administrácia bojovníkov"></figure>
    </div></a>
    <a href='admin_termins.php'><div class='entry_left'>
      <figure><img class='img_medium' src="obr/ikona_admin_termins.png" alt="Administrácia termínov služieb"></figure>
    </div></a>
    <a href='admin_kvizy.php'><div class='entry_right'>
      <figure><img class='img_medium' src="obr/ikona_admin_kvizy.png" alt="Administrácia kvízov"></figure>
    </div></a>
    <a href='admin_levels.php'><div class='entry_left'>
      <figure><img class='img_medium' src="obr/ikona_admin_levels.png" alt="Administrácia titulov"></figure>
    </div></a>
  </main></div>
  <?php
  }else {
  include('no_entry.php');
  }
}else {
  include('login.php');
  include('no_entry.php');
} 
paticka();
?>