<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
#                    Alle Rechte vorbehalten.                               #
#                http://www.supermailinglist.de/                            #
#                http://www.superwebmailer.de/                              #
#   Support SuperMailingList: support@supermailinglist.de                   #
#   Support SuperWebMailer: support@superwebmailer.de                       #
#   Support-Forum: http://board.superscripte.de/                            #
#                                                                           #
#   Dieses Script ist urheberrechtlich geschuetzt. Zur Nutzung des Scripts  #
#   muss eine Lizenz erworben werden.                                       #
#                                                                           #
#   Das Script darf weder als ganzes oder als Teil eines anderen Projekts   #
#   verwendet oder weiterverkauft werden.                                   #
#                                                                           #
#   Beachten Sie fuer den Einsatz des Script-Pakets die Lizenzbedingungen   #
#                                                                           #
#   Fuehren Sie keine Veraenderungen an diesem Script durch. Jegliche       #
#   Veraenderungen koennen nicht supported werden.                          #
#                                                                           #
#############################################################################

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("savedoptions.inc.php");


  $_fi0ij = false;
  if(isset($_GET["PersTrackingRcptsListColumns"]) && $_GET["PersTrackingRcptsListColumns"])
    $_fi0ij = true;
  if(isset($_POST["PersTrackingRcptsListColumns"]) && $_POST["PersTrackingRcptsListColumns"])
    $_fi0ij = true;

  $_Itfj8 = "";

  if(isset($_POST["SaveBtn"])){
    $_jtCOQ = array();
    reset($_POST);
    foreach($_POST as $key => $_QltJO) {
      if(strpos($key, "u_") !== false) {
        $_jtCOQ[] = $key;
      }
    }
    if(!in_array("u_EMail", $_jtCOQ))
        $_jtCOQ[] = "u_EMail";
    if(isset($_POST["ActionsColumn"]))
       $_jtCOQ[] = "ActionsColumn;".$_POST["ActionsColumn"];
       else
       $_jtCOQ[] = "ActionsColumn;right";
    $_I016j = serialize($_jtCOQ);
    if(!$_fi0ij)
      _JOOFF("RcptsListColumns", $_I016j);
      else
      _JOOFF("PersTrackingRcptsListColumns", $_I016j);
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["RcptsColumnsChanged"];
  }

  $_Iljoj = "";
  $_QLJfI = _LCFDR($_Itfj8, $_Iljoj);

  if(isset($_POST["SaveBtn"])){
    $_Iljoj .= "\r\n".'SubmitParentForm(); window.close();';
  }

  $_QLJfI = str_replace("//AUTO_SCRIPT_CODE_PLACEHOLDER//", $_Iljoj, $_QLJfI);

  print $_QLJfI;

 function _LCFDR($_Itfj8, &$_Iljoj) {
    global $_Ij8oL, $resourcestrings, $INTERFACE_LANGUAGE, $_QLl1Q, $UserId, $_fi0ij, $_QLttI;
    $_QLJfI = _JJAQE("rcptscolumns.htm");

    // spam protection
    if($UserId == 0) exit;

    if(!$_fi0ij)
      $_jtCOQ = _JOO1L("RcptsListColumns");
      else
      $_jtCOQ = _JOO1L("PersTrackingRcptsListColumns");
    if( $_jtCOQ != "") {
      $_I016j = @unserialize($_jtCOQ);
      if($_I016j !== false)
        $_jtCOQ = $_I016j;
        else
        $_jtCOQ = array();
    } else
      $_jtCOQ = array();

    if(count($_jtCOQ) <= 1) {
       if(!$_fi0ij) {
         $_jtCOQ[] = "u_LastName";
         $_jtCOQ[] = "u_FirstName";
         $_jtCOQ[] = "u_EMail";
         $_jtCOQ[] = "u_Salutation";
       } else {
         $_jtCOQ[] = "u_EMail";
       }
       $_jtCOQ[] = "ActionsColumn;right";
    }

    $_QLfol = "SELECT `text`, `fieldname` FROM `$_Ij8oL` WHERE `language`='$INTERFACE_LANGUAGE'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_IOJoI = array();
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      if($_QLO0f["fieldname"] == "u_Comments") continue; // no comments
      $_IOJoI[$_QLO0f["fieldname"]] = $_QLO0f["text"];
    }
    mysql_free_result($_QL8i1);

    $_IOiJ0 = _L81DB($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>");
    $_QLoli = "";

    // order
    for($_Qli6J=0; $_Qli6J<count($_jtCOQ); $_Qli6J++){
      $key = $_jtCOQ[$_Qli6J];
      if(!isset($_IOJoI[$key])) continue;
      $_QltJO = $_IOJoI[$key];
      $_Ql0fO = $_IOiJ0.$_QLl1Q;
      $_Ql0fO = str_replace('"COLUMNNAME"', '"'.$key.'"', $_Ql0fO);
      $_Ql0fO = str_replace('<!--COLUMNNAMELOCALIZED-->', $_QltJO, $_Ql0fO);
      $_Ql0fO = str_replace('/>', 'checked="checked" />', $_Ql0fO);
      $_QLoli .= $_Ql0fO;
    }

    reset($_IOJoI);
    foreach($_IOJoI as $key => $_QltJO) {
      if(in_array($key, $_jtCOQ)) continue;
      $_Ql0fO = $_IOiJ0.$_QLl1Q;
      $_Ql0fO = str_replace('"COLUMNNAME"', '"'.$key.'"', $_Ql0fO);
      $_Ql0fO = str_replace('<!--COLUMNNAMELOCALIZED-->', $_QltJO, $_Ql0fO);
      $_QLoli .= $_Ql0fO;
    }
    $_QLJfI = _L81BJ($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>", $_QLoli);

    if(in_array("ActionsColumn;right", $_jtCOQ))
      $_QLJfI = str_replace('name="ActionsColumn" value="right"', 'name="ActionsColumn" value="right" checked="checked"', $_QLJfI);
      else
      $_QLJfI = str_replace('name="ActionsColumn" value="left"', 'name="ActionsColumn" value="left" checked="checked"', $_QLJfI);

    if($_Itfj8 == "") {
      $_QLJfI = _L80DF($_QLJfI, "<SHOWHIDE:ERRORTOPIC>", "</SHOWHIDE:ERRORTOPIC>");
    } else {
      $_QLJfI = _L81BJ($_QLJfI, "<LABEL:ERRORMESSAGETEXT>", "</LABEL:ERRORMESSAGETEXT>", $_Itfj8 );
      $_Iljoj .= "ShowItem('errortopic', true);";
    }

    if($_fi0ij)
      $_QLJfI = str_replace('name="PersTrackingRcptsListColumns"', 'name="PersTrackingRcptsListColumns" value="1"', $_QLJfI);


    return $_QLJfI;
 }

?>
