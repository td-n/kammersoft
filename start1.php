<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
$l="zur&uuml;ck zu start";
logsch ($l);
$query = "TRUNCATE `var`"; //Tabelle Werte löschen
$resultID = @mysql_query($query);
//mysql_close($dz);

?>
<meta http-equiv="refresh" content="0; URL=start.php">
</body>
</html>
<?php  
/*
$l = log eintrag
$query / $resultID = variable in datenbankabfrage
$key / $value = variable in array abarbeitung 
*/
?>