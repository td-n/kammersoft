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
<title>T&Uuml;V-Termin setzen</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
T&Uuml;V-Termin setzen
</big></big></big>
<form  action="tuev-aendern.php" method="post" name="form1">
<table border="1" width="90%">
<br>
<table border="1" width="80%">
<tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>letzter T&Uuml;V</th><th>ausgeliehen</th><th>Anzahl Eins&auml;tze seit T&Uuml;F</th><th>T&Uuml;V-Termin setzen</th></tr>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut,"<br>","<br>";
$l="tuev-aendern ge&ouml;ffnet";
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

if (isset($_POST['date4']))
{
    $sql = mysql_query("SELECT * FROM var WHERE gida=-6");          //Tabelle var auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $id = $ds->zeile ;
    }
    $datum=$_POST['date4'];
//    echo $id,"<br>";
//    echo $datum;
    $query1 = "UPDATE `geraete` SET `anz_tauchg` = 0 ,`TUEV` = '$datum' WHERE `geraete`.`ID` = $id";
    $resultID1 = @mysql_query($query1);                             //Daten updaten

            $ew=" T&Uuml;V-Termin f&uuml;r Ger&auml;teID : " ;
            $ew1=" auf ";
            $ew2=" ge&auml;ndert";
            $l=$ew.$id.$ew1.$datum.$ew2 ;
            logsch($l);
}
$b=0;
if (isset($_POST['aendern']))
    {
    if (isset($_POST['bearbeiten']))
       {
            $b=1;           
       }
        else
        {
            ?>
            <textarea  cols="110" rows="1" class="text-a" readonly>bitte Ger&auml;t ausw&auml;hlen</textarea><br/>
            <?php ;
        }
    }

$sql1 = mysql_query("SELECT * FROM geraete  WHERE Typ=1 or Typ=5 or Typ=3 or Typ=10 ORDER BY Typ, RegNR "); //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql1))
{
    $id = $ds->ID ;
    $typ = $ds->Typ ;                                               // aus Datenbank auslesen
    $regnr=$ds->RegNR ;
    $herst=$ds->Hersteller;
    $bemerk=$ds->Bemerk;
    $tuef=$ds->TUEV;
    $ausgl=$ds->ausgeliehen;
    $anzeins=$ds->anz_tauchg;                                       // aus Datenbank auslesen

    $query    = "SELECT Typ FROM typ WHERE ID='$typ'";              //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
    if ($ausgl==0)
    {
        $ausgl="nein";
    }
    else
    {
        $ausgl="ja";
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
    if (isset ($anzeins))
    {
       echo "<td>",$anzeins,"</td>";
    }
    else
    {
       echo "<td>","kein Eintrag","</td>"; 
    }
    
    echo "<td><input type=\"radio\" name=\"bearbeiten\" value= \" $id \" />"; // häckchen setzen
    echo "</td>";
    echo "</tr>";
}
?>
</table><br>
<input type="submit" name="aendern" value="Eintrag ausw&auml;hlen" class="Button-w"/>
</form>
<form  action="tuev-aendern.php" method="post" name="form1">
<?php

if ($b==1)
{
    $sql = mysql_query("SELECT * FROM geraete WHERE ID=$_POST[bearbeiten]");  //gerät einblenden         //Tabelle var auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $tuevl = $ds->TUEV ;
        $typ1 = $ds->Typ ;                                          // aus Datenbank auslesen
        $regnr1=$ds->RegNR ;
    }
    $query    = "SELECT Typ FROM typ WHERE ID='$typ1'";             //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan1 = mysql_result($resultID,0);
    ?><font color="#FF0000"><big> neuer T&Uuml;V-Termin f&uuml;r  <?php
    echo $typan1," ",$regnr1,"</font></big><br><br>";
//  echo $tuevl,"<br>";
    $sep="-";
    $format="Ymd";
        $pos1    = strpos($format, 'd');
        $pos2    = strpos($format, 'm');
        $pos3    = strpos($format, 'Y');
        $check    = explode($sep,$tuevl);
    $day=$check[$pos1];
    $mont=$check[$pos2];
    $year=$check[$pos3];
    $tuevl=$day.".".$mont.".".$year;
//  echo $tuevl,"<br>";
    if (!isset($tuevl))
    {
        $tuevl= date("d.m.Y");
    }
    $sep=".";
    $format="dmY";
        $pos1    = strpos($format, 'd');
        $pos2    = strpos($format, 'm');
        $pos3    = strpos($format, 'Y');
        $check    = explode($sep,$tuevl);
    $day=$check[$pos1];
    $mont=$check[$pos2];
    $year=$check[$pos3];
//  echo $day,"x", $mont,"x", $year;
    $hl = (isset($_POST["hl"])) ? $_POST["hl"] : false;
    if(!defined("L_LANG") || L_LANG == "L_LANG")
    {
    	if($hl) define("L_LANG", $hl);
    	// You need to tell the class which language do you use.
    	// L_LANG should be defined as en_US format!!! Next line is an example, just put your own language from the provided list
    	else define("L_LANG", "de_DE"); // Greek example
    }
    //get class into the page
    require_once('classes/tc_calendar.php');
    //instantiate class and set properties
    $myCalendar = new tc_calendar("date4", true);
    $myCalendar->setIcon("images/iconCalendar.gif");
    $myCalendar->setDate($day, $mont, $year);
    $end= date("Y");
    $anf=$end;
    $end=$end +4;
    $myCalendar->setYearInterval($anf, $end);
    //output the calendar
    $myCalendar->writeScript();
    ?>
    <br><br><input type="submit" name="aendern1" value="Datum &uuml;bernehmen" class="Button-w"/>    
    <?php   
    $query = "DELETE FROM `var` WHERE 1 ";                          //Tabelle Werte löschen
    $resultID = @mysql_query($query);

    $eintr = "INSERT INTO `var` (`zeile`,`gida`) VALUES ('$_POST[bearbeiten]','-6')";  //zeilennummer in var eintragen
    mysql_query($eintr)  ;
}
?>
<br><br>
</form>
<form method="POST" action="tuev-aendern.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</body>
</html>
<?php  
/*
$anf = anfang calendarzeitraum
$anz = anzahl tage addieren
$anzeins = anzahl tauchgänge
$ausgl = ausgeliehen in geräte
$b =variable bearbeiten
$bemerk = bemerkung in geräte
$check = variable in functionen
$d = variable in functionen
$date = übergabevariable in functionen
$datets / $datets1 / $datets2 = variable in functionen
$datum = übergabevariable un functionen
$day / $mont / $year = variable in calendar
$ds = datenzähler
$eintr = variable in datenbankabfrage
$end = jetziges jahr
$ew / $ew1 / $ew2 = log texte
$format = übregabevariable in functionen
$herst = hersteller in geräte
$heut = heute in deu
$heuteng = heute in engl
$hl = sprachauswahl in calendar
$id = zeile in var TÜV Termin
$l = log eintrag
$jahr / $monat / $tag = variable in functionen
$pos1 / $pos2 / $pos1 = variable un functionen
$query / $query1 / $resultID / $resultID1 = variable in functionen
$regnr / $regnr1 = regnummer in geräte
$sep = seperator / trennzeichen
$sql / $sql1 = variable in datenbankabfrage
$tuef = tüv in geräte engl
$tuefeng = $tuef
$tuevbe = $tuev -30 tage
$tuevl = tüv in geräte
$typ / $typ1 = typ nummer in geräte
$typan / $typan1 = typ text in geräte
*/
?>
