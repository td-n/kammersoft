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
?>
<title>ausgeliehenes</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<a name="anfang"></a><br>
<big><big><big>
ausgeliehenes Material
</big></big></big>
<br><a href="#ende">nach unten</a>
<br>
<table border="1" width="90%">
<tr> <th>Typ</th><th>Nummer</th><th>Entleiher</th><th>Ausgeber</th><th>Ausleihgrund</th><th>Verschiedenes</th><th>Ausleihdatum</th><th>geplante R&uuml;ckgabe</th><th>Ausleihnummer</th></tr>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut,"<br>";
$l="ausgel_mat ge&ouml;ffnet";
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
    
if (isset($_POST['auswahl']))
{
     $ausw = $_POST['auswahl'];
}
?>
<form method = "POST" action="ausgel_mat.php">
Wo nach soll sortiert werden ?
<select name = "auswahl" size="1">
<?php
if (!isset($ausw))                                                  //auswahl drop down
{
    $ausw="typ, RegNR";
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
        if ($ausw=="auslgrund")
        {
            ?><option value="auslgrund" selected >Ausleihgrund</option><?php
        }
        else
        {
            ?><option value="auslgrund" >Ausleihgrund</option><?php
        }
    if ($ausw=="lfnr")
        {
            ?><option  value="lfnr" selected>Ausleihnummer</option><?php
        }
        else
        {
            ?><option value="lfnr">Ausleihnummer</option><?php
        }
     }
if (!isset ($ausw))
   {
        ?>
        <option value="typ, RegNR, verschiedenes" >Ger&auml;t</option>
        <option value="AuslName" >Ausleiher</option>
        <option value="Datum_von" >Ausleihdatum</option>
        <option value="Datum_bis" >geplanter R&uuml;ckgabedatum</option>
        <option value="auslgrund" >Ausleihgrund</option>
        <option value="lfnr">Ausleihnummer</option><?php
   }
       ?>
</select>
<input name="submit" type="submit" value="sortieren" />
</form> <br><br>
<?php
//echo $ausw;
$sql = mysql_query("SELECT * FROM ausleihe  " );                    //Tabelle ausleihe auswählen    ORDER BY $ausw
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
$sql = mysql_query("SELECT * FROM ausleihtabelle WHERE abgeschlAusleihe=0 ORDER BY $ausw " ); //Tabelle ausleihtabelle auswählen    ORDER BY $ausw
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

    echo "<td>",$auslg,"</td>";

    if ($versch!="")
    {
        echo "<td>",$versch,"</td>";
    }
    else
    {
        echo "<td>"," - ","</td>";
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
    if ($datba== "kein Eintrag")                                     // wenn TÜV Termin vorbei auf rot setzen
    {
        ?> <td bgcolor =#0ABDFF > <?php
    }
    elseif($heuteng >= $datb)
    {
        ?> <td bgcolor =#FF6F6C > <?php
    }
    else
    {
        ?> <td bgcolor =#3FFF00 > <?php
    } ;
    echo $datba,"</td>";
    echo "<td>",$jlfnr,"</td>";
    echo "</tr>";

}

$sql = " DROP TABLE IF EXISTS `ausleihtabelle` ";                   //tabelle löschen
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());

//mysql_close($ds);
//mysql_close($ds1);
?>
</table><br>
<a name="ende"></a>
<a href="#anfang">nach oben</a><br> <br>
</form>
<form method="POST" action="ausgel_mat.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$abgs = abgeschlossene Ausleihe aus ausleihe
$ausl = Ausleiher aus ausleihe
$auslg = Ausleihgrund aus ausleihe
$ausln = Ausleihname aus ausleihe
$ausw = Dropdownauswahl
$d = Tag in funktion datum wandeln
$datb = datum bis in ausleihe engl.
$datba = datum bis in ausleihe deu.
$date = Übergabevariable un funktion datum wandeln
$datr = Rückgabedatum in ausleihe
$datv = Datum von in ausleihe engl.
$datva = Datum von in ausleihe deu.
$db_erg = Variable in tabelle erstellen
$ds / $ds1 = Datenzähler in abfrage
$eintr = variable in abfrage
$heut = heute in deu
$heuteng = heute in engl.
$idg = Geräteid in ausleihe
$jahr = Jahr in ausleihe
$jalfnr = Jahr und laufende nummer
$jlfnr = laufende nummer un ausleihtabelle
$l = log text
$lfnr = laufende nummer aus ausleihe
$query = variable in abfrage
$regnr = regnummer in geräte
$resultID = variable in abfragen
$rueckn = Rücknehmer in ausleihe
$sql / $sql1 = variable in ausleihe
$typ = Typ in geräte
$typan = Typ text in Typ
$versch = verschiedenes in ausleihe
*/
?>