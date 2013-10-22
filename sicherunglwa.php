<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php
function lwausw ()
{
?>
<form method = "POST" action="sicherunglwa.php">
       <p>
        <select name="lw" size="1">
<?php
$s=100;                                                             //ab LW e:\
do
{
    $a=  chr($s) ;
    $aa=$a.":/";
    $ab=$a.":/";
    if (file_exists($aa))
    {
        echo  "<option value='$aa' >" .$ab. "</option>" ;
    }
    $s=$s+1;
}
while($s<=122)
?>
</select>
</p>
<input name="submit" type="submit" value="&uuml;bernehmen" />
</form> <br><br>
<?php
}
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
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
if (isset($_POST['lw']))
{
    $filename = $_POST['lw'];
//    echo $filename;
    $query1 = "UPDATE `conf` SET `was` = '$filename'  WHERE `wert` = 'Sichlw'	";
    $resultID1 = @mysql_query($query1);                             //Daten updaten
}
$sql = mysql_query("SELECT * FROM `conf` WHERE wert = 'Sichlw' ");         //Tabelle conf auswählen
while($ds= mysql_fetch_object($sql))
{
    $filename = $ds->was;
}
//echo $filename,"<br>";
if (file_exists($filename)) 
{
    echo "Das Laufwerk $filename existiert","<br>";
    $filenamelw=$filename."sicherungtk/";
//echo $filenamelw,"<br>";
    if (file_exists($filenamelw))
    {
        echo "Das Verzeichnis $filenamelw existiert","<br>";
        $query1 = "UPDATE `conf` SET `was` = '$filenamelw'  WHERE `wert` = 'aktiveseite'	";
        $resultID1 = @mysql_query($query1);                 //Daten updaten
        $query1 = "UPDATE `conf` SET `was` = '$filenamelw'  WHERE `wert` = 'sicherungsverzeichnis'	";
        $resultID1 = @mysql_query($query1);                             //Daten updaten
        $heut = date("Y-m-d");  //heutuger Datum
        $uv = $filenamelw.$heut."/" ;
//echo $uv,"<br>";
        if (!file_exists($uv))
        {
            if ( mkdir ( $uv ) )
            {
              echo 'Verzeichnis erstellt!';
            }
        }
        $buv=$uv."backup/";
//echo $buv,"<br>";
        if (!file_exists($buv))
        {
            if ( mkdir ( $buv ) )
            {
              echo 'Verzeichnis erstellt!';
            }
        }

        $sql = mysql_query("SELECT * FROM conf WHERE wert= 'instverz' ");         //Tabelle user auswählen
        while($ds= mysql_fetch_object($sql))
        {
            $instverz=$ds->was;
        }
        $source=$instverz."/htdocs/msd1.24.4/work/backup/";          //manuelle Sicherung sichern
//        $source = 'C:/xampp/htdocs/msd1.24.4/work/backup/';         //manuelle Sicherung sichern
        $dest = $buv;
        $statsCopyFolder['bytes'] = 0;
        $statsCopyFolder['files'] = 0;
        
        copyFolder($source, $dest, $statsCopyFolder, true);
        
        $statsCopyFolder['bytes'] = binary_multiples($statsCopyFolder['bytes'], 
           true, false);
        echo $statsCopyFolder['files'] . ' Dateien kopiert ('.$statsCopyFolder['bytes'].').'; 
        echo "<br>";

        $sql = mysql_query("SELECT * FROM conf WHERE wert= 'instverz' ");         //Tabelle user auswählen
        while($ds= mysql_fetch_object($sql))
        {
            $instverz=$ds->was;
        }
        $source=$instverz."/mysql/data/Taucherkammer/";          //manuelle Sicherung sichern
                  
        $source = 'C:/xampp/mysql/data/Taucherkammer/';             //automatische Sicherung sichern
        $dest = $uv;
        $statsCopyFolder['bytes'] = 0;
        $statsCopyFolder['files'] = 0;

        copyFolder($source, $dest, $statsCopyFolder, true);

        $statsCopyFolder['bytes'] = binary_multiples($statsCopyFolder['bytes'],
           true, false);
        echo $statsCopyFolder['files'] . ' Dateien kopiert ('.$statsCopyFolder['bytes'].').';
        echo "<br>","gesichert";
        ?>
        <meta http-equiv="refresh" content="5; URL=ende-all.php" />
        <?php
    }
    else
    {
        echo "<h1>Das Sicherungsverzeichnis konnte nicht gefunden werden! <br>W&auml;hlen Sie das Laufwerk aus! </h1>","<br>";
        lwausw () ;
        ?>
        
        <h1>oder w&auml;hlen sie Verzeichnis anlegen </h1>
        <form method = "POST" action="sicherunglwa.php">
        <input name="submit1" type="submit" value="Verzeichnis anlegen" />
        </form>
        <?php
        if (isset($_POST['submit1']))
        {
            echo $filenamelw ;
            if ( mkdir ( $filenamelw ) )
            {
              echo 'Verzeichnis erstellt!';
            }
        ?><meta http-equiv="refresh" content="0; URL=sicherunglwa.php" /><?php
        }
    }
} 
else 
{
    echo "<h1>Das Sicherungslaufwerk konnte nicht gefunden werden!<br>bitte schlie&szlig;en sie das Sicherungsmedium an oder<br> w&auml;hlen Sie das Laufwerk aus! </h1>";

lwausw () ;
}
?>
</body>
</html>
<?php  
/*
$a = laufwerksbuchstaben
$aa = laufwerksbuchstaben:\
$ab = $aa
$buv = backupverzeichnis
$ds = datenzähler
$filename = ausgewähltes laufwerk
$filenamelw = ausgewähltes lw + sicherungtk/
$heut = heute deu
$query1 / $resultID1 = variable in datenbankanfrage
$s = buchstabennummer
$sql = variable datenbankabfrage
$uv = verzeichnis mit heutigen datum
  
*/
?>
