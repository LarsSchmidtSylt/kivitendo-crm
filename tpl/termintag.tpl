<!-- $ID: $ -->
<html>
	<head><title></title>
	<link type="text/css" REL="stylesheet" HREF="css/main.css"></link>

	<script language="JavaScript">
	<!--
		function tamin() {
			self.location.href="termlist.php?ansicht=T&datum={dat1}";
	}
	function taplu() {
			self.location.href="termlist.php?ansicht=T&datum={dat2}";
	}
	function zeige(tid) {
		if (tid>0)
			f=open("showTermin.php?termid="+tid,"termine","width=400,height=300,left=300,top=150");
	}
	//-->
	</script>
<body >
<center>
<input type="button" value="<--" onClick="tamin()"> {tag} <input type="button" value="-->" onClick="taplu()">
<table style="width:350px" bgcolor="#ffffff">
<!-- BEGIN Stunden -->
	<tr><td class="smal {col} re" width="60px">{zeit}</td><td class="smal">{text}</td></tr><!--td onClick="zeige({tid})">{text}</td></tr-->
<!-- END Stunden -->
</table>
</center>
</body>
</html>