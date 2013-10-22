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
?>
<title>Ausleihgrund</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Ausleihe nach Ausleihgrund
</big></big></big><br>
<br>
<?php  
echo "Benutzer :", $_SESSION['kname'], "<br>";                       //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
echo $heut,"<br>";
$l="Ausleihgrund ge&ouml;ffnet";
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

?>
<big><br><br>w&auml;hlen Sie einen Ausleihgrund aus</big>
<form method = "POST" action="Ausleihgrund.php">
<select name = "ausname" size="1">
<?php
$auslgrd=$_POST[ausname];
$sql = mysql_query("SELECT DISTINCT auslgrund FROM ausleihe WHERE abgeschlAusleihe=0 ORDER by auslgrund");           //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $ag = $ds->auslgrund ;                                         //verschiedene Gründe aus ausleihe suchen
    if (isset($_POST['ausname']))
    {
        if ($auslgrd==$ag)
        {
            echo "<option selected value = '$ag'>" .$ag. "</option>"  ;   // Dropdown schreiben ausgewählt
        }
        else
        {
            echo "<option value = '$ag'>" .$ag. "</option>"  ;      // Dropdown schreiben
        }
    }
    else
    {
        echo "<option value = '$ag'>" .$ag. "</option>"  ;          // Dropdown schreiben standard
    }
}
?></select>
<input name="submit" type="submit" value="&uuml;bernehmen" class="Button-w"/>       <!-- Abschicktaste -->
</form>
<?php
 
if (isset($_POST['submit']))
{
    ?>
    <table border="1" width="80%">
    <tr><th>Typ</th><th>Nummer</th><th>Entleiher</th><th>Ausgeber</th><th>Ausleihgrund</th><th>Verschiedenes</th><th>Ausleihdatum</th><th>geplante R&uuml;ckgabe</th><th>Ausleihnummer</th></tr>
    <?php
    $sql = mysql_query("SELECT * FROM ausleihe WHERE auslgrund='$auslgrd' and abgeschlAusleihe = 0 "); //Tabelle ausleihe auswählen mit gewählten ausleihgrund 
    while($ds= mysql_fetch_object($sql))
    {
        $idg=$ds->IDGeraet;
        $ausln=$ds->AuslName;
        $ausl=$ds->Ausgeber;
        $auslg=$ds->auslgrund;
        $versch=$ds->verschiedenes;
        $datv=$ds->Datum_von;
        $datb=$ds->Datum_bis;
        $jahr=$ds->jahr;
        $lfnr=$ds->lfnr;
       if ($idg > 0)
        {
            $sql1 = mysql_query("SELECT * FROM geraete WHERE ID=$idg "); //Tabelle gereate auswählen
            while($ds2= mysql_fetch_object($sql1))
            {
                $typ = $ds2->Typ ;                                  // aus Datenbank auslesen
                $regnr=$ds2->RegNR ;                                // aus Datenbank auslesen
            }                                                    
            $query    = "SELECT Typ FROM typ WHERE ID=$typ";        //aus Typ-ID Typ machen
            $resultID = @mysql_query($query);                       
            $typan = mysql_result($resultID,0);                     //aus Typ-ID Typ machen
         }
         else
         {
             $typ = 0;                                              //wenn typ und regnr nicht vorhanden ->0
             $regnr= 0;
         }                                                    
        $datva= "" . date_mysql2german($datv) . " \n";
        $datba= "" . date_mysql2german($datb) . " \n";
        $jalfnr=$jahr."/".$lfnr;
        $heuteng= "" . date_mysql2engl($heut) . " \n";
        echo "<tr>";                                                //tabelle ausgeben
        if ($idg > 0)
        {
        echo "<td>",$typan,"</td>";
        echo "<td>",$regnr,"</td>";
        }
        else
        {
        echo "<td>"," - ","</td>";
        echo "<td>"," - ","</td>";
        }
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
        }                                                           //Wert 0 abblocken
        else
        {
            $datba= "" . date_mysql2german($datb) . " \n";          // Formatwandlung
        }
        if ($datba== "kein Eintrag")                                // wenn TÜV Termin vorbei auf rot setzen
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
    
        echo "<td>",$jalfnr,"</td>";
        echo "</tr>";
    }
}   
?>
</table><br>
</form>
<form method="POST" action="Ausleihgrund.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$ag = ausleihgrund in ausleihe
$ausl = ausgeber in ausleihe
$auslg = ausleihgrund in ausleihe
$auslgrd = ausgewählter Ausleihgrund in ausleihe
$ausln = ausleihname in ausleihe
$d = variable in function
$datb = datum bis in ausleihe
$datba = $datb in ausleihe deu
$date = übergabevariable in function
$datv = datum von in ausleihe engl
$datva = $datv in ausleihe deu
$ds / $ds2 = datenzähler
$heut = heute deu
$heuteng = heute engl
$idg = geräteid in geräte
$jahr = jahr in ausleihe
$jalfnr = jahr/laufende nummer
$l = log eintrag
$lfnr = laufende nummer in ausleihe
$query = variable in datenbankabfrage
$regnr = registriernummr in geräte
$resultID / $sql / $sql1 = variable in datenbankabfrage
$typ = typ nummer in geräte
$typan = typ text in typ
$versch = verschiedenes in ausleihe
*/
?>
