<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#mysqla.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php include ("style.php") ?>
<?php
if ($_SESSION['lesen']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
}
function date_mysql2german($date) {
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
?>
<title>Reservierung anzeigen - Vergleich</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Reservierung anzeigen - Vergleich
</big></big></big><br>
<br>
<table border="1" width="90%">
<tr> <th>DB</th><th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>ausgeliehen</th><th>Res. von</th><th>Res. bis</th><th>Name der Res.</th><th>Ausleihgrund</th></tr>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                       //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="reserva ge&ouml;ffnet";
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

$sql = " CREATE TABLE IF NOT EXISTS `taucherkammera`.`vergleich` (
    `id` INT( 50 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `var` VARCHAR( 25 ) NOT NULL,
    `gid` VARCHAR( 25 ) NOT NULL ,
    `typ` VARCHAR( 60 ) NOT NULL ,
    `regnr` VARCHAR( 5 ) NOT NULL ,
    `bemerkung` VARCHAR( 60 ) NOT NULL ,
    `rep_notw` TINYINT(1) NOT NULL,
    `tuev` DATE NULL DEFAULT NULL,
    `ausgeliehen` VARCHAR( 25 ) NOT NULL ,
    `info` VARCHAR( 60 ) NOT NULL,
    `reuckn` VARCHAR( 60 ) NOT NULL,
    `erl` TINYINT(1) NOT NULL,
    `dat_eintr` INT( 10 ) NOT NULL,
    `hersteller` VARCHAR( 60 ) NOT NULL ,
    `res_von` INT( 10 ) NOT NULL,
    `res_bis` INT( 10 ) NOT NULL,
    `res` TINYINT(1) NOT NULL,
    `auslgr` VARCHAR( 60 ) NOT NULL ,
    `nam` VARCHAR( 60 ) NOT NULL
    ) ENGINE = MYISAM ;
    ";
$db_erg = mysql_query($sql)                             // MySQL-Anweisung ausführen lassen
or die("Anfrage fehlgeschlagen: " . mysql_error());

$sql1 = mysql_query("SELECT * FROM taucherkammer.res ");         //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1= mysql_fetch_object($sql1))
    {
        $rid=$ds1->id;
        $gid=$ds1->gid;
        $vone=$ds1->von;
        $bise=$ds1->bis;
        $von= "" . date_mysql2german($vone) . " \n";
        $bis= "" . date_mysql2german($bise) . " \n";
        $nam=$ds1->name;
        $grund=$ds1->grund;
        $sql = mysql_query("SELECT * FROM taucherkammer.geraete WHERE ID=$gid ORDER BY typ, RegNR");           //Tabelle gereate auswählen
        while($ds= mysql_fetch_object($sql))
        {
            $gid=$ds->ID;
            $typ = $ds->Typ ;                                               // aus Datenbank auslesen
            $regnr= $ds->RegNR ;                                            // aus Datenbank auslesen
            $hersteller= $ds->Hersteller ;                                  // aus Datenbank auslesen
            $bemerkung= $ds->Bemerk;	                                    // aus Datenbank auslesen
            $ausgel=$ds->ausgeliehen;
            $res=$ds->res;
            $query    = "SELECT Typ FROM taucherkammer.typ WHERE ID=$typ";                //aus Typ-ID Typ machen
            $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
            $typan = mysql_result($resultID,0);

            $query = "INSERT INTO `taucherkammera`.`vergleich`
            (`var`,`gid`,`typ`,`regnr`,`hersteller`,`bemerkung`,`ausgeliehen`,`res_von`, `res_bis`, `nam`, `auslgr`)
            VALUES ('neu','$gid','$typan','$regnr','$hersteller','$bemerkung','$ausgel','$vone','$bise','$nam','$grund')";
            mysql_query($query);
        }
    }

$sql1 = mysql_query("SELECT * FROM taucherkammera.res ");         //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1= mysql_fetch_object($sql1))
    {
        $rid=$ds1->id;
        $gid=$ds1->gid;
        $vone=$ds1->von;
        $bise=$ds1->bis;
        $von= "" . date_mysql2german($vone) . " \n";
        $bis= "" . date_mysql2german($bise) . " \n";
        $nam=$ds1->name;
        $grund=$ds1->grund;
        $sql = mysql_query("SELECT * FROM taucherkammera.geraete WHERE ID=$gid ORDER BY typ, RegNR");           //Tabelle gereate auswählen
        while($ds= mysql_fetch_object($sql))
        {
            $gid=$ds->ID;
            $typ = $ds->Typ ;                                               // aus Datenbank auslesen
            $regnr= $ds->RegNR ;                                            // aus Datenbank auslesen
            $hersteller= $ds->Hersteller ;                                  // aus Datenbank auslesen
            $bemerkung= $ds->Bemerk;	                                    // aus Datenbank auslesen
            $ausgel=$ds->ausgeliehen;
            $res=$ds->res;
            $query    = "SELECT Typ FROM taucherkammera.typ WHERE ID=$typ";                //aus Typ-ID Typ machen
            $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
            $typan = mysql_result($resultID,0);

            $query = "INSERT INTO `taucherkammera`.`vergleich`
            (`var`,`gid`,`typ`,`regnr`,`hersteller`,`bemerkung`,`ausgeliehen`,`res_von`, `res_bis`, `nam`, `auslgr`)
            VALUES ('alt','$gid','$typan','$regnr','$hersteller','$bemerkung','$ausgel','$vone','$bise','$nam','$grund')";
            mysql_query($query);
        }
    }

$sql = mysql_query("SELECT * FROM `taucherkammera`.`vergleich` ORDER BY typ, regnr, var");    //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
    {
        $gid = $ds->id ;
        $var = $ds->var;
        $typan = $ds->typ ;                                               // aus Datenbank auslesen
        $regnr= $ds->regnr ;
        $hersteller= $ds->hersteller ;
        $bemerkung= $ds->bemerkung;
        $von=$ds->res_von;
        $bis=$ds->res_bis;
        $nam=$ds->nam;
        $grund=$ds->auslgr;
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
        echo "<td>",$typan,"</td>";
        echo "<td>",$regnr,"</td>";
        if ($hersteller!="")
        {
            echo "<td>",$hersteller,"</td>";
        }
        else
        {
            echo "<td>"," - ","</td>";
        }
        if ($bemerkung!="")
        {
            echo "<td>",$bemerkung,"</td>";
        }
        else
        {
            echo "<td>"," - ","</td>";
        }
        if ($ausgel == "1")                                             //bei ausgeliehen auf rot setzen
        {
            ?>
            <td bgcolor =#FF6F6C >
            <input type="checkbox" name="anzei[]" checked disabled />
            </td>
            <?php
        }
        else
        {
            ?>
            <td>
            <input type="checkbox" name="anzei[]" disabled />
            </td>
            
            <?php
        }
        echo "<td>",$von,"</td>";
        echo "<td>",$bis,"</td>";
        echo "<td>",$nam,"</td>";
        echo "<td>",$grund,"</td>";
        echo "</tr>";
    }
$sql = " DROP TABLE IF EXISTS `taucherkammera`.`vergleich` ";                   //tabelle löschen
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());

//mysql_close($dz);
?>
</table>
</form>
<form method="POST" action="reserva-v.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php
/*
$anzahl = anzahl einträge in res
$ausgel = ausgeliehen in geräte
$bemerkung = bemerkung in geräte
$bis = bis in res deu
$bise = bis in res engl
$d = variable in function
$date = übergabevariable un function
$ds / $ds1 = datenzähler
$ew = log text
$gid geräte id in res
$grund = ausleihgrund in res
$hersteller = hersteller in geräte
$heut heute deu
$heuteng = heute engl
$key = variable in array auslesen
$l = log eintrag
$nam = name in res
$query / $query1 = variable in res
$regnr = regnummer in geräte
$res = reserviert in geräte
$result / $resultID / $resultID1 = veriable in daternbankabfrage
$rid = id in res
$sql / $sql1 = variable in datenbankabfrage
$typ = typ nummer in geräte
$typan = typ name in typ
$user = user in res
$val = variable in array abfrage
$von = von in res deu
$vone = von in res engl
$z = zeilennumemr in res von gid
$zeile = zeilenanzahl in res
*/
?>
