<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php

while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}

$vereine="Tauchklub Dresden Nord";
$k=mysql_query("SELECT * FROM conf WHERE wert='code' ");
if (mysql_num_rows($k)==1)
    echo "ja";
else
{
echo "nein";
$query = "INSERT INTO `conf` (`wert`)VALUES ('code')";
mysql_query($query);
} 
$vereinp=md5($vereine);
$query1 = "UPDATE `conf` SET  `was` = '$vereinp' WHERE `wert` = 'code'";
$resultID1 = @mysql_query($query1);

echo "<br>",$vereine,"<br>", $vereinp,"<br>",$resultID1;



/*
$eintrag= mysql_query("INSERT INTO `conf` (`wert` ,`was`)
VALUES  ('code',  '')");
$db_erg = mysql_query($eintrag)                             // MySQL-Anweisung ausführen lassen
or die("Anfrage fehlgeschlagen: " . mysql_error());
echo  $db_erg;
*/

/*

*/




/*
$query = "
INSERT INTO  `taucherkammer`.`invhist` (
`info`
)
VALUES (
'abc'
)";
$resultID = @mysql_query($query);
echo "+",$resultID,"+","<br>";

$query = "UPDATE  `taucherkammer`.`invhist` SET `info` = '2' WHERE `id` = '12' ";
$resultID = @mysql_query($query);
echo "+",$resultID,"+","<br>";

// UPDATE  `taucherkammer`.`invhist` SET  `info` =  'abc' WHERE  `invhist`.`id` =12;
*/
?>




