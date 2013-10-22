<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php include ("style.php") ?>
<?php
if ($_SESSION['schreiben']==0)
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
<title>Log Datei</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<a name="anfang"></a>
<a href="#ende">nach unten</a><br/>
<table border="1" width="90%">
<tr><th>Eintrag</th><th>Name</th><th>Zeit</th></tr>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //aktueller Benutzer
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut,"<br>";
$l="log ge&ouml;ffnet / ".$_SESSION['kname'];
logsch ($l);

if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$sql = mysql_query("SELECT * FROM `log` ORDER BY 'id'");  //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))                                // aus Datenbank auslesen 
{   $eintrag = $ds->eintrag ;
    $name = $ds->name;
    $time = $ds->time;
echo "<td>";                                                        //ausgeben
echo $eintrag,"<br>";
echo "</td>";                                                       //ausgeben
echo "<td>";
echo $name,"<br>";
echo "</td>";
echo "<td>";
echo $time,"<br>";
echo "</td>";
echo "</tr>";
}
?>
</table>
<a name="ende"></a>
<a href="#anfang">nach oben</a><br> <br>
<form method="POST" action="Ausleihe-m.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$ds = datenzähler in datenbankabfrage
$eintrag = logtext
$l = logtexteintrag eigen
$name = logname
$sql = variable datenbankabfrage
$time = logzeit
*/
?>
