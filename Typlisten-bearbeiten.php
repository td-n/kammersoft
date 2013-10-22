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
if ($_SESSION['schreiben']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
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
<title>Typenliste bearbeiten</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<a name="anfang"></a>
<big><big><big>
Ger&auml;teseite
</big></big></big><br>
<a href="#ende">nach unten</a>
<br>
<form name="aendern" action="Typlisten-bearbeiten.php" method="post" name="form1">
<table border="1" width="90%">
<tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>Rep. notw. </th><th>letzte Termine:<br>Regler und Flasche T&Uuml;V<br>Computer Batt.-wechsel</th><th>ausgeliehen</th><th>Auswahl</th> </tr>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");                                              //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;                                                         //Datum anzeigen
$l="Typenliste-bearbeiten ge&ouml;ffnet / ".$_SESSION['kname'];
logsch ($l);

while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
echo "+",$_POST['nr'],"+";

if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}
if (isset ($_POST['zu1']))
{
    ?><meta http-equiv="refresh" content="0; URL=Typlisten-tuev.php" /><?php ;
}

if (isset($_POST['ausg']))
{
    echo "+-+",$_POST['ausg'],"+-+" ;
    ?>
    <br/><textarea  cols="110" rows="1" class="text-a" readonly><?php echo $_POST['ausg']; ?></textarea><br/>
    <?php 
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

//$ausgabe="";
if (isset($_POST['eintr']))                                         //Gerät eintragen
{                                                                   
    $tueve=$_POST['date3'];                                         //übertragenen TÜV
    if (isset($_POST['ke']))                                        //bei leer def. setzen
    {
        $tueve="0000-00-00";
    }
    else
    {
        $tuev= "" . date_mysql2german($tueve) . " \n";              //in engl. format konvert für db 
    }
    if ($tueve<>"0000-00-00")                                       //nicht leer
    {
         if ($tuev<= $heut)                                         //datum > heute
        {
            $ausgabe="Bitte T&Uuml;V-Termin kontrollieren Daten werden eingetragen(Termin in der Vergangenheit)";
        }   
    }
    echo  "<br>","+-",$_POST['nr'],"-+";
    if ($_POST['nr']=="")                                           //nummerkontrolle - gesetzt ?
    {
        $ausgabe="Bitte tragen Sie eine g&uuml;ltige Nummer ein";
//        echo "Bitte tragen Sie eine g&uuml;ltige Nummer ein";
                                                                        
    }                                                               //ob nummer frei
    
    $sql = mysql_query("SELECT * FROM geraete WHERE Typ=$_POST[eintrag]");  //Tabelle gereate auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $regnr= $ds->RegNR ;    
        if ($_POST['nr']==$regnr)
        {
            $ausgabe="Nummer bereits vorhanden";
        }        
    }
    $j = date("Y");
    if ($_POST['ajahr']=="")                                           //nummerkontrolle - gesetzt ?
        {
            $ajahr=$j;
        }
        else
        {
            $ajahr= $_POST['ajahr'];
        }
    $query = "INSERT INTO `geraete` (`ID`, `Typ`, `RegNR` ,`Hersteller`, `Bemerk`, `TUEV`, `kaufjahr` ) VALUES (NULL , '$_POST[eintrag]', '$_POST[nr]', '$_POST[hersteller]', '$_POST[bemerkung]', '$tueve', '$ajahr');";
    mysql_query($query);
    $query    = "SELECT Typ FROM typ WHERE ID=$_POST[eintrag]";     //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
    $typan = mysql_result($resultID,0);
    $ew=" eingetragen in Ger&auml;teliste" ;                        //log
    $l=$typan.$_POST['nr'].$ew ;
    logsch($l);
}
if (isset($_POST['ausgeber']))                                      //loeschen oder aendern gedrückt
{
        if (isset($_POST['bearbeiten']))                             //Haeckchen gesetzt
        {
            $sql = mysql_query("SELECT * FROM geraete WHERE ID=$_POST[bearbeiten]");                           //Tabelle benutzer auswählen nur ausgewähltes Gerät 1 Wert
            while($ds= mysql_fetch_object($sql))                    // aus Datenbank geraete auslesen
            {
                $nrtyp = $ds->Typ ;
                $regnrb = $ds->RegNR;
                $rep_notw = $ds->Rep_notw;
                $herst = $ds->Hersteller;
                $bemerk = $ds->Bemerk;
                $tuevl = $ds->TUEV;                                 //engl  aus geraete    
            }
            switch($_POST['ausgeber'])                              //loeschen oder aendern gedrückt
            {                                                       //Gerät löschen
                case"loeschen":
                    $query    = "SELECT Typ FROM typ WHERE ID=$nrtyp";  //aus Typ-ID Typ machen
                    $resultID = @mysql_query($query);                               
                    $typan = mysql_result($resultID,0);
                    if ($rep_notw=="0")                               //ist Mängelinfo gesetzt?
                    {
                        $query = "DELETE FROM `geraete` WHERE ID=$_POST[bearbeiten] ";  //Tabelle 
                        $resultID = @mysql_query($query);
                        $ew=" in Ger&auml;te geloescht" ;           //log
                        if (!isset($regnr))
                        {
                            $regnr="";
                        }
                        $l=$typan.$regnr.$ew ;
                        logsch($l);
                    }
                    else
                    {
                        $ausgabe="bitte l&ouml;schen Sie zuerst die M&auml;ngelinfo";
                    }
                break;
                case"aendern":
                    $tuevd= "" . date_mysql2german($tuevl) . " \n"; //ins deutsche wandeln
                    $query = "DELETE FROM `var` WHERE 1 ";          //Tabelle Werte löschen
                    $resultID = @mysql_query($query);
                    $eintr = "INSERT INTO `var` (`zeile`,`rdat2`) VALUES ('$_POST[bearbeiten]','$tuevd')";  //zeilennummer in var eintragen
                    mysql_query($eintr)  ;
                    $query    = "SELECT Typ FROM typ WHERE ID=$nrtyp";   //aus Typ-ID Typ machen
                    $resultID = @mysql_query($query);               //aus Typ-ID Typ machen
                    $atypan = mysql_result($resultID,0);
                break;
                default:
            }
        }
        else
        {
            ?> 
            <br/><textarea  cols="110" rows="1" class="text-a" readonly>bitte ein Ger&auml;t ausw&auml;hlen</textarea><br/>
            <?php ;
        }  
}
/*
?>
<br/><textarea  cols="110" rows="1" class="text-a" readonly>bitte ein Ger&auml;t ausw&auml;hlen</textarea><br/>
<?php ;
*/
if (isset($_POST['aeintr']))                                        //änderung eintragen
{               
                if (isset ($_POST['kaufjahr']))                       //kaufjahr eintragen
                {
                     $kj= $_POST['kaufjahr'];
                }
                else
                {
                    $kj="-";
                }
                $sql = mysql_query("SELECT * FROM var ");           //Tabelle var auswählen
                while($ds= mysql_fetch_object($sql))
                {
                    $zeile = $ds->zeile ;
                    $tuevdk = $ds->rdat2 ;                          // aus Datenbank var auslesen deutsch
                }
                $query = "DELETE FROM `var` WHERE 1 ";              //Tabelle Werte löschen
                $resultID = @mysql_query($query);
                $tuevee= $_POST['date4'];
                if (isset($_POST['ke']))                            //bei leer def. setzen
                {
                    $tuevee="0000-00-00";
                }
                $tuevde =  "" . date_mysql2german($tuevee) . " \n";
                if (isset($_POST['ke']))                            //bei leer def. setzen
                {
                    $tuevde="00.00.0000";
                }
                        
                if ($tuevde==$tuevdk)
                {
                    $r0=1;                                          //TÜV hat sich nciht geändert
                }
                else
                {
                    $r0=0;                                          //TÜV hat sich geändert
                }
                $ahersteller=$_POST['ahersteller'];
                $abemerkung=$_POST['abemerkung'];
                if (!isset($typan))
                {
                    $typan=" ";
                }
                if (!isset($regnr))
                {
                    $regnr=" ";
                }
                if ($r0==0)
                {
                    $query1 = "UPDATE `geraete` SET  `Hersteller` = '$ahersteller', `Bemerk` = '$abemerkung', `TUEV` = '$tuevee', `kaufjahr` = '$kj' WHERE `geraete`.`ID` = $zeile";
                }
                else
                {
                    $query1 = "UPDATE `geraete` SET `Hersteller` = '$ahersteller', `Bemerk` = '$abemerkung', `TUEV` = '$tuevee' , `kaufjahr` = '$kj' WHERE `geraete`.`ID` = $zeile";
                }
                $resultID1 = @mysql_query($query1);                 //Daten updaten
                $ew=" in Ger&auml;te geaendert" ;                   //log
                $ew1=" Hersteller=";
                $ew2=" Bemerkung=";
                $ew3=" T&Uuml;V-Ablaufdatum=";
                $l=$ew1.$ahersteller.$ew2.$abemerkung.$ew3.$tuevde.$typan.$regnr.$kj.$ew ;
                logsch($l);
}
$sql = mysql_query("SELECT * FROM geraete ORDER BY typ, RegNR");    //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid = $ds->ID;
    $typ = $ds->Typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->RegNR ;                                            
    $hersteller= $ds->Hersteller ;                                  
    $bemerkung= $ds->Bemerk;	                                    
    $rep_notw= $ds->Rep_notw ;                                      
    $tuef=$ds->TUEV;	                                            
    $ausgel=$ds->ausgeliehen;                                       
    $kaufjahr=$ds->kaufjahr;
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
        $anz=30;                                                    //TÜV in nächsten 30 Tagen
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
        if (($typ==1)or($typ==3)or($typ==5)or($typ==10))
        {
            echo $tuef,"<br>";
        }
        else
        {
            echo "-";
        }
    }
         echo "</td>";
    if ($ausgel == "1")                                             //bei ausgeliehen auf 1 setzen
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
    echo "<td><input type=\"radio\" name=\"bearbeiten\" value= \" $gid \" />"; // häckchen setzen
    echo "</td>";
    echo "</tr>";
    //echo $heut ;                                                  //Service
    //echo $tuef ;                                                  //Service
}
?>
</table>
<?php
$sql="SELECT COUNT(*) AS Anzahl FROM geraete";                      //anzahl der einträge
$result = mysql_query($sql);
$zeile = @mysql_fetch_array($result);
$anzahl = $zeile['Anzahl'];
if ($anzahl == 0)
    {
    ?>
    <br/><input type="submit" name="ausgeber" value="loeschen" disabled="disabled" />  ausgew&auml;hlte Technik l&ouml;schen  <!-- löschbutton -->
    <br/><input type="submit" name="ausgeber" value="aendern" disabled="disabled" />  ausgew&auml;hlte Technik bearbeiten  <!-- button -->
    <?php
    }
    else
    {
    ?>
    <br><input type="submit" name="ausgeber" value="loeschen"/>  ausgew&auml;hlte Technik l&ouml;schen  <!-- löschbutton -->
    <br><input type="submit" name="ausgeber" value="aendern"/>  ausgew&auml;hlte Technik bearbeiten  <!-- button -->
    <?php
    }
