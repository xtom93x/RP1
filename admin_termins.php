<?php
session_start();
include("_funkcie.php");
hlavicka("Administrácia termínov služieb"); 
if (isset($_SESSION['user'])){
  include('osobny.php');
  if (substr($_SESSION['user']['admin'],3,1)){
    ?>
    <div class='hlavny'><main>
    <a href='admin_termins.php?page_perm=1'><div class='entry_left'>
      <figure><img class='img_small' src="obr/ikona_hodiny.png" alt="Hodiny"></figure>
      <h2>Permanentné termíny</h2>
    </div></a>
    <a href='admin_termins.php?page_spec=1'><div class='entry_right'>
      <figure><img class='img_small' src="obr/ikona_kalendar.png" alt="Kalendár"></figure>
      <h2>Špeciálne termíny</h2>
    </div></a>
    <?php
    if (isset($_POST['zmaz_perm'])){
        zmaz_perm_termin($_POST['id']);
    }
    if (isset($_POST['zmaz_spec'])){
      zmaz_spec_termin($_POST['id']);      
    }
    if(isset($_GET['page_spec'])){
      if (isset($_POST['pridaj_spec'])&& isset($_POST['datum']) &&
          isset($_POST['hodiny']) && isset($_POST['minuty']) && isset($_POST['max_pocet'])){
        $poz='';
        if (isset($_POST['poznamka'])) $poz=$_POST['poznamka'];
        if (pridaj_spec_termin($_POST['datum'],$_POST['hodiny'].':'.$_POST['minuty'].":00",$_POST['max_pocet'],$poz)){
          echo "<h2>Podarilo sa pridať špeciálny termín.</h2>";
        }    
      }
      vypis_spec_temins($_GET['page_spec']);
      ?>
        <hr>
        <script type='text/javascript' src='_js/kalendar_admin_term.js'></script>
        <h2>Pridaj nový špeciálny termín</h2>
        <form method=post><table>
          <tr><td class=opis_pole><label id=datum_label>Dátum:<?php echo date("j.n.Y");?></label></td><td>
            <input type=hidden id='datum' name='datum' value='<?php echo date("Y-m-d");?>'>
            <div id=kalendar_box></div>  
          </td></tr>
          <tr><td class=opis_pole><label for=hodiny>Čas:</label></td><td><input type=number class=time_input name=hodiny min=0 max=23 value=12 required>:
            <input type=number class=time_input name=minuty min=0 max=59 value=0 required></td></tr>
          <tr></tr>
          <tr><td class=opis_pole><label for=max_pocet>Počet miest:</label></td><td><input type=number name=max_pocet min=1 value=3 required></td></tr>
          <tr><td class=opis_pole><label for=poznamka>Poznámka:</label></td><td><textarea name=poznamka id=poznamka cols=50 rows=5></textarea></td></tr>
          <tr><td></td><td><input type=submit name=pridaj_spec id=pridaj_spec value="Pridaj termín"></td></tr>
        </table></form>
        <script>
          zobrazKalendar(m,r);
        </script>
      <?php  
    }else{
      if (isset($_POST['pridaj_perm'])&& isset($_POST['den']) &&
          isset($_POST['hodiny']) && isset($_POST['minuty']) && isset($_POST['max_pocet'])){
        $poz='';
        if (isset($_POST['poznamka'])) $poz=$_POST['poznamka'];
        if (pridaj_perm_termin($_POST['den'],$_POST['hodiny'].':'.$_POST['minuty'].":00",$_POST['max_pocet'],$poz)){
          echo "<h2>Podarilo sa pridať permanentný termín.</h2>";
        }    
      }
      if (isset($_GET['page_perm']))  
        vypis_perm_temins($_GET['page_perm']);
      else vypis_perm_temins(1);
      ?>
        <hr>
        <h2>Pridaj nový permanentný termín</h2>
        <form method=post><table>
          <tr><td class=opis_pole><label for=den>Deň v týždni:</label></td><td>
            <select name=den id=den required>
              <option value=2>Pondelok</option>
              <option value=3>Utorok</option>
              <option value=4>Streda</option>
              <option value=5>Štvrtok</option>
              <option value=6>Piatok</option>
              <option value=7>Sobota</option>
              <option value=1 selected>Nedeľa</option>
            </select>
          </td></tr>
          <tr><td class=opis_pole><label for=hodiny>Čas:</label></td><td><input type=number class=time_input name=hodiny min=0 max=23 value=12 required>:
            <input type=number class=time_input name=minuty min=0 max=59 value=0 required></td></tr>
          <tr></tr>
          <tr><td class=opis_pole><label for=max_pocet>Počet miest:</label></td><td><input type=number name=max_pocet min=1 value=3 required></td></tr>
          <tr><td class=opis_pole><label for=poznamka>Poznámka:</label></td><td><textarea name=poznamka id=poznamka cols=50 rows=5></textarea></td></tr>
          <tr><td></td><td><input type=submit name=pridaj_perm id=pridaj_perm value="Pridaj termín"></td></tr>
        </table></form>
      <?php
    }
    ?></main></div><?php
  }else include('no_entry.php'); 
}else {
  include('login.php');
  include('no_entry.php');
} 
paticka();
?>