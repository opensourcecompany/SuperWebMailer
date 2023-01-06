<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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
  include_once("savedoptions.inc.php");
  include_once("inboxcheck.php");

  if( !isset($_GET["inbox_id"]) && !isset($_POST["inbox_id"])  )
    exit;

  if(isset($_6Ctt8))
     unset($_6Ctt8);

  if(isset($_GET["inbox_id"]))
    $_6Ctt8 = intval($_GET["inbox_id"]);
  if(isset($_POST["inbox_id"]))
    $_6Ctt8 = intval($_POST["inbox_id"]);

  if(isset($_6Ctt8)) {
    SetHTMLHeaders($_QLo06);

    $_Itfj8 = "";

    // Inboxes
    $_QLfol = "SELECT * FROM $_IjljI WHERE id=".$_6Ctt8;
    $_QL8i1 = mysql_query($_QLfol);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) exit; // spam protection
    $_6CO8C = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);

    $_Jji6J = new _LCAO8(InstallPath."js");

    $_Jji6J->Name = $_6CO8C["Name"];
    $_Jji6J->InboxType = $_6CO8C["InboxType"]; // 'pop3', 'imap'
    $_Jji6J->EMailAddress = $_6CO8C["EMailAddress"];
    $_Jji6J->Servername = $_6CO8C["Servername"];
    $_Jji6J->Serverport = $_6CO8C["Serverport"];
    $_Jji6J->Username = $_6CO8C["Username"];
    $_Jji6J->Password = $_6CO8C["Password"];
    $_Jji6J->SSLConnection = $_6CO8C["SSL"];
    $_Jji6J->LeaveMessagesInInbox = $_6CO8C["LeaveMessagesInInbox"];
    $_Jji6J->NumberOfEMailsToProcess = _JOLQE("BounceEMailCount");
    if( isset($_6CO8C["UIDL"]) && $_6CO8C["UIDL"] != "") {
         $_Jji6J->UIDL = @unserialize($_6CO8C["UIDL"]);
         if($_Jji6J->UIDL === false)
            $_Jji6J->UIDL = array();
       }
       else
       $_Jji6J->UIDL = array();

    $_J0COJ = "";
    $_6oL8t = 0;
    $_I1o8o = $_Jji6J->_LCAR0($_J0COJ, $_6oL8t);
    if($_I1o8o) {
      $_Itfj8 = sprintf( $resourcestrings[$INTERFACE_LANGUAGE]["000164"], $_6oL8t);
    } else {
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000165"].$_J0COJ;
    }

    $_QLJfI = _LCFDR($_Itfj8, $_6CO8C["Name"]);
    print $_QLJfI;
    exit;
  }

 function _LCFDR($_Itfj8, $_6CoI6) {
   global $_I18lo, $_6Coii, $UserId, $resourcestrings, $INTERFACE_LANGUAGE, $_QLl1Q;
    $_QLJfI = _JJAQE("inbox_test.htm");
    if(isset($_GET["inbox_id"]))
       $_6Ctt8 = intval($_GET["inbox_id"]);
       else
       $_6Ctt8 = intval($_POST["inbox_id"]);
    $_QLJfI = str_replace('name="inbox_id"', 'name="inbox_id" value="'.$_6Ctt8.'"', $_QLJfI);

    // spam protection
    if($UserId == 0) exit;


    $_QLJfI = str_replace('%SERVER_NAME%', $_6CoI6, $_QLJfI);

    if($_Itfj8 == "") {
      $_QLJfI = _L80DF($_QLJfI, "<SHOWHIDE:ERRORTOPIC>", "</SHOWHIDE:ERRORTOPIC>");
    } else {
      $_QLJfI = _L81BJ($_QLJfI, "<LABEL:ERRORMESSAGETEXT>", "</LABEL:ERRORMESSAGETEXT>", $_Itfj8 );
    }

    return $_QLJfI;
 }

?>