?>
<br/><br/>
<?php  
if (isset ($_POST['ausgeber']))
{
    if (isset($_POST['bearbeiten']))
        {
            if ($_POST['ausgeber']=="aendern")
                {
                ?>
                <big><big>
                <br/>ausgew&auml;hlte Technik &auml;ndern:<br/>
                </big></big>
                <table border="1" width="90%" bgcolor="#DAE239">
                <colgroup>
                    <col width="70"/>
                    <col width="70"/>
                    <col width="300"/>
                    <col width="300"/>
                    <col width="100"/>
                    <col width="500"/>
                </colgroup>
                <tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>letzter T&Uuml;V</th><th>Anschaffungsjahr</th></tr>
                <tr>
                <td><?php echo $atypan ?></td>
                <td><?php echo $regnrb ?></td>
                <td><input type="text" name="ahersteller" value= "<?php echo $herst;?>"/></td>
                <td><input type="text" name="abemerkung" value= "<?php echo $bemerk;?>"/></td>

            <td>
<?php
if (!isset($tuevl))
{
    $tuevl= $heuteng;
}
//else
$sep="-";
$format="Ymd";
    $pos1    = strpos($format, 'd');
    $pos2    = strpos($format, 'm');
    $pos3    = strpos($format, 'Y');
    $check    = explode($sep,$tuevl);
$day=$check[$pos1];
$mont=$check[$pos2];
$year=$check[$pos3];
//            echo $day,"-", $mont,"-", $year;
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
            $end=$end +1;
            $myCalendar->setYearInterval(2010, $end);
            //output the calendar
            $myCalendar->writeScript();
            ?>
            <input type="checkbox" name="ke[]"  /><br>kein Eintrag notwendig
            </td>
            <td>
            <input type="text" name="kaufjahr" value= "<?php echo $kaufjahr;?>"/>
            <?php 
//            echo $kaufjahr;
            ?>
            </td>
                </tr><br/>
                </table><br/>                
                <input type="submit" name="aeintr" value="eintragen" class="Button-w"/>
                </form>
                <?php
                }
        }   
}
?>
<big><big>
<br>neue Technik eintragen:                                     
</big></big>
<form  action="Typlisten-bearbeiten.php" method="POST" name="form1">
<?php  
    ?>
