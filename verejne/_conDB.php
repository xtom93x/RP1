<?php
function conDB() {
	if ($link = mysql_connect('mysql.hostinger.sk', 'u121972399_rp', 'uniba65')) {
		if (mysql_select_db('u121972399_rp', $link)) {
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