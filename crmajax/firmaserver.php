<?
	require_once("../inc/stdLib.php");
	include("FirmenLib.php");
	include("persLib.php");
	include("crmLib.php");
	require_once("documents.php");

    function getCustomTermin($id,$tab,$day) {
        $termine = getCustTermin($id,$tab,$day);
        if ($termine)  {
            foreach ($termine as $term) {
               $inhalt .= "<span onClick='getCall(".$term["cid"].")'>";
               $inhalt .= db2date(substr($term["start"],0,10))." ".$term["cause"].":".$term["cp_name"]."<br /></span>";
            }
        } else {
            $inhalt = "Keine Termine";
        };
		$objResponse = new xajaxResponse();
        $objResponse->assign("termin-container", "innerHTML", $inhalt);
		return $objResponse;
    }
	function getShipto($id) {
        $data=getShipStamm($id);
		$objResponse = new xajaxResponse();
		$objResponse->assign("shiptoname", 		"value", $data["shiptoname"]);
		$objResponse->assign("shiptodepartment_1",	"value", $data["shiptodepartment_1"]);
		$objResponse->assign("shiptodepartment_2",	"value", $data["shiptodepartment_2"]);
		$objResponse->assign("shiptostreet", 	"value", $data["shiptostreet"]);
		$objResponse->assign("shiptocity", 		"value", $data["shiptocity"]);
		$objResponse->assign("shiptocontact", 	"value", $data["shiptocontact"]);
		$objResponse->assign("shiptocountry", 	"value", $data["shiptocountry"]);
		$objResponse->assign("shiptophone", 		"value", $data["shiptophone"]);
		$objResponse->assign("shiptofax", 		"value", $data["shiptofax"]);
		$objResponse->assign("shiptoemail", 		"value", $data["shiptoemail"]);
		$objResponse->assign("shiptozipcode", 	"value", $data["shiptozipcode"]);
		$objResponse->assign("shipto_id", 		"value", $data["shipto_id"]);
		$objResponse->assign("module", 		"value", $data["module"]);
		$objResponse->assign("shiptobland", 		"value", $data["shiptobland"]);
		return $objResponse;
	}
	function Buland($land,$bl) {
		$data=getBundesland(strtoupper($land));
		$objResponse = new XajaxResponse();
        $sScript = "var i = document.getElementById('".$bl."').length;";
        $sScript.= "while ( i > 0) {";
        $sScript.= "document.getElementById('".$bl."').options[i-1]=null;";
        $sScript.= "i--;}";
		$objResponse->script($sScript);
		if (preg_match("/UTF-8/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="UTF-8"; }
		else if (preg_match("/ISO-8859-15/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="ISO-8859-15"; }
		else if (preg_match("/ISO-8859-1/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="ISO-8859-1"; }
		else { $charset="ISO-8859-1"; };
        $sScript = "var objOption = new Option('', '');";
        $sScript .= "document.getElementById('".$bl."').options.add(objOption);";
		foreach ($data as $row) {
            $sScript .= "var objOption = new Option('".html_entity_decode($row["bundesland"],ENT_NOQUOTES,$charset)."', '".$row["id"]."');";
            $sScript .= "document.getElementById('".$bl."').options.add(objOption);";
		}
		$objResponse->script($sScript);
		return $objResponse;
	}
	function showShipadress($id,$tab){
		$data=getShipStamm($id);
		if (preg_match("/UTF-8/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="UTF-8"; }
		else if (preg_match("/ISO-8859-15/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="ISO-8859-15"; }
		else if (preg_match("/ISO-8859-1/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="ISO-8859-1"; }
		else { $charset="ISO-8859-1"; };
		$maillink="<a href='mail.php?TO=".$data["shiptoemail"]."&KontaktTO=$tab".$data["trans_id"]."'>".$data["shiptoemail"]."</a>";
		$htmllink="<a href='".$data["shiptohomepage"]."' target='_blank'>".$data["shiptohomepage"]."</a>";
		$objResponse = new xajaxResponse();
                $objResponse->assign("SID",         		"innerHTML", $id);
                $objResponse->assign("shiptoname",           "innerHTML", htmlentities($data["shiptoname"],ENT_NOQUOTES,$charset));
                $objResponse->assign("shiptodepartment_1",   "innerHTML", htmlentities($data["shiptodepartment_1"],ENT_NOQUOTES,$charset));
                $objResponse->assign("shiptodepartment_2",   "innerHTML", htmlentities($data["shiptodepartment_2"],ENT_NOQUOTES,$charset));
                $objResponse->assign("shiptostreet",         "innerHTML", htmlentities($data["shiptostreet"],ENT_NOQUOTES,$charset));
                $objResponse->assign("shiptocountry",        "innerHTML", $data["shiptocountry"]);
                $objResponse->assign("shiptobland",          "innerHTML", html_entity_decode($data["shiptobundesland"],ENT_NOQUOTES,$charset));
                $objResponse->assign("shiptozipcode",        "innerHTML", $data["shiptozipcode"]);
                $objResponse->assign("shiptocity",           "innerHTML", htmlentities($data["shiptocity"],ENT_NOQUOTES,$charset));
                $objResponse->assign("shiptocontact",        "innerHTML", $data["shiptocontact"]);
                $objResponse->assign("shiptophone",          "innerHTML", $data["shiptophone"]);
                $objResponse->assign("shiptofax",            "innerHTML", $data["shiptofax"]);
                $objResponse->assign("shiptocontact",        "innerHTML", $data["shiptocontact"]);
                $objResponse->assign("shiptoemail",		"innerHTML", $maillink);
                $objResponse->assign("shiptohomepage",	"innerHTML", $htmllink);
                return $objResponse;
	}
	function showContactadress($id){
		$cp_sonder = getSonder();
		$data=getKontaktStamm($id,".");
		if (preg_match("/UTF-8/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="UTF-8"; }
		else if (preg_match("/ISO-8859-15/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="ISO-8859-15"; }
		else if (preg_match("/ISO-8859-1/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="ISO-8859-1"; }
		else { $charset="ISO-8859-1"; };
		$root="dokumente/".$_SESSION["mansel"]."/".$data["tabelle"].$data["nummer"]."/".$data["cp_id"];
		if (!empty($data["cp_grafik"]) && $data["cp_grafik"]<>"     ") {
			$img="<img src='$root/kopf$id.".$data["cp_grafik"]."' ".$data["icon"]." border='0'>";
			$data["cp_grafik"]="<a href='$root/kopf$id.".$data["cp_grafik"]."' target='_blank'>$img</a>";
		};
		$tmp=glob("../$root/vcard".$data["cp_id"].".*");
		$data["cp_vcard"]="";
		$objResponse = new xajaxResponse();
		if ($tmp)  foreach ($tmp as $vcard) {
			$ext=explode(".",$vcard);
			$ext=strtolower($ext[count($ext)-1]);
			if (in_array($ext,array("jpg","jpeg","gif","png","pdf","ps"))) {
				$data["cp_vcard"]="<a href='$root/vcard$id.$ext' target='_blank'>Visitenkarte</a>";
				//$data["cp_vcard"]="<a href='$vcard' target='_blank'>Visitenkarte</a>";
				break;
			}
		}
		$data["cp_email"]="<a href='mail.php?TO=".$data["cp_email"]."&KontaktTO=P".$data["cp_id"]."'>".$data["cp_email"]."</a>";
		$data["cp_homepage"]="<a href='".$data["cp_homepage"]."' target='_blank'>".$data["cp_homepage"]."</a>";
		if (strpos($data["cp_birthday"],"-")) {
			$data["cp_birthday"]=db2date($data["cp_birthday"]);
		}
		$sonder="";
		if ($cp_sonder)	{
			foreach ($cp_sonder as  $row) {
				$sonder.=($data["cp_sonder"] & $row["svalue"])?$row["skey"]." ":"";
			}
			$data["cp_sonder"]=$sonder;
		}
 		if ($data["cp_gender"]=='m') { $data["cp_greeting"]=translate('.:greetmale:.','firma'); 
 		} else { $data["cp_greeting"]=translate('.:greetfemale:.','firma'); };
		if ($data["cp_phone2"]) $data["cp_phone2"]="(".$data["cp_phone2"].")";
		if ($data["cp_privatphone"]) $data["cp_privatphone"]="Privat: ".$data["cp_privatphone"];
		if ($data["cp_mobile2"]) $data["cp_mobile2"]="(".$data["cp_mobile2"].")";
		if ($data["cp_privatemail"]) $data["cp_privatemail"]="Privat: <a href='mail.php?TO=".$data["cp_privatemail"]."&KontaktTO=P".$data["cp_id"]."'>".$data["cp_privatemail"]."</a>";;
		$nocodec = array("cp_email","cp_homepage","cp_zipcode","cp_birthday","cp_grafik","cp_privatemail","cp_vcard");
		foreach ($data as $key=>$val) {
			if (in_array($key,$nocodec)) {
                		$objResponse->assign($key,            "innerHTML", $val);
			} else {
                		$objResponse->assign($key,            "innerHTML", htmlentities($val,ENT_NOQUOTES,$charset));
			}
		}
		$objResponse->assign("cp_id", 	"value", $data["cp_id"]);
        if ($data["cp_phone1"] || $data["cp_phone2"]) $objResponse->script("document.getElementById('phone').style.visibility='visible'");
        if ($data["cp_mobile1"] || $data["cp_mobile2"]) $objResponse->script("document.getElementById('mobile').style.visibility='visible'");
        if ($data["cp_fax"]) $objResponse->script("document.getElementById('fax').style.visibility='visible'");
        $objResponse->script("document.getElementById('cpinhalt2').style.visibility='visible'");
        $objResponse->script("document.getElementById('cpbrief').style.visibility='visible'");
                return $objResponse;
	}
	function showCalls($id,$start,$fa=false) {
		if (preg_match("/UTF-8/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="UTF-8"; }
		else if (preg_match("/ISO-8859-15/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="ISO-8859-15"; }
		else if (preg_match("/ISO-8859-1/i",$_SERVER["HTTP_ACCEPT_CHARSET"])) { $charset="ISO-8859-1"; }
		else { $charset="ISO-8859-1"; };
		$i=0;
		$nun=date("Y-m-d h:i");
		$itemN[]=array(id => 0,calldate => $nun, caller_id => $employee, cause => translate('.:newItem:.','firma') );
		$zeile ="<tr class='calls%d' onClick='showItem(%d);'>";
		$zeile.="<td class='calls' nowrap width='15%%'>%s %s</td>";
		$zeile.="<td class='calls re' width='5%%'>%s&nbsp;</td>";
		$zeile.="<td class='calls le' width='55%%'>%s</td>";
		$zeile.="<td class='calls le' width='15%%'>%s</td></tr>\n";
		$items=getAllTelCall($id,$fa,$start);
		if ($items) {
			$item=array_merge($itemN,$items);
		} else {
			$item=$itemN;
		}
		$tmp="<table class='calls' width='99%'>";
		//$tmp="";
		if ($item) foreach($item as $col){
			if ($col["new"]) { $cause="<b>".htmlentities($col["cause"],ENT_NOQUOTES,$charset)."</b>"; }
			else { $cause=htmlentities($col["cause"],ENT_NOQUOTES,$charset); }
			$tmp.=sprintf($zeile,$i,$col["id"],db2date(substr($col["calldate"],0,10)),substr($col["calldate"],11,5),
						$col["id"],$cause,htmlentities($col["cp_name"],ENT_NOQUOTES,$charset));
			$i=($i==1)?0:1;
		}
		$tmp.="</table>";
		$objResponse = new xajaxResponse();
		$objResponse->assign("tellcalls", 	"innerHTML", $tmp);
		if ($start==0) {
			$max=getAllTelCallMax($id,$firma);
			$objResponse->script("max = $max;");
		}
        $objResponse->script("document.getElementById('threadtool').style.visibility='visible'");
        return $objResponse;
	}
	function showDir($id,$directory) {
		$directory = trim( rtrim( $directory, " /\\" ) );
		chkdir($directory,".");
		if ( is_dir("../dokumente/".$_SESSION["mansel"]."/".$directory)) {
		    $dir_object = dir( "../dokumente/".$_SESSION["mansel"]."/".$directory );
		    // Gibt neues Verzeichnis aus
			$inhalt="<ul>";
			$dir="<li class='ptr' onClick='dateibaum(\"$id\",\"%s\")'>%s";
			$datei="<li class='ptr' onClick='showFile(\"$id\",\"%s\")'>%s";
			clearstatcache();
	        	while ( false !== ( $entry = $dir_object->read() ) ) {
		            // '.' interessiert nicht
		            if ( $entry !== '.' ) {
				if ($entry === '..' ) {
						if ($directory=="/" || $directory=="") {
							continue;
						} else {
							$tmp=substr($directory,0,strrpos($directory,"/"));
							$inhalt.=sprintf($dir,$tmp,"[ .. ]");
						}
		                } else if (is_dir("../dokumente/".$_SESSION["mansel"]."/".$directory."/".$entry)) {
						$inhalt.=sprintf($dir,$directory."/".$entry,"[ $entry ]");
				} else {
					$inhalt.=sprintf($datei,$entry,"$entry");
				}
		            }
	        	}
			$inhalt.="</ul>";
	        $dir_object->close();
	    }
		$objResponse = new xajaxResponse();
		$objResponse->assign("fb$id", 	"innerHTML", $inhalt);
		$objResponse->assign("path", 	"innerHTML", ($directory)?$directory:"/");
        return $objResponse;

	}
	function showFile($pfad,$file) {
		if (substr($pfad,-1)=="/") $pfad=substr($pfad,0,-1);
		clearstatcache();
		$zeit=date("d.m.Y H:i:s",filemtime("../dokumente/".$_SESSION["mansel"]."/$pfad/$file"));
		$size=filesize("../dokumente/".$_SESSION["mansel"]."/$pfad/$file");
		$ext=strtoupper(substr($file,strrpos($file,".")+1));
		$pic="file.gif";
		     if ($ext=="PDF") { $type="PDF-File"; $pic="pdf.png"; }
		else if (in_array($ext,array("ODT","ODF","SXW","STW","WPD","DOC","TXT","RTF","LWP","WPS"))) { $type="Textdokument"; $pic="text.png";}
		else if (in_array($ext,array("ODS","SXC","STC","VOR","XLS","CSV","123"))) { $type="Tabellendokument"; $pic="calc.png"; }
		else if (in_array($ext,array("ODP","SXI","SDP","POT","PPS"))) { $type="Pr&auml;sentation"; $pic="praesent.png";}
		else if (in_array($ext,array("ODG","SXD","SDA","SVG","SDD","DXF"))) { $type="Zeichnungen"; $pic="zeichng.png";}
		else if (in_array($ext,array("HTM","HTML","STW","SSI","OTH"))) { $type="Webseiten"; $pic="web.png"; }
		else if (in_array($ext,array("DBF","ODB"))) { $type="Datenbank"; $pic="db.png";}
		else if (in_array($ext,array("PS", "EPS"))) { $type="Postscript"; $pic="ps.png";}
		else if (in_array($ext,array("GZ", "TGZ","BZ","ZIP","TBZ"))) { $type="Komprimiert"; $pic="zip.png"; }
		else if (in_array($ext,array("MP3","OGG","WAV"))) { $type="Audiodatei"; $pic="sound.png";}
		else if (in_array($ext,array("BMP","GIF","JPG","JPEG","PNG","TIF","PGM","PPM","PCX","PSD","TIFF"))) { $type="Grafik-Datei"; $pic="grafik.png";}
		else if (in_array($ext,array("WMF","MOV","AVI","VOB","MPG","MPEG","WMV","RM"))) { $type="Video-Datei"; $pic="video.png";}
		else if ($ext=="XML") { $type="XML-Datei"; $pic="xml.png";}
		else if (in_array($ext,array("SH","BAT"))) { $type="Shell-Script"; $pic="exe.png";}
		else { $type="Unbekannt"; $pic="foo.png"; };
		$info ="<br>$pfad/<b>$file</b><br><br>";
		$info.=translate('.:filetyp:.','firma').": <img src='image/icon/$pic'> $type<br>";
		$info.=translate('.:filesize:.','firma').": $size<br>".translate('.:filetime:.','firma').": $zeit<br>";
		$dbfile=new document();
		$rs=$dbfile->searchDocument($file,$pfad);
		$id=0;
		if ($rs) {
			$rs=$dbfile->getDokument($rs);
			$info.="<br>".translate('.:Description update:.','firma').": ".db2date($rs["datum"])." ".$rs["zeit"]."<br>";
			$info.=translate('.:Description:.','firma').": ".nl2br($rs["descript"])."<br>";
			$id=$rs["id"];
		}
		$objResponse = new xajaxResponse();
		$objResponse->assign("docname",	"value", $file);
		$objResponse->assign("docoldname",	"value", $file);
		$objResponse->assign("docpfad",	"value", $pfad);
		$objResponse->assign("docid",	"value", $id);
		$objResponse->assign("docdescript",	"value", $rs["descript"]);
		$objResponse->assign("fbright", 	"innerHTML", $info);
		$objResponse->assign("subdownload",	"innerHTML",
				"<a href='#' onClick='download(\"dokumente/".$_SESSION["mansel"]."$pfad/$file\")'>".translate('.:download:.','firma')."</a>");
		$objResponse->assign("subdelete",	"innerHTML",
				"<a href='#' onClick='deletefile(\"dokumente/".$_SESSION["mansel"]."$pfad/$file\",$id)'>".translate('.:delete:.','firma')."</a>");
		$objResponse->assign("submove",	"innerHTML",
				"<a href='#' onClick='movefile(\"dokumente/".$_SESSION["mansel"]."$pfad/$file\",$id)'>".translate('.:move:.','firma')."</a>");
		$objResponse->assign("subedit",	"innerHTML",
				"<a href='#' onClick='editattribut($id)'>".translate('.:edit attribute:.','firma')."</a>");
                return $objResponse;
	}
	function moveFile($file,$pfadleft) {
		$oldpath=substr($file,0,strrpos($file,"/"));
		$file=substr($file,strrpos($file,"/"));
		if ($oldpath<>$pfadleft) {
			$pre="../dokumente/".$_SESSION["mansel"];
			rename("../".$oldpath.$file,$pre.$pfadleft.$file);
			$dbfile=new document();
			$rs=$dbfile->searchDocument($file,$oldpath);
			if ($rs) {
				$rc=$dbfile->getDokument($rs["id"]);
				$dbfile->setDocData("pfad",$pfadleft);
				$rc=$dbfile->saveDocument();
			}
		}
		if ($file[0]=="/") $file=substr($file,1);
		$objResponse = new xajaxResponse();
		$objResponse->script("dateibaum('left','$pfadleft')");
		$objResponse->script("showFile('left','$file')");
                return $objResponse;
	};
	function saveAttribut($name,$oldname,$pfad,$komment,$id=0) {
		$dbfile=new document();
		if ($id>0) {
			$rc=$dbfile->getDokument($id);
		} else {
			$dbfile->setDocData("pfad",$pfad);
		};
		if ($oldname<>$name) {
			$path="../dokumente/".$_SESSION["mansel"]."$pfad/";
			$dbfile->setDocData("name",$name);
			rename($path.$oldname,$path.$name);
			$oldname=$name;
		} else {
			$dbfile->setDocData("name",$oldname);
		}
		$dbfile->setDocData("descript",$komment);
		$rc=$dbfile->saveDocument();
		$objResponse = new xajaxResponse();
		if ($rc) {
			//$objResponse->script("dateibaum('left','$pfad');showFile('left','$name');editattribut();");
			$objResponse->script("dateibaum('left','$pfad')");
			$objResponse->script("showFile('left','$oldname')");
			$objResponse->script("editattribut()");
		} else {
			$objResponse->script("alert('Fehler beim Sichern')");
		}
                return $objResponse;
	}
	function newDir($id,$pfad,$newdir) {
		chdir("../dokumente/".$_SESSION["mansel"]."/$pfad");
		mkdir($newdir);
		return new xajaxResponse();
	}
	function delFile($id=0,$pfad="",$file="") {
		$dbfile=new document();
		if ($id>0) {
			$dbfile->getDokument($id);
		} else {
			$dbfile->setDocData("name",$file);
			$dbfile->setDocData("pfad",$pfad);
		}
		$dbfile->deleteDocument(".");
		$objResponse = new xajaxResponse();
		return $objResponse;
	}
	function getDocVorlage_($did,$fid=0,$pid=0,$tab="C") {
		$inhalt="<div id='iframe2'>";
        	$inhalt.="        <iframe id='newdoc' width='100%' height='100%' name='newdoc' src='firma4a.php?did=$did&fid=$fid&tab=$tab&pid=$pid' frameborder='0'></iframe>";
	   	$inhalt.="</div>";
		$objResponse = new xajaxResponse();
		$objResponse->assign("fbright", 	"innerHTML", $inhalt);
        return $objResponse;
	}
	function getDocVorlage__($did,$fid=0,$pid=0,$tab="C") {
		$document=getDOCvorlage($did);
		if ($pid>0) {
			$co=getKontaktStamm($pid);
		    if ($data["cp_gender"]=='m') { $anredepers=translate('.:greetmale:.','firma'); 
            } else { $anredepers=translate('.:greetfemale:.','firma'); };
			$anredepers.=($co["cp_title"])?" ".$co["cp_title"]:"";
			$namepers=$co["cp_givenname"]." ".$co["cp_name"];
			$plzpers=$co["cp_zipcode"];
			$ortpers=$co["cp_city"];
			$strassepers=$co["cp_street"];
			if (!$co["cp_cv_id"]) $art="Einzelperson";
		};
		if ($fid>0) {
			$fa=getFirmenStamm($fid,$tab);
			$anrede=$fa["greeting"];
			$name=$fa["name"];
			$name1=$name;
			$name2=$fa["department_1"];
			$kontakt=$fa["contact"];
			$plz=$fa["zipcode"];
			$ort=$fa["city"];
			$strasse=$fa["street"];
			if ($pid>0) { $art="Firma/Kontakt"; } else { $art="Firmendokumente"; };
		};
		$datum=date("d.m.Y");
		$zeit=date("H:i");
		$input="<iframe width='100%'><html><body><form name='' action='' method='post'><table>\n";
		if ($document["felder"]) {
		 	foreach($document["felder"] as $zeile) {
				$value=strtolower($zeile["platzhalter"]);
				$input.="<tr><td>".$zeile["feldname"]."</td><td>";
				if ($zeile["laenge"]>60) {
					$rows=floor($zeile["laenge"]/60)+1;
					$input.="<textarea cols=60 rows=$rows name='".$zeile["platzhalter"]."'>".${$value}."</textarea>";
				} else {
					$input.="<input type='text' name='".$zeile["platzhalter"]."' size='".$zeile["laenge"]."' value='".${$value}."'>";
				}
				$input.="</td></tr>\n";
				$i++;
	 		}
		};
		$input.="</table><input type='submit' name='send' value='erzeugen'></form></body></html>";
		$objResponse = new xajaxResponse();
		$objResponse->assign("fbright", 	"innerHTML", $input);
                return $objResponse;
	}
	require("crmajax/firmacommon".XajaxVer.".php");
	$xajax->processRequest();

?>
