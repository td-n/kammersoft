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
?>
<title>Ausleihe komplett</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Komplette Ausleihe anzeigen
</big></big></big>
<table border="1" width="40%" align="left">
<br>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut , "<br>", "<br>";
$l="Ausleihe4 ge&ouml;ffnet";
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

$sql = mysql_query("SELECT * FROM var WHERE gida=-5 ");             //Tabelle var Ausleihnummer
while($ds= mysql_fetch_object($sql))
{
    $anr = $ds->gernr;
}
?><big><font color="#0000FF">Ausleihnummer: </font><?php
echo $anr;
?></big><br><?php
$sql1 = mysql_query("SELECT * FROM var WHERE gida > 0 ");           //Tabelle var nur Geräte 
while($ds1 = mysql_fetch_object($sql1))
    {
        $gida = $ds1->gida ;                                        //aus Datenbank var auslesen
            $sql = mysql_query("SELECT * FROM geraete WHERE ID=$gida");  //Tabelle gereate auswählen nur ausgewähltes Gerät
            while($ds= mysql_fetch_object($sql))
            {
                $typa = $ds->Typ ;                                  // aus Datenbank auslesen
                $regnra=$ds ->RegNR ;                               // aus Datenbank auslesen
                $herstellera= $ds->Hersteller ;                     // aus Datenbank auslesen
                $query    = "SELECT Typ FROM typ WHERE ID=$typa";   //aus Typ-ID Typ machen
                $resultID = @mysql_query($query);                   //aus Typ-ID Typ machen
                $typanz = mysql_result($resultID,0);                //aus Typ-ID Typ machen
                echo "<tr></tr><tr>" ;
                echo "<td>";
                echo "<big>",$typanz,"</big><br>";                  //Typ anzeigen
                echo "<td>";
                echo "<big>",$regnra,"</big><br>";                  //Regnummer anzeigen
                echo "<td>";
                echo "<big>",$herstellera,"</big><br>";             //Hersteller anzeigen
                echo "</tr>";
            }
    }
$sql1 = mysql_query("SELECT * FROM var WHERE gida = -4 ");          //Tabelle var Rückgabedatum
while($ds = mysql_fetch_object($sql1))
    {
      $rdat= $ds->user;
      $rdatd=date_mysql2german($rdat);
    }
$sql1 = mysql_query("SELECT * FROM var WHERE gida = -1 ");          //Tabelle var Name
while($ds = mysql_fetch_object($sql1))
    {
      $ausl= $ds->user;
    }
?><font color="#0000FF"><big>Entleiher: </font><?php
echo $ausl;
?><br><font color="#0000FF">R&uuml;ckgabedatum ist: </font><?php
echo $rdatd;
?><br><big><font color="#0000FF">ausgeliehen:  </font></big><br><?php
$sql1 = mysql_query("SELECT * FROM var WHERE gida = 0 ");          //Tabelle var Name
while($ds = mysql_fetch_object($sql1))
    {
      $versch= $ds->mang;
      echo "<big>", $versch,"</big><br>";
    }
/*
$query = "TRUNCATE `var`";                                          //Tabelle var Werte löschen
$resultID = @mysql_query($query);
*/
//mysql_close($ds);
//mysql_close($ds1);
 ?>
</table><br><br>
<br><br><big>
<form method="POST" action="start1.php">
<input name="zu" type="submit" value="der Entleiher best&auml;tigt diese Ausleihe mit seiner Unterschrift" class="Button-w"/>
</form>
</big>
</body>
</html>
<?php  
/*
$anr = gernr in var
$ausl = user in var
$d = variable in function
$date = übergabevariable in function
$ds / $ds1 = datenzähler in datenbankabfrage
$gida = gida in var
$herstellera = hersteller in geräte
$heut = heute in deu
$query = variable in datenbankabfrage
$rdat = user in var engl
$rdatd = user in var deu
$regnra = registriernummer in geräte
$resultID / $sql / $sql1 = variable in datenbankabfrage
$typa = typnummer in geräte
$typanz = typtext in typ
$versch = mang in var
*/
?>
