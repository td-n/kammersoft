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
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte anzeigen
{
  echo $key." => ".$value."<br />\n";
}
*/
?>
<title>Update</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Update
</big></big></big>
<br>
<form name="ausw" action="update.php" method="post" >
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
echo $heut;
$l="Update ge&ouml;ffnet";
logsch ($l);

if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}
if (isset ($_POST['zu1']))
{
    ?><meta http-equiv="refresh" content="0; URL=update1.php" /><?php ;
}

$sql = mysql_query("SELECT * FROM conf WHERE wert= 'instverz' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $instverz=$ds->was;
}
$instverzu=$instverz."/htdocs/Tauchkammer";
$instverzu = str_replace("/","\\", $instverzu);
//echo "<br>",$instverzu,"<br>",$instverz;
?>
<br><br><big><big>Kopieren Sie die Update-Datei in folgenden Pfad:"
<?php echo $instverzu; ?>
", danach klicken Sie auf "Update ausf&uuml;hren"</big></big>
</form>
<form method="POST" action="update.php">
<input name="zu1" type="submit" value="Update ausf&uuml;hren" class="Button-w"/><br/><br/>
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
