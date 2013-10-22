
<?php include ("#mysql.inc.php"); ?>

<?php
//Update1

$res=mysql_query("
ALTER TABLE  `geraete` 
ADD  `ges_tauchg` INT( 11 ) 
NOT NULL DEFAULT  '0'
");

$res1=mysql_query("
ALTER TABLE  `geraete` 
ADD  `kaufjahr` INT( 11 ) 
NOT NULL
");

if(($res=="1")AND($res1=="1"))
{
    echo "<br>Update1 - erfolgreich durchgef&uuml;hrt<br>"; 
}
else
{
    echo "<br>Update1 - bitte kontrollieren Sie die Tabelle Geräte, ob eine Spalten kaufjahr und ges_tauchg existieren, dann wurde es bereits durchgef&uuml;hrt<br>";
}

//Update 2
$sql = mysql_query("SELECT * FROM `conf` WHERE wert = 'verein' ");         //Tabelle conf auswählen
while($ds= mysql_fetch_object($sql))
{
    $verein = $ds->was;
}
if (isset($verein))
{
    echo "Update2-1 bereits eingespielt";
}
else
{
    $query = "INSERT INTO `conf` (`wert`)VALUES ('verein')";
    mysql_query($query);
    echo "Update2-1 eingespielt";
}



$sql = " CREATE TABLE IF NOT EXISTS `paket` (
    `id` INT( 50 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `paketnr` VARCHAR( 5 ) NOT NULL ,
    `typ` VARCHAR( 60 ) NOT NULL ,
    `regnr` VARCHAR( 5 ) NOT NULL ,
    `geraeteid` VARCHAR( 5 ) NOT NULL ,
    `verschiedenes` VARCHAR( 60 ) NOT NULL
    ) ENGINE = MYISAM ;
    ";
$db_erg = mysql_query($sql)                             // MySQL-Anweisung ausführen lassen
or die("Anfrage fehlgeschlagen: " . mysql_error());
//echo $db_erg;
if($db_erg=="1")
{
    echo "<br>Update2 - erfolgreich durchgef&uuml;hrt<br>";
}
else
{
    echo "<br>Update2 - bitte kontrollieren Sie die Tabelle paket wenn sie existiert wurde es bereits durchgef&uuml;hrt<br>";
}




?>