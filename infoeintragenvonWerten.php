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
<title>Ausleihe</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<br>
die Daten werden eingetragen<br/>
<br />
<?php                                                               
function date_mysql2engl($date)                                     //in datenbankformat wandeln
{
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]);
}

echo "Benutzer :", $_SESSION['kname'], "<br>";                      //aktueller Benutzer
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut;
$heuteng= "" . date_mysql2engl($heut) . " \n";
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
if (isset ($_POST['zur']))
{
    ?><meta http-equiv="refresh" content="0; URL=info_eintragen.php" /><?php ;
}

$sql = mysql_query("SELECT * FROM var ");                           //Tabelle gereate auswählen nur ausgewähltes Gerät 1 Wert
while($ds= mysql_fetch_object($sql))
{
    $gida = $ds->ingid ;                                            // aus Datenbank var auslesen
    $manga=$ds->mang ;                                              // aus Datenbank var auslesen
    $sperra=$ds->user;
    if ($gida<> "0") $gid = $gida ;
    if ($manga<> " ") $mang = $manga ;
    if ($sperra<> "0") $sperr = $sperra;
}
//echo "<br>",$sperr,"+",$sperra,"<br>";
//echo "+",$gid,"+","<br>";                                                      //Service
//echo "+",$mang,"+","<br>" ;                                                      //Service
$user=$_SESSION['kname'];                                            //user aus session-user auslesen
//echo $user,"<br>" ;                                                      //Service
$sql = mysql_query("SELECT * FROM user ");                          //Tabelle gereate auswählen nur ausgewähltes Gerät 1 Wert
while($ds= mysql_fetch_object($sql))
{
    $usera = $ds->kname ;                                            // aus Datenbank var auslesen
    $uida=$ds->ID ;                                                 // aus Datenbank var auslesen
    if ($usera== $user) $uide = $uida ;
}
//echo $usere;                                                      //Service
$query = "DELETE FROM `var` WHERE 1 ";                              //Tabelle Werte löschen
$resultID = @mysql_query($query); 
if ($mang!=="")
{
    $query = "UPDATE `geraete` SET `Rep_notw` = '1', `ausgeliehen` = '$sperr' WHERE `geraete`.`ID` =$gid";  //Reparatur notwendig in Geräte auf 0 setzen
    $resultID = @mysql_query($query);
    $query = "INSERT INTO `info` (`ID_Geraet`, `Maengel`, `Rueckgeber`, `Datum`)VALUES ('$gid', '$mang','$uide', '$heuteng')";  // in Info eintragen
    mysql_query($query);
    $ew="-Text als Maengel eingetragen bei Geraetenr." ;
    $l=$mang.$ew.$gid ;
    logsch($l);
}
else
{
    ?>
    <br/><textarea  cols="110" rows="1" class="text-a" readonly>Bitte tragen Sie dei Fehlerursache ein</textarea><br/>
    <form method="POST" action="infoeintragenvonWerten.php">
    <input name="zur" type="submit" value="zur&uuml;ck zur Eingabe" class="Button-w"/>
    </form>
    <?php
}
?>
<meta http-equiv="refresh" content="5; URL=info.php">               <!--weiterleitung zur Übersichtsseite  -->
<form method="POST" action="infoeintragenvonWerten.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$d = variable in functionen
$date = übergabevariable in funktionen
$ds = datenzähler
$ew = text in logeintrag
$gid = gida ausser 0 in var
$gida = ingid in var
$heut = heute deu
$heuteng = heute in engl
$l = logeintrag
$mang = manga in var ausser 0
$manga = mang in var
$query / $resultID / $sql = variable in datenbankabfrage
$uida = id in user
$uide = ausgewählter user in user
$user = aktueller benutzer
$usera = user in user
*/
?>
