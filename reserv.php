<html>
<head>
<?php include ("#mysql.inc.php"); ?>
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
<title>reservieren</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Technik reservieren
</big></big></big>

<form name="res" action="reserv-1.php" method="post" >
<a name="anfang"></a>
<a href="#ende">nach unten</a>
<br>
<table border="1" width="90%">
<tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>Rep. notw. </th><th>Termine:<br>Regler und Flasche T&Uuml;V<br>Computer Batt.-wechsel</th><th>ausgeliehen</th><th>reserviert</th></tr>
<br>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                       //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="reserv ge&ouml;ffnet";
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
    $rep_notw= $ds->Rep_notw ;                                      // aus Datenbank auslesen
    $tuef=$ds->TUEV;	                                            // aus Datenbank auslesen
    $ausgel=$ds->ausgeliehen;                                       // aus Datenbank auslesen
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
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
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
    if ($rep_notw == "1")                                           //bei Reparatur notwendig auf rot setzen
    {
        ?>
        <td bgcolor =#FF6F6C >
        <input type="checkbox" name="anzei[]" checked disabled />
        <a href="info.php" >zur M&auml;ngelanzeige </a>
        </td>
        <?php
    }
    else
    {
        ?>
        <td>
        <input type="checkbox" name="anzei[]"  disabled />
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

    if ($tuef !== "kein Eintrag")                                   // wenn TÜV Termin vorbei auf rot setzen
    {
    $anz=30;                                                        //TÜV in nächsten 30 Tagen
    $format="d.m.Y";
    $tuevbe = tage_subtrahieren($tuef, $anz, $format);
//    echo $heut;
//    echo $tuef,"<br>";
//    echo $tuevbe,"<br>";
        if(date2timestamp($heut)>date2timestamp($tuef)+$tuefl)
            {
                ?> <td bgcolor =#FF6F6C ><?php
                echo $tuef,"<br>";
                echo "</td>";
            }
        elseif(date2timestamp($heut)>date2timestamp($tuevbe)+$tuefl)
            {
            ?> <td bgcolor =#FFFF00 > <?php
            echo $tuef,"<br>";
            echo "</td>";
            }
        else
            {
            ?> <td bgcolor =#3FFF00 > <?php
            echo $tuef,"<br>";
            echo "</td>";
            }
    }
    else
        {
        echo "<td>";
        echo $tuef,"<br>";
        echo "</td>";
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
    if ($res == "0")                                                //bei ausgeliehen auf rot setzen
    {
        echo "<td><input type=\"checkbox\" name=\"ausl[]\" value= \" $gid \" />";
        echo "</td>";
    }
    else
    {
        echo "<td bgcolor =#DEFF05><input type=\"checkbox\" name=\"ausl[]\" value= \" $gid \" /><br>";
//        echo "</td>";

        $sql1 = mysql_query("SELECT * FROM res WHERE gid=$gid");    //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
        while($ds1= mysql_fetch_object($sql1))
        {
            $von=$ds1->von;
            $bis=$ds1->bis;
            $nam=$ds1->name;
            $von= "" . date_mysql2german($von) . " \n";
            $bis= "" . date_mysql2german($bis) . " \n";
            $anz=$von." - ".$bis." f&uuml;r  ".$nam ;
            if (isset($anza))
            {
                $anza=$anza." /<br> ".$anz;
            }
            else
            {
                $anza=$anz;
            }
        }
        echo $anza;
        unset($anza);
        echo "</td>";
    
    }
    
    echo "</tr>";
}
$query = "DELETE FROM `var` WHERE 1 ";                              //Tabelle var Werte löschen
$resultID = @mysql_query($query);
//mysql_close($dz);
?>
</table>
<a name="ende"></a>
<a href="#anfang">nach oben</a><br> <br>
<input type="submit" name="res" value="reservieren" class="Button-w"/>
</form>
</form>
<form method="POST" action="reserv.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$date / $anz / $format / $monat / $tag / $jahr / $datum = übergabevariable un functionen
$anza = reservierungsanzeige
$ausgel = ausgeliehen in geräte
$bemerkung = bemerkung in geräte
$bis = bis res in res
$d = variable in functionen
$date = übrgabevariable in functionen
$datets / $datets1 / $datets2 = variable in functionen
$datum = variable in functionen
$ds / $ds1 = datenzähler
$gid = id in geräte
$hersteller = hersteller in geräte
$heut = heute deu
$heuteng = heute engl
$l = logeintrag
$nam = name in res
$query = variable in datenbankabfrage
$regnr = regnummer in geräte
$rep_notw = rep notwendig in geräte
$res = res in geräte
$resultID / $sql / $sql1 = variable in datenbankabfrage
$tuef / $tuefeng = tüv-datum in geräte engl
$tuevbe = tüv termin -30 tage
$typ = typnummer in typ
$typan = typtext in typ
$von = von in res     
*/
?>
