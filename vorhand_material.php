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
<title>vorhandenes</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
vorhandenes Material
</big></big></big>
<br>
<a name="anfang"></a>
<a href="#ende">nach unten</a>
<br>
<table border="1" width="90%">
<tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>Rep. notw. </th><th>letzte Termine:<br>Regler und Flasche T&Uuml;V<br>Computer Batt.-wechsel</th></tr>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="Archiv ge&ouml;ffnet";
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle R�ckgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$sql1 = mysql_query("SELECT * FROM conf WHERE wert='tuevflasche'");    //Tabelle var ausw�hlen nur ausgew�hltes Ger�t 1 Wert
while($ds1= mysql_fetch_object($sql1))
{
    $tueff=$ds1->was;
}
$sql1 = mysql_query("SELECT * FROM conf WHERE wert='tuevregler'");    //Tabelle var ausw�hlen nur ausgew�hltes Ger�t 1 Wert
while($ds1= mysql_fetch_object($sql1))
{
    $tuefr=$ds1->was;
}

$sql = mysql_query("SELECT * FROM geraete WHERE ausgeliehen=0 ORDER BY typ, RegNR");  //Tabelle gereate ausw�hlen
while($ds= mysql_fetch_object($sql))
{
    $typ = $ds->Typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->RegNR ;                                            
    $hersteller= $ds->Hersteller ;                                  
    $bemerkung= $ds->Bemerk;	                                    
    $rep_notw= $ds->Rep_notw ;                                      
    $tuef=$ds->TUEV;	                                            
    $ausgel=$ds->ausgeliehen;                                       
    $tuefeng=$tuef ;
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
    echo "<td>";
    echo $regnr,"<br>";
    echo "<td>";
    echo $hersteller,"<br>";
    echo "<td>";
    echo $bemerkung,"<br>";
    if ($rep_notw == "1")                                           //bei Reparatur notwendig auf rot setzen
    {
        ?>
        <td bgcolor =#FF6F6C >
        <input type="checkbox" name="anzei[]" checked disabled />
        <a href="info.php" >zur M&auml;ngelanzeige </a>
        <?php
    }
    else
    {
        ?>
        <td>
        <input type="checkbox" name="anzei[]"  disabled />
        <?php
    }

    if (($typ==1) or ($typ==5))
    {
        $tuefl=$tuefr*86400;                                              //wenn regler dann t�vzeit f�r regler und computer
    }
    if (($typ==3) or ($typ==10))
    {
        $tuefl=$tueff*86400;                                              //wenn flasche dann t�vzeit f�r flasche
    }

    if ($tuef !== "kein Eintrag")                                   // wenn T�V Termin vorbei auf rot setzen
    {
    $anz=30;                                                        //T�V in n�chsten 30 Tagen
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
        if (($typ==1)or($typ==3)or($typ==5)or($typ==10))
        {
             echo $tuef,"<br>";
        }
        else
        {
            echo "-";
        }
       echo "</td>";
    echo "</tr>";
    //echo $heut ;                                                  //Service
    //echo $tuef ;                                                  //Service
}
//mysql_close($dz);
?>
</table>

<a name="ende"></a>
<a href="#anfang">nach oben</a><br> <br>
</form>
<form method="POST" action="vorhand_material.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$anz = variable in funktionen
$ausgel = ausgelihen in ger�te
$bemerkung = bemerkung in ger�te
$d = variable in functionen
$date = �brgabevariable in functionen
$datets / $datets1 / $datets2 = variable in functionen
$datum = �bergabevariable in functionen
$ds = datenz�hler
$format = variable in functionen
$hersteller = variable in functionen
$heut = heute deu
$heuteng = heute engl
$jahr / $monat / $tag = variable in functionen
$l = log eintrag
$query /  $resultID / $sql = variable in datenbanlabfrage
$regnr = regnummer in ger�te
$rep_notw = reparatur notwendig in ger�te
$tuef = t�v in ger�te
$tuefeng = $tuef
$tuevbe = tuev -30 tage
$typ = typ nummer in ger�te
$typan = typ text   
*/
?>
