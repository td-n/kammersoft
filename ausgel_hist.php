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
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
?>
<title>ausgeliehenes</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<a name="anfang"></a><br>
<big><big><big>
ausgeliehenes Material gesamte Historie
</big></big></big>
<br><a href="#ende">nach unten</a>
<br>
<table border="1" width="90%">
<tr> <th>Typ</th><th>Nummer</th><th>Entleiher</th><th>Ausgeber</th><th>R&uuml;cknehmer</th><th>Ausleihgrund</th><th>Verschiedenes</th><th>abgeschlossen</th><th>Ausleihdatum</th><th>geplante R&uuml;ckgabe</th><th>R&uuml;ckgabedatum</th><th>Ausleihnummer</th></tr>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut,"<br>";
$l="ausgel_hist ge&ouml;ffnet";
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/                                                                  //tabelle erstellen
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$sql = " CREATE TABLE IF NOT EXISTS `ausleihtabelle` (              
    `id` INT( 50 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `typ` VARCHAR( 25 ) NOT NULL ,
    `RegNR` INT( 5 ) NOT NULL ,
    `AuslName` VARCHAR( 25 ) NOT NULL ,
    `Ausgeber` VARCHAR( 25 ) NOT NULL ,
    `Ruecknehmer` VARCHAR( 25 ) NOT NULL ,
    `auslgrund` VARCHAR( 35 ) NOT NULL,
    `verschiedenes` VARCHAR( 65 ) NOT NULL,
    `abgeschlAusleihe` TINYINT(1) NOT NULL,
    `Datum_von` DATE NOT NULL,
    `Datum_bis` DATE NOT NULL,
    `Datum_rueck` DATE NOT NULL,
    `lfnr` VARCHAR( 25 ) NOT NULL
    ) ENGINE = MYISAM ;
    ";
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());
    
if (isset($_POST['auswahl']))                                       //dropdown auswahl ?
{
     $ausw = $_POST['auswahl'];
}
?>
<form method = "POST" action="ausgel_hist.php">
Wo nach soll sortiert werden ?
<select name = "auswahl" size="1">
<?php
if (!isset($ausw))
{
    $ausw="typ, RegNR";                                             //standardauswahl
}
if (isset($ausw))
    {
    if ($ausw=="typ, RegNR, verschiedenes")
        {
            ?><option value="typ, RegNR, verschiedenes" selected >Ger&auml;t</option><?php
        }
        else
        {
            ?><option value="typ, RegNR, verschiedenes" >Ger&auml;t</option><?php
        }
    if ($ausw=="AuslName" )
        {
            ?><option value="AuslName" selected >Entleiher</option><?php
        }
        else
        {
            ?><option value="AuslName" >Entleiher</option><?php
        }
        if ($ausw=="Datum_von")
        {
            ?><option value="Datum_von" selected >Ausleihdatum</option><?php
        }
        else
        {
            ?><option value="Datum_von" >Ausleihdatum</option><?php
        }
    if ($ausw=="Datum_bis")
        {
            ?><option  value="Datum_bis" selected>geplante R&uuml;ckgabe</option><?php
        }
        else
        {
            ?><option value="Datum_bis">geplante R&uuml;ckgabe</option><?php
        }
        if ($ausw=="Datum_rueck")
        {
            ?><option value="Datum_rueck" selected >R&uuml;ckgabedatum</option><?php
        }
        else
        {
            ?><option value="Datum_rueck" >R&uuml;ckgabedatum</option><?php
        }
    if ($ausw=="verschiedenes")
        {
            ?><option  value="verschiedenes DESC" selected>verschiedenes</option><?php
        }
        else
        {
            ?><option value="verschiedenes DESC">verschiedenes</option><?php
        }
     }
if (!isset ($ausw))                                                 //Liste schreiben
   {
        ?>
        <option value="typ, RegNR, verschiedenes" >Ger&auml;t</option>
        <option value="AuslName" >Ausleiher</option>
        <option value="Datum_von" >Ausleihdatum</option>
        <option value="Datum_bis" >geplanter R&uuml;ckgabedatum</option>
        <option value="Datum_rueck" >R&uuml;ckgabedatum</option>
        <option value="verschiedenes">verschiedenes</option><?php
   }
       ?>
