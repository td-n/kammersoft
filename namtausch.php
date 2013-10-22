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
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
?>
<title>Ausleihe bearbeiten</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Ausleihnummer mit neuen Namen versehen
</big></big></big><br>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut,"<br>";
$l="namtausch ge&ouml;ffnet";
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

if (isset($_POST['jahr']))                                          //existiert ausgewähltes jahr
{
    $ausjahr = $_POST['jahr'];                                      //ausgewähltes jahr
//    echo $ausjahr;
    $query = "TRUNCATE `var`"; //Tabelle var Werte löschen
    $resultID = @mysql_query($query);
    $eintr = "INSERT INTO `var` (`rdat`, `gida`) VALUES ('$ausjahr', -7)";  //jahr in var eintragen -7
    mysql_query($eintr)  ;
 }
if (isset($_POST['ausnummer']))                                       //existiert ausgewählte nummer
   {
    $ausnummer = $_POST['ausnummer'];
//    echo $ausjahr;
    $eintr = "INSERT INTO `var` (`gernr`, `gida`) VALUES ('$ausnummer', -8)";  //nr in var eintragen -8
    mysql_query($eintr)  ;
    ?>
    <meta http-equiv="refresh" content="0; URL=namtausch1.php" />
    <?php
    }    
?>
<br><br>
W&auml;hlen Sie das Jahr der Ausleihe!
<form method = "POST" action="namtausch.php">
  <p>
    <select name="jahr" size="1">
<?php  
$sql = mysql_query("SELECT DISTINCT jahr FROM ausleihe WHERE abgeschlAusleihe=0 ");   //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $jahr = $ds->jahr ;
    if ($jahr==$_POST['jahr'])
    {
         echo "<option value = '$jahr' selected >" .$jahr. "</option>"  ;   //Dropdown mit ausgewählten schreiben
    }
    else
    {
         echo "<option value = '$jahr'>" .$jahr. "</option>"  ;     //Dropdown schreiben
    }
}
?>
    </select>
  </p>
<input name="submit" type="submit" value="Jahr &uuml;bernehmen" class="Button-w"/>  
</form>
<?php  
if (isset($_POST['jahr']))                                          //jahr ausgewählt
{
    echo "Sie haben das Jahr: ",$_POST['jahr']," gew&auml;hlt","<br>" ;
    echo "<font color=#FF0000>Achtung !!! im n&auml;chsten Schritt werden ohne ihr Zutun die ausgew&auml;hlten Ger&auml;te zur&uuml;ckgegeben und sp&auml;ter neu ausgeliehen</font>","<br>";
    echo "<font color=#FF0000>wenn sie vor dem Abschluss -wo ihre Ausleihnummer angezeigt wird- abbrechen, m&uuml;ssen Sie alle Ger&auml;te neu ausleihen!</font>","<br>";
}
?>
<form method = "POST" action="namtausch.php">
<br>bitte Nummer zum ausgew&auml;hlten Jahr ausw&auml;hlen!<br> <p>
<select name = "ausnummer" size="1">
<?php
$sql = mysql_query("SELECT DISTINCT lfnr FROM ausleihe WHERE jahr=$ausjahr AND abgeschlAusleihe=0 ORDER BY lfnr");           //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $nr = $ds->lfnr ;
    echo $nr;
    echo "<option value = '$nr'>" .$nr. "</option>"  ;              //Dropdown mit ausgewählten schreiben
}
?></select> </p>
<input name="submit" type="submit" value="Nummer &uuml;bernehmen" class="Button-w"/>       <!-- Abschicktaste -->
</form>
<form method="POST" action="namtausch.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html
<?php  
/*
$ausjahr = ausgewähltes ausleihjahr
$ausnummer = ausgewählte ausleihnummer
$d = variable in function
$date = übergabevariable in function
$ds = datenzähler in datenbankabfrage
$eintr = variable in datenbankabfrage
$heut = heute deu
$heuteng = heute engl
$jahr = jahr in ausleihe
$l = logeintrag
$nr = nummr in ausleihe ausleihnummer
$query / $resultID / $sql = variable in datenbankabfrage  
*/
?>
