<html>
<head>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<?php include ("#mysql.inc.php"); ?>
<?php include ("style.php") ?>
<?php  
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
//        echo $verein,"</font>";
        $vereinc=md5($verein);
        if ($vereinc!==$code)
        {
            $i=1;
        }
        if ($i==1)
        {
            ?><big><font color="#FF0000"><br> Sie arbeiten mit einer illegalen Version des Programms!<br>
            Bitte lassen Sie sich Registrieren</font></big><br>
            </font><meta http-equiv="refresh" content="1; URL=login.php" /><?php
        }
    }
}
?>
<div style="position: absolute ;top:40%; left :40% ; float: left; height: 110px; width: 380px ; border: black 1px solid ; background-color: #FFFFFF;">
<form method="post" form="" action="passcheck.php">
<br>
<table border="0" width="300" align="center" cellpadding="0" cellspacing="0">
<tr  >
<td align="left" valign="top">User:</td>
<td align="left" valign="top"><input name="user"/></td>
</tr>
<tr>
<td align="left" valign="top">Passwort:</td>
<td align="left" valign="top"><input name="pass" type="password">
</td>
</tr>
</table>
<br>
<table border="0" width="300" align="center" cellpadding="0" cellspacing="0">
<tr>
<td align="center" valign="top">
<input value="login" type="submit">
</td>
</tr>
</table>
</form>
</div>
</body>
</html>
