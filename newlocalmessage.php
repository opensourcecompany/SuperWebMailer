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
  include_once("localmessages_ops.inc.php");
  include_once("htmltools.inc.php");

  // Boolean fields of form
  $_I01C0 = Array ();

  $_I01lt = Array ();

  if(isset($_POST["BackBtn"])) {
    if( isset($_POST["OneLocalMessageId"]) )
       unset($_POST["OneLocalMessageId"]);
    include_once("browselocalmessages.php");
    exit;
  }

  $errors = array();
  $OneLocalMessageId = 0;

  if(isset($_POST['OneLocalMessageId'])) // Formular speichern?
    $OneLocalMessageId = intval($_POST['OneLocalMessageId']);
  if(isset($_POST["OneLocalMessageAction"]))
     unset( $_POST["OneLocalMessageAction"] );

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeLocalMessageCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  // Template
  $_I0600 = "";

  if(isset($_POST['SendLocalMessageBtn'])) { // Senden?
    if(empty($_POST["message_subject"]))
       $errors[] = "message_subject";
    if(empty($_POST["to_user"]))
       $errors[] = "to_user";

    if(!empty($_POST["to_user"])) {
      $_QJlJ0 = "SELECT `Username`, `id` FROM `$_Q8f1L` WHERE `Username`="._OPQLR($_POST["to_user"]);
      $_Q60l1 = mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
      if(mysql_num_rows($_Q60l1) == 0) {
         $errors[] = "to_user";
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001118"];
      } else {
        $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
        $_JOj86 = $_Q6Q1C["id"];
      }

      mysql_free_result($_Q60l1);
    }

    if(empty($_POST["LocalMessage"]))
      $errors[] = "LocalMessage";
  } else {
    $_QJlJ0 = "SELECT `From_users_id`, `MessageSubject`, `MessageText` FROM `$_Io680` WHERE id=$OneLocalMessageId AND `To_users_id`=$UserId";
    $_Q60l1 = mysql_query($_QJlJ0);
    if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
       $_Io6iJ = array();
       $_POST["to_user"] = _OELDQ($_Q6Q1C["From_users_id"], $_Io6iJ);
       $_POST["message_subject"] = "Re: ".$_Q6Q1C["MessageSubject"];
       if( strpos($_Q6Q1C["MessageText"], "<br") !== false || strpos($_Q6Q1C["MessageText"], "<p") !== false || strpos($_Q6Q1C["MessageText"], "<a") !== false || strpos($_Q6Q1C["MessageText"], "<div") !== false || strpos($_Q6Q1C["MessageText"], "<html") !== false )
          $_POST["LocalMessage"] = _ODQAB($_Q6Q1C["MessageText"]);
          else {
            $_POST["LocalMessage"] = $_Q6Q1C["MessageText"];
            $_Q8otJ = explode("\n", $_POST["LocalMessage"]);
          }
       $_POST["LocalMessage"] = wordwrap( $_POST["LocalMessage"], 70, "\n" );
       $_Q8otJ = explode("\n", $_POST["LocalMessage"]);
       for($_Q6llo=0; $_Q6llo<count($_Q8otJ); $_Q6llo++)
          $_Q8otJ[$_Q6llo] = "> ".$_Q8otJ[$_Q6llo];
       $_POST["LocalMessage"] = implode("\n", $_Q8otJ);
       mysql_free_result($_Q60l1);
    }
  }

  if(count($errors) == 0 && isset($_POST['SendLocalMessageBtn']) ) {
    if(_OELQQ($UserId, $_JOj86, $_POST["message_subject"], $_POST["LocalMessage"])) {
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001120"];
      if( isset($_POST["OneLocalMessageId"]) )
         unset($_POST["OneLocalMessageId"]);
      include_once("browselocalmessages.php");
      exit;
    } else {
      $errors[] = "LocalMessage";
      $_I0600 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001119"], mysql_error($_Q61I1));
    }
  }

  if( $OneLocalMessageId == 0 )
     $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001116"], $_I0600, 'browselocalmessages', 'new_localmessage.htm');
     else
     $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001117"], $_I0600, 'browselocalmessages', 'new_localmessage.htm');

  $_Io6iJ = array();
  $_QJCJi = _OPR6L($_QJCJi, "<LIST:FROM_USERS_ID>", "</LIST:FROM_USERS_ID>", _OELDQ($UserId, $_Io6iJ));

  if(count($errors) > 0) {
      if($_I0600 == "")
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
    }

  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  print $_QJCJi;
?>