<p> Welcher Gerätetyp soll eingetragen werden?  </p>               
<table border="1" width="90%" bgcolor="#38FF2C">
    <colgroup>
        <col width="70"/>
        <col width="70"/>
        <col width="300"/>
        <col width="300"/>
        <col width="500"/>
    </colgroup>
    <tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>letzter T&Uuml;V</th><th>Anschaffungsjahr</th></tr>
    <tr>
    <td>
                <select name = "eintrag" size="1" >
                <?php
                $sql = mysql_query("SELECT * FROM typ ");           // Typenliste aus Datenbank auslesen
                while($ds= mysql_fetch_object($sql))
                {
                    $typn = $ds->Typ ;
                    $idn = $ds->ID ;
                    echo "<option value='$idn'>" .$typn. "</option>" ;
                }
                ?>
                </select>
                </td>
                <td><input type="text" name="nr" size="4"/></td>
                <td><input type="text" name="hersteller"/></td>
                <td><input type="text" name="bemerkung"/></td>
<td>
<?php
$day=date("d");                                                     //Kalender
$mont=date("m");
$year=date("Y");
//echo $day, $mont, $year;
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
?>
<input type="checkbox" name="ke[]"  /><br/>kein Eintrag notwendig
</td>
<td><input type="text" name="ajahr" size="4"/></td>                           
</tr></table><br/>
<input type="submit" name="eintr" value="eintragen" class="Button-w"/> 
</form>
<?php  

