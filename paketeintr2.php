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
<title>Paketeintrag verschiedenes</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Paketeintrag verschiedenes
</big></big></big>
<br>
<?php
function eintr_mysql()                                              //eintragen
{
     if (isset($_POST['versch']))
        {
            $verschein = $_POST['versch'];
            if ($_POST['versch']!== "")
            {
                $eintr = "INSERT INTO `var` (`mang`,`gida`) VALUES ('$verschein', 0)";  //verschiedenes in var eintragen
                mysql_query($eintr);
            }
            else                                                    //wenn nichts eingegeben
            {
               ?>
               <textarea  cols="110" rows="1" class="text-a" readonly >Bitte Bezeichnung eintragen</textarea><br/>
               <?php ;
            anz_mysql();
            }
       }
}
function anz_mysql()                                                //anzeigen
{
    ?>
    <form method = "POST" >
    <table border="1" width="60%">
    <font color="#0000F8"><big><big>verschiedenes</big></big><br></font>
    <tr><th>Teilbezeichnung</th><th>Auswahl</th> </tr>
    <?php
    $sql1 = mysql_query("SELECT * FROM var WHERE gida = 0 ");      //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
    while($ds1 = mysql_fetch_object($sql1))
        {
            $versch = $ds1->mang ;                                  //aus Datenbank var auslesen
            echo "<tr>" ;
            echo "<td>";
            echo $versch,"<br>";
            echo "<td><input type=\"radio\" name=\"lo[]\" value= \"$versch\" />"; // löschhäckche
            echo "</tr>";
        }
            ?>
            </table>
            <?php
}
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut , "<br>", "<br>";
$l="Ausleihe-v1 ge&ouml;ffnet";
logsch ($l);
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$anz=0;                                                             //andere Tasten anzeigen
if (isset($_POST['submit']))
{
    switch($_POST['submit'])
    {
        case"weitere Teile ausleihen":
            eintr_mysql();
            anz_mysql();
            $anz=0;                                                 //andere Tasten anzeigen
        break;
        case"abschließen":                                          //Schritt3
        ?>
        <meta http-equiv="refresh" content="0; URL=paketeintr3.php">
        <?php
        break;
        case"ausgewählte löschen":
            if (isset($_POST['lo']))
                {
                     $lgaida = 0;
                        foreach ($_POST['lo'] as $key => $val)
                        {
                            $sql = mysql_query("SELECT * FROM var "); //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
                            while($ds= mysql_fetch_object($sql))
                            {
                                $lg = $ds->mang ;
//                                echo "+",$lg,"+",$val;
//                                $lga = " ".$lg." " ;                // aus Datenbank var auslesen
                                $lgaid = $ds->id ;                  // aus Datenbank var auslesen
                                if ($lg == $val)
                                {
                                    $lgaida = $lgaid ;
                                }
                            }
                            $query = "DELETE FROM `var` WHERE id=$lgaida "; //Tabelle Wert löschen
                            $resultID = @mysql_query($query);
                            anz_mysql();
                            $anz=1;                                 //andere Tasten anzeigen
                         }
                }
                else
                {
                    ?> 
                    <textarea  cols="110" rows="1" class="text-a" readonly >Bitte ausw&auml;hlen</textarea><br/>
                    <?php ;
                    anz_mysql();
                }
        break;
        case"eintragen":
            eintr_mysql();
            if ($_POST['versch']!== "")
               {
                anz_mysql();
                $anz=1;
               }
               else
               {
                $anz=0;
               }
        break;
        default:
     }
}
if ($anz==0)                                                         //ist noch nichts eingetragen - dann
{
    ?>
    <form action="paketeintr2.php" method = "POST" >
    <br><font color="#0A0AF0"><big>Teil bitte benennen: </big><br></font>  <input type="text" name="versch"><br><br>
    <input type="submit" name="submit" value="eintragen" class="Button-w"/>
    </form>
    <?php
    $sql = mysql_query("SELECT * FROM conf WHERE wert = 'aktiveseite'");            //
    while($ds= mysql_fetch_object($sql))
    {
        $test=$ds->was;
    }
//    echo "+",$test,"+";
    if ($test==0)
    {

        $sql1 = mysql_query("SELECT * FROM var WHERE gida > 0");    //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
        while($ds1 = mysql_fetch_object($sql1))
            {
                $gida = $ds1->gida ;                                //aus Datenbank var auslesen
            }
        if(!isset($gida))
        {
            $query = "TRUNCATE `var`";                              //Tabelle Werte löschen
            $resultID = @mysql_query($query);
        }
        $query1 = "UPDATE `conf` SET `was` = 1 WHERE `wert` = aktiveseite";
        $resultID1 = @mysql_query($query1);
    }
}
else
{
    ?><br><br>
    <input name="submit" type="submit" value="weitere Teile ausleihen" />    <!-- weiter ausleihen -->
    <?php
    $sql="SELECT COUNT(*) AS Anzahl FROM var WHERE gida = 0";
    $result = mysql_query($sql);
    $zeile = @mysql_fetch_array($result);
    $anzahl = $zeile['Anzahl'];
//    echo $anzahl;
    if ($anzahl == 0)                                               //disenable bei keinen werten
        {
        ?>
        <input name="submit" type="submit" value="abschlie&szlig;en" class="Button-w"/>    <!-- abschliessen -->
        <input name="submit" type="submit" value="ausgew&auml;hlte l&ouml;schen" disabled="disabled" />    <!-- löschen -->
        <?php
        }
        else
        {
        ?>
        <input name="submit" type="submit" value="abschlie&szlig;en" class="Button-w"/>    <!-- abschliessen -->
        <input name="submit" type="submit" value="ausgew&auml;hlte l&ouml;schen" />    <!-- löschen -->
        <?php
        }
?>
    </form> <br>
    <?php
}
//mysql_close($ds);
//mysql_close($ds1);

$sql1 = mysql_query("SELECT * FROM var WHERE gida > 0");            //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1 = mysql_fetch_object($sql1))
    {
        $gida = $ds1->gida ;                                        //aus Datenbank var auslesen
    }
if(isset($gida))
{
    ?>
    <form method = "POST" >
    <table border="1" width="80%">
    <font color="#0000F8"><big><big>Ger&auml;te</big></big><br></font>
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
}
?>
<form method="POST" action="paketeintr2.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php
/*
$anz = anzeigen oder nicht
$anzahl = anzahl var gida=0
$ds / $ds1 = Zählervariable
$eintr = variable in datenbankabfrage
$heut = heute deu
$key = variable in array auslesen
$l = log text
$lg = mang in var
$lgaid = id in var
$lgaida = variable zu $lgaid
$query / $query1 / $result / $resultID / $resultID1 / $sql / $sql1 = variable in datenbankabfrage
$test = test aktive seite
$val = variable in array auslesen
$versch = mang in var
$verschein = Texteingabe verschiedenes
$zeile = wieviel zeilen hat var gida=0
*/
?>
