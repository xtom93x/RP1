<div class="navigacia"><nav>
    <a href=index.php>Domov</a>
    <a href=score.php>Rytierská sála</a>
    <a href=tron.php>Trónna sála</a>
<?php
if (isset($_SESSION['user'])) {
?>
    <a href=training.php>Cvičisko</a>
<?php 
  if ($_SESSION['user']['admin']){
?>
    <a href=kancelaria.php>Kancelária správcu</a>
<?php    
  }
}  
?>
    <a href=kniznica.php>Knižnica</a>
    <a href=about.php>O stránke</a>
</nav></div>