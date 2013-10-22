<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php
if ($_SESSION['lesen']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten-all.php" /><?php
}
if ($_SESSION['schreiben']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
}
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/    
if (isset($_POST['verz']))                                          //werte von eingabe übernehmen
{
    $path=$_POST['verz'];
    if ($path=="")                                                  //wenn leer ist
    {
        ?>
        <font color="#FF0000"><big>Bitte geben sie den Pfad ein </big><br></font>
        <?php
    }
    else                                                            //sonst
    {
        $path = str_replace("\\","/", $path);
        $query1 = "UPDATE `conf` SET `was` = '$path'  WHERE `wert` = 'desktopverzeichnis'	";
        $resultID1 = @mysql_query($query1);                         //Daten updaten
        ?><meta http-equiv="refresh" content="0; URL=Archiv.php" /><?php   
        echo $path;   
    }
}
?>
    <form action="deskverz.php" method = "POST" >
    <p><font color="#0A0AF0"><big>Die Dateien sollen auf Ihren Desktop im Verzeichnis "Archiv" abgelegt werden</big><br></font></p>
    <p><font color="#0A0AF0"><big>Bitte geben Sie den Pfad zu ihren Desktop oder einen von ihnen gew&auml;hlten Verzeichnis mit abschlie&szlig;enden "\" ein </big><br></font></p>
    <input type="text" name="verz" size="70" maxlength="70"><br><br> 
    <input type="submit" name="submit" value="eintragen">
<?php
?><br><br>
<a href="start1.php" >zur &Uuml;bersicht </a>
</body>
</html>
<?php  
/*
$path = Eingabewerte desktopverzeichnis
$query1 / $resultID1 variable in datenbankabrage
*/
?>