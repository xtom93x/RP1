<?php
session_start();
include("_funkcie.php");
hlavicka("Úprava špeciálneho termínu"); 
if (isset($_SESSION['user'])){
  include('osobny.php');
  if (substr($_SESSION['user']['admin'],3,1)){
    ?><div class=hlavny><main><?php
    if (isset($_POST['uprav_spec'])&& isset($_POST['datum']) &&
        isset($_POST['hodiny']) && isset($_POST['minuty']) && isset($_POST['max_pocet'])){
      $poz='';
      if (isset($_POST['poznamka'])) $poz=$_POST['poznamka'];
      if (uprav_spec_termin($_GET['id'],$_POST['datum'],$_POST['hodiny'].':'.$_POST['minuty'].":00",$_POST['max_pocet'],$poz)){
        echo "<h2>Podarilo sa upraviť špeciálny termín.</h2>";
      }    
    }else if(isset($_GET['id']) && $termin=vrat_spec_termin($_GET['id'])){
      ?>
      <script type='text/javascript' src='_js/kalendar_admin_term.js'></script>
      <form method=post><table>
        <tr><td class=opis_pole><label id=datum_label>Dátum:<?php echo date("j.n.Y");?></label></td><td>
          <input type=hidden id='datum' name='datum' value='<?php echo date("Y-m-d");?>'>
          <div id=kalendar_box></div>  
        </td></tr>
        <tr><td class=opis_pole><label for=hodiny>Čas:</label></td><td><input type=number class=time_input name=hodiny min=0 max=23 value=<?php if(isset($_POST['hodiny'])) echo $_POST['hodiny'];else echo date("H",strtotime($termin['cas']))?> required>:
          <input type=number class=time_input name=minuty min=0 max=59 value=<?php if(isset($_POST['minuty'])) echo $_POST['minuty'];else echo date("i",strtotime($termin['cas']))?> required></td></tr>
        <tr><td class=opis_pole><label for=max_pocet>Počet miest:</label></td><td><input type=number name=max_pocet min=1 value=<?php if(isset($_POST['max_pocet'])) echo $_POST['max_pocet'];else echo $termin['max_pocet']?> required></td></tr>
        <tr><td class=opis_pole><label for=poznamka>Poznámka:</label></td><td><textarea name=poznamka id=poznamka cols=50 rows=5><?php if(isset($_POST['poznamka'])) echo $_POST['poznamka'];else echo $termin['poznamka']?></textarea></td></tr>
        <tr><td></td><td><input type=submit name=uprav_spec id=uprav_spec value="Uprav termín"></td></tr>
      </table></form>
      <script>
          aktual_d=<?php if(isset($_POST['datum'])) echo date('j',strtotime($_POST['datum'])); else echo date('j',strtotime($termin['datum']));?>;
          aktual_m=<?php if(isset($_POST['datum'])) echo date('n',strtotime($_POST['datum'])); else echo date('n',strtotime($termin['datum']));?>;
          aktual_r=<?php if(isset($_POST['datum'])) echo date('Y',strtotime($_POST['datum'])); else echo date('Y',strtotime($termin['datum']));?>;
          d=aktual_d;
          m=aktual_m;
          r=aktual_r;
          zobrazKalendar(aktual_m,aktual_r);
      </script>
      <?php
    }else echo "<h1 class=alarm>Termín sa nenašiel.</h1>";
    ?></main></div><?php
  }else include('no_entry.php'); 
}else {
  include('login.php');
  include('no_entry.php');
} 
paticka();
?>