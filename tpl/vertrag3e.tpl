<!-- $Id: vertrag3e.tpl,v 1.4 2005/11/02 11:35:45 hli Exp $ -->
<html>
	<head><title></title>
	<link type="text/css" REL="stylesheet" HREF="css/main.css"></link>
	<script language="JavaScript">
	<!--

		function suchFa() {
			val=document.formular.name.value;
			f1=open("suchFa.php?nq=1&name="+val,"suche","width=350,height=200,left=100,top=100");
		}
		function suchMa() {
			val=document.formular.masch.value;
			f1=open("suchMa.php?masch="+val,"suche","width=350,height=200,left=100,top=100");
		}
		function drucke(nr)  {
			f=open("prtWVertrag.php?aid="+nr,"drucke","width=10,height=10,left=10,top=10");
		}	
	//-->
	</script>
<body >

<table><tr><td>
<!-- Beginn Code ------------------------------------------->
<p class="listtop">Vertr&auml;ge eingeben/editieren</p>
<form name="formular" enctype='multipart/form-data' action="{action}" method="post"">
<input type="hidden" name="vid" value="{VID}">
Vertrag: {VertragNr}
<table>
	<tr>
		<td class="norm" colspan="2">
			<input type="hidden" name="vorlage_old" value="{vorlage}">
			<select name="vorlage"  tabindex="1" style='width:300px;z-index: 1;'>
<!-- BEGIN Vorlage -->
				<option value="{Vertrag}" {Vsel}>{Vertrag}</option>
<!-- END Vorlage -->
			</select> 
			<input type="checkbox" name="new" value="1">
			{vorlage}
			<!--a href="{vorlage_old}">{vorlage}</a-->
			<br>Vertragsvorlage<br><br>
		</td>
	</tr>
	<tr>
		<td class="norm" colspan="2"><textarea name="bemerkung" cols="80" rows="3" tabindex="2">{Notiz}</textarea><br>Bemerkungen<br><br></td>
	</tr>
	<tr>
		<td class="norm" width="40%"><input type="text" name="name" size="30" maxlength="75" value="{Firma}" onFocus="blur()"> <b>
			<a href="firma1.php?id={FID}">[{FID}]</a> </b><br>Firma<br><br></td>
		<td class="norm" width="60%"><input type="hidden" name="contractnumber" value="{VertragNr}"><input type="hidden" name="cp_cv_id" value="{FID}">
			<input type="text" name="anfangdatum" size="10" maxlength="10" value="{anfangdatum}" tabindex="6">&nbsp; <input type="text" name="endedatum" size="10" maxlength="10" value="{endedatum}" tabindex="6"><br>
			<b>Vertragsdatum von &nbsp; bis</b></td>
	</tr>
	<tr>
		<td class="norm"><input type="text" name="masch" size="30" value="" tabindex="6"> <input type="button" name="ma" value="suchen" onClick="suchMa();"  tabindex="7"><br>Maschine<br><br></td>
		<td class="norm"><input type="text" name="betrag" size="10" maxlength="10" value="{betrag}" tabindex="6">&euro;<br>Betrag </td>
	</tr>
	<tr>
		<td class="norm"><input type="hidden" name="maschinen[0][0]" value=""><input type="text" name="maschinen[0][1]" size="30" maxlength="15" value="" tabindex="8"><br>neue Maschine<br><br></td>
		<td class="norm"><input type="text" name="maschinen[0][2]" size="30" maxlength="75" value="" tabindex="9"> l&ouml;schen<br>Standort</td>		
	</tr>	
<!-- BEGIN Maschinen -->		
	<tr>
		<td class="norm"><input type="hidden" name="maschinen[{I}][0]" value="{MID}"><input type="text" name="maschinen[{I}][1]" size="30" maxlength="15" value="{Maschine}" tabindex="8"><br>Maschine</td>
		<td class="norm"><input type="text" name="maschinen[{I}][2]" size="30" maxlength="125" value="{Standort}" tabindex="9">
			<input type="checkbox" name="maschinen[{I}][3]" value="1"> <a href="maschine1.php?sernr={SerNr}">[mehr]</a><br>Standort</td>		
	</tr>
<!-- END Maschinen -->
	<tr>
		<td class="norm"><br>
			<input type="text" name="jahr" size="4" maxlength="4" value="{jahr}">
			<input type="submit" name="stat" value="auswerten">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" name="prt" value=" WV drucken" onCLick="drucke({VertragNr})"><br>Jahr
		</td>
		<td class="norm"><br>
			<input type="submit" name="ok" value="sichern">
		</td>
	</tr>

</table>
</form>

<!-- End Code ------------------------------------------->
</td></tr></table>
</body>
</html>