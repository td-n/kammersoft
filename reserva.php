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
?>
<title>Res. aufheben</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Reservierung anzeigen und aufheben
</big></big></big><br>
<br>
<?php  
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

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
//            echo $z,"<br>";
//            echo $gid;
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
if ((!isset($_POST['lo'])) AND (isset($_POST['submit'])))
    {
        ?> 
        <textarea  cols="110" rows="1" class="text-a" readonly>Bitte ausw&auml;hlen</textarea><br/>
        <?php ;
    }
?>
<form action="<?php echo "reserva.php";?>" method="POST">
<input type="hidden" name="senden" value="$z"/>
<table border="1" width="90%">
<tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>ausgeliehen</th><th>Res. von</th><th>Res. bis</th><th>Name der Res.</th><th>Ausleihgrund</th><th>l&ouml;schen</th></tr>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                       //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="reserva ge&ouml;ffnet";
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
$sql1 = mysql_query("SELECT * FROM res ");         //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1= mysql_fetch_object($sql1))
    {
        $rid=$ds1->id;
        $gid=$ds1->gid;
        $vone=$ds1->von;
        $bise=$ds1->bis;
        $von= "" . date_mysql2german($vone) . " \n";
        $bis= "" . date_mysql2german($bise) . " \n";
        $nam=$ds1->name;
        $grund=$ds1->grund;
        $sql = mysql_query("SELECT * FROM geraete WHERE ID=$gid ORDER BY typ, RegNR");           //Tabelle gereate auswählen
        while($ds= mysql_fetch_object($sql))
        {
            $gid=$ds->ID;
            $typ = $ds->Typ ;                                               // aus Datenbank auslesen
            $regnr= $ds->RegNR ;                                            // aus Datenbank auslesen
            $hersteller= $ds->Hersteller ;                                  // aus Datenbank auslesen
            $bemerkung= $ds->Bemerk;	                                    // aus Datenbank auslesen
            $ausgel=$ds->ausgeliehen;
            $res=$ds->res;
            $query    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
            $resultID = @mysql_query($query);                               //aus Typ-ID Typ machen
            $typan = mysql_result($resultID,0);
            echo "<td>",$typan,"</td>";
            echo "<td>",$regnr,"</td>";
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
            if ($ausgel == "1")                                             //bei ausgeliehen auf rot setzen
            {
                ?>
                <td bgcolor =#FF6F6C >
                <input type="checkbox" name="anzei[]" checked disabled />
                </td>
                <?php
            }
            else
            {
                ?>
                <td>
                <input type="checkbox" name="anzei[]" disabled />
                </td>
                
                <?php
            }
            echo "<td>",$von,"</td>";
            echo "<td>",$bis,"</td>";
            echo "<td>",$nam,"</td>";
            echo "<td>",$grund,"</td>";
//            echo "</td>";
            echo "<td><input type=\"radio\" name=\"lo[]\" value= \" $rid \" /></td>"; // löschhäckchen
        }
            echo "</tr>";

}
//mysql_close($dz);
?>
</table>
<?php  
$sql="SELECT COUNT(*) AS Anzahl FROM res";
$result = mysql_query($sql); 
$zeile = @mysql_fetch_array($result);
$anzahl = $zeile['Anzahl'];
if ($anzahl == 0)
    {
     ?>
     <p><input type="submit" value="L&ouml;schen" name="submit" disabled="disabled" /> ausgew&auml;hlte Reservierungen werden ohne R&uuml;ckfrage gel&ouml;scht</p>
     <?php
//     echo $zeile,$anzahl,"+++";
    }
    else
    {
     ?>
     <p><input type="submit" value="L&ouml;schen" name="submit"  /> ausgew&auml;hlte Reservierungen werden ohne R&uuml;ckfrage gel&ouml;scht</p>   
     <?php
    }
?>
</form>
<form method="POST" action="reserva.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php
/*
$anzahl = anzahl einträge in res
$ausgel = ausgeliehen in geräte
$bemerkung = bemerkung in geräte
$bis = bis in res deu
$bise = bis in res engl
$d = variable in function
$date = übergabevariable un function
$ds / $ds1 = datenzähler
$ew = log text
$gid geräte id in res
$grund = ausleihgrund in res
$hersteller = hersteller in geräte
$heut heute deu
$heuteng = heute engl
$key = variable in array auslesen
$l = log eintrag
$nam = name in res
$query / $query1 = variable in res
$regnr = regnummer in geräte
$res = reserviert in geräte
$result / $resultID / $resultID1 = veriable in daternbankabfrage
$rid = id in res
$sql / $sql1 = variable in datenbankabfrage
$typ = typ nummer in geräte
$typan = typ name in typ
$user = user in res
$val = variable in array abfrage
$von = von in res deu
$vone = von in res engl
$z = zeilennumemr in res von gid
$zeile = zeilenanzahl in res
*/
?>
