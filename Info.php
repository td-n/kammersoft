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
function date_mysql2german($date)                                   //in deutsches Format wandeln
{
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date)                                     //in datenbankformat wandeln
{
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); 
}
function date2timestamp($datum) {                                   //timestamp erstellen
    list($tag, $monat, $jahr) = explode(".", $datum);
    $jahr = sprintf("%04d", $jahr);
	$monat = sprintf("%02d", $monat);
    $tag = sprintf("%02d", $tag);
	return(mktime(0, 0, 0, $monat, $tag, $jahr));  
}
?>
<title>Info</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
M&auml;ngelliste
</big></big></big>
<br>
<br>
<?php 
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //aktueller Benutzer
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut,"<br>";
$l="Info ge&ouml;ffnet";
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
if (isset ($_POST['zu1']))
{
    ?><meta http-equiv="refresh" content="0; URL=info.php" /><?php ;
}
if (isset ($_POST['zu2']))
{
    ?><meta http-equiv="refresh" content="0; URL=info.php" /><?php ;
}

$sql1 = mysql_query("SELECT * FROM conf WHERE wert='tuevflasche'");    //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1= mysql_fetch_object($sql1))
{
    $tueff=$ds1->was;
}
$sql1 = mysql_query("SELECT * FROM conf WHERE wert='tuevregler'");    //Tabelle var auswählen nur ausgewähltes Gerät 1 Wert
while($ds1= mysql_fetch_object($sql1))
{
    $tuefr=$ds1->was;
}

if (!isset($_POST['senden']))                                       //wenn nicht existiert
   {
    $_POST['senden'] = '';                                          //wurde bereits gesendet?
    $ausgabe = 0;                                                   //Variablen bekannt machen
   }
if ($_POST['senden'] == 1)                                          //ist senden =1 
{                                                                   // dann 
    if (isset($_POST['lo']))                                        // ist anzeirn ein array
    {                                                               //dann
        $i = 0;
        foreach ($_POST['lo'] as $key => $value) 
        {
            if ($i == 0) $ausgabe= $value;
            else $ausgabe= ', '.$value;
//            echo $ausgabe,$i;
            $i++;
        } 
        if ($ausgabe != 0) 
        { 
            $query = "UPDATE `geraete` SET `Rep_notw` = '0' ,`ausgeliehen` = '0'  WHERE `geraete`.`ID` =$ausgabe";  //Reparatur notwendig in Geräte auf 0 setzen
            $resultID = @mysql_query($query);

            $query = "UPDATE `info` SET `erl` = '1' WHERE ID_Geraet=$ausgabe";  //Mängel aus erledigt setzen
            $resultID = @mysql_query($query);

            $ew="=Geraete-ID eintrag reparatur notwendig wurde erledigt" ;
            $l=$value.$ew ;
            logsch($l);
//            echo '<p>'.$ausgabe.'</p>';
//            echo '<p><a href="info.php">anzeigen bzw weitere l&ouml;schen</a></p>';
//            echo '<p><a href="start.php">zur &Uuml;bersicht</a></p>';
            ?>
            <form method="POST" action="Info.php">
            <input name="zu1" type="submit" value="anzeigen bzw weitere l&ouml;schen" />
            </form>
            <form method="POST" action="Info.php">
            <input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
            </form>
            <?php 
        }
      } 
      else 
      { 
            ?><p>
            <textarea  cols="110" rows="1" class="text-a" readonly>Es wurde kein M&auml;ngel zum l&ouml;schen ausgew&auml;hlt!</textarea><br/>
            <form method="POST" action="Info.php">
            <input name="zu2" type="submit" value="noch einmal versuchen" class="Button-w"/><br/>
            <input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
            </form>
            <?php ;
      }
} 
else 
{ 
    ?>
    <form action="info.php" method="POST">
    <input type="hidden" name="senden" value="1"/>                  
    <table border="1" width="90%" summary="M&auml;ngeltabelle">
    <tr> <th>Typ</th><th>Nummer</th><th>Bemerkung</th><th>Rep. notw. </th><th>letzter T&Uuml;V</th><th>ausgeliehen/gesperrt</th><th>M&auml;ngel</th><th>R&uuml;cknehmer</th><th>l&ouml;schen</th> </tr>
    <?php
    $heuteng= "" . date_mysql2engl($heut) . " \n";
    $sql = mysql_query("SELECT * FROM geraete WHERE `Rep_notw` =1");//Tabelle gereate auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $gid=$ds->ID;                                               // Geräte ID
        $typ = $ds->Typ ;                                           // aus Datenbank auslesen
        $regnr=$ds ->RegNR ;                                       
        $bemerkung=$ds->Bemerk;	                                   
        $rep_notw= $ds->Rep_notw ;                                 
        $tuef=$ds->TUEV;	                                       
        $ausgel=$ds->ausgeliehen;                                  
        $tuefeng=$tuef ;
        $query="SELECT Maengel FROM info WHERE ID_Geraet=$gid AND erl=0 "; // Mängel auslesen
//        echo $gid,"+";
        $resultID=@mysql_query($query);
        $maengan= mysql_result($resultID,0);
//        $maengan= $ds1->Maengel;
        $query="SELECT Rueckgeber FROM info WHERE ID_Geraet=$gid AND erl=0 "; // Namen Rücknehmer dazu auslesen
        $resultID=@mysql_query($query);
        $rueckga= mysql_result($resultID,0);
        $query    = "SELECT kname FROM user WHERE ID=$rueckga";      //aus Rückgeber-ID Rückgeber machen
        $resultID = @mysql_query($query);                           //aus Rückgeber-ID Rückgeber machen
        $rueckgan = mysql_result($resultID,0);
        //echo $gid;
        $tuefeng=$tuef ;
        if ($tuef == '0000-00-00')     
        {
             $tuef= "kein Eintrag";                                 //Wert 0 abblocken
        }           
        else 
        {
            $tuef= "" . date_mysql2german($tuef) . " \n";           // Formatwandlung
        }                                                           //Wert 0 abblocken
        $query    = "SELECT Typ FROM typ WHERE ID=$typ";            //aus Typ-ID Typ machen
        $resultID = @mysql_query($query);                           //aus Typ-ID Typ machen
        $typan = mysql_result($resultID,0);
        echo "<td>";                                                //ausgeben
        echo $typan,"<br>";
        echo "</td>";
        echo "<td>";
        echo $regnr,"<br>";
        echo "</td>";
        echo "<td>";
        echo $bemerkung,"<br>";
        if ($rep_notw == "1")                                       //bei Reparatur notwendig auf rot setzen
        {
            ?>
            <td bgcolor =#FF6F6C >
            <input type="checkbox" name="anz"  value= "2" checked disabled />
            </td>
            <?php
        }
        else 
        {
            ?>
            <td>
            <input type="checkbox" name="anze" disabled  />
            </td>
            <?php
        }

        if (($typ==1) or ($typ==5))
        {
            $tuefl=$tuefr*86400;                                              //wenn regler dann tüvzeit für regler und computer
        }
        if (($typ==3) or ($typ==10))
        {
            $tuefl=$tueff*86400;                                              //wenn flasche dann tüvzeit für flasche
        }
        if ($tuef== "kein Eintrag")                                 // wenn TÜV Termin vorbei auf rot setzen
        {
            ?> <td bgcolor =#FFFF00 > <?php
        }
        elseif(date2timestamp($heut)>date2timestamp($tuef)+$tuefl)
        {
            ?> <td bgcolor =#FF6F6C > <?php
        }
        else 
        {
            ?> <td bgcolor =#3FFF00 > <?php
        } ;
        echo $tuef,"<br>";
        if (($ausgel == "1") OR ($ausgel == "3"))                                        //bei ausgeliehen auf rot setzen
        {
            ?>
            <td bgcolor =#FF6F6C >
            <input type="checkbox" name="an" checked disabled />
            </td>
            <?php
        }
        else 
        {
            ?>
            <td>
            <input type="checkbox" name="anzei" disabled />
            </td>
            <?php
        }
        echo "<td>";
        echo $maengan,"<br>";
        echo "</td>";
        echo "<td>";
        echo $rueckgan,"<br>";
        echo "</td>";
        echo "<td><input type=\"radio\" name=\"lo[]\" value= \" $gid \" />"; // löschhäckche
        echo "</td>";
        echo "</tr>";
    }
