<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php
if ($_SESSION['lesen']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten-all.php" /><?php
}
?>
<?php
if ($_SESSION['schreiben']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
}
?>
<title>Inventur-Tool</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Inventur Tool
</big></big></big><br>
<br>
<?php
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //aktueller Benutzer
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut,"<br>";
$l="Inventur Tool ge&ouml;ffnet / ".$_SESSION['kname'];
logsch ($l);

?>
<form method = "POST" action="invhist.php">
<table border="0" width="70%" cellpadding="0">
<tr><th>Nr.</th><th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>Bestand</th><th>Info</th></tr>
<?php
$jahrj=date("Y");
$sql="SELECT COUNT(nr) FROM invhist WHERE `jahr`= $jahrj";         //anzahl der Einträge
$result = mysql_query($sql);
$zeile = mysql_fetch_row($result);
$max = $zeile['0'];
for ($i=1;$i<=$max;$i++)                                 //alle checkbox löschen
{
    $query1 = "UPDATE `invhist` SET `abgeschl` = '0' WHERE `nr` = $i AND `jahr`= $jahrw  ";
    $resultID1 = @mysql_query($query1);
}
?>
</form>
aktuelles Jahr zur&uuml;ckgesetzt
<a href="invhist.php" >zur Auswahl </a>
</body>
</html>
