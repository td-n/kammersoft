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
function tabelle_oeffnen() {
?>
<table border="0" width="100%" cellpadding="0">
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
$l="Inventur druck ge&ouml;ffnet / ".$_SESSION['kname'];
logsch ($l);

if (isset($_POST['submit']))                                        //welche taste wurde gedrückt
{
    switch($_POST['submit'])
    {
        case"zur Übersicht":
            ?>
            <meta http-equiv="refresh" content="0; URL=start1.php" />
            <?php
        break;
        default:
    }
}

    $sql1 = mysql_query("SELECT * FROM var WHERE gida = -3");   //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
    while($ds = mysql_fetch_object($sql1))
        {
            $jahrw = $ds->rdat ;
        }
    $si=1;
//    $zu=0;
    echo "<big>","Inventur: ", $jahrw,"</big>";
    tabelle_oeffnen() ;
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
    //  echo $ijahr;
        echo "<tr>";
        $zi=1;                                          //seitenumbruch
        while($zi<100)
        {
            $zi40=$zi*28;

            if (!$zi<$si)
            {
                if ($si==$zi40)
                {
//                  $zu=1;

                    ?>
                    </table>
                    <p style="page-break-before:always" ></p>
                    <b><?php echo "Seite: ",$zi+1, "  /  Inventur: ", $jahrw," / ", $heut; ?></b>
                    <table border="0" width="100%" cellpadding="0">
                    <?php
//                    $zu=0;
                }
            }

        $zi=$zi+1;
        }                                               //seitenumbruch - ende

        
        if ($typ!=="Ende")
        {
            echo "<td>",$iid,"</td>";
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
            ?>
            </tr></table><table><tr>
            <?php 
            echo "<td>",$iid,"</td>";
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
    ?></tr></table><?php
$sql = mysql_query("SELECT * FROM conf WHERE wert='vereinvorsitzende' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $vorsitz=$ds->was;
}
$sql = mysql_query("SELECT * FROM conf WHERE wert='technikverantwortliche' ");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $tkchef=$ds->was;
}
?>
<br><br>
<br>
<b>Unterschriften: <br></b>
Datum: <?php echo $heut  ?><br>
<br><br>
<table border="0" width="100%" >
<tr>
<hr>
<td><b>Durchf&uuml;hrende</b></td>
<td>Unterschrift  /  in Druckbuchstaben</td>
<td>Unterschrift  /  in Druckbuchstaben</td>
</tr>
</table>
<br><br>
<table border="0" width="100%" >
<tr>
<hr>
<td><b>Kenntnisnahme</b></td>
<td>Vereinsvorsitzende: <?php echo $vorsitz ?></td>
<td>Technikverantwortliche: <?php echo $tkchef ?></td>
</tr>
</table>
<br><br>
<form method = "POST" action="invhist-p.php">
<input type="button" value=" drucken " onClick="javascript:window.print()">
<input name="submit" type="submit" value="zur &Uuml;bersicht" /><font color="#0000FF"><big><br></font></big>       <!-- Abschicktaste -->
</form>
</body>
</html>