</select>
<input name="submit" type="submit" value="sortieren" />
</form> <br><br>
<?php
//echo $ausw;
$sql = mysql_query("SELECT * FROM ausleihe  " );                    //Tabelle ausleihe auswählen    
while($ds= mysql_fetch_object($sql))
{
    $idg=$ds->IDGeraet;
    $ausln=$ds->AuslName;
    $ausl=$ds->Ausgeber;
    $rueckn=$ds->Ruecknehmer;
    $auslg=$ds->auslgrund;
    $versch=$ds->verschiedenes;
    $abgs=$ds->abgeschlAusleihe;
    $datv=$ds->Datum_von;
    $datb=$ds->Datum_bis;
    $datr=$ds->Datum_rueck;
    $jahr=$ds->jahr;
    $lfnr=$ds->lfnr;
    if ($idg > 0)
    {
        $sql1 = mysql_query("SELECT * FROM geraete WHERE ID='$idg' "); //Tabelle gereate auswählen
        while($ds1= mysql_fetch_object($sql1))
        {
            $typ = $ds1->Typ ;                                          // aus Datenbank auslesen
            $regnr=$ds1 ->RegNR ;                                       // aus Datenbank auslesen
        }
        if (isset($typ))
        {
            $query    = "SELECT Typ FROM typ WHERE ID=$typ";            //aus Typ-ID Typ machen
            $resultID = @mysql_query($query);                           //aus Typ-ID Typ machen
            $typan = mysql_result($resultID,0);
        }
        else
        {
            $typan="Ger&auml;t nicht mehr vorhanden";
        }
     }
    if (!isset($typan))
    {
         $typan="-";
    }
    if (!isset($regnr))
    {
         $regnr="-";
    }
    $jalfnr=$jahr."/".$lfnr;
    $eintr = "INSERT INTO ausleihtabelle                            
        (typ, RegNR, AuslName, Ausgeber, Ruecknehmer, auslgrund, verschiedenes, abgeschlAusleihe, Datum_von, Datum_bis, Datum_rueck, lfnr)
        VALUES ('$typan','$regnr','$ausln','$ausl','$rueckn','$auslg','$versch','$abgs', '$datv', '$datb', '$datr', '$jalfnr')";
    mysql_query($eintr);                                            //in neue tabelle schreiben
//echo  $typan,$regnr,$ausln,$ausl,$rueckn,$auslg,$versch,$abgs,$datv,$datb,$datr,$jahr,$lfnr,"<br>";
unset  ($typan,$regnr,$ausln,$ausl,$rueckn,$auslg,$versch,$abgs,$datv,$datb,$datr,$jahr,$lfnr);
}
// echo $ausw;
$sql = mysql_query("SELECT * FROM ausleihtabelle ORDER BY $ausw " ); //Tabelle ausleihtabelle auswählen    ORDER BY $ausw
while($ds= mysql_fetch_object($sql))
{
    $typan=$ds->typ;
    $regnr=$ds->RegNR;
    $ausln=$ds->AuslName;
    $ausl=$ds->Ausgeber;
    $rueckn=$ds->Ruecknehmer;
    $auslg=$ds->auslgrund;
    $versch=$ds->verschiedenes;
    $abgs=$ds->abgeschlAusleihe;
    $datv=$ds->Datum_von;
    $datb=$ds->Datum_bis;
    $datr=$ds->Datum_rueck;
    $jlfnr=$ds->lfnr;
    $datva= "" . date_mysql2german($datv) . " \n";
    $datba= "" . date_mysql2german($datb) . " \n";
    $heuteng= "" . date_mysql2engl($heut) . " \n";
    echo "<tr>";
    echo "<td>",$typan,"</td>";
    echo "<td>",$regnr,"</td>";
    echo "<td>",$ausln,"</td>";
    echo "<td>",$ausl,"</td>";

    if ($rueckn=="")                                                //Anzeige
    {
        $rueckn="noch ausgeliehen";
    }
    echo "<td>",$rueckn,"</td>";
    echo "<td>",$auslg,"</td>";

    if ($versch!="")
    {
        echo "<td>",$versch,"</td>";
    }
    else
    {
        echo "<td>"," - ","</td>";
    }
    if ($abgs=="0")
    {
        $abgs="nein";
        echo "<td>",$abgs,"</td>";
    }
    else
    {
        $abgs="ja";
        echo "<td>",$abgs,"</td>";
    }
    echo "<td>",$datva,"</td>";
    if ($datb == '0000-00-00')
    {
        $datba= "kein Eintrag";
    }                                                               //Wert 0 abblocken
    else
    {
        $datba= "" . date_mysql2german($datb) . " \n";               // Formatwandlung
    }
    echo "<td>",$datba,"</td>";

    if ($datr == '0000-00-00')
    {
        $datra= "kein Eintrag";
    }                                                               //Wert 0 abblocken
    else
    {
        $datra= "" . date_mysql2german($datr) . " \n";              // Formatwandlung
    }
    echo "<td>",$datra,"</td>";
    echo "<td>",$jlfnr,"</td>";
    echo "</tr>";

}
//mysql_close($ds);
//mysql_close($ds1);
$sql = " DROP TABLE IF EXISTS `ausleihtabelle` ";                   //tabelle löschen
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());
?>
</table><br>
<a name="ende"></a>
<a href="#anfang">nach oben</a><br> <br>
<form method="POST" action="ausgel_hist.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php 
/*
$abgs = abgeschlossene Ausleihe in ausleihe
$ausl = Ausgeber in ausleihe
$auslg = Ausleiihgrund in ausleihe
$ausln = Ausleihname in ausleihe
$ausw = Dropdownauswahl
$d = variable un funktion datum übersetzen
$datb = Datumbis in ausleihe engl.
$datba = datum bis in ausleihe deu
$date = Übergabevariable un funktion
$datr = Datum Rückgabe in ausleihe engl.
$datra = Datum Rückgabe in ausleihe deu
$datv = Datum von in ausleihe engl.
$datva =  Datum von in ausleihe deu
$db_erg = variable tabelle erstellen
$ds / $ds1 = datenzähler in abfragen
$eintr = variable un datenbankabfrage
$heut = heute deu
$heuteng = heute engl.
$idg = Geräteid in ausleihe
$jahr = Jahr in ausleihe
$jalfnr = Jahr und laufende nummer
$jlfnr = Jahr und laufende nummer in ausleihtabellle
$l = Texteintrag in log
$lfnr = laufende nummer in ausleihe
$query = variable in abfragen
$regnr = Regnummer in geräte
$resultID = variable in abfragen
$rueckn = Rücknehmer in ausleihe
$sql /$sql1 = variable in abfragen
$typ = typ in geräte
$typan = text typ in typ
$versch = verschiedenes in ausleihe 
*/
?>
