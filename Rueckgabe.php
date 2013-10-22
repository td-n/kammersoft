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
<title>R&uuml;ckgabe</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
R&uuml;ckgabe<br>
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
$l="Rueckgabe ge&ouml;ffnet";
logsch ($l);
$query = "TRUNCATE `var`";                                          //Tabelle var Werte löschen
$resultID = @mysql_query($query);
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}
?>
<form method = "POST" action="Rueckgabe1.php">
<table border="0" width="80%" align="center" > 
<colgroup>
    <col width="50%">
    <col width="50%">
  </colgroup>
<tr><th>Name des Entleihers</th><th>oder</th></tr>
 <tr><td align="center">  </td><td align="center">  </td></tr>
 <tr><td align="center"> 
<select name = "ausname" size="5">
<?php  
$sql = mysql_query("SELECT DISTINCT AuslName FROM ausleihe WHERE abgeschlAusleihe=0 ORDER by AuslName");           //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $name = $ds->AuslName ;                                         //verschiedene Namen aus ausleihe suchen
    echo "<option value = '$name'>" .$name. "</option>"  ;          //Dropdown mit ausgewählten schreiben
}             
//mysql_close($dz);
?></select>
<input name="submit" type="submit" value="&uuml;bernehmen" class="Button-w"/>       <!-- Abschicktaste -->
</form>
</td><td align="center">             
<a href="ausgel_mat.php" target="_blank">Liste ausgeliehene Ger&auml;te anzeigen, um den Namen des Ausleihers zu bestimmen (im neuen Fenster)</a>
</td></tr>
</table><br>
<br>
<form method="POST" action="Rueckgabe.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$ds = datenzähler
$heut = heute deu
$l = log eintrag
$name = ausleihname in ausleihe
$query / $resultID / $sql = variable in datenbankabfrage 
*/
?>
