<html>
<head>
<?php include ("#authenticated.inc.php"); ?>
<?php include ("#mysql.inc.php"); ?>
<?php include ("#log.inc.php") ?>
<?php include ("style.php") ?>
<?php
if ($_SESSION['lesen']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten-all.php" /><?php
}
?>
<?php
function date_mysql2german($date) {
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
?>
<title>R&uuml;ckgabe</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
R&uuml;ckgabe 2. Schritt<br>
</big></big></big>
<?php
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
echo "Benutzer :", $_SESSION['kname'], "<br>";                      //aktueller Benutzer
$user =  $_SESSION['kname'];
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut,"<br>";
$heuteng= "" . date_mysql2engl($heut) . " \n";
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}
if (isset($_POST['anztg']))
{
    $anztg=$_POST['anztg'];
}
else
{
    $anztg="0";
}
if (isset($_POST['neu']))
{
    switch($_POST['neu'])
    { 
        case"ausgewählte Geräte zurücknehmen":
            if (isset($_POST['z']))
                {
                    foreach ($_POST['z'] as $key => $val1)
                        {
                            $val=str_replace(' ','',$val1);         //Leerzeichen entfernen
                            $sql1 = mysql_query("SELECT * FROM var  ");      
                            while($ds = mysql_fetch_object($sql1))
                                {
                                    $ausname= $ds->user;            //entleiher
                                }
                            $sql = mysql_query("SELECT * FROM ausleihe WHERE abgeschlAusleihe=0 AND ID=$val1");                           //Tabelle benutzer auswählen nur ausgewähltes Gerät 1 Wert
                            while($ds= mysql_fetch_object($sql))
                                {
                                    $ride = $ds->ID ;                //id in ausleihe  aus Datenbank var auslesen
                                    $gide = $ds->IDGeraet;           //geräte-id in ausleihe
                                    $name = $ds->AuslName;           // für nächste seite übergeben noch zu machen
                                    $versch=$ds->verschiedenes;
                                }
//                                        echo $ride,":ride<br>";
                                        $ridex=str_replace(' ','',$ride);     //Leerzeichen entfernen
//                                        echo $ridex,":ridex<br>"; 
//                                        echo $gide,":gide<br>";
//                                       echo $val,":val<br>";
//                                       echo $key,":key<br>";
//                                        echo $name,":name<br>";
//                                        echo $num,":num<br>";
//                                        echo $num1,":num1<br>";
//                                        echo $ausname,":ausname<br>";
//                                        $anztgarray=$_POST['anztg'];
//                                        $anztgarr=$anztgarray[$key];
//                                        echo $anztgarr,"+++";
                                        $sql1 = mysql_query("SELECT * FROM geraete WHERE ID=$gide "); //Tabelle gereate auswählen
                                        while($ds2= mysql_fetch_object($sql1))
                                        {
                                            $typ = $ds2->Typ ;                //typ auslesen
                                            $anztgg = $ds2->anz_tauchg;       // Anzahl TG aus Datenbank auslesen
                                            $gestg= $ds2->ges_tauchg;         //gesamttauchgänge
                                        }
                                $query = "UPDATE `ausleihe` SET `abgeschlAusleihe` = 1 WHERE `ausleihe`.`ID` = '$ridex' ";  //abgeschlAusleihe in ausleihe auf 1 setzen
                                $resultID = @mysql_query($query);
                                $query = "UPDATE `ausleihe` SET `Datum_rueck` = '$heuteng' WHERE `ausleihe`.`ID` = '$ridex' ";  //Rückgabedatum in ausleihe setzen
                                $resultID = @mysql_query($query);
                                $query = "UPDATE `ausleihe` SET `Ruecknehmer` = '$user' WHERE `ausleihe`.`ID` = '$ridex' ";  //Rückgabedatum in ausleihe setzen
                                $resultID = @mysql_query($query);
                                if ($versch=="")
                                {
                                    $query = "UPDATE `geraete` SET `ausgeliehen` = 0 WHERE `geraete`.`ID` = '$gide' ";  //ausgeliehen in geräte auf 0 setzen
                                    $resultID = @mysql_query($query);
                                }
                                $sql = mysql_query("SELECT * FROM ausleihe WHERE `ausleihe`.`AuslName`='$ausname' AND `ausleihe`.`abgeschlAusleihe`=0 ");                           //wenn kein eintrag mehr ist
                                $num2 = mysql_num_rows($sql);
//                                echo $num2,":num2<br>";
                                if ($num2 == 0)
                                {
                                        echo "<big><big>R&uuml;ckgabe komplett</big></big><br>" ;                                  
                                        ?>
                                        <form method="POST" action="Rueckgabe2.php">
                                        <input name="neu" type="submit" value="zur&uuml;ck" />
                                        </form>
                                        <?php ;
                                }
                        }
                   if (isset ($_POST['anztg']))
                      {
                        foreach ($_POST['anztg'] as $key => $val2)
                        {
//                            echo "+",$key,"->";
//                            echo $val2,"+","<br>";
                            if ($val2 !=="");
                            {
                                $aanztge=$val2;
                                $sql1 = mysql_query("SELECT * FROM geraete WHERE ID=$key "); //Tabelle gereate auswählen
                                while($ds2= mysql_fetch_object($sql1))
                                {
                                    $typ = $ds2->Typ ;                //typ auslesen
                                    $anztg = $ds2->anz_tauchg;       // Anzahl TG aus Datenbank auslesen
                                    $gestg= $ds2->ges_tauchg;         //gesamttauchgänge
                                }
                                 $anztg=$anztg+$aanztge;
                                 $gestg=$gestg+$aanztge;
                                 $query = "UPDATE `geraete` SET `anz_tauchg` = '$anztg', `ges_tauchg` = '$gestg' WHERE `geraete`.`ID` = '$key' ";
                                 $resultID = @mysql_query($query);
                             }
                        }
                      }  
                }
                else
                {
                    ?>
                    <textarea  cols="110" rows="1" class="text-a" readonly>Sie haben nichts ausgew&auml;hlt</textarea><br/>
                    <?php 
                }
        ?><meta http-equiv="refresh" content="3; URL=Rueckgabe.php" /><?php ;
        break;
        case"weiter":
            ?>
            <form  action="Rueckgabe3.php" method="POST" name="form1">
            <p> An welchen Tag soll die Technik zur&uuml;ckgegeben werden?  </p>
            <?php
    $rdatd= date('d.m.Y', strtotime('+7 day'));
//    echo $rdatd;
    $sep=".";
    $format="dmY";
         $pos1    = strpos($format, 'd');
        $pos2    = strpos($format, 'm');
        $pos3    = strpos($format, 'Y');
        $check    = explode($sep,$rdatd);
    $day=$check[$pos1];
    $mont=$check[$pos2];
    $year=$check[$pos3];
//    echo "<br>",$day,"<br>", $mont,"<br>", $year;
    $hl = (isset($_POST["hl"])) ? $_POST["hl"] : false;
    if(!defined("L_LANG") || L_LANG == "L_LANG")
    {
    	if($hl) define("L_LANG", $hl);
    	// You need to tell the class which language do you use.
    	// L_LANG should be defined as en_US format!!! Next line is an example, just put your own language from the provided list
    	else define("L_LANG", "de_DE"); // Greek example
    }
    //get class into the page
    require_once('classes/tc_calendar.php');
    //instantiate class and set properties
    $myCalendar = new tc_calendar("date3", true);
    $myCalendar->setIcon("images/iconCalendar.gif");
    $myCalendar->setDate($day, $mont, $year);
    $end= date("Y");
    $end=$end +1;
    $myCalendar->setYearInterval(2010, $end);
    //output the calendar
    $myCalendar->writeScript();

            ?><br><br>
            <input type="submit" name="eintr" value="eintragen" class="Button-w"/>
            </form>
                        
            <?php            
//            $query = "DELETE FROM `var` WHERE 1 ";                //Tabelle var Werte löschen
//            $resultID = @mysql_query($query);
        break;
        case"zurück zur Auswahl":
            ?><meta http-equiv="refresh" content="0; URL=Rueckgabe.php" /><?php ;
        break;
        default:
    }    
}
//mysql_close($dz);
?>
<br>
<form method="POST" action="Rueckgabe2.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$anztg / $anztga = anzahl tauchgänge
$anztgg = anzahl tauchgänge aus datenbank
$ausname = ausleihname in user
$check / $d= variable in function
$date = übergabevariable in function
$day / $mont / $year = variable für calendar
$ds / $ds2 = variable für datenbankabfrage
$end = endejahr calendar
$format = variable datumsformat
$gid = geräte id in ausleihe
$gide = $gid
$heut = heute deu
$heuteng = heute engl
$hl = sprachauswahl un calendar
$key = variable in array abfrage
$nam = ausleihname in ausleihe
$name = $nam
$num2 = anzahl der offenen rückgaben
$pos1 / $pos2 / $pos3 = variable in function
$query / $resultID = variable in datenbankabfrage
$rdatd = heute + 7 tage
$rid = id in ausleihe
$ride = $rid
$ridex = $ride mit komma
$sep = variable seperator
$sql / $sql1 = datenbankvariable
$typ = typ nummer in ausleihe
$user = angemeldeter user
$val / $val1 = variable in arrayabfrage
$ver / $versch = verschiedenes in ausleihe
   
*/
?>
