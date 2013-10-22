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
if ($_SESSION['schreiben']==0)
{
    ?><meta http-equiv="refresh" content="0; URL=forbitten.php" /><?php
}
?>
<title>User bearbeiten</title>
</head>
<big><big><big>
Techniker anzeigen, hinzuf&uuml;gen und l&ouml;schen  <br>
</big></big></big>
<?php
echo "Benutzer :", $_SESSION['kname'], "<br>";
$heut = date("d.m.Y");  //heutuger Datum
echo $heut;
$l="Ausgeber ge&ouml;ffnet / ".$_SESSION['kname'];
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
    
if (isset($_POST['auswahl']))
{
    if (isset ($_POST['anzeil']))                                   //löchen gedrückt ?
    {
        $les="1";
    }
    else
    {
        $les="0";
    }
    if (isset ($_POST['anzeis']))                                   //schreiben gedrückt?
    {
        $schrei="1";
    }    
    else
    {
        $schrei="0";
    }
//    echo $les;                                                    //Service
//    echo $schrei;                                                 //Service
    $sql = mysql_query("SELECT * FROM var ");                       //Variable auslesen
    while($ds= mysql_fetch_object($sql))
    {
        $uid = $ds->uid ;                                           //aus Datenbank var auslesen
    }
    $sql = mysql_query("SELECT * FROM user WHERE ID=$uid");         //Tabelle user auswählen
    while($ds= mysql_fetch_object($sql))
    {
        $nam = $ds->Name ;                                          //aus Datenbank var auslesen
    }
//    echo $nam;                                                    //Service

    $query = "TRUNCATE `var`";                                      //Tabelle Werte löschen
    $resultID = @mysql_query($query);

    $query = "UPDATE `user` SET `lesen` = $les WHERE `user`.`ID` =$uid";  //lesen in user nach vorgabe setzen
    $resultID = @mysql_query($query);
    $query = "UPDATE `user` SET `schreiben` = $schrei WHERE `user`.`ID` =$uid";  //schreiben in user nach vorgabe setzen
    $resultID = @mysql_query($query);
    $ew="User-Rechte neu gesetzt: lesen= " ;                        //log schreiben
    $ew1=" schreiben= ";
    $ew2=" bei ";
    $ew3=" von ";
    $anw= $_SESSION['kname'];
    $l=$ew.$les.$ew1.$schrei.$ew2.$nam.$ew3.$anw ;
    logsch($l);
}
else
{
    if (isset($_POST['ausgeber']))
    {
        switch($_POST['ausgeber'])
        {
            case"bearbeiten":                                       //bearbeiten
                if (isset($_POST['b']))
                {
                    foreach ($_POST['b'] as $key => $val)
                    {
                        $sql = mysql_query("SELECT * FROM user WHERE ID=$val");   //Tabelle user auswählen nur ausgewähltes user
                        while($ds= mysql_fetch_object($sql))
                        {
                            $bid= $ds->ID;
                            $nameb= $ds->Name ;
                            $lesenb= $ds->lesen ;
                            $schreibenb= $ds->schreiben ;
                            if ($nameb=="admin" || $nameb=="Passwort")    //user und Passwort abblocken
                            {
                                ?> <font color="#FF0000"><big><big><br><br>Benutzerrechte des Users k&ouml;nnen aus Sicherheitsgr&uuml;nden nicht ge&auml;ndert werden</big></big></font> <?php
                            }
                            else
                            {
                                ?><form name="bearbeiten" action="Ausgeber.php" method="post" >
                                <table border="1" >
                                <tr> <th>Name</th><th>Techniker</th><th>Admin</th></tr><?php
                                echo "<td>";                               //ausgeben
                                $eintr = "INSERT INTO `var` (`uid`)VALUES ('$bid')";         //uid in var eintragen
                                mysql_query($eintr);
                                echo $nameb, "<br>";
                                if ($lesenb == "1")                 //checkboxen schreiben                             
                                    {
                                        ?>
                                        <td>
                                        <input type="checkbox" name="anzeil" checked  />   
                                        <?php
                                    }
                                else
                                    {
                                        ?>
                                        <td>
                                        <input type="checkbox" name="anzeil" />
                                        <?php
                                    }
                                if ($schreibenb == "1")                    
                                    {
                                        ?>
                                        <td>
                                        <input type="checkbox" name="anzeis" checked  />
                                        <?php
                                    }
                                else
                                    {
                                        ?>
                                        <td>
                                        <input type="checkbox" name="anzeis" />
                                        <?php
                                    }
                            }  
                        }
                        ?>
                        </table>
                        <?php 
                        if ($nameb=="admin" || $nameb=="Passwort")  //übernehmen ausblenden
                        {
                            ?>
                            <br><input type="submit" name="auswahl" value="&uuml;bernehmen" disabled="disabled">  ausgew&auml;hlten Techniker Rechte &auml;ndern  <!-- button -->
                            <?php
                        }                                                                                 
                        else
                        {
                        ?>
                            <br><input type="submit" name="auswahl" value="&uuml;bernehmen">  ausgew&auml;hlten Techniker Rechte &auml;ndern  <!-- button -->
                        <?php
                        }
                        ?>
                        </tr>
                        </table>
                        </form>
                        <?php
                    }
                }
                else
                {
                    ?> <font color="#FF0000"><big><big><br><br>bitte Techniker ausw&auml;hlen</big></big></font> <?php ;
                }
                break;
            case"loeschen":                                         //löschen
                if (isset($_POST['b']))
                {
                    foreach ($_POST['b'] as $key => $val)
                    {
                        $query    = "SELECT Name FROM user WHERE ID=$val";       
                        $resultID = @mysql_query($query);                       
                        $namanz = mysql_result($resultID,0);                    
//                        echo $namanz;                             //Service
                            if ($namanz=="admin" || $namanz=="Passwort")  //user und Passwort abblocken
                        {
                            ?> <font color="#FF0000"><big><big><br><br>Dieser Eintrag kann aus Sicherheitsgr&uuml;nden nicht gel&ouml;scht werden</big></big></font> <?php   
                        }
                        else
                        {
                        $query = "DELETE FROM `user` WHERE id=$val ";   //user in var löschen
                        $resultID = @mysql_query($query);
                        $ew=" in user geloescht" ;
                        $l=$namanz.$ew ;
                        logsch($l);
                        }
                    }       
                }
                else
                {
                    ?> <font color="#FF0000"><big><big><br><br>bitte Techniker ausw&auml;hlen</big></big></font> <?php ;
                }
                break;
            case"eintragen":                                        //eintragen
                $vorh="0";
                if (isset($_POST['ename']))                         //mit POST wird übergeben
                    {
                        if  (($_POST['ename']== NULL ) or  ($_POST['evname']== NULL ))                  //ist wert leer
                        {
                            ?> <font color="#FF0000"><big><big><br><br>Kein Namen eingegeben</big></big></font> <?php   //dann
                        }
                        else
                        {                                           //sonst
                            $ename = $_POST['ename'];               //Anmeldename
                            $evname = $_POST['evname'];             //vollständiger Name
                            //echo $ename;                          //Service
                            $sql = mysql_query("SELECT * FROM user ORDER BY Name"); //ist wert schon vorhanden
                            while($ds= mysql_fetch_object($sql))
                            {
                                $namee = $ds->Name ;
                                //echo $namee;                      //Service
                                if ($namee  == $ename)
                                {
                                    ?> <font color="#FF0000"><big><big><br><br>Anmeldename bereits vorhanden</big></big></font> <?php ;    //ja
                                    $vorh="1" ;                     //vorhanden auf 1
                                }
                            }
                            if ($vorh==0)
                            {                                       //bei vorhanden =0 eintragen
                                $query = "INSERT INTO `user` (`id`, `Name`, `lesen` ,`schreiben`,`Passwort`, `kname`) VALUES (NULL , '$ename', '1', '0','d41d8cd98f00b204e9800998ecf8427e', '$evname');";
                                mysql_query($query);
                                $ew=" in user eingetragen" ;
                                $l=$ename.$ew ;
                                logsch($l);
                            }
                        }
                    }
                break;
            default:
        }
    }
}
?>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">  <br><br>
<form name="aendern" action="Ausgeber.php" method="post" >          <!--  Tabelle ändern schreiben -->
<table border="1" >
<tr> <th>Anmeldename</th><th>Techniker</th><th>Admin</th><th>bearbeiten<br>l&ouml;schen</th><th>vollst&auml;ndiger Name</th> </tr>
<?php                                                               //Tabelle ausgeben
$sql = mysql_query("SELECT * FROM user ORDER BY Name");             //user auslesen
while($ds= mysql_fetch_object($sql))
{
    $name = $ds->Name ;
    $kname = $ds->kname;
    $lesen= $ds->lesen;
    $schreiben= $ds->schreiben;
    $pid= $ds->ID ;
    echo "<td>";                                                    //ausgeben
    echo $name, "<br>";
    if ($lesen == "1")                                              //bei lesen aktiv auf grün setzen
    {
        ?>
        <td bgcolor =#00FF00 >
        <input type="checkbox" name="anzei[]" checked disabled />
        <?php
    }
    else
    {
        echo "<td>";
        ?>
        <input type="checkbox" name="anzei[]"  disabled />
        <?php
    }
    if ($schreiben == "1")                                          //bei schreiben aktiv auf grün setzen
    {
        ?>
        <td bgcolor =#00FF00 >
        <input type="checkbox" name="anzei[]" checked disabled />
        <?php
    }
    else
    {
        echo "<td>";
        ?>
        <input type="checkbox" name="anzei[]"  disabled />
        <?php
    }
    echo "<td><input type=\"radio\" name=\"b[]\" value= \" $pid \" />"; // häckchen setzen
    //echo $pid;                                                    //Service
    echo "<td>";
    echo $kname;
    echo "</td>";
    echo "</tr>";
}
//mysql_close($ds);
?>
</table>
<br><input type="submit" name="ausgeber" value="bearbeiten">  ausgew&auml;hlte Techniker bearbeiten  <!-- button -->
<br><input type="submit" name="ausgeber" value="loeschen">  ausgew&auml;hlte Techniker l&ouml;schen  <!-- löschbutton -->
</form>
<big><big>
neuen Techniker eintragen:                                  <!--neuen Benutzer anlegen -->
</big></big><br><br>
<form name="eingabe" action="Ausgeber.php" method="post" >
<p>Anmeldename: <input type="text" name="ename"></p>                <!-- neuen Namen eingeben -->
vollst&auml;ndiger Name: <input type="text" name="evname">
<p><input type="submit" name="ausgeber" value="eintragen"></p>
</form>
<form method="POST" action="Ausgeber.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>

<?php /* 
$anw = Klarname
$bid = id in user
$ds = Datenzähler
$eintr = Übergabevariable
$ename = neuer Ausgeber - Anmeldename
$evname = neuer Ausgeber voööständiger name
$ew /$ew1 ... = Textpasagen für log datei schreiben
$heut =  deutsches heute
$key = variable bei array auslesen
$kname = Klarname
$l = fertiger log-eintrag
$les = variable für eingabe berechtigung lesen
$lesen = variable für lesen aus datenbank
$lesenb = variable für lesen aus var
$nam = name aus user
$namanz = name in löschen
$name = name beim tabelle schreiben
$nameb = name bei bearbeiten
$namee = name bei eintragen
$pid = id un user
$query = variable bei abfragen
$resultID = variable bei abfragen
$schrei = variable schreiben
$schreiben = in user schreiben
$schreibenb = variable bei bearbeiten
$sql = variable bei abfragen
$uid = userid aus var
$val = ariable bei array auslesen
$vorh = bei eintragen variable ob bereits vorhanden 
*/?>