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
    ?><meta http-equiv="refresh" content="0; URL=forbitten-all.php" /><?php
}
?>
<?php
function date_mysql2german($date)                                   //in deutsches Format wandeln
{
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date)                                     //in datenbankformat wandeln
{
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); 
}
function date2timestamp($datum) {                                   //timestamp erstellen
    list($tag, $monat, $jahr) = explode(".", $datum);
    $jahr = sprintf("%04d", $jahr);
	$monat = sprintf("%02d", $monat);
    $tag = sprintf("%02d", $tag);
	return(mktime(0, 0, 0, $monat, $tag, $jahr));  
}
?>
<title>Vergleich - M&auml;ngelliste</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Vergleich - M&auml;ngelliste
</big></big></big>
<br>
<br>
<?php 
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //aktueller Benutzer
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut,"<br>";
$l="Info ge&ouml;ffnet";
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

$sql1 = mysql_query("SELECT * FROM `taucherkammera`.conf WHERE wert='tuevflasche'");    //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1= mysql_fetch_object($sql1))
{
    $tueff=$ds1->was;
}
$sql1 = mysql_query("SELECT * FROM `taucherkammera`.conf WHERE wert='tuevregler'");    //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1= mysql_fetch_object($sql1))
{
    $tuefr=$ds1->was;
}
    ?>
    <form action="info.php" method="POST">
    <table border="1" width="90%" summary="M&auml;ngeltabelle">
    <tr> <th>DB</th><th>Typ</th><th>Nummer</th><th>Bemerkung</th><th>Rep. notw. </th><th>letzter T&Uuml;V</th><th>ausgeliehen</th><th>M&auml;ngel</th><th>R&uuml;cknehmer</th></tr>
    <?php
    $heuteng= "" . date_mysql2engl($heut) . " \n";
    $sql = mysql_query("SELECT * FROM `taucherkammera`.geraete WHERE `Rep_notw` =1");//Tabelle gereate auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $gid=$ds->ID;                                               // Geräte ID
        $typ = $ds->Typ ;                                           // aus Datenbank auslesen
        $regnr=$ds ->RegNR ;                                       
        $bemerkung=$ds->Bemerk;	                                   
        $rep_notw= $ds->Rep_notw ;                                 
        $tuef=$ds->TUEV;	                                       
        $ausgel=$ds->ausgeliehen;                                  
        $tuefeng=$tuef ;
        $query    = "SELECT Typ FROM typ WHERE ID=$typ";            //aus Typ-ID Typ machen
        $resultID = @mysql_query($query);                           //aus Typ-ID Typ machen
        $typan = mysql_result($resultID,0);

        $query = "INSERT INTO `taucherkammera`.`vergleich`
        (`var`,`gid`,`typ`,`regnr`,`bemerkung`,`rep_notw`,`tuev`,`ausgeliehen`)
        VALUES ('alt','$gid','$typan','$regnr','$bemerkung','$rep_notw','$tuef','$ausgel')";
        mysql_query($query);
    }



    $sql = mysql_query("SELECT * FROM `taucherkammer`.geraete WHERE `Rep_notw` =1");//Tabelle gereate auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $gid=$ds->ID;                                               // Geräte ID
        $typ = $ds->Typ ;                                           // aus Datenbank auslesen
        $regnr=$ds ->RegNR ;
        $bemerkung=$ds->Bemerk;
        $rep_notw= $ds->Rep_notw ;
        $tuef=$ds->TUEV;
        $ausgel=$ds->ausgeliehen;
        $tuefeng=$tuef ;
        $query    = "SELECT Typ FROM typ WHERE ID=$typ";            //aus Typ-ID Typ machen
        $resultID = @mysql_query($query);                           //aus Typ-ID Typ machen
        $typan = mysql_result($resultID,0);

        $query = "INSERT INTO `taucherkammera`.`vergleich`
        (`var`,`gid`,`typ`,`regnr`,`bemerkung`,`rep_notw`,`tuev`,`ausgeliehen`)
        VALUES ('neu','$gid','$typan','$regnr','$bemerkung','$rep_notw','$tuef','$ausgel')";
        mysql_query($query);
    }

