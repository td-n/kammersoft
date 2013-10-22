<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php include ("style.php") ?>
<?php
if ($_SESSION['lesen']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
}
function date_mysql2german($date) {                                 //Funktion ins deutsche Format
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
    }
function date_mysql2engl($date) {                                   //Funktion ins englische Format
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]);
    }
function date2timestamp($datum) {
    list($tag, $monat, $jahr) = explode(".", $datum);
    $jahr = sprintf("%04d", $jahr);
	$monat = sprintf("%02d", $monat);
    $tag = sprintf("%02d", $tag);
	return(mktime(0, 0, 0, $monat, $tag, $jahr));
}
?>
<title>reservieren</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Technik reservieren - 5. Schritt
</big></big></big>
<br>
<?php
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut , "<br>", "<br>";
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$kbed=1;
if (isset ($_POST['date4']) AND isset ($_POST['date5']))
{
    $rdat=$_POST['date5'];
    $rdatd=date_mysql2german($rdat);
    $adat=$_POST['date4'];
    $adatd=date_mysql2german($adat);
    $heute= "" . date_mysql2engl($heut) . " \n";
//    echo $adat,"+",$rdat,"+","<br>";
}
if ((isset($_POST['eintr'])))                     //Gerät eintragen
{
    $sql = mysql_query("SELECT * FROM var WHERE gida > 0");         //Tabelle var auswählen nur Gerät
    while($ds= mysql_fetch_object($sql))
    {
        $aga = $ds->gida ;                                          //
        $sql1 = mysql_query("SELECT * FROM res WHERE gid=$aga ");   //Tabelle res alle obigen geräte durchlaufen
        while($ds1= mysql_fetch_object($sql1))
            {
//                $gid=$ds->gid;
                $vone=$ds1->von;                                    //von bis auslesen
                $bise=$ds1->bis;
//                echo $gid;
//                echo $aga;
//                echo $vone;
//                echo $bise;
                $von= "" . date_mysql2german($vone) . " \n";
                $bis= "" . date_mysql2german($bise) . " \n";
//                echo $von,"<br>",$bis,"<br>",$adatd,"<br>",$rdatd,"<br>";
                if((date2timestamp($von)>date2timestamp($rdatd))OR (date2timestamp($adatd)>date2timestamp($bis)))    //vergleichen ob zeitraum bereits vergeben
                {
                	if ((isset($kbed)) AND ($kbed==0))
                	{
                        $kbed=0;
                    }
                    else
                    {
                        $kbed=1;
                    }
                }
                else
                {
                    $kbed=0;
                    ?><big><font color="#FF0000">Die ausgew&auml;hlten Termine &uuml;berschneiden sich und k&ouml;nnen nicht eingetragen werden. Bitte kontrollieren sie die Eingabe<br></font></big><?php ; 
                    ?><meta http-equiv="refresh" content="5; URL=reserv-3.php" /><?php
                }
            }
            
        $sql1 = mysql_query("SELECT * FROM ausleihe WHERE IDGeraet=$aga AND abgeschlAusleihe=0 ");   //Tabelle ausleihe alle obigen geräte durchlaufen
        while($ds2= mysql_fetch_object($sql1))
            {
                 $bisea= $ds2->Datum_bis ;
//                 echo $bisea;
                 $bisa= "" . date_mysql2german($bisea) . " \n";
                 if((date2timestamp($adatd)>date2timestamp($bisa))) //vergleichen ob zeitraum bereits ausgeliehen
                    {
                    	if ((isset($kbed)) AND ($kbed==0))
                    	{
                            $kbed=0;
                        }
                        else
                        {
                            $kbed=1;
                        }
                    }
                    else
                    {
                        $kbed=0;
                        ?><big><font color="#FF0000">Zu dem Anfangstermin ist die Technik noch ausgeliehen und konnte nicht eingetragen werden. Bitte kontrollieren sie die Eingabe<br></font></big><?php ;
                        ?><meta http-equiv="refresh" content="5; URL=reserv-3.php" /><?php
                    }
            }
    }                                                                //ende Geräteschleife
    if ($kbed==1)
    {
//        $rdat=trim($rdat," ");
//        $adat=trim($adat);
//        echo $datbed,"<br>";
//        echo $rdat,"<br>";
//        echo $adat,"<br>";
        $sql = mysql_query("SELECT * FROM var WHERE gida=-1");      //Tabelle var auswählen nur User
        while($ds= mysql_fetch_object($sql))
        {
            $usera = $ds->user ;                                    // aus Datenbank var auslesen
        }
        $sql = mysql_query("SELECT * FROM var WHERE gida=-2");      //Tabelle var auswählen nur User
        while($ds= mysql_fetch_object($sql))
        {
            $grund = $ds->user ;                                    // aus Datenbank var auslesen
        }
        $sql = mysql_query("SELECT * FROM var WHERE gida > 0");     //Tabelle var auswählen nur Gerät
        while($ds= mysql_fetch_object($sql))
        {
            $aga = $ds->gida ;                                          //
            ?><big><font color="#0000FF">Die Termine &uuml;berschneiden sich nicht mit anderen Reservierungen<br></font></big> <?php ;
            $query1 = "UPDATE `geraete` SET `res` = 1 WHERE `geraete`.`ID` = $aga";
            $resultID1 = @mysql_query($query1);                     //in
            $query = "INSERT INTO `res` (`name`,`von`,`bis`,`gid`,`grund`)VALUES ('$usera','$adat','$rdat','$aga','$grund')";
            mysql_query($query);                                    //ende daten ins res eintragen bei doppelten einträgen
        }
    }
}                                                                   //ende gesamtschleife 2.teil
if (isset($ausgabe))
{
echo $ausgabe,"<br>";
}
else
{
    if ($kbed==1)
    {
        ?><big> <font color="#0000FF">Die Reservierung wurde auf  </font><?php ;
        echo $usera ;
        ?> <font color="#0000FF"> f&uuml;r die Zeit vom: </font><?php ;
        echo $adatd ;
        ?> <font color="#0000FF"> bis: </font><?php ;
        echo $rdatd ;
        ?> <font color="#0000FF"> eingetragen.<br></big></font><?php ;
    }
}
?>
<form method="POST" action="reserv-4.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$adat = res von datum engl
$adatd = $adat deu
$aga = gida in var
$bis = res bis datum engl
$bisa = bis datum in ausleihe
$bise = $bis engl
$bisea = $bise in deu
$d = variable in functionen
$date / $datum = übergabevariable un functionen
$ds / $ds1 / $ds2 = datenzähler
$grund = gida = 2 ausleihgrund
$heut = heute deu
$heute = heute engl
$jahr / $monat / $tag = variable in functionen
$kbed = variable kontrollbedingeungen
$query / $query1 /$sql / $sql1 = variable datenbankabfrage
$rdat = res bis - rückgabe engl
$rdatd = $rdat deu
$usera = user in var
$von = res von deu
$vone = res von engl
*/
?>
