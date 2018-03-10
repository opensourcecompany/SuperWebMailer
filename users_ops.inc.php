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

  // used from browserecpts.php, browsmailinglists.php, nl.php, defaultnewsletter.php

  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP") && !defined("API"))
    exit;

  if(!defined("API")) {
    $_fjfo1 = array();

    if ( isset($_POST["OneUserId"]) && $_POST["OneUserId"] != "" && intval($_POST["OneUserId"]) != 0 )
        $_fjfo1[] = intval($_POST["OneUserId"]);
        else
        if ( isset($_POST["UsersIDs"]) ){
          foreach($_POST["UsersIDs"] as $key) {
            $_fjfo1[] = intval($key);
          }
        }


    if  ( ( isset($_POST["UsersActions"]) && $_POST["UsersActions"] == "RemoveUsers" ) ||
       ( isset($_POST["OneUserAction"]) && $_POST["OneUserAction"] == "DeleteUser" )
       ) {
            if(isset($_QtIiC))
              unset($_QtIiC);
            $_QtIiC = array();
            _L6OBB($_fjfo1, $_QtIiC);
      }
  }

  // we don't check for errors here
 function _L6OBB($_fjfo1, &$_QtIiC) {
    global $_Q8f1L, $_QLtQO, $_Q6fio, $_Io680, $_Q6ftI;
    global $OwnerUserId, $UserId, $_Q61I1;
    for($_Q6llo=0; $_Q6llo<count($_fjfo1); $_Q6llo++) {
      $_fjfo1[$_Q6llo] = intval($_fjfo1[$_Q6llo]);
      // TableLocalUserMessages
      $_QJlJ0 = "DELETE FROM `$_Io680` WHERE `From_users_id`=$_fjfo1[$_Q6llo] OR `To_users_id`=$_fjfo1[$_Q6llo]";
      mysql_query($_QJlJ0, $_Q61I1);

      // Admin, remove tables
      if($OwnerUserId == 0) {
        $_QJlJ0 = "SELECT * FROM `$_Q8f1L` WHERE id=".$_fjfo1[$_Q6llo];
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
        $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
        if(!$_Q6Q1C) continue;
        mysql_free_result($_Q60l1);

        foreach($_Q6Q1C as $key => $_Q6ClO) {
          if(strpos($key, "TableName") === false) continue; // only Tables!!
          if($_Q6ClO != "") {
            _OBOAB($_Q6ClO);

            $_QJlJ0 = "DROP TABLE IF EXISTS `$_Q6ClO`";
            mysql_query($_QJlJ0, $_Q61I1);
            if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
          }
        }
      }

      // Delete User
      $_QJlJ0 = "DELETE FROM `$_Q8f1L` WHERE id=".$_fjfo1[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      $_QJlJ0 = "DELETE FROM `$_QLtQO` WHERE users_id=".$_fjfo1[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      $_QJlJ0 = "DELETE FROM `$_Q6fio` WHERE users_id=".$_fjfo1[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      if(defined("SWM") && $_Q6ftI != "") {
        $_QJlJ0 = "DELETE FROM `$_Q6ftI` WHERE users_id=".$_fjfo1[$_Q6llo];
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
      }


    }
  }

?>
