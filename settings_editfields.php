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
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
  }

  $errors = array();
  $_Itfj8 = "";

  if(isset($_POST["SaveBtn"])) {
    _JO8B1();
    $_Itfj8=$resourcestrings[$INTERFACE_LANGUAGE]["000021"];
  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001900"], $_Itfj8, 'settings_editfields', 'settings_editfields_snipped.htm');

  $_IOiJ0 = _L81DB($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>");
  $_QLfol = "SELECT `text`, `fieldname` FROM `$_Ij8oL` WHERE `language`="._LRAFO($INTERFACE_LANGUAGE);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);

  $_QLoli = "";
  $_Ift08=1;
  $_IOJoI=array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    $_IOJoI[$_QLO0f["fieldname"]]=$_QLO0f["text"];
    $_Ql0fO = $_IOiJ0;
    $_Ql0fO = str_replace('<!--FIELD-->', '<label for="editfield'.$_Ift08.'">'.$_QLO0f["text"]."</label>", $_Ql0fO);
    $_Ql0fO = str_replace('<!--VALUE-->', '<input type="text" name="'.$_QLO0f["fieldname"].'" id="editfield'.$_Ift08.'" size="30" />', $_Ql0fO);
    $_Ift08++;
    $_QLoli .= $_Ql0fO;
    $_Ql0fO = "";
  }
  if($_Ql0fO != "")
    $_QLoli .= $_Ql0fO;
  $_QLJfI = _L81BJ($_QLJfI, "<TABLE:ROW>", "</TABLE:ROW>", $_QLoli);

  $_POST=array_merge($_IOJoI, $_POST);

  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

  print $_QLJfI;

  function _JO8B1() {
   global $_Ij8oL, $INTERFACE_LANGUAGE, $_QLttI, $_QLo06;
   reset($_POST);
   foreach($_POST as $key => $_QltJO){
     if(strpos($key, 'u_') === false) continue;
     $_QLfol = "UPDATE `$_Ij8oL` SET ";
     $_QLfol .= "`text`="._LRAFO( htmlentities(trim($_QltJO), ENT_COMPAT, $_QLo06) );
     $_QLfol .= " WHERE `language`="._LRAFO($INTERFACE_LANGUAGE)." AND `fieldname`="._LRAFO($key);
     mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
   }
  }
?>
