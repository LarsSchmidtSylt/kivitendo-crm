<?
// $Id: suchMail.php,v 1.3 2005/11/02 10:37:51 hli Exp $
	require_once("inc/stdLib.php");
	include("inc/crmLib.php");
?>
<html>
	<script language="JavaScript">
	<!--
		function auswahl() {
			nr=document.mails.Alle.selectedIndex;
			txt=document.mails.Alle.options[nr].text;
			val=document.mails.Alle.options[nr].value;
			opener.document.mailform.<?= $_GET["adr"] ?>.value=txt;
			opener.document.mailform.Kontakt<?= $_GET["adr"] ?>.value=val;
		}
	//-->
	</script>
<body onLoad="self.focus()">
<center>Gefundene Eintr&auml;ge:<br><br>
<form name="mails">
<select name="Alle" >
<?
	$daten=getAllMails($_GET["name"]);
	if ($daten) foreach ($daten as $zeile) {
		echo "\t<option value='".$zeile["src"].$zeile["id"]."'>".$zeile["name"]." &lt;".$zeile["email"]."&gt;</option>\n";
	}
?>
</select><br>
<br>
<input type="button" name="ok" value="&uuml;bernehmen" onClick="auswahl()"><br>
<input type="button" name="ok" value="Fenster schlie&szlig;en" onClick="self.close();">
</form>
</body>
</html>