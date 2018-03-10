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

  if($OwnerUserId != 0) {
    if(empty($_QJojf) || !is_array($_QJojf))
      $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeBirthdayMailsBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(isset($_66Jti))
     unset($_66Jti);
  $_66Jti = array();
  if ( isset($_POST["OneBirthdayresponderListId"]) && $_POST["OneBirthdayresponderListId"] != "" )
      $_66Jti[] = $_POST["OneBirthdayresponderListId"];
      else
      if ( isset($_POST["OneBirthdayresponderListIds"]) )
        $_66Jti = array_merge($_66Jti, $_POST["OneBirthdayresponderListIds"]);


  $_QtIiC = array();
  _L1PQL($_66Jti, $_QtIiC);

  // we don't check for errors here
  function _L1PQL($_66Jti, &$_QtIiC) {
    global $_IIl8O, $_IjQIf, $_Q61I1;

    for($_Q6llo=0; $_Q6llo<count($_66Jti); $_Q6llo++) {
      $_66Jti[$_Q6llo] = intval($_66Jti[$_Q6llo]);

      $_QJlJ0 = "SELECT * FROM $_IIl8O WHERE id=$_66Jti[$_Q6llo]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      reset($_Q6Q1C);
      foreach($_Q6Q1C as $key => $_Q6ClO) {
        if (strpos($key, "TableName") !== false) {
          $_QJlJ0 = "DROP TABLE IF EXISTS `$_Q6ClO`";
          mysql_query($_QJlJ0, $_Q61I1);
          if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
        }
      }

      // and now from Birthdayresponders table
      $_QJlJ0 = "DELETE FROM $_IIl8O WHERE id=".$_66Jti[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      // stat
      $_QJlJ0 = "DELETE FROM $_IjQIf WHERE birthdayresponders_id=".$_66Jti[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

    }
  }

?>