echo "<br>","+-",$ausgabe,"-+";
if(isset($ausgabe))
{
    if ($ausgabe==!"")
    {
        ?>
        <form name="ausg" method="POST">
        <input type="hidden" name="ausg" value="aaaa" />
        <br/><textarea  cols="110" rows="1" class="text-a" readonly> <?php echo $ausgabe; ?></textarea><br/>
        </form>
        <?php 
    }
}

?>
<font color="#FF0000"><big><big>
<?php 
//echo $ausgabe; 
?> 


</big></big></font>
<form method="POST" action="Typlisten-bearbeiten.php">
<input name="zu1" type="submit" value="die Ger&auml;teliste nach T&Uuml;V-Termin sortiert anzeigen" />
</form>
<a name="ende"></a> 
<a href="#anfang">nach oben</a><br/><br/>
<form method="POST" action="Typlisten-bearbeiten.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>           
<?php  
/*
$abemerkung = bemerkung in geräte
$anz = übergabevariable in functionen
$anzahl = anzahl der zeilen in geräte
atypan = typ text in typ
$ausgabe = ausgabevariable
$ausgel = ausgeliehen in geräte
$bemerk = bemerkung in geräte
$bemerkung = bemerkung in geräte
$check = variable in functionen
$d = variable in functionen
$date = übergabevariable in functionen
$datets / $datets1 / $datets2 = variable in functionen
$datum = übergabevariable in functionen
$day / $mont / $year = variable im calendar
$ds = datenzähler
$eintr = variable in datenbankabfrage
$end = jahresvariable in calendar
$ew / $ew1 / $ew2 / $ew3 = log text
$format = übergabevariable in functionen
$gid = id in geräte
$herst = hersteller in geräte
$hersteller = hersteller in geräte
$heut = heite in deu
$heute = heute in engl
$hl = länderienstellung in calendar
$idn = id in typ
$jahr / $monat /$tag = variable in functionen
$l = log eintrag
$nrtyp = typnummer in geräte
$pos1 / $pos2 / $pos3= variable in calendar
$query / $query1 = variable in datenbankabfrage
$r0 = tüvtermin hat sich geändert
$regnr / $regnrb = regnummer in geräte
$rep_notw / $rep_notw = reparatur notwendig in geräte
$result / $resultID / $resultID1 = variable in datenbankabfrage
$sep = seperator
$sql =  variable in datenbankabfrage
$tuef = tüv in geräte
$tuefeng = $tuef
$tuev = tuev deu
$tuevbe = tuev -30 tage
$tuevd = $tuevl deu
$tuevde = $tuevee deu
$tuevdk = var rdat2
$tueve = rückgabedatum
$tuevee =  rückgabedatum
$tuevl = tüv geräte
$typ = typ nummer
$typan = typ text
$typn = typ text in typ
$zeile = zeile in var
*/
?>
