<html>
<head>
<?php include ("#mysql.inc.php"); ?>
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
<tr> <th>Typ</th><th>Nummer</th><th>M&auml;ngel</th><th>R&uuml;cknehmer</th><th>erledigt</th><th>Datum Eintrag</th><th>l&ouml;schen</th></tr>
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
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$sql = " CREATE TABLE IF NOT EXISTS `manghist` (
    `id` INT( 50 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
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

if (isset($_POST['erl2']))                                          //Eintrag löschen wenn erledigt
{
    if (isset($_POST['bearbeiten']))
    {
            $val=$_POST['bearbeiten'];                              //id in mängelliste

            $query    = "SELECT ID_Geraet FROM info WHERE ID=$val"; //aus info-id geräte-id machen
            $resultID = @mysql_query($query);
            $valg = mysql_result($resultID,0);                      //id in geräte
//            echo "+",$val, $valg;

            $sql = mysql_query("SELECT * FROM info WHERE ID=$val"); //Tabelle info auswählen
            while($ds= mysql_fetch_object($sql))
            {
                $idg=$ds->ID_Geraet;
                $mang=$ds->Maengel;
                $rueck=$ds->Rueckgeber;
                $erl=$ds->erl;
                $dat=$ds->Datum;                                    //datum eintrag
                $sql1 = mysql_query("SELECT * FROM geraete WHERE id=$idg "); //Tabelle gereate auswählen
                while($ds1= mysql_fetch_object($sql1))
                {
                    $typ = $ds1->Typ ;                              // aus Datenbank auslesen
                    $regnr=$ds1 ->RegNR ;                           
                }
                $query    = "SELECT Typ FROM typ WHERE ID=$typ";    //aus Typ-ID Typ machen
                $resultID = @mysql_query($query);                   //aus Typ-ID Typ machen
                $typan = mysql_result($resultID,0);
                $query    = "SELECT kname FROM user WHERE ID=$rueck"; //aus Rückgeber-ID Rückgeber machen
                $resultID = @mysql_query($query);                   //aus Rückgeber-ID Rückgeber machen
                $rueckgan = mysql_result($resultID,0);
            }
            $query = "DELETE FROM `info` WHERE ID=$val ";           //ausgewählten Eintrag löschen
            $resultID = @mysql_query($query);

            $query1 = "UPDATE `geraete` SET  `Rep_notw` = '0' WHERE `geraete`.`ID` = $valg";  //in geräte rep notw setzen
            $resultID1 = @mysql_query($query1);
 
        $ew=" in M&auml;ngelliste geloescht" ;                      //log
        $l=$typan. "-". $regnr. "/". $mang. "  vom: ". $dat. $ew ;
//        echo $l;
        logsch($l);      
    }
    else
    {
        ?><font color="#FF0000"><big>bitte w&auml;hlen sie einen Eintrag</big></font><?php 
    }
}
$sql = mysql_query("SELECT * FROM info ");                          //Tabelle info auswählen
while($ds= mysql_fetch_object($sql))
{
    $id=$ds->ID;
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
    $query    = "SELECT kname FROM user WHERE ID=$rueck";            //aus Rückgeber-ID Rückgeber machen
    $resultID = @mysql_query($query);                               //aus Rückgeber-ID Rückgeber machen
    $rueckgan = mysql_result($resultID,0);

    $eintr = "INSERT INTO manghist
        (gid, typ, RegNR, mang, Ruecknehmer, erl, Datum_rueck)
        VALUES ('$id','$typan','$regnr','$mang','$rueckgan','$erl', '$dat' )";
    mysql_query($eintr);                                            //in neue tabelle schreiben
}
unset ($typan,$regnr,$mang,$rueck,$erl, $dat);

if ((isset($_POST['erl'])) or ((!isset($_POST['erl'])) and (!isset($_POST['erl1']))))
{
    $sql = mysql_query("SELECT * FROM manghist ORDER BY erl");      //Tabelle manghist auswählen
}
elseif (isset($_POST['erl1']))
{
    $sql = mysql_query("SELECT * FROM manghist ORDER BY typ, RegNR"); //Tabelle manghist eintragen

};
while($ds1= mysql_fetch_object($sql))
{
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
        echo "<td><input type=\"radio\" name=\"bearbeiten\" value= \" $gidv \" /></td>";
//        <input type="checkbox" name="anzei[]" /></td>
    echo "</tr>";
}

$sql = " DROP TABLE IF EXISTS `manghist` ";                   //tabelle löschen
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());

?>
</table>
<input type="submit" name="erl1" value="nach Ger&auml;t sortieren">
<input type="submit" name="erl" value="nach erledigt sortieren"><br><br>
<input type="submit" name="erl2" value="l&ouml;schen"><font color="#FF0000"><big>ausgew&auml;hlte Eintr&auml;ge l&ouml;schen</big><br></font>
</form>

<br>
<form method="POST" action="info-loeschen.php">
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
