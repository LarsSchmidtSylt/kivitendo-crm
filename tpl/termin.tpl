<!-- $ID: $ -->
<html>
	<head><title></title>
	<link type="text/css" REL="stylesheet" HREF="css/main.css"></link>

	<script language="JavaScript">
	<!--
		function showlist(was) {
			mo=document.termedit.Monat.options[document.termedit.Monat.selectedIndex].value;
			ja=document.termedit.Jahr.options[document.termedit.Jahr.selectedIndex].value;
			tg=document.termedit.Tag.options[document.termedit.Tag.selectedIndex].value;
			Termine.location.href="termlist.php?ansicht="+was+"&datum="+tg+"."+mo+"."+ja; //month="+mo+"&year="+ja+"&day="+tg;
		}
		function suchName() {
			f=open("suchName.php?name="+document.termedit.suchname.value,"Name","width=400,height=200,left=200,top=100");
		}
		function subusr() {
			nr=document.termedit.elements[22].selectedIndex;
			document.termedit.elements[22].options[nr]=null
		}
		function addusr() {
			nr=document.termedit.teiln.selectedIndex;
			val=document.termedit.teiln.options[nr].value;
			txt=document.termedit.teiln.options[nr].text;
			NeuerEintrag = new Option(txt,val,false,true);
 			document.termedit.elements[22].options[document.termedit.elements[22].length] = NeuerEintrag;
		}
		function selall() {
			len=document.termedit.elements[22].length;
			document.termedit.elements[22].multiple=true;
			for (i=0; i<len; i++) {
				document.termedit.elements[22].options[i].selected=true;
			}
		}
		function kal(fld) {
			mo=document.termedit.Monat.options[document.termedit.Monat.selectedIndex].value;
			ja=document.termedit.Jahr.options[document.termedit.Jahr.selectedIndex].value;
			f=open("terminmonat.php?datum=01."+mo+"."+ja+"&fld="+fld,"Name","width=370,height=360,left=200,top=100");
		}
		function init() {
			/*
			document.termedit.Tag.options[{TT}-1].selected=true;
			document.termedit.Monat.options[{MM}-1].selected=true;
			for (i=0; i<document.termedit.Jahr.length; i++) {
				if (document.termedit.Jahr.options[i].value=={YY})
					document.termedit.Jahr.options[i].selected=true;
			}*/
			showlist("T");
		}
		function go() {
			selall();
			return true;
		}
	//-->
	</script>
<body>

<!--table width="103%" class="karte" border="0"><tr><td class="karte"-->
<!-- Beginn Code ------------------------------------------->
<p class="listtop">Termine</p>
<font color="red">{Msg}</font>
<table>
<form name="termedit" action="termin.php" method="post" onSubmit="return go()";>
<input type="hidden" name="uid" value="{uid}">
<tr>
	<td width="*" >
		<select name="Tag" style="width:44px">
<!-- BEGIN Tage -->
			<option value="{TV}"{TS}>{TK}</option>
<!-- END Tage -->
		</select>
		<select name="Monat" style="width:97px">
<!-- BEGIN Monat -->
			<option value="{MV}"{MS}>{MK}</option>
<!-- END Monat -->
		</select>
		<select name="Jahr" style="width:57px">
<!-- BEGIN Jahre -->
			<option value="{JV}"{JS}>{JK}</option>
<!-- END Jahre -->
		</select>
		<input type="button" value="Tag" onClick="showlist('T')">
		<input type="button" value="Woche" onClick="showlist('W')">
		<input type="button" value="Monat" onClick="showlist('M')"><hr>
		{OK}
		<!--input type="button" value="Zeige" onClick="showlist('T')"><hr-->
		<table>
			<tr><td class="norm">von:</td><td class="norm"><input type="text" name="vondat" size="8" maxlength="10" value="{VONDAT}"><input type="button" value="K" onClick="kal('vondat')">
					<select name="von">
<!-- BEGIN Time1 -->
						<option value="{tval1}"{tsel1}>{tkey1}</option>
						<option value="{tval2}"{tsel2}>{tkey2}</option>
<!-- END Time1 -->
					</select>
					<select name="wdhlg">
<!-- BEGIN repeat -->
						<option value="{RPTK}"{RPTS}>{RPTV}</option>
<!-- END repeat -->
					</select>
				</td></tr>
			<tr><td class="norm">bis:</td><td class="norm"><input type="text" name="bisdat" size="8" maxlength="10" value="{BISDAT}"><input type="button" value="K" onClick="kal('bisdat')">
					<select name="bis">
<!-- BEGIN Time2 -->
						<option value="{tval1}"{tsel1}>{tkey1}</option>
						<option value="{tval2}"{tsel2}>{tkey2}</option>
<!-- END Time2 -->
					</select>
					nur Arbeitstage<input type="checkbox" name="ft" value="1" {FT}>
				</td></tr>
			<tr><td class="norm" colspan="2"><input type="text" name="grund" size="35" maxlength="75" value="{GRUND}">
						 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href="termin.php"><input type="reset" name="clear" value="clear"></a>
						<br>Grund</td></tr>
			<tr><td class="norm" colspan="2"><textarea name="lang" cols="40" rows="3">{LANG}</textarea>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="sichern" value="sichern">	
						<br>Bemerkungen</td></tr>
			<tr><td class="norm" colspan="2">
					<input type="text" name="suchname" size="20" maxlength="25" value=""><input type="button" value="suche Teilnehmer" onClick="suchName()">
					<br>
					<table><tr>
					<td class="norm"><select name="user[]" size="5">
<!-- BEGIN Selusr -->
						<option value="{UID}">{UNAME}</option>
<!-- END Selusr -->
					</select><br><span class="norm">Teilnehmer</span></td>
					<td class="norm"><input type="button" value="&lt;--" onClick="addusr()"><br><br><input type="button" value="--&gt;" onClick="subusr()"></td>
					<td class="norm"><select name="teiln" size="5">
<!-- BEGIN User -->
						<option value="{USRID}">{USRNAME}</option>
<!-- END User -->
					</select><br><span class="norm">CRM-User</span></td>
					</tr></table>
			</td></tr>
		</table>
	</td>
	<td width="20px"></td>
	<td width="*" class="norm ce">
		<iframe src="termlist.php?ansicht={ANSICHT}" name="Termine" width="370" height="380" marginheight="0" marginwidth="0" align="left">
		<p>Ihr Browser kann leider keine eingebetteten Frames anzeigen</p>
		</iframe>
	</td>
</tr>
<input type="hidden" name="tid" value="{TID}">
</form>
</table>
</center>
<!-- End Code ------------------------------------------->
<!--/td></tr></table-->
</body>
</html>