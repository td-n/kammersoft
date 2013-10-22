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
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);}
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
<title>Technikeinsatz</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Technikeinsatz
</big></big></big>
<br>
<table border="1" width="80%">
<tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>letzter T&Uuml;V</th><th>ausgeliehen</th><th>Anzahl Eins&auml;tze seit T&Uuml;V</th><th>Anschaffungsjahr</th><th>gesamte Tauchg&auml;nge</th></tr>
<?php  
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut,"<br>","<br>";
$l="reglereinsatz ge&ouml;ffnet";
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

$sql1 = mysql_query("SELECT * FROM geraete  WHERE Typ=1 or Typ=5 ORDER BY Typ, RegNR "); //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql1))
{
    $typ = $ds->Typ ;                                               // aus Datenbank auslesen
    $regnr=$ds->RegNR ;
    $herst=$ds->Hersteller;
    $bemerk=$ds->Bemerk;
    $tuef=$ds->TUEV;
    $agl=$ds->ausgeliehen;
    $anzeins=$ds->anz_tauchg;                                       // aus Datenbank auslesen
    $abjahr=$ds->kaufjahr;
    $gestg=$ds->ges_tauchg;
    $query    = "SELECT Typ FROM typ WHERE ID='$typ'";              //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
    if ($agl==0)
    {
        $ausgl="nein";
    }
    else
    {
        $ausgl="ja";
    }
    if ($agl==3)
    {
        $ausgl="zur Reparatur";
    }
    echo "<tr>";
    echo "<td>",$typan,"</td>";
    echo "<td>",$regnr,"</td>";
    if ($herst!="")
    {
    echo "<td>",$herst,"</td>";
    }
    else
    {
    echo "<td>"," - ","</td>";
    }
    if ($bemerk!="")
    {
    echo "<td>",$bemerk,"</td>";
    }
    else
    {
    echo "<td>"," - ","</td>";
    }
    $tuefeng=$tuef;
    if ($tuef == '0000-00-00')
    {
        $tuef= "kein Eintrag";
    }                                                               //Wert 0 abblocken
    else
    {
        $tuef= "" . date_mysql2german($tuef) . " \n";               // Formatwandlung
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
            ?> <td bgcolor =#FF6F6C > <?php
        }
        elseif(date2timestamp($heut)>date2timestamp($tuevbe)+$tuefl)
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
        echo $tuef,"<br>";

       echo "</td>";

    echo "<td>",$ausgl,"</td>";
    echo "<td>",$anzeins,"</td>";
    echo "<td>",$abjahr,"</td>";
    echo "<td>",$gestg,"</td>";
    echo "</tr>";
}
?>
</table><br>
</form>
<form method="POST" action="reglereinsatz.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$anz / $date / $datum / $format / $jahr / $monat / $tag = übergabevariable function
$anzeins = anzahl tauchgänge geräte
$ausgl = ausgeliehen in geräte
$bemerk = bemerkungen in geräte
$d = variable in function
$datets / $datets1 / $datets2 = variable function
$ds = datenzähler
$herst = hersteller in geräte
$heut = heute in deu
$heuteng = heute in engl
$l = logeintrag
$query = variable in datenbankabfrage
$regnr = regnummer in geräte
$resultID / $sql = variable in datenbankabfraage
$tuef / $tuefeng = tüvdatum in geräte
$tuevbe = tüvdatum -30 tage
$typ = typnummer
$typan = typtext
*/
?>
