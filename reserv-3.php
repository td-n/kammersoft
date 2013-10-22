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
function date_mysql2german($date) {                                 //Funktion ins deutsche Format
    $d    =    explode("-",$date);
    return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
    }
function date_mysql2engl($date) {                                   //Funktion ins englische Format
    $d    =    explode(".",$date);
    return    sprintf("%04d-%02d-%02d", $d[2], $d[1], $d[0]);
    }
?>
<title>reservieren</title>
</head>
<body style="background-image: url(logo.jpg); background-repeat: no-repeat;">
<big><big><big>
Technik reservieren - 4. Schritt
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
if (isset ($_POST['zu']))
{
    ?><meta http-equiv="refresh" content="0; URL=start1.php" /><?php ;
}

?>
<a href="reserva.php" target="_blank" >zur Kontrolle der Reservierungen </a>
<?php
$hl = (isset($_POST["hl"])) ? $_POST["hl"] : false;                 // sprachauswahl calendar
if(!defined("L_LANG") || L_LANG == "L_LANG")
{
	if($hl) define("L_LANG", $hl);
	// You need to tell the class which language do you use.
	// L_LANG should be defined as en_US format!!! Next line is an example, just put your own language from the provided list
	else define("L_LANG", "de_DE"); // Greek example
}
?>
<form  action="reserv-4.php" method="POST" name="form1">
<p> An welchem Tag soll die Technik ausgeliehen bzw. zur&uuml;ckgegeben werden?  </p>
<?php

//get class into the page                                           //calendar
require_once('classes/tc_calendar.php');
//instantiate class and set properties
$heut = date("Y-m-d");
$heutend= date('Y-m-d', strtotime('+14 day'));
$date1=$heut;
$date2=$heutend;
$end= date("Y");
$end=$end +1;
$date4_default = "";
$date5_default = "";
	  $myCalendar = new tc_calendar("date4", true, false);
	  $myCalendar->setIcon("images/iconCalendar.gif");
	  $myCalendar->setDate(date("d", strtotime($date1))
            , date("m", strtotime($date1))
            , date("Y", strtotime($date1)));
      $myCalendar->setYearInterval(2010, $end);
	  $myCalendar->setAlignment("left", "bottom");
	  $myCalendar->setDatePair("date4", "date5", $date5_default);
	  $myCalendar->writeScript();

	  $myCalendar = new tc_calendar("date5", true, false);
	  $myCalendar->setIcon("images/iconCalendar.gif");
	  $myCalendar->setDate(date("d", strtotime($date2))
           , date("m", strtotime($date2))
           , date("Y", strtotime($date2)));
      $myCalendar->setYearInterval(2010, $end);
	  $myCalendar->setAlignment("right", "bottom");
	  $myCalendar->setDatePair("date4", "date5", $date4_default);
	  $myCalendar->writeScript();

?><br><br>
<input type="submit" name="eintr" value="eintragen" class="Button-w"/>
</form>
<form method="POST" action="reserv-3.php">
<input name="zu" type="submit" value="zur &Uuml;bersicht" class="Button-z"/>
</form>
</body>
</html>
<?php  
/*
$d = variable in function
$date = übergabevariable in function
$date1 = voreinstellung anfangsdatum
$date2 = voreinstellung enddatum
$end = aktuelles und folgendes jahr
$heut = heute deu
$heutend = heute in 14 tagen
$hl = sprachauswahl calendar
*/
?>
