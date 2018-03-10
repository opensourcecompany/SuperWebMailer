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
  include_once("savedoptions.inc.php");
  include_once("inboxcheck.php");

  if( !isset($_GET["inbox_id"]) && !isset($_POST["inbox_id"])  )
    exit;

  if(isset($_Jt6ft))
     unset($_Jt6ft);

  if(isset($_GET["inbox_id"]))
    $_Jt6ft = intval($_GET["inbox_id"]);
  if(isset($_POST["inbox_id"]))
    $_Jt6ft = intval($_POST["inbox_id"]);

  if(isset($_Jt6ft)) {
    SetHTMLHeaders($_Q6QQL);

    $_I0600 = "";

    // Inboxes
    $_QJlJ0 = "SELECT * FROM $_QolLi WHERE id=".$_Jt6ft;
    $_Q60l1 = mysql_query($_QJlJ0);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) exit; // spam protection
    $_Jt6fL = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);

    $_jftQf = new _ODPCC(InstallPath."js");

    $_jftQf->Name = $_Jt6fL["Name"];
    $_jftQf->InboxType = $_Jt6fL["InboxType"]; // 'pop3', 'imap'
    $_jftQf->EMailAddress = $_Jt6fL["EMailAddress"];
    $_jftQf->Servername = $_Jt6fL["Servername"];
    $_jftQf->Serverport = $_Jt6fL["Serverport"];
    $_jftQf->Username = $_Jt6fL["Username"];
    $_jftQf->Password = $_Jt6fL["Password"];
    $_jftQf->SSLConnection = $_Jt6fL["SSL"];
    $_jftQf->LeaveMessagesInInbox = $_Jt6fL["LeaveMessagesInInbox"];
    $_jftQf->NumberOfEMailsToProcess = _LQDLR("BounceEMailCount");
    if($_Jt6fL["UIDL"] != "") {
         $_jftQf->UIDL = @unserialize($_Jt6fL["UIDL"]);
         if($_jftQf->UIDL === false)
            $_jftQf->UIDL = array();
       }
       else
       $_jftQf->UIDL = array();

    $_jj0JO = "";
    $_J8C8O = 0;
    $_Q8COf = $_jftQf->_ODA1F($_jj0JO, $_J8C8O);
    if($_Q8COf) {
      $_I0600 = sprintf( $resourcestrings[$INTERFACE_LANGUAGE]["000164"], $_J8C8O);
    } else {
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000165"].$_jj0JO;
    }

    $_QJCJi = _ODFLQ($_I0600, $_Jt6fL["Name"]);
    print $_QJCJi;
    exit;
  }

 function _ODFLQ($_I0600, $_Jtf01) {
   global $_Q8f1L, $_Jtf66, $UserId, $resourcestrings, $INTERFACE_LANGUAGE, $_Q6JJJ;
    $_QJCJi = join("", file(_O68QF()."inbox_test.htm"));
    if(isset($_GET["inbox_id"]))
       $_Jt6ft = intval($_GET["inbox_id"]);
       else
       $_Jt6ft = intval($_POST["inbox_id"]);
    $_QJCJi = str_replace('name="inbox_id"', 'name="inbox_id" value="'.$_Jt6ft.'"', $_QJCJi);

    // spam protection
    if($UserId == 0) exit;


    $_QJCJi = str_replace('%SERVER_NAME%', $_Jtf01, $_QJCJi);

    if($_I0600 == "") {
      $_QJCJi = _OP6PQ($_QJCJi, "<SHOWHIDE:ERRORTOPIC>", "</SHOWHIDE:ERRORTOPIC>");
    } else {
      $_QJCJi = _OPR6L($_QJCJi, "<LABEL:ERRORMESSAGETEXT>", "</LABEL:ERRORMESSAGETEXT>", $_I0600 );
    }

    return $_QJCJi;
 }

?>
