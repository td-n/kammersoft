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
?>
<title>Ausleihe bearbeiten</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Ausleihnummer mit neuen Namen versehen 2.Schritt
</big></big></big><br>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                       //Benutzer anzeigen
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
    
if (isset($_POST['submit']))
{
    switch($_POST['submit'])
    {
        case"weiter":
            ?><meta http-equiv="refresh" content="1; URL=Ausleihe-m3.php" /><?php
        break;
        default:
    }
}
    $user=$_SESSION['kname'];
    $query    = "SELECT rdat FROM var WHERE gida=-7";               //jahr aus var auslesen -7 
    $resultID = @mysql_query($query);                               
    $ajahr = mysql_result($resultID,0);
    $query    = "SELECT gernr FROM var WHERE gida=-8";              //nr aus var auslesen -8  
    $resultID = @mysql_query($query);                               
    $anr = mysql_result($resultID,0);
    ?><big><font color="#0000FF">Sie haben die Ausleihnummer:  </font><?php
    echo $ajahr,"/",$anr;
    ?><font color="#0000FF">  ausgew&auml;hlt   </font></big><?php
    ?>
    <table border="1" >
    <tr> <th>Ger&auml;t</th><th>Nummer</th><th>verschiedenes</th></tr>
    <?php
    $sql = mysql_query("SELECT * FROM ausleihe WHERE jahr=$ajahr AND lfnr=$anr AND abgeschlAusleihe=0 ");  //Tabelle ausleihe auswählen nur offene ausleihe
    while($ds= mysql_fetch_object($sql))
    {
        $gid = $ds-> IDGeraet;
        $id = $ds-> ID;
        $versch = $ds-> verschiedenes;
//        echo "+",$gid,"+", $id,"+", $versch,"+";
        $eintr = "INSERT INTO `var` (`gida`, `mang`) VALUES ('$gid', '$versch')";  //Name in var eintragen
        mysql_query($eintr)  ;
        $query1 = "UPDATE `ausleihe` SET `Ruecknehmer` = '$user', `abgeschlAusleihe` = 1, `Datum_rueck` = '$heuteng' WHERE `ausleihe`.`ID` = $id";
        $resultID1 = @mysql_query($query1);                         //Daten austragen
        if (isset($gid))
        {
            $sql2 = mysql_query("SELECT * FROM geraete WHERE ID=$gid");    //Tabelle gereate auswählen
            while($ds= mysql_fetch_object($sql2))
            {
                $typ = $ds->Typ ;                                               // aus Datenbank auslesen
                $regnr= $ds->RegNR ;
                $query2    = "SELECT Typ FROM typ WHERE ID=$typ";                //aus Typ-ID Typ machen
                $resultID2 = @mysql_query($query2);                               //aus Typ-ID Typ machen
                $typan = mysql_result($resultID2,0);
            }
        } 
        echo "<tr>";
//        echo $gid, $typ, $typan, $versch;
        if ((isset ($typan))and($versch==""))
        {
            echo "<td>",$typan,"</td>";
            echo "<td>",$regnr,"</td>";
        }
        else
        {
            echo "<td>","-","</td>";
            echo "<td>","-","</td>";
        }
        if ($versch!=="")
        {
            echo "<td>",$versch,"</td>";
        }
        else
        {
            echo "<td>","-","</td>";
        }
            echo "</tr>";
    }    

$sql = mysql_query("SELECT * FROM ausleihe WHERE jahr=$ajahr AND lfnr=$anr ");  //Tabelle ausleihe auswählen ohne offene ausleihe
while($ds= mysql_fetch_object($sql))
{
    $auslname = $ds-> AuslName;
}
?>
<br><big><font color=#FF0000>Der bisherige Ausleiher war: </font></big>
<?php
echo  "<font color=#0000FF><big>",$auslname, "</font></big>";
?>
</table>
<br>
<form method = "POST" action="namtausch1.php"><br>
<input name="submit" type="submit" value="weiter" class="Button-w"/><p></p>         <!-- Abschicktaste -->
</form>
</form>
<form method="POST" action="namtausch1.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html
<?php  
/*
$ajahr = ausleihjahr in var
$anr = ausleihnumemr in var
$d = variable in function
$date = überganevariable in function
$ds = datenzähler in datenbankabfrage
$eintr = variable in datenbankabfrage
$gid = ID_geraet in geräte
$heut = heute deu
$heuteng = heute engl
$id = id in geräte
$query / $query1 / $resultID / $resultID1 / $sql = variable in datenbankabfrage
$user = aktueller benutzer
$versch = verschiedenes in ausleihe 
*/
?>
