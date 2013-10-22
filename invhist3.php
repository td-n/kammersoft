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
?>
<?php
if ($_SESSION['schreiben']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
function tabelle_loeschen() {
    $sql = " DROP TABLE IF EXISTS `inventur` ";                   //tabelle löschen
    // MySQL-Anweisung ausführen lassen
    $db_erg = mysql_query($sql)
      or die("Anfrage fehlgeschlagen: " . mysql_error());}
?>
<title>Inventur</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Inventur erstellen
</big></big></big><br>
<br>
<?php
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //aktueller Benutzer
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut,"<br>";
$l="Inventur neu ge&ouml;ffnet / ".$_SESSION['kname'];
logsch ($l);

?>
<form method = "POST" action="invhist3.php">
<table border="0" width="70%" cellpadding="0">
<tr><th>Nr.</th><th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>Bestand</th><th>Info</th></tr>
<?php

$sql = " CREATE TABLE IF NOT EXISTS `inventur` (
    `id` INT( 50 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `typ` VARCHAR( 60 ) NOT NULL ,
    `regnr` VARCHAR( 5 ) NOT NULL ,
    `hersteller` VARCHAR( 60 ) NOT NULL ,
    `bemerkung` VARCHAR( 60 ) NOT NULL ,
    `ausgeliehen` VARCHAR( 25 ) NOT NULL ,
    `bestand` TINYINT(1) NOT NULL,
    `info` VARCHAR( 60 ) NOT NULL
    ) ENGINE = MYISAM ;
    ";
// MySQL-Anweisung ausführen lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());

$sql = mysql_query("SELECT * FROM geraete ORDER BY typ, RegNR");    //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid = $ds->ID ;
    $typ = $ds->Typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->RegNR ;
    $hersteller= $ds->Hersteller ;
    $bemerkung= $ds->Bemerk;
    $ausgel=$ds->ausgeliehen;
    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
    $resultID = @mysql_query($query);
    $typan = mysql_result($resultID,0);

    $eintr = "INSERT INTO inventur
        (typ, regnr, hersteller, bemerkung, ausgeliehen, bestand, info)
        VALUES ('$typan','$regnr','$hersteller','$bemerkung','$ausgel','0','-')";
    mysql_query($eintr);                                            //in neue tabelle schreiben
}
//echo  $typan,$regnr,$hersteller,$bemerkung,$ausgel,"<br>";
unset  ($typan,$regnr,$hersteller,$bemerkung,$ausgel);

$sql = mysql_query("SELECT * FROM zusatz ORDER BY typ, regnr");    //Tabelle zusatz auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid = $ds->id ;
    $typ = $ds->typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->regnr ;
    $hersteller= $ds->hersteller ;
    $bemerkung= $ds->bemerkung;

    $eintr = "INSERT INTO inventur
        (typ, regnr, hersteller, bemerkung, bestand, info)
        VALUES ('$typ','$regnr','$hersteller','$bemerkung','0','-')";
    mysql_query($eintr);                                            //in neue tabelle schreiben
}
    $eintr = "INSERT INTO inventur
         (typ) VALUES ('Ende')";
    mysql_query($eintr);     
$sql = mysql_query("SELECT * FROM inventur " ); //Tabelle inventur auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid = $ds->id ;
    $typ = $ds->typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->regnr ;
    $hersteller= $ds->hersteller ;
    $bemerkung= $ds->bemerkung;
    $ausgel=$ds->ausgeliehen;
    echo "<td>",$gid,"</td>";
    echo "<td>",$typ,"</td>";                                       //anzeige
    if ($typ!=="Ende")
    {
        if ($regnr!="")
        {
        echo "<td>",$regnr,"</td>";
        }
        else
        {
        echo "<td>"," - ","</td>";
        }
        if ($hersteller!="")
        {
        echo "<td>",$hersteller,"</td>";
        }
        else
        {
        echo "<td>"," - ","</td>";
        }
        if ($bemerkung!="")
        {
        echo "<td>",$bemerkung,"</td>";
        }
        else
        {
        echo "<td>"," - ","</td>";
        }                                                               //ende anzeige
        if ($typ!=="Ende")                                              //bei ende ausblenden
        {
            echo "<td><input type=\"checkbox\" name=\"v[$gid]\"  />";
            echo "</td>";
            if ($ausgel==1)
            {
                ?>
                    <td><input type="text" name="info[]" value="ausgeliehen" ></td>
                    </tr>
                <?php
            }
            else
            {
                ?>
                    <td><input type="text" name="info[]" maxlength="300"></td>
                    </tr>
                <?php
            }
        }
    }
    else
    {
        echo "+++";       //wenn es ende ist noch schreiben
    }
}
?>
</table>
<big>Bemerkungen</big>  (300 Zeichen)
<textarea name="lizenz" cols="50" rows="10" maxlength="500"></textarea><br><br><br>
<?php 

//tabelle_loeschen();

if (isset($_POST['submit']))
{
    switch($_POST['submit'])
    {
        case"Zwischenstand speichern":
    
    
        break;
        case"abschließen":
    
    
        break;
         case"ausgeben":


        break;
        default:
    }
}

?>
<input name="submit" type="submit" value="Zwischenstand speichern" /><font color="#0000FF"><big>    Den aktuellen Stand der Inventur zwischenspeichen<br></font></big>       <!-- Abschicktaste -->
<input name="submit" type="submit" value="abschlie&szlig;en" /><font color="#0000FF"><big>    Die Inventur abschlie&szlig;en<br></font></big>       <!-- Abschicktaste -->
<input name="submit" type="submit" value="ausgeben" /><font color="#0000FF"><big>    Die Daten werden auf den Desktop exportiert<br></font></big>       <!-- Abschicktaste -->
</form>
<a href="invhist.php" >zur Auswahl </a>
</body>
</html>
