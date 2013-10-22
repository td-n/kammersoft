<html>
	<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("style.php") ?>
<title>index</title>
	</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<br /><br /><br />
<form name="verein" action="index.php" method="post" >
<big><big><big><big>Herzlich willkommen in
der Technikverwaltung vom :<br>
<?php  
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
/*
$k=mysql_query("SELECT * FROM conf WHERE wert='verein' ");
if (!mysql_num_rows($k)==1)
{
$query = "INSERT INTO `conf` (`wert`)VALUES ('verein')";
mysql_query($query);
}
if (isset($_POST['submit']))
{
    if ($_POST['verein']!=="")
    {
        $vereine=$_POST['verein'];
        $query1 = "UPDATE `conf` SET `was` = '$vereine'  WHERE `wert` = 'verein'";
        $resultID1 = @mysql_query($query1);                             //Daten updaten

        $k=mysql_query("SELECT * FROM conf WHERE wert='code' ");
        if (!mysql_num_rows($k)==1)
        {
        $query = "INSERT INTO `conf` (`wert`)VALUES ('code')";
        mysql_query($query);
        }
        $vereinp=md5($vereine);
        $query1 = "UPDATE `conf` SET  `was` = '$vereinp' WHERE `wert` = 'code'";
        $resultID1 = @mysql_query($query1);
    }
    else
    {
        ?>
            <font color="#FF0000"> bitte geben Sie den Namen ihres Vereins ein!</font>        
        <?php 
    }
}        */
$i=0;
$sql = mysql_query("SELECT * FROM `conf` WHERE `wert` = 'verein'");         //Tabelle conf auswählen
while($ds= mysql_fetch_object($sql))
{
    $verein = $ds->was;
}
$sql = mysql_query("SELECT * FROM `conf` WHERE `wert` = 'code'");         //Tabelle conf auswählen
while($ds= mysql_fetch_object($sql))
{
    $code = $ds->was;
}

//echo "+",$verein,"+";
if(isset($verein))
{
    if (!$verein=="")
    {
        ?>
        <font color="#0000FF">
        <?php  
        echo $verein,"</font>";
        $vereinc=md5($verein);
        if ($vereinc!==$code)
        {
            $i=1;
        }
        if ($i==1)
        {
            ?><big><font color="#FF0000"><br> Sie arbeiten mit einer illegalen Version des Programms!<br>
            Bitte lassen Sie sich Registrieren</font></big><br>
            </font><meta http-equiv="refresh" content="10; URL=index.php" /><?php
        }
        else
        {
            ?><br>
            <meta http-equiv="refresh" content="5; URL=login.php" /><?php
        }    
    }
/*    else
    {
        if ($code!=="d41d8cd98f00b204e9800998ecf8427e")
        {
                $i=1;
        }
        if ($i==1)
        {
            ?><big><font color="#FF0000"> Sie arbeiten mit einer illegalen Version des Programms!<br>
            Bitte lassen Sie sich Registrieren</font></big>
            </font><meta http-equiv="refresh" content="10; URL=index.php" /><?php
        }
        else
        {
            ?></big><p>
            Vereinsname:<input type="text" name="verein"><font color="#0000FF"> bitte geben Sie den Namen ihres Vereins ein!</font><br>                 <!-- neuen Namen eingeben -->
            <input name="submit" type="submit" value="&uuml;bernehmen" />
            </p>
            <?php
        }
    }    
}
else
{
    if ($code!=="d41d8cd98f00b204e9800998ecf8427e")
    {
            $i=1;
    }
    if ($i==1)
    {
        ?><big><font color="#FF0000"> Sie arbeiten mit einer illegalen Version des Programms!<br>
        Bitte lassen Sie sich Registrieren</font></big>
        </font><meta http-equiv="refresh" content="10; URL=index.php" /><?php
    }
    else
    {
        ?></big><p>
        Vereinsname:<input type="text" name="verein"><font color="#0000FF"> bitte geben Sie den Namen ihres Vereins ein!</font><br>                 <!-- neuen Namen eingeben -->
        <input name="submit" type="submit" value="&uuml;bernehmen" />
        </p>
        <?php
    }     */
}
?>
Version 1.4<br>
</form>
</big></big></big></big><br />
by J.M&ouml;bius
</body>
</html>
