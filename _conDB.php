<?php
function conDB() {
	if ($link = mysql_connect('localhost', 'root', 'usbw')) {
		if (mysql_select_db('ministranti', $link)) {
			mysql_query("SET CHARACTER SET 'utf8'", $link); 
			return $link;
		} else {
			error("Nepodarilo sa vybrať databázu!");
			return false;
		}
	} else {
		error("Nepodarilo sa spojiť s databázovým serverom.");
		return false;
	}
}
?>