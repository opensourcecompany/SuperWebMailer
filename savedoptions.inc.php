<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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

  function _JOO1L($_IliOC, $_81t66 = "") {
    global $UserId, $_I18lo, $_QLttI;
    $_QLfol = "SELECT `$_IliOC` FROM `$_I18lo` WHERE id=$UserId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if( mysql_errno($_QLttI) == 1054 || strpos(mysql_error($_QLttI), "Unknown column") !== false ) {
       $_QLlO6 = "ALTER TABLE `$_I18lo` ADD `$_IliOC` TEXT NULL";
       mysql_query($_QLlO6, $_QLttI);
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    }
    if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
      $_QLO0f = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
      return $_QLO0f[0];
    }
    return $_81t66;
  }

  function _JOOFF($_IliOC, $_QltJO) {
    global $UserId, $_I18lo, $_QLttI;
    $_QLfol = "UPDATE `$_I18lo` SET `$_IliOC`="._LRAFO($_QltJO)." WHERE `id`=$UserId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);

    if( mysql_errno($_QLttI) == 1054 || strpos(mysql_error($_QLttI), "Unknown column") !== false ) {
       $_QLlO6 = "ALTER TABLE `$_I18lo` ADD `$_IliOC` TEXT NULL";
       mysql_query($_QLlO6, $_QLttI);
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    }

    _L8D88($_QLfol);
  }

  function _JOLQE($_IliOC, $_81t66 = 0) {
    global $_I1O0i, $_QLttI;
    $_QLfol = "SELECT `$_IliOC` FROM `$_I1O0i`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
      $_QLO0f = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
      return $_QLO0f[0];
    }
    return $_81t66;
  }

  function _JOLOA($_ftf0t = null){
    if($_ftf0t == null || !is_array($_ftf0t)){
       $_ftf0t = _JOLQE("Dashboard", "");
    } else {
      $_ftf0t = isset($_ftf0t["Dashboard"]) ? $_ftf0t["Dashboard"] : "";
    }
    if($_ftf0t == "")
      return array();
    $_I1OoI = @unserialize($_ftf0t);
    if($_I1OoI === false) {
       $_ftf0t = utf8_encode($_ftf0t);
       $_I1OoI = @unserialize($_ftf0t);
    }
    if($_I1OoI === false || $_ftf0t == "")
      $_I1OoI = array();
    return $_I1OoI;
  }

  function _JOLFC(){
   global $_I16ll;
   $_I1OoI = _JOLOA();
   if($_I1OoI !== false && !empty($_I1OoI["DashboardTag"]))
     $_I16ll = sprintf("%06x", intval(strrev(substr(substr($_I1OoI["DashboardTag"], 0, 13), 7))));
  }

  function _JOJPO($_IliOC, $_81t66 = 0) {
    global $_JQ1I6, $_QLttI;
    $_QLfol = "SELECT `$_IliOC` FROM `$_JQ1I6`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
      $_QLO0f = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
      return $_QLO0f[0];
    }
    return $_81t66;
  }

  function _JO61Q(){
     $_81tC0 = _JOLOA();
     if(is_array($_81tC0) && count($_81tC0))
      return ord( substr($_81tC0["DashboardTag"], strlen($_81tC0["DashboardTag"]) - 1, 1) );
      else
      return 0;
  }

?>
