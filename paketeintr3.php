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
<title>Paketeintrag speichern</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Paketeintrag speichern
</big></big></big>
<br>
<?php
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$sql = mysql_query("SELECT * FROM var WHERE gida=-9");                       //Tabelle var auswählen
while($ds= mysql_fetch_object($sql))
{
    $paket = $ds->user ;                                         // aus Datenbank var auslesen
}
//echo "Paket:",$paket,"<br>";
$sql = mysql_query("SELECT * FROM var WHERE gida > 0");         //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds= mysql_fetch_object($sql))
{                                               //in ausleihe schreiben
    $gida = $ds->gida ;                                          // Geräte aus Datenbank var auslesen
    $sql1 = mysql_query("SELECT * FROM geraete WHERE ID=$gida");           //Tabelle gereate auswählen
    while($ds1= mysql_fetch_object($sql1))
    {
        $gid = $ds1->ID ;
        $typ = $ds1->Typ ;                                               // aus Datenbank auslesen
        $regnr= $ds1->RegNR ;                                            // aus Datenbank auslesen
    }
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);

    $eintr = "INSERT INTO `paket` (`paketnr`,`typ`,`regnr`,`geraeteid`) VALUES ('$paket','$typan','$regnr','$gida')";  //Rückgabedatum in var eintragen
    mysql_query($eintr);
//    echo $gida,"-",$typ,"-",$typan,"-",$regnr,"<br>";
}
$sql = mysql_query("SELECT * FROM var WHERE gida = 0");         //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds= mysql_fetch_object($sql))
{                                               //in ausleihe schreiben
     $vers = $ds->mang ;
     echo "<br>",$vers,"<br>";

    $eintr = "INSERT INTO `paket` (`paketnr`,`verschiedenes`) VALUES ('$paket','$vers')";  //Rückgabedatum in var eintragen
    mysql_query($eintr);
}
$query = "TRUNCATE `var`"; //Tabelle Werte löschen
$resultID = @mysql_query($query);

?><meta http-equiv="refresh" content="0; URL=packausw.php"><?php
?>
<form method="POST" action="paketeintr3.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
