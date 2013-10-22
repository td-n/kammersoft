<html>
<head>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php include ("style.php") ?>
<?php
if ($_SESSION['lesen']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
}
function date_mysql2german($date) {
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
function anz ()
{
?>
<form method = "POST" >
<table border="1" width="80%">
<tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Auswahl</th> </tr>
<?php
$sql1 = mysql_query("SELECT * FROM var WHERE gida > 0");            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1 = mysql_fetch_object($sql1))
    {
        $gida = $ds1->gida ;                                        //aus Datenbank var auslesen
            $sql = mysql_query("SELECT * FROM geraete WHERE ID=$gida");  //Tabelle gereate auswählen nur ausgewähltes Gerät
            while($ds= mysql_fetch_object($sql))
            {
                $typa = $ds->Typ ;                                  // aus Datenbank auslesen
                $regnra=$ds ->RegNR ;                   
                $herstellera= $ds->Hersteller ;         
                $query    = "SELECT Typ FROM typ WHERE ID=$typa";   //aus Typ-ID Typ machen
                $resultID = @mysql_query($query);                   //aus Typ-ID Typ machen
                $typanz = mysql_result($resultID,0);    
                echo "<tr>" ;
                echo "<td>";
                echo $typanz,"<br>";
                echo "<td>";
                echo $regnra,"<br>";
                echo "<td>";
                echo $herstellera,"<br>";
                echo "<td><input type=\"radio\" name=\"lo[]\" value= \" $gida \" />"; // löschhäckche
                echo "</tr>";
            }
    }
?>
</table>
<?php
$sql="SELECT COUNT(*) AS Anzahl FROM var WHERE gida > 0";           //bei leer ausblenden
$result = mysql_query($sql);
$zeile = @mysql_fetch_array($result);
$anzahl = $zeile['Anzahl'];
if ($anzahl == 0)
    {
    ?>
    <br>
    <input name="submit" type="submit" value="weiter" disabled="disabled" />    <!-- Abschicktaste -->
    <input name="submit" type="submit" value="ausgew&auml;hlte l&ouml;schen" disabled="disabled" />    <!-- Abschicktaste -->
    <?php
    }
    else
    {
    ?>
    <br>
    <input name="submit" type="submit" value="weiter" class="Button-w"/>            <!-- Abschicktaste -->
    <input name="submit" type="submit" value="ausgew&auml;hlte l&ouml;schen" />    <!-- Abschicktaste -->
    <?php
    }
?>
</form> <br>
<?php
}
?>
<title>reservieren</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Technik reservieren - 2. Schritt
</big></big></big><br>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut,"<br>";
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
if (isset ($_POST['zu1']))
{
    ?><meta http-equiv="refresh" content="0; URL=reserv.php" /><?php ;
}

if ((isset($_POST['res']))and (isset($_POST['ausl'])))              //Häckchen auswerten
{
    foreach ($_POST['ausl'] as $key => $val)
    {
        $sql = mysql_query("SELECT * FROM geraete ");               //Tabelle geräte auswählen nur ausgewähltes Gerät 
        while($ds= mysql_fetch_object($sql))
        {
            $gid=$ds->ID;
            
            if ($gid = $val)
            {
                $gida = $gid ;
            }
        }
        $eintr = "INSERT INTO `var` (`gida`)VALUES ('$gida')";      //$gida in var eintragen
        mysql_query($eintr);
    }
}
if ((isset($_POST['res']))and (!isset($_POST['ausl'])))
{
?>
<textarea  cols="110" rows="1" class="text-a" readonly>Bitte Ger&auml;t ausw&auml;hlen</textarea><br/>
<form method="POST" action="reserv-1.php">
<input name="zu1" type="submit" value="Auswahl wiederholen" class="Button-w"/>
</form>
<?php
}
if (isset ($_POST['ausl']))
{
    anz ();
}
if (isset($_POST['submit']))
{
    switch($_POST['submit'])
    {
        case"ausgewählte löschen":                                  //taste löschen
            if (isset($_POST['lo']))
                {
                     $lgaida = 0;
                        foreach ($_POST['lo'] as $key => $val)
                        {
                            $sql = mysql_query("SELECT * FROM var "); //Tabelle var auswählen 
                            while($ds= mysql_fetch_object($sql))
                            {
                                $lg = $ds->gida ;
                                $lga = " ".$lg." " ;                // aus Datenbank var auslesen
                                $lgaid = $ds->id ;                  // aus Datenbank var auslesen
                                if ($lga == $val)
                                {
                                    $lgaida = $lgaid ;
                                }
                            }
                    $query = "DELETE FROM `var` WHERE id=$lgaida "; //Tabelle Wert löschen
                    $resultID = @mysql_query($query);
                         }
                }
                else
                {
                    ?> 
                    <textarea  cols="110" rows="1" class="text-a" readonly>Bitte Ger&auml;t ausw&auml;hlen</textarea><br/>
                    <?php ;
                }
                anz();
        break;
        case"weiter":
        ?><meta http-equiv="refresh" content="0; URL=reserv-2.php"><?php
        break;
        default:
     }
}
?>
<form method="POST" action="reserv-1.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$anzahl = anzahl der zeilen >0 in var
$d = variable in functionen
$date = übergabevariable in functionen
$ds / $ds1 = datenzähler
$eintr = variable in datenbankabfrage
$gid = id in geräte
$gida = gida in var
$herstellera = herstewller in geräte
$heut = heute deu
$heuteng = heute engl
$key = variable in arrayabfrage
$lg = gida in var auslesen
$lga = $lg mit leerzeichen
$lgaid = id in var
$lgaida = ausgewählte id
$query = variable in datenbankabfrage
$regnra = regnummer in geräte
$result / $resultID / $sql / $sql1 = variable in datenbankabfrage
$typa = typnummer in geräte
$typanz = typtext in typ
$val = variable in array abfrage
$zeile = anzahl der Zeilen in var
*/
?>
