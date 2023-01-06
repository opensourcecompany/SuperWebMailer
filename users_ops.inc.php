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

  // used from browserecpts.php, browsmailinglists.php, nl.php, defaultnewsletter.php

  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP") && !defined("API"))
    exit;

  if(!defined("API")) {
    $_8L6tI = array();

    if ( isset($_POST["OneUserId"]) && $_POST["OneUserId"] != "" && intval($_POST["OneUserId"]) != 0 )
        $_8L6tI[] = intval($_POST["OneUserId"]);
        else
        if ( isset($_POST["UsersIDs"]) ){
          foreach($_POST["UsersIDs"] as $key) {
            $_8L6tI[] = intval($key);
          }
        }


    if  ( ( isset($_POST["UsersActions"]) && $_POST["UsersActions"] == "RemoveUsers" ) ||
       ( isset($_POST["OneUserAction"]) && $_POST["OneUserAction"] == "DeleteUser" )
       ) {
            if(isset($_IQ0Cj))
              unset($_IQ0Cj);
            $_IQ0Cj = array();
            _J68QL($_8L6tI, $_IQ0Cj);
      }
  }

  // we don't check for errors here
 function _J68QL($_8L6tI, &$_IQ0Cj) {
    global $_I18lo, $_IfOtC, $_QlQot, $_jJtt8, $_Ql18I;
    global $OwnerUserId, $UserId, $_QLttI;
    for($_Qli6J=0; $_Qli6J<count($_8L6tI); $_Qli6J++) {
      $_8L6tI[$_Qli6J] = intval($_8L6tI[$_Qli6J]);
      // TableLocalUserMessages
      $_QLfol = "DELETE FROM `$_jJtt8` WHERE `From_users_id`=$_8L6tI[$_Qli6J] OR `To_users_id`=$_8L6tI[$_Qli6J]";
      mysql_query($_QLfol, $_QLttI);

      // Admin, remove tables
      if($OwnerUserId == 0) {
        $_QLfol = "SELECT * FROM `$_I18lo` WHERE id=".$_8L6tI[$_Qli6J];
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        $_QLO0f = mysql_fetch_assoc($_QL8i1);
        if(!$_QLO0f) continue;
        mysql_free_result($_QL8i1);

        foreach($_QLO0f as $key => $_QltJO) {
          if(strpos($key, "TableName") === false) continue; // only Tables!!
          if($_QltJO != "") {
            _LP1AQ($_QltJO);

            $_QLfol = "DROP TABLE IF EXISTS `$_QltJO`";
            mysql_query($_QLfol, $_QLttI);
            if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
          }
        }
      }

      // Delete User
      $_QLfol = "DELETE FROM `$_I18lo` WHERE id=".$_8L6tI[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      $_QLfol = "DELETE FROM `$_IfOtC` WHERE users_id=".$_8L6tI[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      $_QLfol = "DELETE FROM `$_QlQot` WHERE users_id=".$_8L6tI[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      if(defined("SWM") && $_Ql18I != "") {
        $_QLfol = "DELETE FROM `$_Ql18I` WHERE users_id=".$_8L6tI[$_Qli6J];
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
      }


    }
  }

?>
