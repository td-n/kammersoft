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
?>
<title>Inventur</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<a name="anfang"></a>
<big><big><big>
Ger&auml;teliste - Inventur   <img src="logo.jpg" width="40" height="40" >
</big></big></big><br>
<a href="#ende">-</a>
<br>
<table border="0" width="70%" cellpadding="0">
<tr><th>Nr.</th><th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>Bestand</th><th>Info</th></tr> 
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";  
echo $heut;
$l="Typenliste - Inventur ge&ouml;ffnet / ".$_SESSION['kname'];
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle R�ckgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/                                                                    //tabelle inventur erstellen
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
// MySQL-Anweisung ausf�hren lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());

$sql = mysql_query("SELECT * FROM geraete ORDER BY typ, RegNR");    //Tabelle gereate ausw�hlen
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

$sql = mysql_query("SELECT * FROM zusatz ORDER BY typ, regnr");    //Tabelle zusatz ausw�hlen
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

$sql = mysql_query("SELECT * FROM inventur " ); //Tabelle inventur ausw�hlen 
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
    echo "<td><input type=\"checkbox\" name=\"ausl[]\" value= \" $gid \" />";
    echo "</td>";

    if ($ausgel==1)
    {
        ?>
            <td><input type="text" name="info" value="ausgeliehen" ></td>
            </tr>
        <?php
    }    
    else
    {
        ?>
            <td><input type="text" name="info" maxlength="300"></td>
            </tr> 
        <?php      
    }      
}
$sql = " DROP TABLE IF EXISTS `inventur` ";                   //tabelle l�schen
// MySQL-Anweisung ausf�hren lassen
$db_erg = mysql_query($sql)
  or die("Anfrage fehlgeschlagen: " . mysql_error());


?>

</table>
<big>Bemerkungen/Anhang - Inventurergebnisse:</big>
<textarea name="lizenz" cols="50" rows="10" ></textarea><br><br><br>
<big>Unterschriften<br><br>
<hr>
Durchf�hrende:    <br>
Datum: <?php echo $heut ?>
<hr>
Kenntnisnahme:   Vereinsvorsitzende
</big>


<a name="ende"></a> 
<a href="#anfang">-</a><br> <br>
<a href="start1.php" >+</a>
</body>
</html>
<?php  
/*
anz = �bergabevariable in functionen
anza = anz
$ausgel = ausgeliehen in ger�te
$bemerkung = bemerkung in ger�te
$bis = bis in res
$d = variable in functionen
$date = �bergabevariable in functionen
$datets / $datets1 / $datets2 = variable in functionen
$datum = �bergabevariable in functionen
$ds / $ds1 = datenz�hler
$format = �bergabevariable in functionen
$gid = id in ger�te
$hersteller = hersteller in ger�te
$heut = heute deu
$heute = heute engl
$jahr = variable in functionen
$l = log eintrag
$monat = variable in functionen
$nam = name in res
$query / $resultID = variable in datenbankabfrage
$regnr = regnummr in ger�te
rep_notw = reperatur notwendig in ger�te
$res = res in ger�te
$sql / $sql1 = variable in datenbankabfrage
$tag = variable in function
$tuef = t�v in ger�te engl
$tuefeng = $tuef
$tuevbe = tuev - 30 tage
$typ = typnummer in ger�te
$typan = typ text in typ
$von = von in res deu - engl  
*/
?>
