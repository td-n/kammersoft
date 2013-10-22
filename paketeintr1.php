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
                $regnra=$ds ->RegNR ;                               // aus Datenbank auslesen
                $herstellera= $ds->Hersteller ;                     // aus Datenbank auslesen
                $query    = "SELECT Typ FROM typ WHERE ID=$typa";   //aus Typ-ID Typ machen
                $resultID = @mysql_query($query);                   //aus Typ-ID Typ machen
                $typanz = mysql_result($resultID,0);                //aus Typ-ID Typ machen
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
?></table>
<?php
$sql="SELECT COUNT(*) AS Anzahl FROM var WHERE gida > 0 ";          //ausblenden wenn nichts in tabelle steht
$result = mysql_query($sql);
$zeile = @mysql_fetch_array($result);
$anzahl = $zeile['Anzahl'];
if ($anzahl == 0)
    {
    ?><br>
    <input name="submit" type="submit" value="weiter" disabled="disabled" />    <!-- Abschicktaste -->
    <input name="submit" type="submit" value="ausgew&auml;hlte l&ouml;schen" disabled="disabled" />    <!-- Abschicktaste -->
    <?php
    }
    else
    {
    ?><br>
    <input name="submit" type="submit" value="weiter" />    <!-- Abschicktaste -->
    <input name="submit" type="submit" value="ausgew&auml;hlte l&ouml;schen" />    <!-- Abschicktaste -->
    <input name="submit" type="submit" value="verschienenes hinzuf&uuml;gen" />
    <?php
    }
?>
</form> <br>
<?php
}
?>
<title>Paketzusammenstellung &Uuml;bersicht</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Paketzusammenstellung &Uuml;bersicht
</big></big></big>
<br>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut , "<br>", "<br>";
$l="paketeintrag1 ge&ouml;ffnet";
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
    ?><meta http-equiv="refresh" content="0; URL=paketeintr.php" /><?php ;
}

if ((isset($_POST['eintr']))and (!isset($_POST['ausl'])))
{
?>
<textarea  cols="110" rows="1" class="text-a" readonly >Bitte ausw&auml;hlen</textarea><br/>
<?php ;
?>
<form method="POST" action="paketeintr1.php">
<input name="zur" type="submit" value="Auswahl wiederholen" class="Button-w"/><br/>
</form>
<?php
}
else
{
?>
<form method = "POST" >
<table border="1" width="80%">
<tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Auswahl</th> </tr>
<?php

if ((isset($_POST['eintr']))and (isset($_POST['ausl'])))
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

$sql1 = mysql_query("SELECT * FROM var WHERE gida > 0");            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1 = mysql_fetch_object($sql1))
    {
        $gida = $ds1->gida ;                                        //aus Datenbank var auslesen
            $sql = mysql_query("SELECT * FROM geraete WHERE ID=$gida");  //Tabelle gereate auswählen nur ausgewähltes Gerät
            while($ds= mysql_fetch_object($sql))
            {
                $typa = $ds->Typ ;                                  // aus Datenbank auslesen
                $regnra=$ds ->RegNR ;                               // aus Datenbank auslesen
                $herstellera= $ds->Hersteller ;                     // aus Datenbank auslesen
                $query    = "SELECT Typ FROM typ WHERE ID=$typa";   //aus Typ-ID Typ machen
                $resultID = @mysql_query($query);                   //aus Typ-ID Typ machen
                $typanz = mysql_result($resultID,0);                //aus Typ-ID Typ machen
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
?></table>
<?php
$sql="SELECT COUNT(*) AS Anzahl FROM var WHERE gida > 0 ";          //ausblenden wenn nichts in tabelle steht
$result = mysql_query($sql);
$zeile = @mysql_fetch_array($result);
$anzahl = $zeile['Anzahl'];
if ($anzahl == 0)
    {
    ?><br>
    <input name="submit" type="submit" value="weiter" disabled="disabled" />    <!-- Abschicktaste -->
    <input name="submit" type="submit" value="ausgew&auml;hlte l&ouml;schen" disabled="disabled" />    <!-- Abschicktaste -->
    <?php
    }
    else
    {
    ?><br>
    <input name="submit" type="submit" value="weiter" class="Button-w"/>    <!-- Abschicktaste -->
    <input name="submit" type="submit" value="ausgew&auml;hlte l&ouml;schen" />    <!-- Abschicktaste -->
    <input name="submit" type="submit" value="verschienenes hinzuf&uuml;gen" />
    <?php
    }

?>
</form> <br>
<?php
if (isset($_POST['submit']))
{
    switch($_POST['submit'])
    {
        case"ausgewählte löschen":                                  //auswahl löschen
            if (isset($_POST['lo']))
                {
                     $lgaida = 0;
                        foreach ($_POST['lo'] as $key => $val)
                        {
                            $sql = mysql_query("SELECT * FROM var "); //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
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
                    echo "<br>",$val,"-",$key,"-",$lgaida,"<br>";
                    $query = "DELETE FROM `var` WHERE id=$lgaida "; //Tabelle Wert löschen
                    $resultID = @mysql_query($query);
                         }
                    ?><meta http-equiv="refresh" content="0; URL=paketeintr1.php"><?php

                }
                else
                {
                    ?> 
                    <textarea  cols="110" rows="1" class="text-a" readonly >Bitte ausw&auml;hlen</textarea><br/>
                    <?php ;
                }
        break;
        case"verschienenes hinzufügen":                                               //auswahl weiter
        ?><meta http-equiv="refresh" content="0; URL=paketeintr2.php"><?php
        break;
        case"weiter":                                               //auswahl weiter
        ?><meta http-equiv="refresh" content="0; URL=paketeintr3.php"><?php
        break;
        default:
     }
}
}
//mysql_close($dz);
?>
<form method="POST" action="paketeintr1.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php
/*
$anzahl = Anzahl der Zeilen in var gida>0
$anzahl = Übergabevariable in function
$ds / $ds1 = Datenzähler
$eintr = variable in datenbankabfrage
$gid = ID in Geräte
$gida = gida in var
$herstellera = hersteller in geräte
$heut = heute in deu
$key = variable in array auslesen
$lg = gida in var
$lga = gida mit leerzeichen voran
$lgaid = id in var
$lgaida = ausgewählte $lgaid
$query = variable in datenbankabfrage
$regnra = Regnummer in geräte
$result / $resultID / $sql / $sql1 = variable in datenbank
$typa = Typnummer in geräte
$typanz = Typ text in geräte
$val = variable in array auslesen
$zeile = anzahl der zeilen in var gida >0
*/
?>