//    mysql_close($dz);
    ?>
    </table>
<?php
$sql="SELECT COUNT(*) AS Anzahl FROM geraete WHERE `Rep_notw` =1 "; //bei leer deaktivieren
$result = mysql_query($sql);
$zeile = @mysql_fetch_array($result);
$anzahl = $zeile['Anzahl'];
if ($anzahl == 0)
     {
     ?>
     <p><input type="submit" value="L&ouml;schen" name="submit" disabled="disabled" /><font color="#FF0000"> ausgew&auml;hlte M&auml;ngelinfo's werden ohne R&uuml;ckfrage gel&ouml;scht</font></p> 
     <?php
     }
     else
     {
     ?>
     <p><input type="submit" value="L&ouml;schen" name="submit"  /><font color="#FF0000"> ausgew&auml;hlte M&auml;ngelinfo's werden ohne R&uuml;ckfrage gel&ouml;scht</font></p>
     <?php
     }
?>
    </form> 
    <form method="POST" action="Info.php">
    <input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
    </form>
    <?php 
}
?>
</body>
</html>
<?php  
/*
$anzahl = anzahl der einträge
$ausgabe = wert des arrayeintrages
$ausgel = ausgeliehen aus geräte
$bemerkung = bemerkung aus geräte
$d = variable im functionen
$date = übergabevariable an function
$ds = datenzähler
$ew = text log eintrag
$gid = id in geräte
$heut = heute in deu
$heuteng = heute in engl
$i = zähler lo-array
$key = array-variable
$l = logbuch eintrag
$maengan = ausgewählter mängeleintrag in info
$query = variable in datenbankabfrage
$regnr = regnr in geräte
$rep_notw = rep notwendig in geräte
$result / $resultID = variable in datenbankabfrage
$rueckga rückgabetermin engl
$rueckgan = rücknehmer
$sql = variable in datenbankabfrage
$tuef /$tuefeng = tüvtermin in geräte engl
$typ = typnummer in geräte
$typan = typ name in typ
$value = wert in array lo
$zeile = zeile in anzahl in geräte
*/
?>
