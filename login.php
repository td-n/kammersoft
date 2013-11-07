<?php require_once("header.php"); ?>

<div id="login">

	<form id="frm_login" name="frm_login" method="post" action="passcheck.php">
	<fieldset>
		<input type="text" id="username" name="username" placeholder="Benutzername eingeben" tabindex="1" autofocus required /><br />
		<input type="password" id="password" name="password" tabindex="2" placeholder="Passwort eingeben" required /><br />
		<input type="submit" value="Anmelden" tabindex="3" />
	</fieldset>
	</form>
</div>

<?php require_once("footer.php"); ?>