<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2014 Mirko Boeer                         #
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

  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP"))
    exit;

  include_once("config.inc.php");

  function _LDAC8($_6ljLL, &$_IQ0Cj) {
    global $_jJtt8, $_QLttI, $UserId;
    $_I0lji = array();
    if(is_array($_6ljLL))
      $_I0lji = array_merge($_I0lji, $_6ljLL);
      else
      $_I0lji[] = $_6ljLL;
    for($_Qli6J=0; $_Qli6J<count($_I0lji); $_Qli6J++) {
      $_I0lji[$_Qli6J] = intval($_I0lji[$_Qli6J]);
      $_QLfol = "DELETE FROM `$_jJtt8` WHERE id=$_I0lji[$_Qli6J] AND `To_users_id`=$UserId";
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "")
         $_IQ0Cj[] = mysql_error($_QLttI);
    }
  }

  function _LDADP($_6lJOO, $_6lJol, $_ILi8o, $_IO08l, $attachments=array()) {
    global $_jJtt8, $_QLttI;
    $_6lJOO = intval($_6lJOO);
    $_6lJol = intval($_6lJol);

    $_QLfol = "INSERT INTO `$_jJtt8` SET `MessageDate`=NOW(), `From_users_id`=$_6lJOO, `To_users_id`=$_6lJol, `MessageSubject`="._LRAFO($_ILi8o).", `MessageText`="._LRAFO($_IO08l);

    if(count($attachments) > 0) {
      $_Qli6J = 1;
      foreach($attachments as $_JfIIf => $_Jfo0j) {
        $_jJCIl = serialize(base64_decode(array("filename"=>$_JfIIf, "content"=>$_Jfo0j)));
        $_QLfol .= ", `Attachment$_Qli6J`="._LRAFO($_jJCIl);
        $_Qli6J++;
        if($_Qli6J>3) break;
      }
    }
    mysql_query($_QLfol, $_QLttI);
    if(mysql_error($_QLttI) == "")
      return true;
      else
      return false;
  }

  function _LDAF0($_j6lIj, &$_jJO6I){
    global $_I18lo, $INTERFACE_LANGUAGE, $resourcestrings, $_QLttI;
    $_j6lIj = intval($_j6lIj);
    if(isset($_jJO6I[$_j6lIj]))
      return $_jJO6I[$_j6lIj];
    if($_j6lIj == 0){
       return "SYSTEM";
    }
    $_QLfol = "SELECT `Username` FROM `$_I18lo` WHERE id=$_j6lIj";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_num_rows($_QL8i1) > 0 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
      $_jJO6I[$_j6lIj] = $_QLO0f["Username"];
    } else{
      $_jJO6I[$_j6lIj] = $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"];
    }
    mysql_free_result($_QL8i1);
    return $_jJO6I[$_j6lIj];
  }

?>
