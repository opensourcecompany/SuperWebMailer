<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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

  if($UserType != "SuperAdmin"){
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
  }

  $errors = array();
  $_I0600 = "";

  if(isset($_POST["SaveBtn"])) {
    _LO1B0();
    $_I0600=$resourcestrings[$INTERFACE_LANGUAGE]["000021"];
  }

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001900"], $_I0600, 'settings_editfields', 'settings_editfields_snipped.htm');

  $_I1OLj = _OP81D($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>");
  $_QJlJ0 = "SELECT `text`, `fieldname` FROM `$_Qofjo` WHERE `language`="._OPQLR($INTERFACE_LANGUAGE);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

  $_Q6ICj = "";
  $_QL8Q8=1;
  $_I16jJ=array();
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    $_I16jJ[$_Q6Q1C["fieldname"]]=$_Q6Q1C["text"];
    $_Q66jQ = $_I1OLj;
    $_Q66jQ = str_replace('<!--FIELD-->', '<label for="editfield'.$_QL8Q8.'">'.$_Q6Q1C["text"]."</label>", $_Q66jQ);
    $_Q66jQ = str_replace('<!--VALUE-->', '<input type="text" name="'.$_Q6Q1C["fieldname"].'" id="editfield'.$_QL8Q8.'" size="30" />', $_Q66jQ);
    $_QL8Q8++;
    $_Q6ICj .= $_Q66jQ;
    $_Q66jQ = "";
  }
  if($_Q66jQ != "")
    $_Q6ICj .= $_Q66jQ;
  $_QJCJi = _OPR6L($_QJCJi, "<TABLE:ROW>", "</TABLE:ROW>", $_Q6ICj);

  $_POST=array_merge($_I16jJ, $_POST);

  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  print $_QJCJi;

  function _LO1B0() {
   global $_Qofjo, $INTERFACE_LANGUAGE, $_Q61I1, $_Q6QQL;
   reset($_POST);
   foreach($_POST as $key => $_Q6ClO){
     if(strpos($key, 'u_') === false) continue;
     $_QJlJ0 = "UPDATE `$_Qofjo` SET ";
     $_QJlJ0 .= "`text`="._OPQLR( htmlentities(trim($_Q6ClO), ENT_COMPAT, $_Q6QQL) );
     $_QJlJ0 .= " WHERE `language`="._OPQLR($INTERFACE_LANGUAGE)." AND `fieldname`="._OPQLR($key);
     mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
   }
  }
?>
