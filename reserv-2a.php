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
<title>Ausleihe 3. Schritt</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Ausleihe
</big></big></big>
<br>
<?php
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");                                              //heutuger Datum
echo $heut , "<br>", "<br>";
?>
<form name="ausw" action="reserv-2a.php" method="post" >
<big><big>
Ausleihgrund ausw&auml;hlen
</big></big><br><br>
<?php
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

$vorh="0";                                                          // vorhanden auf 0
if (isset($_POST['neu']))
{
    switch($_POST['neu'])
    {
        case"eintragen":
            if  ($_POST['ename']== NULL )                           //ist wert leer
            {
                ?> 
                <textarea  cols="110" rows="1" class="text-a" readonly>Keinen Ausleihgrund eingegeben</textarea><br/>
                <?php
            }
            else
            {                                                       //sonst
                $ename = $_POST['ename'];
                $sql = mysql_query("SELECT * FROM auslgrund ORDER BY auslgr"); //ist wert schon vorhanden
                while($ds= mysql_fetch_object($sql))
                {
                    $name = $ds->auslgr ;
                    if ($name  == $ename)
                    {
                        ?> 
                        <textarea  cols="110" rows="1" class="text-a" readonly>Grund bereits vorhanden</textarea><br/>
                        <?php ;
                        $vorh="1" ;                                 //vorhanden auf 1
                    }
                 }
                if ($vorh==0)
                {                                                   //bei vorhanden =0 eintragen
                    $query = "INSERT INTO `auslgrund` (`auslgr`)VALUES ('$ename')";
                    mysql_query($query);
                }
            }
        break;
        case"auswählen":
            if (!isset($_POST['uausg']))
            {
                ?>
                <textarea  cols="110" rows="1" class="text-a" readonly>Bitte ausw&auml;hlen</textarea><br/>
                <?php ;
            }
            else
            {
                $nam=$_POST['uausg'];                              //Nummer des Namen
                $sql = mysql_query("SELECT * FROM auslgrund WHERE id=$nam"); //aus nummer Namen machen
                while($ds= mysql_fetch_object($sql))
                $aname = $ds->auslgr ;
                $eintr = "INSERT INTO `var` (`user`, `gida`) VALUES ('$aname', -2)";  //Name in var eintragen
                mysql_query($eintr)  ;
                ?><meta http-equiv="refresh" content="0; URL=reserv-3.php" /><?php
            }
        break;
        case"löschen":
            if (!isset($_POST['uausg']))
            {
                ?>
                <textarea  cols="110" rows="1" class="text-a" readonly>Bitte ausw&auml;hlen</textarea><br/>
                <?php ;
            }
            else
            {
                $uausg = $_POST['uausg'];
                $sql = mysql_query("SELECT * FROM auslgrund WHERE id=$uausg"); //Tabelle benutzer auswählen nur ausgewähltes Gerät 1 Wert
                while($ds= mysql_fetch_object($sql))
                {
                    $usera = $ds->auslgr ;                          // aus Datenbank var auslesen
                }
                $query = "DELETE FROM `auslgrund` WHERE id=$uausg "; //Tabelle Wert löschen
                $resultID2 = @mysql_query($query);
                $ew=" in ausleihgrund geloescht" ;
                $l=$usera.$ew ;
                logsch($l);
             }
        break;
        default:
    }
}
?><select name = "uausg" size="10" ><br><?php
$sql = mysql_query("SELECT * FROM auslgrund ORDER BY auslgr");      //Tabelle user auswählen
while($ds= mysql_fetch_object($sql))
{
    $auslgr = $ds->auslgr ;
    $pid= $ds->id ;                                                 // aus Datenbank auslesen
    echo "<option value = '$pid'>" .$auslgr. "</option>"  ;         //Dropdown eintragen                                                    //Service
}
//mysql_close($dz);
?>
<input type="submit" name="neu" value="ausw&auml;hlen" class="Button-w"/>
<input type="submit" name="neu" value="l&ouml;schen"/><br><br>
</select>
</form>
<big><big>
neuen Ausleihgrund eintragen:                                       <!--neuen Benutzer anlegen -->
</big></big><br><br>
<form name="eingabe" action="reserv-2a.php" method="post" >
Ausleihgrund:     <input type="text" name="ename">                  <!-- neuen Namen eingeben -->
<input type="submit" name="neu" value="eintragen">
</form>
<form method="POST" action="reserv-2a.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
<?php  
/*
$aname / $auslgr = ausleihgrund in ausleihgrund
$ds = datenzähler
$eintr = variable in datenbankabfrage
$ename = einagbe ausleihgrund
$ew = log text
$heut = heute deu
$l = log eintrag
$nam = auswahl ausleihgrund
$name = ausleihgrund in ausleihgrund
$pid = id in ausleihgrund
$query / $resultID2 / $sql = variable in datenbanlabfrage
$uausg = ausgewählter eintrag in löschen
$usera = ausleihgrund in ausleihgrund löschen
$vorh = variable vorhanden   
*/
?>
