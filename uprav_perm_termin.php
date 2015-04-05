<?php
session_start();
include("_funkcie.php");
hlavicka("Úprava permanentného termínu"); 
if (isset($_SESSION['user'])){
  include('osobny.php');
  if (substr($_SESSION['user']['admin'],3,1)){
    ?><div class=hlavny><main><?php
    if (isset($_POST['uprav_perm'])&& isset($_POST['den']) &&
        isset($_POST['hodiny']) && isset($_POST['minuty']) && isset($_POST['max_pocet'])){
      $poz='';
      if (isset($_POST['poznamka'])) $poz=$_POST['poznamka'];
      if (uprav_perm_termin($_GET['id'],$_POST['den'],$_POST['hodiny'].':'.$_POST['minuty'].":00",$_POST['max_pocet'],$poz)){
        echo "<h2>Podarilo sa upraviť permanentný termín.</h2>";
      }    
    }else if(isset($_GET['id']) && $termin=vrat_perm_termin($_GET['id'])){
      $dni=array('','Nedeľa','Pondelok','Utorok','Streda','Štvrtok','Piatok','Sobota');
      ?>
      <script>
        function potvrd_uprav_perm_termin(e,term){
          var den_elem=document.getElementById('den');
          if (((den_elem.options[den_elem.selectedIndex].value==<?php echo $termin['den']?>)&&
              (document.getElementById('hodiny').value+":"+document.getElementById('minuty').value+":00"=="<?php echo $termin['cas']?>"))&&
              (document.getElementById('max_pocet').value>=<?php echo $termin['max_pocet']?>))
              return;
          var con=confirm('Naozaj chceš upraviť permanentný termín "'+term+'" ?\nSpolu so zmenou sa vymažú všetky zapísané (ešte neuskutočnené) služby viazané na tento termín.');
          if (!con){
            e.preventDefault();
          }
        }
      </script>
      <form method=post><table>
        <tr><td class=opis_pole><label for=den>Deň v týždni:</label></td><td>
          <select name=den id='den' required>
            <option value=2 <?php if((isset($_POST['den']) && $_POST['den']==2)||(!isset($_POST['den']) && $termin['den']==2)) echo "selected";?>>Pondelok</option>
            <option value=3 <?php if((isset($_POST['den']) && $_POST['den']==3)||(!isset($_POST['den']) && $termin['den']==3)) echo "selected";?>>Utorok</option>
            <option value=4 <?php if((isset($_POST['den']) && $_POST['den']==4)||(!isset($_POST['den']) && $termin['den']==4)) echo "selected";?>>Streda</option>
            <option value=5 <?php if((isset($_POST['den']) && $_POST['den']==5)||(!isset($_POST['den']) && $termin['den']==5)) echo "selected";?>>Štvrtok</option>
            <option value=6 <?php if((isset($_POST['den']) && $_POST['den']==6)||(!isset($_POST['den']) && $termin['den']==6)) echo "selected";?>>Piatok</option>
            <option value=7 <?php if((isset($_POST['den']) && $_POST['den']==7)||(!isset($_POST['den']) && $termin['den']==7)) echo "selected";?>>Sobota</option>
            <option value=1 <?php if((isset($_POST['den']) && $_POST['den']==1)||(!isset($_POST['den']) && $termin['den']==1)) echo "selected";?>>Nedeľa</option>
          </select>
        </td></tr>
        <tr><td class=opis_pole><label for=hodiny>Čas:</label></td><td><input type=number class=time_input name=hodiny id=hodiny min=0 max=23 value=<?php if(isset($_POST['hodiny'])) echo $_POST['hodiny'];else echo date("H",strtotime($termin['cas']))?> required>:
          <input type=number class=time_input name=minuty id=minuty min=0 max=59 value=<?php if(isset($_POST['minuty'])) echo $_POST['minuty'];else echo date("i",strtotime($termin['cas']))?> required></td></tr>
        <tr><td class=opis_pole><label for=max_pocet>Počet miest:</label></td><td><input type=number name=max_pocet id=max_pocet min=1 value=<?php if(isset($_POST['max_pocet'])) echo $_POST['max_pocet'];else echo $termin['max_pocet']?> required></td></tr>
        <tr><td class=opis_pole><label for=poznamka>Poznámka:</label></td><td><textarea name=poznamka id=poznamka cols=50 rows=5><?php if(isset($_POST['poznamka'])) echo $_POST['poznamka'];else echo $termin['poznamka']?></textarea></td></tr>
        <tr><td></td><td><input type=submit name=uprav_perm id=uprav_perm onclick='potvrd_uprav_perm_termin(event,"<?php echo $dni[$termin['den']]." o ".date("H:i",strtotime($termin['cas']))?>");' value="Uprav termín"></td></tr>
      </table></form>
      
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