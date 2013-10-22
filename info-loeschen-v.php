<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#mysqla.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php include ("style.php") ?>
<?php
if ($_SESSION['schreiben']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
}
?>
<title>M&auml;ngelliste bereinigen</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
M&auml;ngelliste bereinigen
</big></big></big><br>
<?php 
function date_mysql2german($date) {
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); 
}
?>
<form name="eingabe" action="info-loeschen.php" method="post" >
<table border="1" width="80%">
<tr> <th>DB</th><th>Typ</th><th>Nummer</th><th>M&auml;ngel</th><th>R&uuml;cknehmer</th><th>erledigt</th><th>Datum Eintrag</th></tr>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");                                              //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="info l&ouml;schen ge&ouml;ffnet / ".$_SESSION['kname'];
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=vergleich-start.php" /><?php ;
}

$sql = " CREATE TABLE IF NOT EXISTS `manghist` (
    `id` INT( 50 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `var` VARCHAR( 25 ) NOT NULL ,
    `gid` INT( 3 ) NOT NULL ,
    `typ` VARCHAR( 25 ) NOT NULL ,
    `RegNR` INT( 3 ) NOT NULL ,
    `mang` VARCHAR( 125 ) NOT NULL ,
    `Ruecknehmer` VARCHAR( 25 ) NOT NULL ,
    `erl` INT( 3 ) NOT NULL,
    `Datum_rueck` DATE NOT NULL
    ) ENGINE = MYISAM ;
    ";
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());
//echo  $db_erg;

$sql = mysql_query("SELECT * FROM taucherkammer.info ");                          //Tabelle info auswählen
while($ds= mysql_fetch_object($sql))
{
    $id=$ds->ID;
    $idg=$ds->ID_Geraet;
    $mang=$ds->Maengel;
    $rueck=$ds->Rueckgeber;
    $erl=$ds->erl;
    $dat=$ds->Datum;
    $sql1 = mysql_query("SELECT * FROM taucherkammer.geraete WHERE id=$idg "); //Tabelle gereate auswählen
    while($ds1= mysql_fetch_object($sql1))
    {
        $typ = $ds1->Typ ;                                          // aus Datenbank auslesen
        $regnr=$ds1 ->RegNR ;                                       // aus Datenbank auslesen
    }

    $query    = "SELECT Typ FROM taucherkammer.typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
    $query    = "SELECT kname FROM taucherkammer.user WHERE ID=$rueck";            //aus Rückgeber-ID Rückgeber machen
    $resultID = @mysql_query($query);                               //aus Rückgeber-ID Rückgeber machen
    $rueckgan = mysql_result($resultID,0);

    $eintr = "INSERT INTO manghist
        (var, gid, typ, RegNR, mang, Ruecknehmer, erl, Datum_rueck)
        VALUES ('neu','$id','$typan','$regnr','$mang','$rueckgan','$erl', '$dat' )";
    mysql_query($eintr);                                            //in neue tabelle schreiben
}
unset ($typan,$regnr,$mang,$rueck,$erl, $dat);

$sql = mysql_query("SELECT * FROM taucherkammera.info ");                          //Tabelle info auswählen
while($ds= mysql_fetch_object($sql))
{
    $id=$ds->ID;
    $idg=$ds->ID_Geraet;
    $mang=$ds->Maengel;
    $rueck=$ds->Rueckgeber;
    $erl=$ds->erl;
    $dat=$ds->Datum;
    $sql1 = mysql_query("SELECT * FROM taucherkammera.geraete WHERE id=$idg "); //Tabelle gereate auswählen
    while($ds1= mysql_fetch_object($sql1))
    {
        $typ = $ds1->Typ ;                                          // aus Datenbank auslesen
        $regnr=$ds1 ->RegNR ;                                       // aus Datenbank auslesen
    }

    $query    = "SELECT Typ FROM taucherkammera.typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
    $query    = "SELECT kname FROM taucherkammera.user WHERE ID=$rueck";            //aus Rückgeber-ID Rückgeber machen
    $resultID = @mysql_query($query);                               //aus Rückgeber-ID Rückgeber machen
    $rueckgan = mysql_result($resultID,0);

    $eintr = "INSERT INTO manghist
        (var, gid, typ, RegNR, mang, Ruecknehmer, erl, Datum_rueck)
        VALUES ('alt','$id','$typan','$regnr','$mang','$rueckgan','$erl', '$dat' )";
    mysql_query($eintr);                                            //in neue tabelle schreiben
}
unset ($typan,$regnr,$mang,$rueck,$erl, $dat);


$sql = mysql_query("SELECT * FROM manghist ORDER BY typ, RegNR, var");      //Tabelle manghist auswählen
while($ds1= mysql_fetch_object($sql))
{
    $var=$ds1->var;
    $gidv=$ds1->gid;
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
    if ($var== "neu")
    {
        ?>
        <td bgcolor =#00FF00 >
        <?php
        echo $var;
        ?>
        </td>
        <?php
    }
    else
    {
        ?>
        <td bgcolor =#FFFF00 >
        <?php
        echo $var;
        ?>
        </td>
        <?php
    }
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

$sql = " DROP TABLE IF EXISTS `manghist` ";                   //tabelle löschen
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());

?>
</table>
</form>

<br>
<form method="POST" action="Info-v.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$d = variable in funktionen
$dat = eintragsdatum in info engl
$data = $dat in deu
$date = übergabevariable in function
$db_erg = variable in datenbank erstellen
$ds / $ds1 = datenzähler
$eintr = variable in datenbankeintragen
$erl = mängel erledigt
$erla = text erledigt in tabelle
$ew = logtext
$gidv = ID in maenghist
$heut = heute deu
$heuteng = heute engl
$id = id in info
$idg = geräteid in info
$l = logeintrag
$mang = mängel in info
$query / $query1 = variable in datenbankabfrage
$regnr = gegnummer in geräte
$resultID / $resultID1 = variable in datenbankabfrage
$rueck = rückgeber in info
$rueckg = rücknehmer in maenghist
$rueckgan = rücknehmer in user
$sql / $sql1 = variable in datenbankabfrage
$typ = typnummer in geräte
$typan = typtext in typ
$val = ausgewählter eintrag id
$valg = ausgewählte id in info
*/
?>
