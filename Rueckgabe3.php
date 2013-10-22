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
R&uuml;ckgabe 3. Schritt - nicht Zur&uuml;ckgegebenes speichern<br>
</big></big></big>
<?php
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //aktueller Benutzer
$user =  $_SESSION['user'];
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut,"<br>","<br>";
$heuteng= "" . date_mysql2engl($heut) . " \n";
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}
if (isset ($_POST['date3']))
{
    $rdate=$_POST['date3'];
    $rdatd= "" . date_mysql2german($rdate) . " \n";
    $heute= "" . date_mysql2engl($heut) . " \n";
}
    $sql1 = mysql_query("SELECT * FROM var ");                      //Tabelle var auswählen nur ausgewähltes
    while($ds = mysql_fetch_object($sql1))
        {
          $ausname= $ds->user;
        }
//echo $ausname,":Ausleihname";
//echo $rdatd,":deutsch";
//echo $rdate,":englisch";
//echo $heute,":heute englisch";
?><font color="#0000FF"><big><big>F&uuml;r die Ausleihnummer(n): </font><br> <?php
$sql = mysql_query("SELECT * FROM ausleihe WHERE `ausleihe`.`AuslName`='$ausname' AND `ausleihe`.`abgeschlAusleihe`=0 "); //Tabelle benutzer auswählen nur ausgewähltes Gerät 1 Wert
while($ds= mysql_fetch_object($sql))
    {
          $aid=$ds->ID;
          $j= $ds->jahr;
          $n= $ds->lfnr;
          $nr=$j."/".$n;
          echo $nr,"<br>";
           
//            echo $aid;
            $query = "UPDATE `ausleihe` SET `Datum_bis` = '$rdate' WHERE `ausleihe`.`ID` = '$aid' ";  //Rückgabedatum in ausleihe setzen
            $resultID = @mysql_query($query);
    }
if (isset($ausgabe))
{
echo $ausgabe;
}
else
{
    ?><font color="#0000FF">wurde das geplante R&uuml;ckgabedatum f&uuml;r  </font><?php 
    echo $ausname;
    ?><font color="#0000FF"> auf folgenden Termin gesetzt: </font><?php
    echo $rdatd;
    ?></big></big><?php 
    
}
//mysql_close($dz);
?>
<br>
<form method="POST" action="Rueckgabe2.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$aid = id ausleihe
$ausname = ausleihname in var
$d = variable in function
$date = übergabevariable in function
$ds = datenzähler
$heut = heute deu
$heute = heute engl
$j = jahr in ausleihe
$n = laufende nummer in ausleihe
$nr = jahr-laufende nummer in ausleihe
$query / $resultID / $sql / $sql1 = variable in datenbankabfrage
$rdatd = rückgabedatum deu
$rdate = rückgabedatum engl
$user = angemeldeter user
*/
?>
