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
function date_mysql2german($date) {
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);}
?>
<title>Inventur</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<a name="anfang"></a>
<big><big><big>
Ger&auml;teliste - bearbeiten (zus&auml;tzliche Ger&auml;te)
</big></big></big><br>
<form name="zusatz" action="Typlisten-ib.php" method="POST" name="zus">
<table border="1" width="90%">

<tr> <th>Typ</th><th>Nummer</th><th>Hersteller</th><th>Bemerkung</th><th>bearbeiten / l&ouml;schen</th></tr>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //Benutzer anzeigen
$heut = date("d.m.Y");  //heutuger Datum
$heuteng= "" . date_mysql2engl($heut) . " \n";
echo $heut;
$l="Typenliste - zus&auml;tzlivhe Ger&auml;te ge&ouml;ffnet / ".$_SESSION['kname'];
logsch ($l);
?><br><a href="#ende">nach unten</a><br><?php

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
    
if (isset($_POST['ausgeber']))
{
    switch($_POST['ausgeber'])
    {                                                       //Gerät löschen
        case"loeschen":
            if (isset($_POST['ausl']))
            {
                if ($_POST['ausgeber'] == "loeschen")                                          //ist senden =1
                {                                                                   // dann
                    if (isset($_POST['ausl']))                                        // ist anzeirn ein array
                    {                                                               //dann
                        $i = 0;
                        foreach ($_POST['ausl'] as $key => $value)
                        {
                            if ($i == 0) $ausgabe= $value;
                            else $ausgabe= ', '.$value;
//                            echo $ausgabe,$i;
                            $i++;
                        }
                    }
                }
                $query = "DELETE FROM `zusatz` WHERE id=$ausgabe ";  //Tabelle Werte löschen
                $resultID = @mysql_query($query);
            }
            else
            {
                ?>
                <font color="#FF0000"><big><big>Bitte w&auml;hlen Sie ienen Eintrag aus</big></big></font><br>
                <?php
            }
        break;
        case"hinzufügen":
            if (isset($_POST['Typ']))
            {
                 $type=$_POST['Typ'];
                 if ($type=="")
                    {
                        ?>
                        <font color="#FF0000"><big><big>Bitte geben Sie einen Typ ein</big></big></font><br>
                        <?php
                    }
            }
            else
            {
            }
            if (isset($_POST['nr']))
            {
                 $nre=$_POST['nr'];
            }
            if (isset($_POST['hersteller']))
            {
                 $herste=$_POST['hersteller'];
            }
            if (isset($_POST['bemerkung']))
            {
                 $beme=$_POST['bemerkung'];
            }
            if ($type!="")
            {
                $query = "INSERT INTO `zusatz` (`id`, `typ`, `regnr` ,`hersteller`, `bemerkung`) VALUES (NULL , '$type', '$nre', '$herste', '$beme');";
                mysql_query($query);
            }
        break;
        default:
    }
}
$sql = mysql_query("SELECT * FROM zusatz ORDER BY typ, regnr");    //Tabelle gereate auswählen
while($ds= mysql_fetch_object($sql))
{
    $gid = $ds->id ;
    $typ = $ds->typ ;                                               // aus Datenbank auslesen
    $regnr= $ds->regnr ;
    $hersteller= $ds->hersteller ;
    $bemerkung= $ds->bemerkung;
    echo "<td>",$typ,"</td>";
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
    echo "<td><input type=\"radio\" name=\"ausl[]\" value= \" $gid \" />";
    echo "</td>";
    echo "</tr>";
}
?>
</table>
<?php  

$sql="SELECT COUNT(*) AS Anzahl FROM zusatz";
$result = mysql_query($sql);
$zeile = @mysql_fetch_array($result);
$anzahl = $zeile['Anzahl'];
if ($anzahl == 0)
{
    ?>
    <br><input type="submit" name="ausgeber" value="loeschen" disabled="disabled" />  ausgew&auml;hlte Technik l&ouml;schen  <!-- löschbutton -->
    <?php
}
else
{
    ?>
    <br/><input type="submit" name="ausgeber" value="loeschen"/>  ausgew&auml;hlte Technik l&ouml;schen  <!-- löschbutton -->
    <?php
}
?>
<br/><br/>
Typ: <input type="text" name="Typ"/>
Ger&auml;tenummer: <input type="text" name="nr" size="4"/>
Hersteller: <input type="text" name="hersteller"/>
Bemerkung: <input type="text" name="bemerkung"/>
<br/><br/><input type="submit" name="ausgeber" value="hinzuf&uuml;gen" class="Button-w"/>  Technik hinzuf&uuml;gen  <!-- löschbutton -->
</form>
<a name="ende"></a>
<a href="#anfang">nach oben</a><br/>
<form method="POST" action="Typlisten-ib.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php
/*
anz = übergabevariable in functionen
anza = anz
$ausgel = ausgeliehen in geräte
$bemerkung = bemerkung in geräte
$bis = bis in res
$d = variable in functionen
$date = übergabevariable in functionen
$datets / $datets1 / $datets2 = variable in functionen
$datum = übergabevariable in functionen
$ds / $ds1 = datenzähler
$format = übergabevariable in functionen
$gid = id in geräte
$hersteller = hersteller in geräte
$heut = heute deu
$heute = heute engl
$jahr = variable in functionen
$l = log eintrag
$monat = variable in functionen
$nam = name in res
$query / $resultID = variable in datenbankabfrage
$regnr = regnummr in geräte
rep_notw = reperatur notwendig in geräte
$res = res in geräte
$sql / $sql1 = variable in datenbankabfrage
$tag = variable in function
$tuef = tüv in geräte engl
$tuefeng = $tuef
$tuevbe = tuev - 30 tage
$typ = typnummer in geräte
$typan = typ text in typ
$von = von in res deu - engl
*/
?>
