<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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


  $_6Jt0Q = false;
  if(isset($_GET["PersTrackingRcptsListColumns"]) && $_GET["PersTrackingRcptsListColumns"])
    $_6Jt0Q = true;
  if(isset($_POST["PersTrackingRcptsListColumns"]) && $_POST["PersTrackingRcptsListColumns"])
    $_6Jt0Q = true;

  $_I0600 = "";

  if(isset($_POST["SaveBtn"])){
    $_IL1fi = array();
    reset($_POST);
    foreach($_POST as $key => $_Q6ClO) {
      if(strpos($key, "u_") !== false) {
        $_IL1fi[] = $key;
      }
    }
    if(!in_array("u_EMail", $_IL1fi))
        $_IL1fi[] = "u_EMail";
    if(isset($_POST["ActionsColumn"]))
       $_IL1fi[] = "ActionsColumn;".$_POST["ActionsColumn"];
       else
       $_IL1fi[] = "ActionsColumn;right";
    $_QllO8 = serialize($_IL1fi);
    if(!$_6Jt0Q)
      _LQC66("RcptsListColumns", $_QllO8);
      else
      _LQC66("PersTrackingRcptsListColumns", $_QllO8);
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["RcptsColumnsChanged"];
  }

  $_I6ICC = "";
  $_QJCJi = _ODFLQ($_I0600, $_I6ICC);

  if(isset($_POST["SaveBtn"])){
    $_I6ICC .= "\r\n".'SubmitParentForm(); window.close();';
  }

  $_QJCJi = str_replace("//AUTO_SCRIPT_CODE_PLACEHOLDER//", $_I6ICC, $_QJCJi);

  print $_QJCJi;

 function _ODFLQ($_I0600, &$_I6ICC) {
    global $_Qofjo, $resourcestrings, $INTERFACE_LANGUAGE, $_Q6JJJ, $UserId, $_6Jt0Q, $_Q61I1;
    $_QJCJi = join("", file(_O68QF()."rcptscolumns.htm"));

    // spam protection
    if($UserId == 0) exit;

    if(!$_6Jt0Q)
      $_IL1fi = _LQB6D("RcptsListColumns");
      else
      $_IL1fi = _LQB6D("PersTrackingRcptsListColumns");
    if( $_IL1fi != "") {
      $_QllO8 = @unserialize($_IL1fi);
      if($_QllO8 !== false)
        $_IL1fi = $_QllO8;
        else
        $_IL1fi = array();
    } else
      $_IL1fi = array();

    if(count($_IL1fi) <= 1) {
       if(!$_6Jt0Q) {
         $_IL1fi[] = "u_LastName";
         $_IL1fi[] = "u_FirstName";
         $_IL1fi[] = "u_EMail";
         $_IL1fi[] = "u_Salutation";
       } else {
         $_IL1fi[] = "u_EMail";
       }
       $_IL1fi[] = "ActionsColumn;right";
    }

    $_QJlJ0 = "SELECT `text`, `fieldname` FROM `$_Qofjo` WHERE `language`='$INTERFACE_LANGUAGE'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_I16jJ = array();
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      if($_Q6Q1C["fieldname"] == "u_Comments") continue; // no comments
      $_I16jJ[$_Q6Q1C["fieldname"]] = $_Q6Q1C["text"];
    }
    mysql_free_result($_Q60l1);

    $_I1OLj = _OP81D($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>");
    $_Q6ICj = "";

    // order
    for($_Q6llo=0; $_Q6llo<count($_IL1fi); $_Q6llo++){
      $key = $_IL1fi[$_Q6llo];
      if(!isset($_I16jJ[$key])) continue;
      $_Q6ClO = $_I16jJ[$key];
      $_Q66jQ = $_I1OLj.$_Q6JJJ;
      $_Q66jQ = str_replace('"COLUMNNAME"', '"'.$key.'"', $_Q66jQ);
      $_Q66jQ = str_replace('<!--COLUMNNAMELOCALIZED-->', $_Q6ClO, $_Q66jQ);
      $_Q66jQ = str_replace('/>', 'checked="checked" />', $_Q66jQ);
      $_Q6ICj .= $_Q66jQ;
    }

    reset($_I16jJ);
    foreach($_I16jJ as $key => $_Q6ClO) {
      if(in_array($key, $_IL1fi)) continue;
      $_Q66jQ = $_I1OLj.$_Q6JJJ;
      $_Q66jQ = str_replace('"COLUMNNAME"', '"'.$key.'"', $_Q66jQ);
      $_Q66jQ = str_replace('<!--COLUMNNAMELOCALIZED-->', $_Q6ClO, $_Q66jQ);
      $_Q6ICj .= $_Q66jQ;
    }
    $_QJCJi = _OPR6L($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>", $_Q6ICj);

    if(in_array("ActionsColumn;right", $_IL1fi))
      $_QJCJi = str_replace('name="ActionsColumn" value="right"', 'name="ActionsColumn" value="right" checked="checked"', $_QJCJi);
      else
      $_QJCJi = str_replace('name="ActionsColumn" value="left"', 'name="ActionsColumn" value="left" checked="checked"', $_QJCJi);

    if($_I0600 == "") {
      $_QJCJi = _OP6PQ($_QJCJi, "<SHOWHIDE:ERRORTOPIC>", "</SHOWHIDE:ERRORTOPIC>");
    } else {
      $_QJCJi = _OPR6L($_QJCJi, "<LABEL:ERRORMESSAGETEXT>", "</LABEL:ERRORMESSAGETEXT>", $_I0600 );
      $_I6ICC .= "ShowItem('errortopic', true);";
    }

    if($_6Jt0Q)
      $_QJCJi = str_replace('name="PersTrackingRcptsListColumns"', 'name="PersTrackingRcptsListColumns" value="1"', $_QJCJi);


    return $_QJCJi;
 }

?>