$sql = mysql_query("SELECT * FROM `taucherkammera`.`vergleich` ORDER BY typ, regnr, var");    //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid = $ds->gid ;
    $var = $ds->var;
    $typan = $ds->typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->regnr ;
    $bemerkung= $ds->bemerkung;
    $rep_notw= $ds->rep_notw ;
    $tuef=$ds->tuev;
    $ausgel=$ds->ausgeliehen;
    $tuefeng=$tuef ;
    $res=$ds->res;
    if ($tuef == '0000-00-00')
    {
        $tuef= "kein Eintrag";
    }                                                               //Wert 0 abblocken
    else
    {
        $tuef= "" . date_mysql2german($tuef) . " \n";               // Formatwandlung
    }
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
    if ($var=="alt")
    {
        $query="SELECT Maengel FROM `taucherkammera`.info WHERE ID_Geraet=$gid and erl=0"; // Mängel auslesen
        $resultID=@mysql_query($query);
        $maengan= mysql_result($resultID,0);
    }
    else
    {
        $query="SELECT Maengel FROM `taucherkammer`.info WHERE ID_Geraet=$gid and erl=0"; // Mängel auslesen
        $resultID=@mysql_query($query);
        $maengan= mysql_result($resultID,0);
       
    }
    if ($var=="alt")
    {
        $query="SELECT Rueckgeber FROM `taucherkammera`.info WHERE ID_Geraet=$gid and erl=0"; // Namen Rücknehmer dazu auslesen
        $resultID=@mysql_query($query);
        $rueckga= mysql_result($resultID,0);
    }
    else
    {
        $query="SELECT Rueckgeber FROM `taucherkammer`.info WHERE ID_Geraet=$gid and erl=0"; // Namen Rücknehmer dazu auslesen
        $resultID=@mysql_query($query);
        $rueckga= mysql_result($resultID,0);
    }
    if ($var=="alt")
    {
        $query    = "SELECT kname FROM `taucherkammera`.user WHERE ID=$rueckga";      //aus Rückgeber-ID Rückgeber machen
        $resultID = @mysql_query($query);                           //aus Rückgeber-ID Rückgeber machen
        $rueckgan = mysql_result($resultID,0);
    }
    else
    {
        $query    = "SELECT kname FROM `taucherkammer`.user WHERE ID=$rueckga";      //aus Rückgeber-ID Rückgeber machen
        $resultID = @mysql_query($query);                           //aus Rückgeber-ID Rückgeber machen
        $rueckgan = mysql_result($resultID,0);
    }
    //echo $gid;
    echo "<td>";                                                //ausgeben
    echo $typan,"<br>";
    echo "</td>";
    echo "<td>";
    echo $regnr,"<br>";
    echo "</td>";
    echo "<td>";
    echo $bemerkung,"<br>";
    if ($rep_notw == "1")                                       //bei Reparatur notwendig auf rot setzen
    {
        ?>
        <td bgcolor =#FF6F6C >
        <input type="checkbox" name="anz"  value= "2" checked disabled />
        </td>
        <?php
    }
    else 
    {
        ?>
        <td>
        <input type="checkbox" name="anze" disabled  />
        </td>
        <?php
    }

    if (($typ==1) or ($typ==5))
    {
        $tuefl=$tuefr*86400;                                              //wenn regler dann tüvzeit für regler und computer
    }
    if (($typ==3) or ($typ==10))
    {
        $tuefl=$tueff*86400;                                              //wenn flasche dann tüvzeit für flasche
    }
    if ($tuef== "kein Eintrag")                                 // wenn TÜV Termin vorbei auf rot setzen
    {
        ?> <td bgcolor =#FFFF00 > <?php
    }
    elseif(date2timestamp($heut)>date2timestamp($tuef)+$tuefl)
    {
        ?> <td bgcolor =#FF6F6C > <?php
    }
    else 
    {
        ?> <td bgcolor =#3FFF00 > <?php
    } ;
    echo $tuef,"<br>";
    if ($ausgel == "1")                                         //bei ausgeliehen auf rot setzen
    {
        ?>
        <td bgcolor =#FF6F6C >
        <input type="checkbox" name="an" checked disabled />
        </td>
        <?php
    }
    else 
    {
        ?>
        <td>
        <input type="checkbox" name="anzei" disabled />
        </td>
        <?php
    }
    echo "<td>";
    echo $maengan,"<br>";
    echo "</td>";
    echo "<td>";
    echo $rueckgan,"<br>";
    echo "</td>";
    echo "</tr>";
}

$sql = " DROP TABLE IF EXISTS `taucherkammera`.`vergleich` ";                   //tabelle löschen
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());

//    mysql_close($dz);
    ?>
    </table>
</form> 
<form method="POST" action="Info-v.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>

<?php 
?>
</body>
</html>
<?php  
/*
$anzahl = anzahl der einträge
$ausgabe = wert des arrayeintrages
$ausgel = ausgeliehen aus geräte
$bemerkung = bemerkung aus geräte
$d = variable im functionen
$date = übergabevariable an function
$ds = datenzähler
$ew = text log eintrag
$gid = id in geräte
$heut = heute in deu
$heuteng = heute in engl
$i = zähler lo-array
$key = array-variable
$l = logbuch eintrag
$maengan = ausgewählter mängeleintrag in info
$query = variable in datenbankabfrage
$regnr = regnr in geräte
$rep_notw = rep notwendig in geräte
$result / $resultID = variable in datenbankabfrage
$rueckga rückgabetermin engl
$rueckgan = rücknehmer
$sql = variable in datenbankabfrage
$tuef /$tuefeng = tüvtermin in geräte engl
$typ = typnummer in geräte
$typan = typ name in typ
$value = wert in array lo
$zeile = zeile in anzahl in geräte
*/
?>
