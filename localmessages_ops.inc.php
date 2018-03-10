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

  function _OEOD8($_JOQl0, &$_QtIiC) {
    global $_Io680, $_Q61I1, $UserId;
    $_QfC8t = array();
    if(is_array($_JOQl0))
      $_QfC8t = array_merge($_QfC8t, $_JOQl0);
      else
      $_QfC8t[] = $_JOQl0;
    for($_Q6llo=0; $_Q6llo<count($_QfC8t); $_Q6llo++) {
      $_QfC8t[$_Q6llo] = intval($_QfC8t[$_Q6llo]);
      $_QJlJ0 = "DELETE FROM `$_Io680` WHERE id=$_QfC8t[$_Q6llo] AND `To_users_id`=$UserId";
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "")
         $_QtIiC[] = mysql_error($_Q61I1);
    }
  }

  function _OELQQ($_JOIo6, $_JOj86, $_I6016, $_I11oJ, $attachments=array()) {
    global $_Io680, $_Q61I1;
    $_JOIo6 = intval($_JOIo6);
    $_JOj86 = intval($_JOj86);

    $_QJlJ0 = "INSERT INTO `$_Io680` SET `MessageDate`=NOW(), `From_users_id`=$_JOIo6, `To_users_id`=$_JOj86, `MessageSubject`="._OPQLR($_I6016).", `MessageText`="._OPQLR($_I11oJ);

    if(count($attachments) > 0) {
      $_Q6llo = 1;
      foreach($attachments as $_jt8LJ => $_jtl8I) {
        $_Iofi6 = serialize(base64_decode(array("filename"=>$_jt8LJ, "content"=>$_jtl8I)));
        $_QJlJ0 .= ", `Attachment$_Q6llo`="._OPQLR($_Iofi6);
        $_Q6llo++;
        if($_Q6llo>3) break;
      }
    }
    mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_error($_Q61I1) == "")
      return true;
      else
      return false;
  }

  function _OELDQ($_ICoOt, &$_Io6iJ){
    global $_Q8f1L, $INTERFACE_LANGUAGE, $resourcestrings, $_Q61I1;
    $_ICoOt = intval($_ICoOt);
    if(isset($_Io6iJ[$_ICoOt]))
      return $_Io6iJ[$_ICoOt];
    if($_ICoOt == 0){
       return "SYSTEM";
    }
    $_QJlJ0 = "SELECT `Username` FROM `$_Q8f1L` WHERE id=$_ICoOt";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_num_rows($_Q60l1) > 0 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
      $_Io6iJ[$_ICoOt] = $_Q6Q1C["Username"];
    } else{
      $_Io6iJ[$_ICoOt] = $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"];
    }
    mysql_free_result($_Q60l1);
    return $_Io6iJ[$_ICoOt];
  }

?>
