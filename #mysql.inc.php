<?php
$host="localhost";
$user="root";
$pass="";
$database="taucherkammer";
$dz= mysql_connect($host, $user, $pass);
mysql_select_db($database, $dz);
/*
$host = internetadresse
$user = Anmeldename Datenbank
$pass = Passwort f�r Datenbank
$database = zu ladende Datenbank
*/
$sql = mysql_query("SELECT * FROM `conf` WHERE `wert` = 'verein'");         //Tabelle conf ausw�hlen
while($ds= mysql_fetch_object($sql))
{
//     $verein = $ds->was;
}
// echo $verein,"<br>"; 

?>
