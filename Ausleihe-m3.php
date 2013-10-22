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
function date_mysql2german($date) {                                 //in deutsches Format wandeln
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
function date_mysql2engl($date) {                                   //in englisches Format wandeln
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]); }
?>
<title>Ausleihe Namen w&auml;hlen</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Ausleihe Namen w&auml;hlen
</big></big></big>
<br>
<?php
$l="Ausleihe-m3 ge&ouml;ffnet";
logsch ($l);
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
echo "Benutzer :", $_SESSION['kname'], "<br>";                       //aktueller Benutzer
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut;
?>
<br>
<form name="ausw" action="Ausleihe-m3.php" method="post" >
<big><big><br>
Entleiher ausw&auml;hlen
</big></big><br><br>
<?php
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$query1 = "UPDATE `conf` SET `was` = 0 WHERE `wert` = aktiveseite";             //aktive seite conf auf 0 setzen
$resultID1 = @mysql_query($query1);

$vorh="0";                                                          // vorhanden auf 0
if (isset($_POST['neu']))
{
    switch($_POST['neu'])
    {
        case"eintragen":
            if  ($_POST['ename']== NULL )                           //ist wert leer
            {
            ?> 
            <textarea  cols="110" rows="1" class="text-a" readonly >Kein Name eingegeben</textarea><br/>
            <?php
            }
            else
            {                                                       //sonst
                $ename = $_POST['ename'];
                $sql = mysql_query("SELECT * FROM benutzer ORDER BY Name"); //ist wert schon vorhanden
                while($ds= mysql_fetch_object($sql))
                {
                    $name = $ds->Name ;
                    if ($name  == $ename)
                    {
                        ?> 
                        <textarea  cols="110" rows="1" class="text-a" readonly >Name bereits vorhanden</textarea><br/>
                        <?php ;
                        $vorh="1" ;                                 //vorhanden auf 1
                    }
                 }
                if ($vorh==0)
                {                                                   //bei vorhanden =0 eintragen
                    $query = "INSERT INTO `benutzer` (`name`)VALUES ('$ename')";
                    mysql_query($query);
                    $ew=" in benutzer eingetragen" ;
                    $l=$ename.$ew ;
                    logsch($l);
                }
            }
        break;
        case"auswählen":
            if (!isset($_POST['uausl']))
            {
                ?>
                <textarea  cols="110" rows="1" class="text-a" readonly >Bitte Namen ausw&auml;hlen</textarea><br/>
                <?php ;
            }
            else
            {
            $nam=$_POST['uausl'];
            echo $nam;                                   //Nummer des Namen
            $sql = mysql_query("SELECT * FROM benutzer WHERE id=$nam"); //aus nummer Namen machen
            while($ds= mysql_fetch_object($sql))
            $aname = $ds->Name ;
            $eintr = "INSERT INTO `var` (`user`, `gida`) VALUES ('$aname', -1)";  //Name in var eintragen
            mysql_query($eintr)  ;
            ?><meta http-equiv="refresh" content="0; URL=Ausleihe2.php" /><?php
            }
        break;
        default:
    }
}
?><select name = "uausl" size="20" ><br><?php
$sql = mysql_query("SELECT * FROM benutzer ORDER BY Name");         //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $name = $ds->Name ;
    $pid= $ds->id ;                                                 // aus Datenbank auslesen
    echo "<option value = '$pid'>" .$name. "</option>"  ;           //Dropdown eintragen                                                    //Service
}
//mysql_close($dz);
?>
<input type="submit" name="neu" value="ausw&auml;hlen" class="Button-w"/>
</select>
</form>
<big><big>
weiteren Entleiher eintragen:                                       <!--neuen Benutzer anlegen -->
</big></big>
<form name="eingabe" action="Ausleihe-m3.php" method="post" >
Name, Vorname:     <input type="text" name="ename"/>                 <!-- neuen Namen eingeben -->
<input type="submit" name="neu" value="eintragen"/>
</form>
<form method="POST" action="Ausleihe-m3.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$aname = name in benutzer
$date = übergabevariable in function
$ds = datenzähler
$eintr = variable in datenbankabfrage
$ename = eingegebener name vorname
$ew = text in log eintrag
$heut heute in deu
$l = log eintrag
$nam = ausgewählter wert aus dropdown
$name = name in benutzer
$pid = id in benutzer
$query / $query1 / $resultID1 / $ql = variable in datenbankabfrage
$vorh = bereits vorhanden
*/
?>
