<?php
	session_start(); //n�tig um auf Sessionvariablen zuzugreifen!
	if (isset($_SESSION['authenticated'])) //pr�ft ob die Sessionvariable 'authenticated' bereits gesetzt ist
   {
    if ($_SESSION['authenticated'] == "false") //wenn die Sessionvariable 'authenticated' nicht gesetzt ist
    {
     header("Location: login.php"); //leite den Nutzer auf die loginseite um 
     exit;                          //und stoppe ab da die PHP-Interpretation
    }  
   }   
   else
   {
    header("Location: login.php"); //leite den Nutzer auf die loginseite um 
    exit;                          //und stoppe ab da die PHP-Interpretation
   }      
?>
<?php  
/*
var code: gida
>0 = Ger�te ID
0 = verschiedenes
-1 = Name
-2 = Ausleihgrund
-3 = 
-4 = R�ckgabedatum
-5 = Ausleihnummer
-6 = T�V-Termin
-7 = Jahr
-8 = Nummer
-9 = Paketnummer
*/
?>