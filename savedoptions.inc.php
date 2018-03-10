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

  function _LQB6D($_I6oQj, $_6t60I = "") {
    global $UserId, $_Q8f1L, $_Q61I1;
    $_QJlJ0 = "SELECT `$_I6oQj` FROM `$_Q8f1L` WHERE id=$UserId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if( mysql_errno($_Q61I1) == 1054 || strpos(mysql_error($_Q61I1), "Unknown column") !== false ) {
       $_QtjtL = "ALTER TABLE `$_Q8f1L` ADD `$_I6oQj` TEXT NULL";
       mysql_query($_QtjtL, $_Q61I1);
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    }
    if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      return $_Q6Q1C[0];
    }
    return $_6t60I;
  }

  function _LQC66($_I6oQj, $_Q6ClO) {
    global $UserId, $_Q8f1L, $_Q61I1;
    $_QJlJ0 = "UPDATE `$_Q8f1L` SET `$_I6oQj`="._OPQLR($_Q6ClO)." WHERE `id`=$UserId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

    if( mysql_errno($_Q61I1) == 1054 || strpos(mysql_error($_Q61I1), "Unknown column") !== false ) {
       $_QtjtL = "ALTER TABLE `$_Q8f1L` ADD `$_I6oQj` TEXT NULL";
       mysql_query($_QtjtL, $_Q61I1);
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    }

    _OAL8F($_QJlJ0);
  }

  function _LQDLR($_I6oQj, $_6t60I = 0) {
    global $_Q88iO, $_Q61I1;
    $_QJlJ0 = "SELECT `$_I6oQj` FROM `$_Q88iO`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      return $_Q6Q1C[0];
    }
    return $_6t60I;
  }

  function _LQEQR($_6I1QI = null){
    if($_6I1QI == null || !is_array($_6I1QI)){
       $_6I1QI = _LQDLR("Dashboard", "");
    } else {
      $_6I1QI = isset($_6I1QI["Dashboard"]) ? $_6I1QI["Dashboard"] : "";
    }
    if($_6I1QI == "")
      return array();
    $_Q8otJ = @unserialize($_6I1QI);
    if($_Q8otJ === false) {
       $_6I1QI = utf8_encode($_6I1QI);
       $_Q8otJ = @unserialize($_6I1QI);
    }
    if($_Q8otJ === false || $_6I1QI == "")
      $_Q8otJ = array();
    return $_Q8otJ;
  }

  function _LQF1R(){
   global $_Q8J1j;
   $_Q8otJ = _LQEQR();
   if($_Q8otJ !== false && !empty($_Q8otJ["DashboardTag"]))
     $_Q8J1j = sprintf("%06x", intval(strrev(substr(substr($_Q8otJ["DashboardTag"], 0, 13), 7))));
  }

  function _LQFDJ($_I6oQj, $_6t60I = 0) {
    global $_jJJjO, $_Q61I1;
    $_QJlJ0 = "SELECT `$_I6oQj` FROM `$_jJJjO`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      return $_Q6Q1C[0];
    }
    return $_6t60I;
  }

?>
