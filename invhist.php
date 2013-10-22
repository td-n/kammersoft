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
<?php
if ($_SESSION['schreiben']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
function tabelle_oeffnen() {
?>
<table border="0" width="90%" cellpadding="0">
<tr><th>Nr.</th><th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>Bestand</th><th>Info</th></tr>
<?php
}
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
Inventur
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
$l="Inventur ge&ouml;ffnet / ".$_SESSION['kname'];
logsch ($l);

if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$jahrj=date("Y");                                                   //aktuelles Jahr
?>
<form method = "POST" action="invhist.php">
<?php
if (isset($_POST['ausname']))                                       //jahr ausgewählt?
{
    $jahrw=$_POST['ausname'];
}
else
{
    ?>
    <font color="#FF0000"><big>Sie haben das Jahr noch kein Jahr ausgew&auml;hlt<br></font></big>
    <select name = "ausname" size="1">
    <option selected>bitte w&auml;hlen</option>
    <?php
    $sql = mysql_query("SELECT DISTINCT jahr FROM invhist ");       //welche jahreseinträge existieren
    while($ds= mysql_fetch_object($sql))
    {
        $jahr = $ds->jahr ;
        echo "<option value = '$jahr'>" .$jahr. "</option>"  ;      //Dropdown mit ausgewählten schreiben
    }
    ?>
    </select>
    <input name="submit" type="submit" value="&uuml;bernehmen" class="Button-w"/>
    <br><br>
<?php
}
if (!isset($jahrw))                                                 //kein jahr ausgewählt
{
    $sql1 = mysql_query("SELECT * FROM var WHERE gida > -3");       //Tabelle var auswählen jahr auslesen
    while($ds = mysql_fetch_object($sql1))
        {
            $jahrw = $ds->rdat ;
        }
}
if (isset($jahrw))                                                  //wenn keins existiert
{
    if ($jahrw!=="bitte wählen")
    {
        ?>
        <font color="#0000FF"><big>Sie haben das Jahr <?php echo $jahrw ?> ausgew&auml;hlt<br></font></big>
        <?php
    }
}
if (isset($_POST['submit']))                                        //welche taste wurde gedrückt
{
    switch($_POST['submit'])
    {
        case"übernehmen":
            if ($jahrw!=="bitte wählen")
            {
                tabelle_oeffnen() ;
        
                $query = "TRUNCATE `var`";                                  //Tabelle var Werte löschen
                $resultID = @mysql_query($query);
        
                $eintr = "INSERT INTO `var` (`rdat`,`gida`) VALUES ('$jahrw', -3)";  //ausgewähltes jahr in var gida=-3 in rdat eintragen
                mysql_query($eintr)  ;
        
                $sql1 = mysql_query("SELECT * FROM var WHERE gida = -3");   //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
                while($ds = mysql_fetch_object($sql1))
                    {
                        $jahrw = $ds->rdat ;
                    }
                $si=1;
                $zu=0;
                $sql = mysql_query("SELECT * FROM invhist WHERE jahr=$jahrw ORDER BY nr "); //Tabelle inventur auswählen mit gewählten jahr
                while($ds= mysql_fetch_object($sql))
                {
                    $id = $ds->id ;
                    $iid = $ds->nr ;
                    $typ = $ds->typ ;                                  // aus Datenbank auslesen
                    $regnr= $ds->regnr ;
                    $hersteller= $ds->hersteller ;
                    $bemerkung= $ds->bemerkung;
                    $bestand=$ds->bestand;
                    $info=$ds->info;
                    $abgeschl=$ds->abgeschl;
                    $ijahr=$ds->jahr;
//                    echo $ijahr;
                    echo "<tr>";
                    echo "<td>",$iid,"</td>";
                    if ($typ!=="Ende")
                    {
                        echo "<td>",$typ,"</td>";                          //anzeige
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
                        }
                        if ($abgeschl==0)                                   //wenn abgeschlossen bearbeitung deaktivieren
                        {
                            if ($bestand == "1")
                            {
                                echo "<td><input type=\"checkbox\" name=\"w[$id]\"  checked/></td>";
                            }
                            else
                            {
                                echo "<td><input type=\"checkbox\" name=\"w[$id]\"  /></td>";
                            }
                            echo "<td><input type=\"text\" name=\"infol[$id]\" value=\"$info\" /></td>";
                        }
                        else
                        {
                            if ($bestand == "1")
                            {
                            ?>
                            <td><input type="checkbox" name="w[]" checked disabled /></td>
                            <?php
                            }
                            else
                            {
                            ?>
                            <td><input type="checkbox" name="w[]" disabled /></td>
                            <?php
                            }
                            echo "<td><input type=\"text\" name=\"infol[$id]\" value=\"$info\" readonly /></td>";
                        }
                    
                    }
                    else
                    {
                        echo "<td>",$typ,"</td>";                          //anzeige
                        echo "<td>","-","</td>";
                        echo "<td>"," - ","</td>";
                        echo "<td>"," - ","</td>";
                        if ($abgeschl==0)                                   //wenn abgeschlossen bearbeitung deaktivieren
                        {
                            echo "<td><textarea name=\"lizenz\" cols=\"50\" rows=\"10\" maxlength=\"500\" >$info</textarea><br><br><br></td>";
                        }
                        else
                        {
                            echo "<td><textarea name=\"lizenz\" cols=\"50\" rows=\"10\" maxlength=\"500\"  readonly >$info</textarea><br><br><br></td>";
                        }    
                    }
                    
                $si=$si+1;                                          //zeilenzähler
                }
                ?></tr><?php
            }
            else
            {
                ?>
                <font color="#FF0000"><big>Sie haben noch kein Jahr ausgew&auml;hlt<br></font></big>
                <?php 
            }
        break;
        case"neues Jahr anlegen":
            $vorh="0";
            $query = "TRUNCATE `var`";                                  //Tabelle var Werte löschen
            $resultID = @mysql_query($query);
            $jahrj=date("Y"); 
            $sql = mysql_query("SELECT DISTINCT jahr FROM invhist ");       //welche jahreseinträge existieren
            while($ds= mysql_fetch_object($sql))
            {
                $jahre = $ds->jahr ;
                if ($jahrj==$jahre)
                {
                    $vorh=1;
                }
            }
            if ($vorh==1)
            {
                ?>
                <font color="#AF0000"><big><?php echo $jahrj?>- Jahr bereits vorhanden. Bitte dieses Jahr ausw&auml;hlen<br></font></big>
                <?php
            }
            else
            {
                $sql = " CREATE TABLE IF NOT EXISTS `inventur` (
                    `id` INT( 50 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    `typ` VARCHAR( 60 ) NOT NULL ,
                    `regnr` VARCHAR( 5 ) NOT NULL ,
                    `hersteller` VARCHAR( 60 ) NOT NULL ,
                    `bemerkung` VARCHAR( 60 ) NOT NULL ,
                    `ausgeliehen` VARCHAR( 25 ) NOT NULL ,
                    `bestand` TINYINT(1) NOT NULL,
                    `info` VARCHAR( 60 ) NOT NULL,
                    `jahr` INT( 10 ) NOT NULL
                    ) ENGINE = MYISAM ;
                    ";
                $db_erg = mysql_query($sql)                             // MySQL-Anweisung ausführen lassen
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
    //                echo $typan;
                    $eintr = "INSERT INTO inventur
                        (typ, regnr, hersteller, bemerkung, ausgeliehen, bestand, info, jahr)
                        VALUES ('$typan','$regnr','$hersteller','$bemerkung','$ausgel','0','-', '$jahrj')";
                    mysql_query($eintr);                                            //in neue tabelle schreiben
                }
                
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
                        (typ, regnr, hersteller, bemerkung, bestand, info, jahr)
                        VALUES ('$typ','$regnr','$hersteller','$bemerkung','0','-', '$jahrj')";
                    mysql_query($eintr);                                            //in neue tabelle schreiben
                }
                $eintr = "INSERT INTO inventur
                     (typ) VALUES ('Ende')";
                mysql_query($eintr);
              
                $sql = mysql_query("SELECT * FROM inventur " );         //Tabelle inventur auswählen
                while($ds= mysql_fetch_object($sql))
                {
                    $gid = $ds->id ;
                    $typ = $ds->typ ;                                               // aus Datenbank auslesen
                    $regnr= $ds->regnr ;
                    $hersteller= $ds->hersteller ;
                    $bemerkung= $ds->bemerkung;
                    $ausgel=$ds->ausgeliehen;
                    $info= $ds->info;
                    $jahrt= $jahrj;
                    if ($ausgel==1)
                    {
                        $info="ausgeliehen";
                    }
                    else
                    {
                        $info="-";
                    }
                    $eintr = "INSERT INTO invhist
                        (nr, typ, regnr, hersteller, bemerkung, info, jahr)
                        VALUES ('$gid', '$typ','$regnr','$hersteller','$bemerkung','$info', '$jahrj')";
                    mysql_query($eintr);                                            //in neue tabelle schreiben
                }
                $eintr = "INSERT INTO invhist
                (nr, info, jahr)
                VALUES ('Ende','-', '$jahrj')";
                tabelle_loeschen();
                ?>
                <font color="#AF0000"><big><?php echo $jahrj?>- angelegt. <br></font></big>
                <?php 
            }
            ?>
            <meta http-equiv="refresh" content="3; URL=start1.php" />
            <?php

        break;
        case"Jahr neu auswählen":
            $query = "TRUNCATE `var`";                                  //Tabelle var Werte löschen
            $resultID = @mysql_query($query);
            ?>
            <meta http-equiv="refresh" content="0; URL=invhist.php" />
            <?php
        break;
        case"Daten zwischenspeichern":
            $sql1 = mysql_query("SELECT * FROM var WHERE gida = -3");   //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
            while($ds = mysql_fetch_object($sql1))
                {
                    $jahrw = $ds->rdat ;
                }
            $sql="SELECT COUNT(nr) FROM invhist WHERE `jahr`= $jahrw";         //anzahl der Einträge
            $result = mysql_query($sql);
            $zeile = mysql_fetch_row($result);
            $max = $zeile['0'];
            for ($i=1;$i<=$max;$i++)                                 //alle checkbox löschen
            {
                $query1 = "UPDATE `invhist` SET `bestand` = '0' WHERE `nr` = $i AND `jahr`= $jahrw  ";
                $resultID1 = @mysql_query($query1);       
            }
            if (isset($_POST['w']))
            {
                foreach ($_POST['w'] as $key => $val)               //checkbox auslesen
                {
//                    echo "+",$key,"->";
//                    echo $val,"+","<br>";
                    if ($val=="on")
                    {
                        $val=1;
                    }
                    else
                    {
                        $val=0;
                    }
//                    echo $val,"+","<br>";
                    $query1 = "UPDATE `invhist` SET `bestand` = '$val' WHERE `id` = '$key' ";  //wert schreiben
                    $resultID1 = @mysql_query($query1);
//                    echo $jahrw,"<br>";
//                    echo "+erg:",$resultID1,"+","<br>";
                 }
            }
            if (isset ($_POST['infol']))
            {
                 foreach ($_POST['infol'] as $key => $val)               //checkbox auslesen
                 {
//                    echo "+",$key,"->";
//                    echo $val,"+","<br>";
                    $query = "UPDATE  `invhist` SET `info` = '$val' WHERE `id` = '$key' ";
                    $resultID = @mysql_query($query);
//                    echo $jahrw,"<br>";
//                    echo "+erg:",$resultID,"+","<br>";
                 }
             }
             if (isset ($_POST['lizenz']))
                {
                    $ainfo=$_POST['lizenz'];
//                    echo $ainfo;
                    $query = "UPDATE  `invhist` SET `info` = '$ainfo' WHERE `typ` = 'Ende' AND `jahr`= $jahrw";
                    $resultID = @mysql_query($query);
                }
        break;
        case"Inventur abschließen":
            $sql1 = mysql_query("SELECT * FROM var WHERE gida = -3");   //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
            while($ds = mysql_fetch_object($sql1))
                {
                    $jahrw = $ds->rdat ;
                }
            $sql="SELECT COUNT(nr) FROM invhist WHERE `jahr`= $jahrw";         //anzahl der Einträge
            $result = mysql_query($sql);
            $zeile = mysql_fetch_row($result);
            $max = $zeile['0'];
            for ($i=1;$i<=$max;$i++)                                 //alle checkbox löschen
            {
                $query1 = "UPDATE `invhist` SET `abgeschl` = '1' WHERE `nr` = $i AND `jahr`= $jahrw  ";
                $resultID1 = @mysql_query($query1);
            }
        break;
        case"ausgeben":
            $sql1 = mysql_query("SELECT * FROM var WHERE gida = -3");   //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
            while($ds = mysql_fetch_object($sql1))
                {
                    $jahrw = $ds->rdat ;
                }

            $sql = mysql_query("SELECT * FROM conf  WHERE wert=desktopverzeichnis");       //Desktoppfad aus conf auslesen
            while($ds= mysql_fetch_object($sql))
            {
                $dv=$ds->was;
            }
            $path = $dv."/";                                        // inventur.txt beginnen
            $datei= "inventur.txt";
            $datei_name = $path.$datei;

                    $daten= "Inventur".$jahrw. "\r\n"; //in log.txt schreiben
                    $fp1 = fopen($datei_name, "a");
                    fwrite($fp1, $daten);
                    fclose($fp1);

            $sql = mysql_query("SELECT * FROM invhist WHERE jahr=$jahrw ORDER BY nr");                      //Tabelle log auswählen
            while($ds= mysql_fetch_object($sql))
            {
                $iid = $ds->nr ;
                $typ = $ds->typ ;                                  // aus Datenbank auslesen
                $regnr= $ds->regnr ;
                $hersteller= $ds->hersteller ;
                $bemerkung= $ds->bemerkung;
                $bestand=$ds->bestand;
                $info=$ds->info;
                    $daten= $iid."|".$typ."|".$regnr."|".$hersteller."|".$bemerkung."|".$bestand."|".$info. "\r\n"; //in log.txt schreiben
                    $fp1 = fopen($datei_name, "a");
                    fwrite($fp1, $daten);
                    fclose($fp1);
            }
            ?><meta http-equiv="refresh" content="0; URL=invhist-p.php" /><?php 
        break;
        case"löschen":
            $z="0";
            $sql = mysql_query("SELECT * FROM invhist " );         //Tabelle inventur auswählen
            while($ds= mysql_fetch_object($sql))
            {
                $gid = $ds->id ;
                $abgeschl = $ds->abgeschl ;                                               // aus Datenbank auslesen
                if ($abgeschl=="0")
                {
                    $query = "DELETE FROM `invhist` WHERE id=$gid ";           //Tabelle Werte löschen
                    $resultID = @mysql_query($query);
                    $z=$z+1;
                }
            }
            ?>
            <font color="#AF0000"><big>
            <?php  
            echo "Es wurden ";
            echo $z;
            echo "  Datens&auml;tze gel&ouml;scht","<br>";
            ?>
            <br></font></big>
            <meta http-equiv="refresh" content="10; URL=invhist.php" />    
            <?php
        break;
        default:
    }
}                                                                   //tasten beim Druck ausblenden
?>
</table>
<style type="text/css">                                             
@media print {
 input {
   display: none;
 }
}
</style>
<?php
if (isset ($abgeschl))                                              //tastenanzeige
{
    if ($abgeschl==1)
    {
        ?>
        <font color="#AF0000"><big>Das gew&auml;hlte Jahr ist bereits abgeschlossen und kann nicht mehr bearbeitet werden<br></font></big>
        <input name="submit" type="submit" value="Daten zwischenspeichern" disabled/><font color="#0000FF"><big>    aktuellen Daten werden gespeichert<br></font></big>
        <input name="submit" type="submit" value="Inventur abschlie&szlig;en" disabled/><font color="#0000FF"><big>    die Inventur wird abgeschlossen<br></font></big>
        <input name="submit" type="submit" value="l&ouml;schen" disabled/><font color="#0000FF"><big><br>
        <?php
    }
}
//echo $abgeschl;
if ((isset ($abgeschl)) or (isset ($_POST['submit'])))
{
    if (isset($_POST['submit']))
    {
        if((isset($abgeschl)AND($abgeschl=="0")) or ($_POST['submit']=="neues Jahr anlegen"))
        {
        ?>
        <input name="submit" type="submit" value="Daten zwischenspeichern" /><font color="#0000FF"><big>    aktuellen Daten werden gespeichert<br></font></big>
        <input name="submit" type="submit" value="Inventur abschlie&szlig;en" /><font color="#0000FF"><big>    die Inventur wird abgeschlossen<br></font></big>
        <input name="submit" type="submit" value="l&ouml;schen" /><font color="#0000FF"><big>   noch nicht abgeschlossene Inventur wird gel&ouml;scht<br></font></big>
        <?php
        }
    }
}
$sql1 = mysql_query("SELECT * FROM var WHERE gida = -3");   //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds = mysql_fetch_object($sql1))
    {
        $jahrw = $ds->rdat ;
    }

if (isset($jahrw))
    {
//        echo "+",$jahrw,"+";
        ?>
        <input name="submit" type="submit" value="ausgeben" /><font color="#0000FF"><big>    die Daten werden auf den Desktop exportiert<br></font></big>
        <?php         
    }
    else
    {
        ?>
        <input name="submit" type="submit" value="ausgeben" disabled/><font color="#0000FF"><big>    die Daten werden auf den Desktop exportiert<br></font></big>
        <?php
    }
?>
<input name="submit" type="submit" value="neues Jahr anlegen" /><font color="#0000FF"><big>    die Inventur f&uuml;r das laufende Jahr wird angelegt<br></font></big>       <!-- Abschicktaste -->
<input name="submit" type="submit" value="Jahr neu ausw&auml;hlen" /><font color="#0000FF"><big><br></font></big>       <!-- Abschicktaste -->
</form>
<form method="POST" action="invhist.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>

</body>
</html>
<?php  

?>