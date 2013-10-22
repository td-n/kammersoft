<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php include ("style.php") ?>
<?php
if ($_SESSION['lesen']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten-all.php" /><?php
}
?>
<?php
function date_mysql2german($date) {
    $d    =    explode("-",$date);                                  //in deutsches Format wandeln
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
?>
<title>Packetausleihe - Auswahl</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Packetausleihe - Auswahl
</big></big></big>
<br>
<form name="ausw" action="paketeintr1.php" method="post" >
<br>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="paketeintrag ge&ouml;ffnet";
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte anzeigen
{
  echo $key." => ".$value."<br />\n";
}
*/
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$sql = mysql_query("SELECT * FROM var "); //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds= mysql_fetch_object($sql))
{
    $pnr = $ds->user ;
}
//echo "<br>",$pnr;

?>
<table border="1" width="90%">
<tr><th>ausw&auml;hlen</th><th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th></tr>
<?php 


$sql1 = mysql_query("SELECT * FROM conf WHERE wert='tuevflasche'");    //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1= mysql_fetch_object($sql1))
{
    $tueff=$ds1->was;
}
$sql1 = mysql_query("SELECT * FROM conf WHERE wert='tuevregler'");    //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1= mysql_fetch_object($sql1))
{
    $tuefr=$ds1->was;
}

$sql = mysql_query("SELECT * FROM geraete ORDER BY typ, RegNR");    //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid = $ds->ID;
    $typ = $ds->Typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->RegNR ;                                            // aus Datenbank auslesen
    $hersteller= $ds->Hersteller ;                                  // aus Datenbank auslesen
    $bemerkung= $ds->Bemerk;	                                    // aus Datenbank auslesen
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
        echo "<td><input type=\"checkbox\" name=\"ausl[]\" value= \" $gid \" />";
        echo "</td>";
    echo "<td>";                                                    //ausgeben
    echo $typan,"<br>";
    echo "</td>";
    echo "<td>";
    echo $regnr,"<br>";
    echo "</td>";
    echo "<td>";
    echo $hersteller,"<br>";
    echo "</td>";
    echo "<td>";
    echo $bemerkung,"<br>";
    echo "</td>";
    echo "</tr>";
}
?>
</table>
<input type="submit" name="eintr" value="eintragen" class="Button-w"/>ausgew&auml;hlte in die Paketliste eintragen
</form>
<form name="zu" action="paketeintr.php" method="post" >
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
