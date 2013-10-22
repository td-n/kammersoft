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
function date_mysql2german($date) {
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
function tage_addieren($date, $anz, $format){
    $datets1= strtotime($date);
    $datets2=$datets1 + ($anz*86400);
    $datets=date($format, $datets2);
    return  $datets;  }
function tage_subtrahieren($date, $anz, $format){
    $datets1= strtotime($date);
    $datets2=$datets1 - ($anz*86400);
    $datets=date($format, $datets2);
    return  $datets;  }
function date2timestamp($datum) {
    list($tag, $monat, $jahr) = explode(".", $datum);
    $jahr = sprintf("%04d", $jahr);
	$monat = sprintf("%02d", $monat);
    $tag = sprintf("%02d", $tag);
	return(mktime(0, 0, 0, $monat, $tag, $jahr));  }
?>
<title>Vergleich - Ger&auml;telisten</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<a name="anfang"></a>
<big><big><big>
Vergleich - Ger&auml;telisten
</big></big></big><br>
<a href="#ende">nach unten</a>
<br>
<table border="1" width="90%">
<tr> <th>DB</th><th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>Rep. notw. </th><th>Termine:<br>Regler und Flasche T&Uuml;V<br>Computer Batt.-wechsel</th><th>ausgeliehen</th><th>reserviert</th></tr> 
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";  
echo $heut;
$l="Typenliste-vergleich ge&ouml;ffnet";
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

$sql = mysql_query("SELECT * FROM `taucherkammera`.`geraete` ORDER BY typ, RegNR");    //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid = $ds->ID ;
    $typ = $ds->Typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->RegNR ;                                            
    $hersteller= $ds->Hersteller ;                                  
    $bemerkung= $ds->Bemerk;	                                    
    $rep_notw= $ds->Rep_notw ;                                      
    $tuef=$ds->TUEV;	                                            
    $ausgel=$ds->ausgeliehen;                                       
    $tuefeng=$tuef ;
    $res=$ds->res;
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);

    $query = "INSERT INTO `taucherkammera`.`vergleich` 
    (`var`,`gid`,`typ`,`regnr`,`hersteller`,`bemerkung`,`rep_notw`,`tuev`,`ausgeliehen`,`res`)
    VALUES ('alt','$gid','$typan','$regnr','$hersteller','$bemerkung','$rep_notw','$tuef','$ausgel','$res')";
    mysql_query($query);
}
//echo "1.Durchlauf";

$sql = mysql_query("SELECT * FROM `taucherkammer`.`geraete` ORDER BY typ, RegNR");    //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid = $ds->ID ;
    $typ = $ds->Typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->RegNR ;
    $hersteller= $ds->Hersteller ;
    $bemerkung= $ds->Bemerk;
    $rep_notw= $ds->Rep_notw ;
    $tuef=$ds->TUEV;
    $ausgel=$ds->ausgeliehen;
    $tuefeng=$tuef ;
    $res=$ds->res;
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);

    $query = "INSERT INTO `Taucherkammera`.`vergleich`
    (`var`,`gid`,`typ`,`regnr`,`hersteller`,`bemerkung`,`rep_notw`,`tuev`,`ausgeliehen`,`res`)
    VALUES ('neu','$gid','$typan','$regnr','$hersteller','$bemerkung','$rep_notw','$tuef','$ausgel','$res')";
    mysql_query($query);
}
//echo "2.Durchlauf";
$sql = mysql_query("SELECT * FROM `taucherkammera`.`vergleich` ORDER BY typ, regnr, var");    //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid = $ds->id ;
    $var = $ds->var;
    $typan = $ds->typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->regnr ;
    $hersteller= $ds->hersteller ;
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
    if ($rep_notw == "1")                                           //bei Reparatur notwendig auf rot setzen 
    {
        ?>
        <td bgcolor =#FF6F6C >
        <input type="checkbox" name="anzei[]" checked disabled /></td>
        <?php
    }
    else 
    {
        ?>
        <td>
        <input type="checkbox" name="anzei[]"  disabled /></td>    
        <?php
    }

    if ($tuef !== "kein Eintrag")                                   // wenn TÜV Termin vorbei auf rot setzen
    {
    $anz=30;                                                        //TÜV in nächsten 30 Tagen
    $format="d.m.Y";
    $tuevbe = tage_subtrahieren($tuef, $anz, $format);
//    echo $heut;
//    echo $tuef,"<br>";
//    echo $tuevbe,"<br>";
        if(date2timestamp($heut)>date2timestamp($tuef))
        {
            ?> <td bgcolor =#FF6F6C > <?php                                                
        }
        elseif(date2timestamp($heut)>date2timestamp($tuevbe))
            {
            ?> <td bgcolor =#FFFF00 > <?php
            }
        else 
        {
            ?> <td bgcolor =#3FFF00 > <?php 
        } ;
    }
    else
    {
        ?> <td> <?php    
    }
//        echo $typ;
        if (($typ==1)or($typ==3)or($typ==5)or($typ==10))
        {
            echo $tuef,"<br>";
        }
        else
        {
            echo "-";
        }
       echo "</td>";
        if ($ausgel == "1")                                         //bei ausgeliehen auf rot setzen
        {
            ?>
            <td bgcolor =#FF6F6C >
            <input type="checkbox" name="anzei[]" checked disabled /> 
            <?php
        }
        else 
        {
            ?>
            <td>
            <input type="checkbox" name="anzei[]" disabled /> </td>
            <?php
        }
    if($res=="1")
    {
        ?><td bgcolor =#DEFF05 ><?php 
        echo "reserviert";
        unset($anza);
        echo "</td>"; 
    }
    else
    {
        echo "<td>"," - ","</td>";    
    }
    echo "</tr>";
    //echo $heut ;                                                  //Service
    //echo $tuef ;                                                  //Service
}

$sql = " DROP TABLE IF EXISTS `taucherkammera`.`vergleich` ";                   //tabelle löschen
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());

//mysql_close($ds);
//mysql_close($ds1);
?>
</table>
<a name="ende"></a> 
<a href="#anfang">nach oben</a><br> <br>
<form method="POST" action="Info-v.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
anz = übergabevariable in functionen
anza = anz
$ausgel = ausgeliehen in geräte
$bemerkung = bemerkung in geräte
$bis = bis in res
$d = variable in functionen
$date = übergabevariable in functionen
$datets / $datets1 / $datets2 = variable in functionen
$datum = übergabevariable in functionen
$ds / $ds1 = datenzähler
$format = übergabevariable in functionen
$gid = id in geräte
$hersteller = hersteller in geräte
$heut = heute deu
$heute = heute engl
$jahr = variable in functionen
$l = log eintrag
$monat = variable in functionen
$nam = name in res
$query / $resultID = variable in datenbankabfrage
$regnr = regnummr in geräte
rep_notw = reperatur notwendig in geräte
$res = res in geräte
$sql / $sql1 = variable in datenbankabfrage
$tag = variable in function
$tuef = tüv in geräte engl
$tuefeng = $tuef
$tuevbe = tuev - 30 tage
$typ = typnummer in geräte
$typan = typ text in typ
$von = von in res deu - engl  
*/
?>
