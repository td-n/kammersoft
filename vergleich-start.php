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
if ($_SESSION['schreiben']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
}
?>
<title>Datenbest&auml;nde vergleichen</title>
</head>
<big><big><big>
ausgew&auml;hlte Datenbest&auml;nde vergleichen<br>
</big></big></big>
<?php
function date_mysql2german($date) {
    $d    =    explode("-",$date);                                  //in deutsches Format wandeln
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);                                  //in englisches Format wandeln
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
function copyFolder($source, $dest, &$statsCopyFolder,
    $recursive = true)
{
    if (!is_dir($dest))
    {
        mkdir($dest);
  }
    $handle = @opendir($source);
    if(!$handle)
        return false;
    while ($file = @readdir ($handle))
    {
        if (preg_match("/^\.{1,2}$/",$file))
        {
            continue;
        }
        if(!$recursive && $source != $source.$file."/")
        {
            if(is_dir($source.$file))
                continue;
        }
        if(is_dir($source.$file))
        {
            copyFolder($source.$file."/", $dest.$file."/",
                $statsCopyFolder, $recursive);
        }
        else
        {
            copy($source.$file, $dest.$file);
            $statsCopyFolder['files']++;
            $statsCopyFolder['bytes'] += filesize($source.$file);
        }
    }
    @closedir($handle);
}
function binary_multiples($size, $praefix=true, $short= true)
{
    if($praefix === true)
    {
        if($short === true)
        {
            $norm = array('B', 'kB', 'MB', 'GB', 'TB',
                          'PB', 'EB', 'ZB', 'YB');
        }
        else
        {
            $norm = array('Byte',
                                        'Kilobyte',
                                        'Megabyte',
                                        'Gigabyte',
                                        'Terabyte',
                                        'Petabyte',
                                        'Exabyte',
                                        'Zettabyte',
                                        'Yottabyte'
                                        );
        }

        $factor = 1000;
    }
    else
    {
        if($short === true)
        {
            $norm = array('B', 'KiB', 'MiB', 'GiB', 'TiB',
                          'PiB', 'EiB', 'ZiB', 'YiB');
        }
        else
        {
            $norm = array('Byte',
                                        'Kibibyte',
                                        'Mebibyte',
                                        'Gibibyte',
                                        'Tebibyte',
                                        'Pebibyte',
                                        'Exbibyte',
                                        'Zebibyte',
                                        'Yobibyte'
                                        );
        }
        $factor = 1024;
    }
    $count = count($norm) -1;
    $x = 0;
    while ($size >= $factor && $x < $count)
    {
        $size /= $factor;
        $x++;
    }
  $size = sprintf("%01.2f", $size) . ' ' . $norm[$x];
    return $size;
}

echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="Datenbestandsvergleich ge&ouml;ffnet / ".$_SESSION['kname'];
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
if (isset($_POST['vergl']))
{
    switch($_POST['vergl'])
    {                                                       //Gerät löschen
        case"Gerätelisten anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=Typlisten-v.php" /><?php
        break;
        case"Mängelinfo anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=Info-v.php" /><?php
        break;
        case"Mängelhistorie anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=info-loeschen-v.php" /><?php
        break;
        case"Ausleihhistorie anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=ausgel_hist-v.php" /><?php
        break;
        case"Reservierung anzeigen":
            ?><meta http-equiv="refresh" content="0; URL=reserva-v.php" /><?php
        break;
        default:
    }
}
    
if (isset($_POST['ausw']))
{
    $datume= $_POST['ausw'];
?><font color="#0000FF"><big><br> Sie haben das Datum <?php echo $datume ?> ausgew&auml;hlt </big></font><?php 

$eintr = "INSERT INTO `var` (`user`)VALUES ('$datume')";      //$gida in var eintragen
mysql_query($eintr);


}
$sql = mysql_query("SELECT * FROM `conf` WHERE wert = 'sicherungsverzeichnis' ");         //Tabelle conf auswählen
while($ds= mysql_fetch_object($sql))
{
    $lw = $ds->was;
}

$ipf= realpath ('start.php');                                       //Programmpfad ermitteln und eintragen
$ipf = str_replace("\\","/", $ipf);
$ipf = str_replace("/htdocs/Tauchkammer/start.php","", $ipf);
$query = "UPDATE `conf` SET  `was` = '$ipf' WHERE `wert` = 'instverz' ";
$resultID1 = @mysql_query($query);                 //Daten updaten
$ipf=$ipf."/mysql/data/taucherkammera/";
//echo $ipf;

if (isset($_POST['ausw']))
{
//echo "<br>","+",$lw,"+",$datume,"+","<br>";
    $source= $lw.$datume."/";
    $dest = $ipf;
//    echo "<br>",$source,"<br>",$dest,"<br>";
    $statsCopyFolder['bytes'] = 0;
    $statsCopyFolder['files'] = 0;
    copyFolder($source, $dest, $statsCopyFolder, true);
    $statsCopyFolder['bytes'] = binary_multiples($statsCopyFolder['bytes'],
       true, false);
    echo "<br>",$statsCopyFolder['files'] . ' Dateien kopiert ('.$statsCopyFolder['bytes'].').';
    echo "<br>","Daten vorbereitet";
}
?>
<form method = "POST" action="vergleich-start.php">
<select name = "ausw" size="1">
<?php
$lwa=str_replace("\/","/",$lw);
$dir = opendir ($lwa);
if ($dir==false)
{
    $ausw=1; 
}
else
{
    $ausw=0;
}
while ($ordner = readdir($dir)) {
     if ($ordner != "." && $ordner != "..") 
        {

        $sql1 = mysql_query("SELECT * FROM var  ");            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
        while($ds1 = mysql_fetch_object($sql1))
            {
                $wahl = $ds1->user ;                                        //aus Datenbank var auslesen
            }
          echo $wahl,"+";
          if ($ordner==$wahl)
            {
                echo "<option value = '$ordner' selected />" .$ordner. "</option>"  ;
            }
            else
            {
                echo "<option value = '$ordner'/>" .$ordner. "</option>"  ;
            }
     }
}
closedir($dir);
?>
</select>
<input name="submit" type="submit" value="&uuml;bernehmen" />       <!-- Abschicktaste -->
</form>
<?php 
//echo "+",$ordner,"+",$wahl,"+";
if ($ausw=="1")
{
    echo $ausw;
    ?>
    <meta http-equiv="refresh" content="0; URL=lwa.php"/>
    <?php
}
if (isset($_POST['ausw']))
{
    ?>
    <br>
    <form name="vergl" action="vergleich-start.php" method="post">
    <input style="height:30;width:160;background-color:#FFFF00;color:#000000" type="submit" name="vergl" value="Ger&auml;telisten anzeigen"/>
    <input style="height:30;width:160;background-color:#FFFF00;color:#000000" type="submit" name="vergl" value="M&auml;ngelinfo anzeigen"/>
    <input style="height:30;width:160;background-color:#FFFF00;color:#000000" type="submit" name="vergl" value="M&auml;ngelhistorie anzeigen"/>
    <input style="height:30;width:160;background-color:#FFFF00;color:#000000" type="submit" name="vergl" value="Ausleihhistorie anzeigen"/>
    <input style="height:30;width:160;background-color:#FFFF00;color:#000000" type="submit" name="vergl" value="Reservierung anzeigen"/>
    </form>
    <?php    
}
else
{
    ?><font color="#0000FF"><big>w&auml;hlen Sie zuerst ein Datum aus</big></font><br><?php 
} 
?>
<form method="POST" action="vergleich-start.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
