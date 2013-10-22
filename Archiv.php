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
function date_mysql2german($date) {
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
?>
<title>Archiv</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Archive erstellen<br>
</big></big></big>
<?php
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //aktueller Benutzer
$user =  $_SESSION['kname'];
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut,"<br>";
$l="Archiv ge&ouml;ffnet / ".$_SESSION['kname'];                                          //log schreiben auf der Seite
logsch ($l);

if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$heuteng= "" . date_mysql2engl($heut) . " \n";

$sql = mysql_query("SELECT * FROM conf  WHERE `wert`='desktopverzeichnis'");       //Desktoppfad aus conf auslesen
while($ds= mysql_fetch_object($sql))
{
    $filenamelw=$ds->was;
}
$path = $filenamelw."Archiv/";
if (!file_exists($filenamelw))
{                                                           //nein
 ?><meta http-equiv="refresh" content="0; URL=deskverz.php" /><?php
}



if (isset ($_POST['jahrwahl']))                                     //existiert ausgewähltes Jahr
{
        $auswjahr = $_POST['jahrwahl'];
//echo $filenamelw,"<br>",$path,"<br>";
        if (file_exists($filenamelw))                               
        {                                                           //ja
//echo "Das Verzeichnis $filenamelw existiert","<br>";
            $query1 = "UPDATE `conf` SET `was` = '$filenamelw'  WHERE `wert` = 'desktopverzeichnis'	";
            $resultID1 = @mysql_query($query1);                     //Daten updaten
            $heut = date("Y-m-d");                                  //heutuger Datum
            $uv = $filenamelw."Archiv/" ;
//echo $uv,"<br>";
            if (!file_exists($uv))                                  //wenn es nicht existiert
            {
                if ( mkdir ( $uv ) )                                //schreiben
                {
                  echo 'Verzeichnis erstellt!<br>';
                }
            }
        }
        $datei= "arch"."-".$auswjahr.".txt";                        //schriebt in Datei arch(Jahr).txt
        $datei_name = $path.$datei;
        $daten1="ID/Typ/Registriernummer/Ausleihname/Ausgeber/Rücknehmer/Ausleihgrund/verschiedenes/abgeschlosseneAusleihe/Datum-von/Datum-bis/Datum-Rückgabe/Jahr/laufendeNummer";  //Überschrift schreiben
        $daten=$daten1."\r\n";                                      //Paramenter
        $fp = fopen($datei_name, "w");
        fwrite($fp, $daten);                                        //schreiben
        fclose($fp);                                                //schließen
    $sql = mysql_query("SELECT * FROM ausleihe WHERE jahr='$auswjahr' AND abgeschlAusleihe=1 ");           //Tabelle gereate auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $idg = $ds->IDGeraet;
        if ($idg > 0)                                               //nur Geräte auswählen
        {
            $sql1 = mysql_query("SELECT * FROM geraete WHERE ID='$idg' "); //Tabelle gereate auswählen
            while($ds1= mysql_fetch_object($sql1))
            {
                $typ = $ds1->Typ ;                                  // aus Datenbank auslesen
                $regnr=$ds1->RegNR ;
                $anztg=$ds1->anz_tauchg;                            // aus Datenbank auslesen
            }
            $query    = "SELECT Typ FROM typ WHERE ID='$typ'";      //aus Typ-ID Typ machen
            $resultID = @mysql_query($query);                       //aus Typ-ID Typ machen
            $typan = mysql_result($resultID,0);
         }
        $pid= $ds->ID ;
        $ausln = $ds->AuslName;
        $ausg = $ds->Ausgeber;
        $rnem = $ds->Ruecknehmer;
        $auslgr = $ds->auslgrund;
        $versch = $ds->verschiedenes;
        $aausl = $ds->abgeschlAusleihe;
        $datv = $ds->Datum_von;
        $datb = $ds->Datum_bis;
        $datr = $ds->Datum_rueck;
        $rjahr = $ds->jahr;
        $lfnr = $ds->lfnr;
        if (isset($typan))
        {
             $daten= $pid."/".$typan."/".$regnr."/".$ausln."/".$ausg."/".$rnem."/".$auslgr."/".$versch."/".$aausl."/".$datv."/".$datb."/".$datr."/".$rjahr."/".$lfnr.  "\r\n";  //daten schreiben Geräte
        }
        else
        {
            $daten= $pid."/"."-"."/"."-"."/".$ausln."/".$ausg."/".$rnem."/".$auslgr."/".$versch."/".$aausl."/".$datv."/".$datb."/".$datr."/".$rjahr."/".$lfnr.  "\r\n";         //daten schreiben verschiedenes
        }
        $fp = fopen($datei_name, "a");
        fwrite($fp, $daten);                                        //schreiben
        if(isset($_POST['lo']))                                     //ist löschen gesetzt?
            {
                $query = "DELETE FROM `ausleihe` WHERE id=$pid ";   //Tabelle Wert löschen
                $resultID2 = @mysql_query($query);
            }
     }
    $ew="Ausleihe des Jahres: " ;                                   //log schreiben
    $ew1=" archiviert in Datei: ";
    $ew2="  und gelöscht";
    if(isset($_POST['lo']))
    {
        $l=$ew.$auswjahr.$ew1.$datei.$ew2 ;
    }
    else
    {
        $l=$ew.$auswjahr.$ew1.$datei ;
    }
    logsch($l);
    
    fclose($fp);                                                    //arch-dateien schliesen
        
    $path = $uv;  // log.txt beginnen
    $datei= "log"."-".$auswjahr.".txt";
    $datei_name = $path.$datei;
    $daten1="ID/Eintrag/Name/Zeit/";
    $daten=$daten1."\r\n";
    $fp1 = fopen($datei_name, "w");
    fwrite($fp1, $daten);
    fclose($fp1);
    $sql = mysql_query("SELECT * FROM log  ");                      //Tabelle log auswählen
    while($ds1= mysql_fetch_object($sql))
    {
        $id= $ds1->id ;
        $eintrag = $ds1->eintrag;
        $logname = $ds1->name;
        $logtime = $ds1->time;

        $datum_ex = explode("-", $logtime);
        $jahr=$datum_ex[0];
        
        if ($jahr==$auswjahr)
        {
            $daten= $id."/".$eintrag."/".$logname."/".$logtime. "\r\n"; //in log.txt schreiben
            $fp1 = fopen($datei_name, "a");
            fwrite($fp1, $daten);
            fclose($fp1);
            if(isset($_POST['lo']))                                 //wenn löschen gesetzt ist
               {
                    $query = "DELETE FROM `log` WHERE id=$id ";     //Tabelle Wert löschen
                    $resultID2 = @mysql_query($query);
               }            
        }
    }
    $ew="log des Jahres: " ;                                        //log schreiben
    $ew1=" archiviert in Datei: ";
    $ew2="  und gelöscht";
    if(isset($_POST['lo']))
    {
        $l=$ew.$auswjahr.$ew1.$datei.$ew2 ;
    }
    else
    {
        $l=$ew.$auswjahr.$ew1.$datei ;
    }
    logsch($l);
}
?>
<big><big>welches Jahr soll archiviert werden?</big></big><br/>
<form method = "POST" action="Archiv.php">
<select name = "jahrwahl" size="1">
<?php 
$sql = mysql_query("SELECT DISTINCT jahr FROM ausleihe order by jahr");  //unterschiedliche Jahr-Einträge in Ausleihe ermitteln 
while($ds= mysql_fetch_object($sql))
{
    $jahr = $ds->jahr;                                              //verschiedene Jahrzahlen aus ausleihe suchen
    echo "<option value = '$jahr'>" .$jahr. "</option>"  ;          //Dropdown mit ausgewählten schreiben
}
?></select>
<br/><br/>
<input name="jahr" type="submit" value="&uuml;bernehmen" class="Button-w"/><br/>       <!-- Abschicktaste -->
<br>
<input type="checkbox" name="lo" value="ausw"/>zus&auml;tzlich das Jahr aus der Datenbank l&ouml;schen<br> 
<big><font color="#FF0000"> (Vorsicht es werden alle abgeschlossenen Ausleihen und alle log-Eintr&auml;ge des gew&auml;hlten Jahres gel&ouml;scht)</font></big><br> 
<big><font color="#0000FF">Offene Ausleihen bleiben erhalten!</font></big><br/>
</form>
<?php 
if (isset($_POST['jahr']))                                          //existiert ein ausgewähltes Jahr?
{
    if(isset($_POST['lo']))                                         //löschen gesetzt?
    {
        ?>
        <p><font color="#0A0AF0"><big>Die Dateien wurden auf den Desktop (oder in dem von ihnen gew&auml;hlten Verzeichnis) im Ordner Archiv archiviert und die Daten in der Datenbank gel&ouml;scht</big><br></font></p>
        <?php
    }
    else
    {
        ?>
        <p><font color="#0A0AF0"><big>Die Dateien wurden auf den Desktop (oder in dem von ihnen gew&auml;hlten Verzeichnis) im Ordner Archiv archiviert</big><br></font></p>
        <?php
    }
}
//mysql_close($ds);
//mysql_close($ds1);
?><br/>
<form method="POST" action="Archiv.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php 
/*
$aausl = abgeschlossenen Ausleihe
$anztg = Anzahl Tauchgänge
$ausg = Ausgeber
$auslgr = Ausleihgrund
$ausln = Ausleihname Ausleiher
$auswjahr = ausgewähltes Jahr
$d = Datumsteil in Funktion
$datb = Datum bis
$date = Variable die in Funktion übergeben wurde
$datei = Dateiname
$datei_name = Dateiname mit Verzeichnis
$daten = Dateiname mit Verzeichnis und Schalter
$daten1 = Überschrift in Archiv-Textdatei
$datr = Rückgabedatum
$datum_ex = Übergabedatei Datum in zu schreibender Textdatei
$datv = Datum von
$ds / $ds1 / ... Datenzähler
$eintrag = Log-Eintrag
$ew / $ew1 ... Texteinträge für log
$filenamelw = Pfad zu Desktop
$fp /$fp1 ...= Variable zum öffnen der Textdatei
$heut = Heutiges Datum deu.
$heuteng = heutiges Datum engl.
$id = log-Eintrag
$idg = Geräte ID in Geräte
$jahr = Variable Jahr  
$l = Texteintrag zur Übergabe an log
$lfnr = laufebde Nummer von Ausleihnummer
$logname = Bediehner Ausgeber
$logtime = Zeit des log Eintrages
$path = Pfad zum Archivverzeichnis auf den Desktop
$pid = ID der Geräte des gewählten Jahres ind abgeschlossener Ausleihe
$query / $query1 ... = Übergabevariable von Abfragen
$regnr = Geräteregistriernummer
$resultID / $resultID1 ... =  Übergabevariable von Abfragen
$rjahr = Rückgabejahr der abgeschlossenen Ausleihe
$rnem = Rücknehmer
$sql / $sql1 ... = Übergabevariable von Abfragen
$typ = Typnummer
$typan = Typbezeichnung
$user = angemeldeter Ausgeber
$uv = Pfad zum Archivverzeichnis
$versch = Bezeichnung Verschidenes
*/
?>
