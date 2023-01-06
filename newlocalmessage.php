<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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
  $_ItI0o = Array ();

  $_ItIti = Array ();

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
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeLocalMessageCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  // Template
  $_Itfj8 = "";

  if(isset($_POST['SendLocalMessageBtn'])) { // Senden?
    if(empty($_POST["message_subject"]))
       $errors[] = "message_subject";
    if(empty($_POST["to_user"]))
       $errors[] = "to_user";

    if(!empty($_POST["to_user"])) {
      $_QLfol = "SELECT `Username`, `id` FROM `$_I18lo` WHERE `Username`="._LRAFO($_POST["to_user"]);
      $_QL8i1 = mysql_query($_QLfol);
      _L8D88($_QLfol);
      if(mysql_num_rows($_QL8i1) == 0) {
         $errors[] = "to_user";
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001118"];
      } else {
        $_QLO0f = mysql_fetch_assoc($_QL8i1);
        $_6lJol = $_QLO0f["id"];
      }

      mysql_free_result($_QL8i1);
    }

    if(empty($_POST["LocalMessage"]))
      $errors[] = "LocalMessage";
  } else {
    $_QLfol = "SELECT `From_users_id`, `MessageSubject`, `MessageText` FROM `$_jJtt8` WHERE id=$OneLocalMessageId AND `To_users_id`=$UserId";
    $_QL8i1 = mysql_query($_QLfol);
    if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)) {
       $_jJO6I = array();
       $_POST["to_user"] = _LDAF0($_QLO0f["From_users_id"], $_jJO6I);
       $_POST["message_subject"] = "Re: ".$_QLO0f["MessageSubject"];
       if( strpos($_QLO0f["MessageText"], "<br") !== false || strpos($_QLO0f["MessageText"], "<p") !== false || strpos($_QLO0f["MessageText"], "<a") !== false || strpos($_QLO0f["MessageText"], "<div") !== false || strpos($_QLO0f["MessageText"], "<html") !== false )
          $_POST["LocalMessage"] = _LBDA8($_QLO0f["MessageText"]);
          else {
            $_POST["LocalMessage"] = $_QLO0f["MessageText"];
            $_I1OoI = explode("\n", $_POST["LocalMessage"]);
          }
       $_POST["LocalMessage"] = wordwrap( $_POST["LocalMessage"], 70, "\n" );
       $_I1OoI = explode("\n", $_POST["LocalMessage"]);
       for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++)
          $_I1OoI[$_Qli6J] = "> ".$_I1OoI[$_Qli6J];
       $_POST["LocalMessage"] = implode("\n", $_I1OoI);
       mysql_free_result($_QL8i1);
    }
  }

  if(count($errors) == 0 && isset($_POST['SendLocalMessageBtn']) ) {
    if(_LDADP($UserId, $_6lJol, $_POST["message_subject"], $_POST["LocalMessage"])) {
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001120"];
      if( isset($_POST["OneLocalMessageId"]) )
         unset($_POST["OneLocalMessageId"]);
      include_once("browselocalmessages.php");
      exit;
    } else {
      $errors[] = "LocalMessage";
      $_Itfj8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001119"], mysql_error($_QLttI));
    }
  }

  if( $OneLocalMessageId == 0 )
     $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001116"], $_Itfj8, 'browselocalmessages', 'new_localmessage.htm');
     else
     $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001117"], $_Itfj8, 'browselocalmessages', 'new_localmessage.htm');

  $_jJO6I = array();
  $_QLJfI = _L81BJ($_QLJfI, "<LIST:FROM_USERS_ID>", "</LIST:FROM_USERS_ID>", _LDAF0($UserId, $_jJO6I));

  if(count($errors) > 0) {
      if($_Itfj8 == "")
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
    }

  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

  print $_QLJfI;
?>
