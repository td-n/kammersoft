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
function date_mysql2german($date) {                                 //Funktion ins deutsche Format
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
    }
function date_mysql2engl($date) {                                   //Funktion ins englische Format
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
<title>Ausleihe R&uuml;ckgabetermin</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Ausleihe R&uuml;ckgabetermin
</big></big></big>
<br>
<?php
$l="Ausleihe3 ge&ouml;ffnet";
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut , "<br>", "<br>";
$heute= "" . date_mysql2engl($heut) . " \n";
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

if (isset($_POST['eintr']))                                         //erfolgte ien eintrag
{
    if (isset ($_POST['date3']))                                    //rückgabe aus calendar
    {
    $rdat=$_POST['date3'];
        $zurd= "" . date_mysql2german($rdat) . " \n";               //wenn ja
        if  (date2timestamp($zurd) <= date2timestamp($heut))
        {
/*            echo "bitte ein Datum in der Zukunft angeben";
            ?><meta http-equiv="refresh" content="5; URL=Ausleihe3.php" /><?php   */       
        }                        
    }
    else                                                            //wenn nein
    {
        $sql1 = mysql_query("SELECT * FROM var WHERE gida = -4 ");  //Tabelle var auswählen Rückgabedatum
        while($ds = mysql_fetch_object($sql1))
            {
              $rdat= $ds->user;
            }
    }
//    $zurd= "" . date_mysql2german($rdat) . " \n";
//    $heute= "" . date_mysql2engl($heut) . " \n";
    $sql = mysql_query("SELECT * FROM var ");                       //Tabelle var auswählen 
    while($ds= mysql_fetch_object($sql))
    {
        $ga = $ds->gida ;                                           // aus Datenbank var auslesen
        $user = $ds->user ;                                         // aus Datenbank var auslesen
        if ($ga == -1)
        {
            $usera = $user ;                                        //benutzer auslesen
        }
        if ($ga == -2)
        {
            $ausgr = $user ;                                        //ausleihgrund auslesen
        }
    }    

$jahr = date("Y");                                                  //Ausleihnummer ermitteln
$nr=1;
$sql = mysql_query("SELECT * FROM ausleihe WHERE jahr='$jahr' ");                  //letzten Eintrag ermitteln
while($ds= mysql_fetch_object($sql))                                //höchsten wert lfnr ermitteln
{
    $jahr=$ds->jahr;
    $n=$ds->lfnr;
    if (isset ($jahr))
    {
        if($nr<=$n)
        {
            $nr=$n+1;
        }
    }
    else
    {
        $nr=1;                                                      //bei neuen jahr
    }
}
    $b=0;                                                           //keine Reservierung
    $sql = mysql_query("SELECT * FROM var WHERE gida > 0");         //Reservierung prüfen
    while($ds= mysql_fetch_object($sql))
    {
        $b=0;                                                       //definiert auf keine Reservierung setzen
        $aga = $ds->gida ;
//        echo $rdat,"aa",$zurd,"bb","<br>";
//        echo $aga,"<br>";
        $sql1 = mysql_query("SELECT * FROM res WHERE gid=$aga ");   //Tabelle res alle obigen geräte durchlaufen
        while($ds1= mysql_fetch_object($sql1))
        {
//                $gid=$ds->gid;
            $vone=$ds1->von;                                        //von bis auslesen
            $bise=$ds1->bis;
            $von= "" . date_mysql2german($vone) . " \n";
            $bis= "" . date_mysql2german($bise) . " \n";
            $b=1;                                                   //Reservierung vorhanden
//            echo $rdat,"xx",$zurd,"yy","<br>";
//            echo $von,"<br>",$bis,"<br>",$heut,"<br>",$zurd,"<br>";
            if ((isset($vone))and (isset($bise)))
            {
                if((date2timestamp($von)>date2timestamp($zurd))OR (date2timestamp($heut)>date2timestamp($bis)))     //vergleichen ob zeitraum bereits vergeben
                {

                    $auslr =  $_SESSION['kname'];
                    $sql = mysql_query("SELECT * FROM var WHERE gida > 0");         //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
                    while($ds= mysql_fetch_object($sql))
                    {                                               //in ausleihe schreiben
                        $aga = $ds->gida ;                                          // Geräte aus Datenbank var auslesen
                        $eintr = "INSERT INTO ausleihe
                            (IDGeraet, AuslName, Ausgeber, auslgrund, Datum_von, Datum_bis, jahr, lfnr)
                            VALUES ('$aga','$usera','$auslr','$ausgr','$heute','$rdat','$jahr','$nr')";
                        mysql_query($eintr);                                        //ausleihe jede Zeile schreiben
                
                        $query1 = "UPDATE `geraete` SET `ausgeliehen` = 1 WHERE `geraete`.`ID` = $aga";
                        $resultID1 = @mysql_query($query1);                         //in geraete ausgeliehen setzen
                    }                                               //ende in ausleihe schreiben
                    $jnr = $jahr." / ".$nr;
                    $eintr = "INSERT INTO `var` (`gida`,`user`) VALUES ( -4, '$rdat')";  //Rückgabedatum in var eintragen
                    mysql_query($eintr);
                    $eintr = "INSERT INTO `var` (`gernr`,`gida`) VALUES ('$jnr', -5)";  //Nr in var eintragen
                    mysql_query($eintr);
                    ?>
                    <meta http-equiv="refresh" content="0; URL=Ausleihe4.php" />
                    <?php

//                        eintragen ();

                }                                                   //ende Datumsvergleich
                else
                {
                    $sql = mysql_query("SELECT * FROM geraete WHERE ID=$aga");           //Tabelle gereate auswählen
                    while($ds= mysql_fetch_object($sql))
                    {
                        $gid = $ds->ID ;
                        $typ = $ds->Typ ;                                               // aus Datenbank auslesen
                        $regnr= $ds->RegNR ;                                            // aus Datenbank auslesen
                    }
                    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
                    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
                    $typan = mysql_result($resultID,0);
                    echo "<big><big>",$typan, $regnr," - Reservierung von :",$von," - ",$bis,"</big></big>";                    
                    echo '<br><font color="#FF0000"><big>F&uuml;r diesen Ausleihzeitraum liegt eine Reservierung vor. Bitte kontrollieren sie die Eingabe</big></font>',"<br>";
                    $eintr = "INSERT INTO `var` (`gida`,`user`) VALUES ( -4, '$rdat')";  //Rückgabedatum in var eintragen
                    mysql_query($eintr);
                    ?><meta http-equiv="refresh" content="5; URL=Ausleihe3.php" /><?php
                }                                                   //res vorhanden    
            }                                                       //ende oder Datumsvergleich
        }                                                           //ende gerärte mit res abarbeiten
    }                                                               //ende Reservierung prüfen
//        echo $b;
    if ($b==0)                                                      //keine Reservierung vorhanden
    {
//            echo $b;
        $auslr =  $_SESSION['kname'];
        $sql = mysql_query("SELECT * FROM var WHERE gida >= 0 ");   //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
        while($ds= mysql_fetch_object($sql))
        {                                               
            $aga = $ds->gida ;                                      // Geräte aus Datenbank var auslesen
            $versch = $ds->mang;
//            echo "+",$versch,"+",$aga;
            $eintr = "INSERT INTO ausleihe
                (IDGeraet, AuslName, Ausgeber, auslgrund, verschiedenes, Datum_von, Datum_bis, jahr, lfnr)
                VALUES ('$aga','$usera','$auslr','$ausgr', '$versch','$heute','$rdat','$jahr','$nr')";
            mysql_query($eintr);                                        //ausleihe jede Zeile schreiben
    
            $query1 = "UPDATE `geraete` SET `ausgeliehen` = 1 WHERE `geraete`.`ID` = $aga";
            $resultID1 = @mysql_query($query1);                     //in geraete ausgeliehen setzen
        }                                                           //ende in ausleihe schreiben
        $jnr = $jahr." / ".$nr;
        $eintr = "INSERT INTO `var` (`gida`,`user`) VALUES ( -4, '$rdat')";  //Rückgabedatum in var eintragen
        mysql_query($eintr);
        $eintr = "INSERT INTO `var` (`gernr`,`gida`) VALUES ('$jnr', -5)";  //Nr in var eintragen
        mysql_query($eintr);
        ?>
        <meta http-equiv="refresh" content="0; URL=Ausleihe4.php" />
        <?php
//                 eintragen ();
    }
}                                                                   // ende datum übernommen
?>
<form  action="Ausleihe3.php" method="POST" name="form1">
<p><font color="#0000FF"><big> An welchem Tag soll die Technik zur&uuml;ckgegeben werden?  </big></font></p>
<?php    
$sql1 = mysql_query("SELECT * FROM var WHERE gida = -4 ");          //Tabelle var auswählen Rückgabedatum
while($ds = mysql_fetch_object($sql1))                              
    {
      $rdat= $ds->user;
    }
if (!isset($rdat))
{
    $rdat="0000-00-00";
}
//echo $rdat," a1<br>",$rdatd." a2<br>";                            //java Datum
    $sep="-";
    $format="Ymd";
        $pos1    = strpos($format, 'd');
        $pos2    = strpos($format, 'm');
        $pos3    = strpos($format, 'Y');
        $check    = explode($sep,$rdat);
    $day=$check[$pos1];
    $mont=$check[$pos2];
    $year=$check[$pos3];
    $rdatk=$day.".".$mont.".".$year;
$rdatd=date_mysql2german($rdat);
//echo $day," a<br>", $mont," b<br>", $year," c<br>",  $rdatd," d<br>",$rdatk,"e<br>";
if ($rdatk=="00.00.0000")
{
    $rdatk= date('d.m.Y', strtotime('+7 day'));
}
//echo $rdat," b1<br>",$rdatk." b2<br>";
$sep=".";
$format="dmY";
    $pos1    = strpos($format, 'd');
    $pos2    = strpos($format, 'm');
    $pos3    = strpos($format, 'Y');
    $check    = explode($sep,$rdatk);
$day=$check[$pos1];
$mont=$check[$pos2];
$year=$check[$pos3];
//echo $day,"a<br>", $mont,"b<br>", $year,"c<br>", $rdatk,"d<br>";
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
$myCalendar = new tc_calendar("date3", true);
$myCalendar->setIcon("images/iconCalendar.gif");
$myCalendar->setDate($day, $mont, $year);
$end= date("Y");
$end=$end +1;
$myCalendar->setYearInterval(2010, $end);
//output the calendar
$myCalendar->writeScript();
//mysql_close($ds);
//mysql_close($ds1);
?><br><br>
<input type="submit" name="eintr" value="eintragen" class="Button-w"/>
</form>
<form method="POST" action="Ausleihe2.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php 
/*
$aga = gida in var
$ajahr = jahr in ausleihe
$ausgr = user in var
$auslr = session user - angemeldeter
$b = keine reservierung
$bis = bis in res deu
$bise = bis in res engl
$check = check in function
$d = variable in function
$date = übergabevariable in function
$datum = übergabevariable in function
$day = variable in function
$ds / $ds1 datenzähler in function
$eintr = variable in abfrage
$end = end variable in calendar
$format = variable in calendarvorbereitung
$ga = variable gida in var
$gid = geräte id in geräte
$heut = heute deu
$heute = heute engl
$hl = sprachvariable in calendar
$j = ist jahr vorhanden
$jahr = aktuelles jahr
$jnr = jahr/nummereintrag in var
$monat = monatsvariable in function
$mont = monatsvariable in function
$myCalendar = Kalendarvariable
$nr = nummer in jahr/nummer
$pos1 / $pos2 / $pos3 = variable in function
$query / $query1 = variable in datenbankabfrage
$rdat = datum aus calendar engl
$rdatd = datum aus calendar deu
$rdatk = datum vorbereitung calendar
$regnr = registriernummer des gerätes
$resultID / $resultID = variable in datenbankabfrage
$sep = seperator in calendar
$sql / $sql1 = variable in datenbankabfrage
$tag = tagesvariable in function
$typ = typnummer in geräte
$typan = typtext in typ
$user = user in var
$usera = ausgewählter user in var
$versch = mang in var
$von = von in res deu
$vone = von in res engl
$year = jahr in calendarvorbereitung
$zurd = rückgabedatum aus calendar deu
*/
?>
