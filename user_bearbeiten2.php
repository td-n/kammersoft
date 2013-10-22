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
<title>User l&ouml;schen</title>
</head>
<big><big><big>
Ausleiher l&ouml;schen  <br>
</big></big></big>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<br>
<?php
$l="user_bearbeiten2 ge&ouml;ffnet";
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
if (isset ($_POST['zur']))
{
    ?><meta http-equiv="refresh" content="0; URL=user_bearbeiten.php" /><?php ;
}

if (isset($_POST['lo']))
{
    
    foreach ($_POST['lo'] as $key => $val) 
    {
        $sql = mysql_query("SELECT * FROM benutzer ");              //Tabelle benutzer auswählen nur ausgewähltes Gerät 1 Wert
        while($ds= mysql_fetch_object($sql))
        {
            $usera = $ds->Name ;                                    // aus Datenbank var auslesen
            $uida = $ds->id ;                                       // aus Datenbank var auslesen
            if ($uida = $val) 
            {
                $usere = $usera ;
            } 
        }
        $query = "DELETE FROM `benutzer` WHERE id=$val ";           //Tabelle Werte löschen
        $resultID = @mysql_query($query);  
        $ew=" in benutzer geloescht" ;
        $l=$usere.$ew ;
        logsch($l);
        //echo $l;                                                  //Service
    }
?>
<meta http-equiv="refresh" content="0; URL=user_bearbeiten.php">   
<?php 
}
else 
{
    ?> 
    <textarea  cols="110" rows="1" class="text-a" readonly >Sie haben keine Person ausgew&auml;hlt</textarea><br/>
    <?php ;
};
 ?>
<form method="POST" action="user_bearbeiten2.php">
<input name="zur" type="submit" value="zur&uuml;ck zur Auswahl" class="Button-w"/><br/>
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html
<?php  
/*
$ds = datenzähler
$ew = log text
$key = variable in array-abarbeitung
$l = log eintrag
$query / $resultID / $sql = variable in datenbankabfrage
$uida = id in benutzer
$usera = name in benutzer
$usere = $usera
$val =  variable in array-abarbeitung 
*/
?>
