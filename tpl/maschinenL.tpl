<!-- $Id: maschinenL.tpl,v 1.3 2005/11/02 10:38:59 hli Exp $ -->
<html>
	<head><title></title>
	<link type="text/css" REL="stylesheet" HREF="css/main.css"></link>
<body >

<table width="99%" border="0"><tr><td>
<!-- Beginn Code ------------------------------------------->
<p class="listtop">Maschinen Trefferliste</p>
<form name="formular" enctype='multipart/form-data' action="{action}" method="post">
<input type="hidden" name="MID" value="{MID}">
<table cellpadding="2">
<!-- BEGIN Sernumber -->
	<tr><td class="norm"><input type="radio" name="{fldname}" value="{number}"></td><td class="norm">{description}</td></tr>
<!-- END Sernumber -->
</table>
<input type="submit" name="search" value="sichern">
</body>
</html>
	