<form method = "POST" action="test1.php"name="ges">
<table border="0" width="70%" cellpadding="0">
<?php
/*
while (list ($key, $value) = each ($_REQUEST))                      //alle Rückgabewerte auslesen
{
  echo $key." => ".$value."<br />\n";
}
*/
for ($i=1;$i<10;$i++)
{
    echo "<tr>";
    echo"<td>",$i,"</td>";
    echo "<td><input type=\"checkbox\" name=\"w[$i]\ value=\"$i\"/></td>";
    echo "</tr>";
}
?>
</table>
<input name="submit" type="submit" value="&uuml;bernehmen" />
</form>
<?php  
if (isset($_POST['w']))
{
    foreach ($_POST['w'] as $key => $val)
    {
    
        echo $key,"->";
        if ($val=="on")
        {
            $val=1;
        }
        else
        {
            $val=0;
        }
        echo $val,"<br>";
     }
}
/*
if (isset($_POST['w']))
{
    $werta=$_POST['w'];
    for ($i=0;$i<9;$i++)
    {
        if (isset($werta[$i]))
        {
            $wert=$werta[$i];
            echo $wert,$i,"<br>";
        }
        else
        {
            $wert=0;
            echo $wert,$i,"<br>";
        }
    }
} */
?>
