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
<title>Laufwerkauswahl</title>
</head>
<big><big><big>
Sicherungslaufwerksauswahl<br>
</big></big></big>
<?php
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
    
if (isset($_POST['lw']))
{
    $lw=$_POST['lw'];
    $lwverz=$lw."sicherungtk/";
    if (file_exists($lwverz))
    {
        $query1 = "UPDATE `conf` SET `was` = '$lw'  WHERE `wert` = 'Sichlw'	";
        $resultID1 = @mysql_query($query1);                         //Daten updaten
        $query1 = "UPDATE `conf` SET `was` = '$lwverz'  WHERE `wert` = 'aktiveseite'	";
        $resultID1 = @mysql_query($query1);                         //Daten updaten
        $query1 = "UPDATE `conf` SET `was` = '$lwverz'  WHERE `wert` = 'sicherungsverzeichnis'	";
        $resultID1 = @mysql_query($query1);                         //Daten updaten
        ?><meta http-equiv="refresh" content="0; URL=vergleich-Start.php" /><?php 
    }
    else
    {
        ?>
        <font color="#0000FF"><big>Das Sicherungsverzeichnis wurde nicht gefunden. Bitte w&auml;hlen Sie ein anderes Laufwerk<br></big></font>
        <meta http-equiv="refresh" content="5; URL=lwa.php" />
        <?php 
    }
}
else
{
    ?>
    <form method = "POST" action="lwa.php">
           <p>
            <select name="lw" size="1">
    <?php
    $s=100;                                                         //ab LW e:\
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
?>
<form method="POST" action="lwa.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
