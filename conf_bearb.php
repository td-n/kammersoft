<html>
<head>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php include ("style.php") ?>
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
<title>Konfiguration bearbeiten</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Konfiguration bearbeiten<br>
</big></big></big>
<form name="conf" action="conf_bearb.php" method="post" >
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");  //heutuger Datum
echo $heut,"<br>";
$l="Konfiguration bearbeiten ge&ouml;ffnet / ".$_SESSION['kname'];
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/

if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}
    
if (isset($_POST['eintr']))
{
    $ft=$_POST['ft'];
    $query = "UPDATE `conf` SET  `was` = '$ft' WHERE `wert` = 'tuevflasche' ";
    $resultID1 = @mysql_query($query);                 //Daten updaten
    $rt=$_POST['rt'];
    $query = "UPDATE `conf` SET  `was` = '$rt' WHERE `wert` = 'tuevregler' ";
    $resultID1 = @mysql_query($query);                 //Daten updaten
    $vs=$_POST['vs'];
    $query = "UPDATE `conf` SET  `was` = '$vs' WHERE `wert` = 'vereinvorsitzende' ";
    $resultID1 = @mysql_query($query);                 //Daten updaten
    $tc=$_POST['tc'];
    $query = "UPDATE `conf` SET  `was` = '$tc' WHERE `wert` = 'technikverantwortliche' ";
    $resultID1 = @mysql_query($query);                 //Daten updaten
}

$sql = mysql_query("SELECT * FROM conf WHERE wert= 'tuevflasche' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $tuevflasche=$ds->was;
}
$sql = mysql_query("SELECT * FROM conf WHERE wert='tuevregler' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $tuevregler=$ds->was;
}
$sql = mysql_query("SELECT * FROM conf WHERE wert='vereinvorsitzende' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $vorsitz=$ds->was;
}
$sql = mysql_query("SELECT * FROM conf WHERE wert='technikverantwortliche' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $tkchef=$ds->was;
}
$sql = mysql_query("SELECT * FROM conf WHERE wert= 'Sichlw' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $slw=$ds->was;
}
$sql = mysql_query("SELECT * FROM conf WHERE wert= 'desktopverzeichnis' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $dvz=$ds->was;
}
$sql = mysql_query("SELECT * FROM conf WHERE wert= 'sicherungsverzeichnis' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $svz=$ds->was;
}
$sql = mysql_query("SELECT * FROM conf WHERE wert= 'verein' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $v=$ds->was;
}
$sql = mysql_query("SELECT * FROM conf WHERE wert= 'instverz' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $ivz=$ds->was;
}

//echo "-",$tuevflasche,"+",$tuevregler,"+",$vorsitz,"+",$tkchef,"-";
?>
<table border="1" >
<tr> <th>Bezeichnung</th><th>Eintrag</th> </tr>
<?php 
    echo "<tr><td>";                                                    //ausgeben
    echo "G&uuml;ltigkeit eines Flaschen-T&Uuml;V's in Tagen", "<br>";
    echo "</td>";
    echo "<td>";                                                    //ausgeben
//    echo $tuevflasche, "<br>";
    echo "<input type=\"text\" name=\"ft\" value=\"$tuevflasche\" />"; 
    echo "</td></tr>";
    echo "<tr><td>";                                                    //ausgeben
    echo "G&uuml;ltigkeit eines Regler-T&Uuml;V's in Tagen", "<br>";
    echo "</td>";
    echo "<td>";                                                    //ausgeben
    echo "<input type=\"text\" name=\"rt\" value=\"$tuevregler\" />";
    echo "</td></tr>";
    echo "<tr><td>";                                                    //ausgeben
    echo "Vereinsvorsitzende", "<br>";
    echo "</td>";
    echo "<td>";                                                    //ausgeben
    echo "<input type=\"text\" name=\"vs\" value=\"$vorsitz\" />";
    echo "</td></tr>";
    echo "<tr><td>";                                                    //ausgeben
    echo "Technikkammerverantwortlicher", "<br>";
    echo "</td>";
    echo "<td>";                                                    //ausgeben
    echo "<input type=\"text\" name=\"tc\" value=\"$tkchef\" />";
    echo "</td></tr>";
?>
</table>
<input type="submit" name= "eintr" value="eintragen" class="Button-w" /><font color="#FF0000"><big> vorgenommene &Auml;nderungen werden abgespeichert!</big><br></font>
</form>
<font color="#0000FF"><big>Anzeige von Eintragungen: </big><br></font>
<table border="1" >
<tr> <th>Bezeichnung</th><th>Eintrag</th> </tr>
<?php  
    echo "<tr><td>";                                                    //ausgeben
    echo "Sicherungslaufwerk", "<br>";
    echo "</td>";
    echo "<td>";                                                    //ausgeben
    echo $slw;
    echo "</td></tr>";
    echo "<tr><td>";                                                    //ausgeben
    echo "Desktopverzeichnis", "<br>";
    echo "</td>";
    echo "<td>";                                                    //ausgeben
    echo $dvz;
    echo "</td></tr>";
    echo "<tr><td>";                                                    //ausgeben
    echo "Sichrungsverzeichnis", "<br>";
    echo "</td>";
    echo "<td>";                                                    //ausgeben
    echo $svz;
    echo "</td></tr>";
    echo "<tr><td>";                                                    //ausgeben
    echo "Verein", "<br>";
    echo "</td>";
    echo "<td>";                                                    //ausgeben
    echo $v;
    echo "</td></tr>";
    echo "<tr><td>";                                                    //ausgeben
    echo "Installationsverzeichnis", "<br>";
    echo "</td>";
    echo "<td>";                                                    //ausgeben
    echo $ivz;
    echo "</td></tr>";
?>
</table>
<form method="POST" action="conf_bearb.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$ds = variable in datenbankabfrage
$ft / $tuevflasche = anzahl tage des flaschentüv
$heut = heutiges datum
$l = log eintrag
$query / $resultID1 / $sql = variable in datenbankabfrage
$rt / $tuevregler =  anzahl tage des reglertüv
$tc / $tkchef = technikverantwortlicher
$vs / $vorsitz = vereinsvorsitzender
*/
?>