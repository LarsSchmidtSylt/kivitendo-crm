<?php
// $Id$
    require_once("inc/stdLib.php");
    include("inc/template.inc");
    include("inc/crmLib.php");
    include("inc/UserLib.php");
    if ($_POST["holen"]) {
        $msg=getCustMsg($_POST["cp_cv_id"],true);
    } else if ($_POST["reset"]) {
        $msg=false;
        $_POST["cp_cv_id"]="";
        $_POST["name"]="";
    } else if ($_POST["sichern"]) {
        $rc=saveCustMsg($_POST);
        $msg=getCustMsg($_POST["cp_cv_id"],true);
    }
    $fid=$_POST["cp_cv_id"];
    $name=$_POST["name"];
    $t = new Template($base);
    $menu =  $_SESSION['menu'];
    $t->set_var(array(
        JAVASCRIPTS   => $menu['javascripts'],
        STYLESHEETS   => $menu['stylesheets'],
        PRE_CONTENT   => $menu['pre_content'],
        START_CONTENT => $menu['start_content'],
        END_CONTENT   => $menu['end_content']
    ));
    $t->set_file(array("msg" => "user4.tpl"));
    $t->set_var(array(
            ERPCSS      => $_SESSION['basepath'].'crm/css/'.$_SESSION["stylesheet"],
            FID => $fid,
            Firma => $name
            ));
    $t->set_block("msg","Selectbox","Block");
    if ($msg) {
        foreach($msg as $zeile) {
            $nr=$zeile["prio"];
            $t->set_var(array(
                "R$nr"        => ($zeile["akt"]=="t")?"checked":"",
                "mid$nr"    => $zeile["id"],
                "MSG$nr"    => $zeile["msg"]
            ));
            $t->parse("Block","Selectbox",true);
        }
    } else {
        $t->set_var(array( R3 => "checked" ));
        $t->parse("Block","Selectbox",true);
    }
    $t->pparse("out",array("msg"));
?>
