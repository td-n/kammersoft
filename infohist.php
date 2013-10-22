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
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
?>
<title>M&auml;ngelhistorie</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
M&auml;ngelhistorie
</big></big></big><br>
<a name="anfang"></a>
<a href="#ende">nach unten</a>
<br>
<table border="1" width="80%">
<tr> <th>Typ</th><th>Nummer</th><th>M&auml;ngel</th><th>R&uuml;cknehmer</th><th>erledigt</th><th>Datum Eintrag</th></tr>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                       //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="infohist ge&ouml;ffnet";
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

$sql = " CREATE TABLE IF NOT EXISTS `manghist` (                    
    `id` INT( 50 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `typ` VARCHAR( 25 ) NOT NULL ,
    `RegNR` INT( 3 ) NOT NULL ,
    `mang` VARCHAR( 125 ) NOT NULL ,
    `Ruecknehmer` VARCHAR( 25 ) NOT NULL ,
    `erl` INT( 3 ) NOT NULL,
    `Datum_rueck` DATE NOT NULL
    ) ENGINE = MYISAM ;
    ";                                                              //virtuelle tabelle erstellen
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());
//echo  $db_erg;
$sql = mysql_query("SELECT * FROM info ");                              
while($ds= mysql_fetch_object($sql))
{
    $idg=$ds->ID_Geraet;
    $mang=$ds->Maengel;
    $rueck=$ds->Rueckgeber;
    $erl=$ds->erl;
    $dat=$ds->Datum;
    $sql1 = mysql_query("SELECT * FROM geraete WHERE id=$idg "); //Tabelle gereate auswählen
    while($ds1= mysql_fetch_object($sql1))
    {
        $typ = $ds1->Typ ;                                          // aus Datenbank auslesen
        $regnr=$ds1 ->RegNR ;                                       // aus Datenbank auslesen
    }

    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
    $query    = "SELECT Name FROM user WHERE ID=$rueck";            //aus Rückgeber-ID Rückgeber machen
    $resultID = @mysql_query($query);                               //aus Rückgeber-ID Rückgeber machen
    $rueckgan = mysql_result($resultID,0);

    $eintr = "INSERT INTO manghist                                  
        (typ, RegNR, mang, Ruecknehmer, erl, Datum_rueck)
        VALUES ('$typan','$regnr','$mang','$rueckgan','$erl', '$dat' )";
    mysql_query($eintr);                                            //Tabelle mit info füllen
}    
unset ($typan,$regnr,$mang,$rueck,$erl, $dat);
    
if (isset($_POST['erl']))
{
    $sql = mysql_query("SELECT * FROM manghist ORDER BY erl");      //Tabelle manghist auswählen
}
else
{
    $sql = mysql_query("SELECT * FROM manghist ORDER BY typ, RegNR"); //Tabelle manghist auswählen

};
while($ds1= mysql_fetch_object($sql))                               //Anzeige füllen
{
    $typ=$ds1->typ;
    $regnr=$ds1->RegNR;
    $mang=$ds1->mang;
    $rueckg=$ds1->Ruecknehmer;
    $erl=$ds1->erl;
    $dat=$ds1->Datum_rueck;    
    if ($erl==0)
    {
        $erla="nein";
    }
    else
    {
        $erla="ja";
    }
    $data= "" . date_mysql2german($dat) . " \n";
    echo "<tr>";
    echo "<td>",$typ,"</td>";
    echo "<td>",$regnr,"</td>";
    if ($mang==" ")
        {
            $mang="-";
        }
    echo "<td>",$mang,"</td>";
    echo "<td>",$rueckg,"</td>";
    echo "<td>",$erla,"</td>";
    echo "<td>",$data,"</td>";
    echo "</tr>";
}

$sql = " DROP TABLE IF EXISTS `manghist` ";                         //tabelle löschen
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());

?>
</table>
<a name="ende"></a>
<a href="#anfang">nach oben</a><br> <br>
<form name="eingabe" action="infohist.php" method="post" >
<input type="submit" name="erl1" value="nach Ger&auml;t sortieren">
<input type="submit" name="erl" value="nach erledigt sortieren"><br>
</form>
<form method="POST" action="infohist.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$d = variable in function
$dat = eintragsdatum in mängelliste
$db_erg = variable in datenbankerstellung
$ds / $ds1 = datebankzähler
$eintr = variable in datenbankeinlesen
$erl = variable mängel erledigt
$erla = anzeiege mängel erledigt
$heut = heute deu
$heuteng = ehute engl
$idg = geräteid in info
$l = logeintrag
$mang = mängel in infi
$query / resultID = variable in datenbankabfrage
$regnr = regnummer in geräte
$rueck = rückgeber
$rueckg = rücknehmer in virtueller tabelle
$rueckgan = rücknehmer in user
$sql / $sql1 = variable in datenbankabfrage
$typ = typnummer in gerät
$typan = typtext in typ
*/
?>
