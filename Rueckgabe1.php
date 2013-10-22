<html>
<head>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#mysql.inc.php"); ?>
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
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
?>
<title>R&uuml;ckgabe</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
R&uuml;ckgabe 2. Schritt<br>
</big></big></big>
<?php
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //aktueller Benutzer
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut,"<br>";
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}
?>
<form name ="zurueck" action="Rueckgabe2.php"  method = "POST" >
<table border="1" width="90%" >
<tr><th>Ger&auml;t</th><th>Nummer</th><th>Entleiher</th><th>Ausleihgrund</th><th>Verschiedenes</th><th>ausgeliehen am</th><th>geplante R&uuml;ckgabe</th><th>Ausleihnummer</th><th>R&uuml;ckgabe</th><th>Anzahl Tauchg&auml;nge</th></tr>
<?php
if (isset ($_POST['ausname']))
{
    $ausname = $_POST['ausname'] ;
    $query = "TRUNCATE `var`"; //Tabelle Werte löschen
    $resultID = @mysql_query($query);
    $eintr = "INSERT INTO `var` (`user`) VALUES ('$ausname')";      //Name in var eintragen
    mysql_query($eintr)  ;
}
else
{
/*    echo "+++";
    $sql1 = mysql_query("SELECT * FROM var ");                      //Tabelle var auswählen nur ausgewähltes
    while($ds = mysql_fetch_object($sql1))
        {
          $ausname= $ds->user;
        } */
        $ausname="kein Ausleiher ausgew&auml;hlt";
}
if ($ausname!=="kein Ausleiher ausgew&auml;hlt")
{
echo "<big><big>","ausgew&auml;hlter Entleiher: ", $ausname, "</big></big><br><br>";
}
else
{
?>
<textarea  cols="110" rows="1" class="text-a" readonly>kein Ausleiher ausgew&auml;hlt</textarea><br/>
<?php    
}
    $sql = mysql_query("SELECT * FROM ausleihe WHERE AuslName='$ausname' AND abgeschlAusleihe=0 ");           //Tabelle gereate auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $idg = $ds->IDGeraet;
        if ($idg > 0)
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
        $auslgr = $ds->auslgrund;
        $versch = $ds->verschiedenes;
        $datv = $ds->Datum_von;
        $datb = $ds->Datum_bis;
        $rjahr = $ds->jahr;
        $lfnr = $ds->lfnr;
        
        $datva= "" . date_mysql2german($datv) . " \n";
        $datba= "" . date_mysql2german($datb) . " \n";
        $jalfnr=$rjahr."/".$lfnr;
        $heuteng= "" . date_mysql2engl($heut) . " \n";
        echo "<tr>";                                                //anzeige
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
        echo "<td>",$auslgr,"</td>";
        if ($versch!="")
        {
            echo "<td>",$versch,"</td>";
        }
        else
        {
            echo "<td>"," - ","</td>";
        }
        echo "<td>",$datva,"</td>";
        echo "<td>",$datba,"</td>";
        echo "<td>",$jalfnr,"</td>";
        echo "<td><input type=\"checkbox\" name=\"z[]\" value=\"$pid\" /></td>"; // Rückgabe
        if ($versch=="")
        {
             if ($typ=="1" or $typ=="5")
             {
//                 echo "<td><input type=\"text\" name=\"infol[$id]\" value=\"$info\" readonly /></td>";
                 echo "<td><input name=\"anztg[$idg]\" type=\"text\" value=\"\"></td>";
             }
             else
             {
                 echo "<td>-</td>";
             }
        }
        else
        {
             echo "<td>-</td>";
        }
        echo "</tr>";
    }

?>
</table>
<font color="#0A0AF0"><big>Die R&uuml;ckgabe erfolgt mit "ausgew&auml;hlte Ger&auml;te zur&uuml;cknehmen"<br>
Mit "weiter" wird aus nicht zur&uuml;ckgegebenen Ger&auml;ten eine neue Ausleihe generiert ! </big><br></font>
<br>
<?php
if ($ausname=="kein Ausleiher ausgew&auml;hlt")
    {
    ?>
    <input type="submit" name="neu" value="ausgew&auml;hlte Ger&auml;te zur&uuml;cknehmen" disabled="disabled" />    <!-- zurücknehmen -->
    <input type="submit" name="neu" value="weiter" disabled="disabled" />    <!-- weiter -->
    <?php
    }
    else
    {
    ?>
    <input type="submit" name="neu" value="ausgew&auml;hlte Ger&auml;te zur&uuml;cknehmen" class="Button-w"/>    <!-- zurücknehmen -->
    <input type="submit" name="neu" value="weiter" class="Button-w"/>    <!-- weiter -->
    <?php
    }
//mysql_close($dz);
?>
<input type="submit" name="neu" value="zur&uuml;ck zur Auswahl"/>                <!-- zurück -->
</form>
<br>
<form method="POST" action="Rueckgabe1.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$anztg = anzahl tauchgänge in geräte
$auslgr = ausleihgrund in ausleihe
$ausln = ausleihname in ausleihe
$ausname = ausgewählter ausleihname
$d = variable in function
$datb = datum bis in ausleihe engl
$datba = $datb deu
$date = übergabevariable in function
$datv = datum von in ausleihe engl
$datva = $datv deu
$ds / $ds1 = datenzähler
$eintr = variable in datenbankabfrage
$heut = heute deu
$heuteng = heute engl
$idg 0 id gerät in ausleihe
$jalfnr = jahr+laufende nummer
$lfnr = laufende nummer
$pid = id in ausleihe
$query / $resultID = variable in datenbankabfrage
$regnr = regnummer in geräte
$rjahr = jahr in ausleihe
$sq / $sql = variable in datenbankabfrage
$typ = typ nummer in geräte
$typan = typ text in typ
$versch = verschiedenes in ausleihe
*/
?>
