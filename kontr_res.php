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
function tage_addieren($date, $anz, $format){
    $datets1= strtotime($date);
    $datets2=$datets1 + ($anz*86400);
    $datets=date($format, $datets2);
    return  $datets;  }
function tage_subtrahieren($date, $anz, $format){
    $datets1= strtotime($date);
    $datets2=$datets1 - ($anz*86400);
    $datets=date($format, $datets2);
    return  $datets;  }

function date2timestamp($datum) {
    list($tag, $monat, $jahr) = explode(".", $datum);
    $jahr = sprintf("%04d", $jahr);
	$monat = sprintf("%02d", $monat);
    $tag = sprintf("%02d", $tag);
	return(mktime(0, 0, 0, $monat, $tag, $jahr));  }
?>
<title>Kontrolle Reservieerung</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Kontrolle Reservierung
</big></big></big>
<form name="k_res" action="kontr_res.php" method="post" >
<br>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                       //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="kontrolle-reserv ge&ouml;ffnet";
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

?> 
<br><font color="#FF0000"><big><big>folgende Reservierungen sind abgelaufen, sollen sie gel&ouml;scht werden?</big></big></font> 
<table border="1" width="90%">
<tr> <th>Typ</th><th>Nummer</th><th>Res. von</th><th>Res. bis</th><th>Name der Res.</th><th>Ausleihgrund</th><th>l&ouml;schen</th></tr>
<?php ;
$vorh = 0;
$sql1 = mysql_query("SELECT * FROM res ");         //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1= mysql_fetch_object($sql1))
{
    $rid=$ds1->id;
    $gid=$ds1->gid;
    $vone=$ds1->von;
    $bise=$ds1->bis;
    $grund=$ds1->grund;
    $von= "" . date_mysql2german($vone) . " \n";
    $bis= "" . date_mysql2german($bise) . " \n";
    $nam=$ds1->name;
    $sql = mysql_query("SELECT * FROM geraete WHERE ID=$gid ORDER BY typ, RegNR");           //Tabelle gereate auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $gid=$ds->ID;
        $typ = $ds->Typ ;                                       // aus Datenbank auslesen
        $regnr= $ds->RegNR ;                                    // aus Datenbank auslesen
        $res=$ds->res;
        $query    = "SELECT Typ FROM typ WHERE ID=$typ";        //aus Typ-ID Typ machen
        $resultID = @mysql_query($query);                       //aus Typ-ID Typ machen
        $typan = mysql_result($resultID,0);
//        echo  date2timestamp($heut),"-", date2timestamp($von); 
        if(date2timestamp($heut)>date2timestamp($von))
        {
            echo "<tr></tr><td>",$typan,"</td>";                             //typ
            echo "<td>",$regnr,"</td>";                             //regnummer
            echo "<td>",$von,"</td>";                             //regnummer
            echo "<td>",$bis,"</td>";                             //regnummer
            echo "<td>",$nam,"</td>";                             //regnummer
            echo "<td>",$grund,"</td>";                             //regnummer
            echo "<td><input type=\"radio\" name=\"lo[]\" value= \" $rid \" /></td>"; // löschhäckchen
            echo "</tr>";
            $vorh = 1;
        }    
    }
}
?>
</table>
<?php                                                               //aktion bei tasten
if (isset ($_POST['submit']))
{
            switch($_POST['submit'])
            {
                case"löschen":                                      //reservierung löschen
                    if (isset($_POST['lo']))
                        {
                        foreach ($_POST['lo'] as $key => $val)
                            {
                                $query    = "SELECT gid FROM res WHERE id=$val ";                //a
                                $resultID = @mysql_query($query);                               //a
                                $gid = mysql_result($resultID,0);
                                $query    = "SELECT gid FROM res WHERE gid=$gid ";                //a
                                $resultID = @mysql_query($query);                               //a
                                $z= mysql_num_rows($resultID);
//                                echo $z,"<br>";
//                                echo $gid;
                                if ($z=="1")
                                {
                                    $query1 = "UPDATE `geraete` SET `res` = 0 WHERE `geraete`.`ID` = $gid";
                                    $resultID1 = @mysql_query($query1);                         //in geraete reserviert setzen
                                }
                                $user=$_SESSION['kname'];
                                $heut = date("d.m.Y");  //heutuger Datum
                                $sql1 = mysql_query("SELECT * FROM res WHERE gid=$gid ");         //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
                                while($ds1= mysql_fetch_object($sql1))
                                    {
                                        $gid=$ds1->gid;
                                        $vone=$ds1->von;
                                        $bise=$ds1->bis;
                                        $nam=$ds1->name;
                                     }
                                $sql = mysql_query("SELECT * FROM geraete WHERE ID=$gid ");           //Tabelle gereate auswählen
                                while($ds= mysql_fetch_object($sql))
                                {
                                    $typ = $ds->Typ ;                                               // aus Datenbank auslesen
                                    $regnr= $ds->RegNR ;                                            // aus Datenbank auslesen
                                    $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
                                    $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
                                    $typan = mysql_result($resultID,0);
                                }
                                $ew="Reservierung " ;
                                $l=$ew.$typan." / ".$regnr." vom: ".$vone." bis: ".$bise." für: ".$nam."  wurde von: ".$user." am: ".$heut." gelöscht" ;
                                logsch($l);
                                $query = "DELETE FROM `res` WHERE id=$val "; //Tabelle Wert löschen
                                $resultID = @mysql_query($query);
                            }
                        }
                    ?><meta http-equiv="refresh" content="0; URL=kontr_res.php" /><?php    
                break;
                case"weiter":                                       //weiter zur reservierung
                    ?><meta http-equiv="refresh" content="0; URL=reserv.php" /><?php
                break;
                default:
            }
}
if ($vorh==0)
{
    ?><meta http-equiv="refresh" content="0; URL=reserv.php" /><?php    
}
?>
<br><input type="submit" value="l&ouml;schen" name="submit"  /> ausgew&auml;hlte Reservierungen werden ohne R&uuml;ckfrage gel&ouml;scht
<br><input type="submit" value="weiter" name="submit" class="Button-w" />weiter zur Reservierung
</form>
<form method="POST" action="Ausgeber.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
