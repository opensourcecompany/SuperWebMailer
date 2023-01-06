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
  include_once("htmltools.inc.php");

  # Required client version
  $_8oojj = 0x100;

  if    (

     ( (!isset($_POST["AppName"])) || ($_POST["AppName"] == "") ) ||
     ( (!isset($_POST["AppVersion"])) || ($_POST["AppVersion"] == "") ) ||
     ( (!isset($_POST["ClientVersion"])) || ($_POST["ClientVersion"] == "") ) ||
     ( (!isset($_POST["Action"])) || ($_POST["Action"] == "") )

     )
  {
    _JJL0F("Login failed, unknown or missing parameters.", 9999);
    exit;
  }

  if ($_POST["ClientVersion"] < $_8oojj) {
    _JJL0F("The client software is too old. Update it with online update function.", 9998);
    exit;
  }
  
  $Action = $_POST["Action"];

  # login
  # params Username, Password
  if($Action == "login"){

   if (
   ( (!isset($_POST["Username"])) || ($_POST["Username"] == "") ) ||
   ( (!isset($_POST["Password"])) || ($_POST["Password"] == "") )
   )
   {
     _JJL0F("Login failed, unknown or missing parameters.", 9999);
     exit;
   }

   $_It0IQ = version_compare(_LBL0A(), '8.0.11') >= 0;
   
   $_QLfol = "SELECT id, Username FROM $_I18lo WHERE Username="._LRAFO($_POST["Username"])." AND ";
   if(!$_It0IQ)
     $_QLfol .= "IF(LENGTH(Password) < 80, Password=PASSWORD("._LRAFO($_POST["Password"]).")".", SUBSTRING(Password, 81)=PASSWORD("."CONCAT(SUBSTRING(Password, 1, 80), "._LRAFO($_POST["Password"]).") )".")";
     else
     $_QLfol .= "IF(LENGTH(Password) < 80, Password=SHA2("._LRAFO($_POST["Password"]).", 224)".", SUBSTRING(Password, 81)=SHA2("."CONCAT(SUBSTRING(Password, 1, 80), "._LRAFO($_POST["Password"])."), 224)".")";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);

   if ( (!$_QL8i1) || (mysql_num_rows($_QL8i1) == 0) ) {
     _JJL0F("Username / Password incorrect.", 9997);
     exit;
   }

   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   // is it a user than we need the owner_id
   $_QLfol = "SELECT owner_id FROM $_IfOtC WHERE users_id=$_QLO0f[id]";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if ( ($_QL8i1) && (mysql_num_rows($_QL8i1) > 0) ) {
     $_I016j = mysql_fetch_row($_QL8i1);
     mysql_free_result($_QL8i1);
     $_QLO0f["OwnerUserId"] = $_I016j[0];
   } else {
     $_QLO0f["OwnerUserId"] = 0;
   }

   # ignore errors if session.auto_start = 1
   if(!ini_get("session.auto_start"))
     session_start();

   #session_register("UserId", "OwnerUserId", "Username", "ClientIP");

   $_SESSION["UserId"] = $_QLO0f["id"];
   $_SESSION["OwnerUserId"] = $_QLO0f["OwnerUserId"];
   $_SESSION["Username"] = $_QLO0f["Username"];
   $_SESSION["ClientIP"] = getOwnIP();


   # Output
   $_8oCJ8 = array(
      "Return" => "OK",
      "ErrorCode" => 0,
      "UserId" => $_QLO0f["id"],
      "OwnerUserId" => $_QLO0f["OwnerUserId"],
      "SessionId" => session_id()
   );
   _JJJAJ($_8oCJ8);

   exit;
  }

  # normal functions

  ########################## check session
  if(!ini_get("session.auto_start"))
    @session_start();
  if ( ( !isset($_SESSION["UserId"]) ) Or ( !isset($_SESSION["OwnerUserId"]) ) Or ( !isset($_SESSION["Username"])) ) {
    _JJL0F("Login incorrect or Session expired.", 100000);
    exit;
  }
  ##########################

  # logout
  # params none
  if($Action == "logout") {
    session_destroy();
    # Output
    $_8oCJ8 = array(
       "Return" => "OK",
       "ErrorCode" => 0
    );
    _JJJAJ($_8oCJ8);

   exit;
  }

  ## Globale vars
  $UserId = intval($_SESSION["UserId"]);
  $OwnerUserId = intval($_SESSION["OwnerUserId"]);
  $Username = $_SESSION["Username"];
  ###

  # list of mailinglists
  # params none
  if($Action == "getmailinglists") {
    // default SQL query
    $_QLfol = "SELECT DISTINCT {} FROM $_QL88I";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_QLfol .= " WHERE (users_id=$UserId)";
       else {
        $_QLfol .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
       }

    // List of MailingLists SQL query
    $_QlI6f = str_replace("{}", "id, Name", $_QLfol);
    $_QlI6f .= " ORDER BY Name ASC";

    $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
    $_8oiIo=array();
    while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_QLO0f["Name"] = unhtmlentities($_QLO0f["Name"], $_QLo06); // UTF-8
      $_8oiIo[] = $_QLO0f;
    }
    mysql_free_result($_QL8i1);

    $_8oCJ8 = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_8oiIo),
       "SessionId" => session_id(),
       "ItemsData" => $_8oiIo
    );
    _JJJAJ($_8oCJ8);

    exit;
  }
  #

  # list groups of mailinglist
  # Params MailingListId
  if($Action == "getmailinglistgroups") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _JJL0F("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _JJODO(intval($_POST["MailingListId"]), $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f, $FormId);
    if($_I8I6o == "" || $_QljJi == "" || $_IfJ66 == "") {
      _JJL0F("Mailinglist not found.", 9995);
      exit;
    }

    $_QLfol = "SELECT id, Name FROM $_QljJi";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_8oiIo=array();
    while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_QLO0f["Name"] = unhtmlentities($_QLO0f["Name"], $_QLo06); // UTF-8
      $_8oiIo[] = $_QLO0f;
    }
    mysql_free_result($_QL8i1);

    $_8oCJ8 = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_8oiIo),
       "SessionId" => session_id(),
       "ItemsData" => $_8oiIo
    );
    _JJJAJ($_8oCJ8);

    exit;
  }

  # list of fieldnames
  # Params MailingListId
  # Params Language
  if($Action == "getfieldnames") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _JJL0F("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    if( !isset($_POST["Language"]) || $_POST["Language"] == "") {
      $_POST["Language"] = "de";
    }
    _JJODO(intval($_POST["MailingListId"]), $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f, $FormId);
    if($_I8I6o == "" || $_QljJi == "" || $_IfJ66 == "") {
      _JJL0F("Mailinglist not found.", 9995);
      exit;
    }

    $_Iflj0 = array();

    $_QLfol = "SHOW COLUMNS FROM $_I8I6o";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if ($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
        while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           foreach ($_QLO0f as $key => $_QltJO) {
              if ($key == "Field")  {
                 if(strpos($_QltJO, "u_") !== false || $_QltJO == "DateOfSubscription" || $_QltJO == "DateOfOptInConfirmation" || $_QltJO == "IPOnSubscription" || $_QltJO == "IdentString") // only user fields
                   $_Iflj0[$_QltJO] = $_QltJO;
                 break;
              }
           }
        }
        mysql_free_result($_QL8i1);
    }

    $_QLfol = "SELECT fieldname, text FROM $_Ij8oL WHERE language=". _LRAFO($_POST["Language"]);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      // no blanks & -
      $_IO08l = str_replace(" ", "_", $_QLO0f["text"]);
      $_IO08l = str_replace("-", "_", $_IO08l);
      $_IO08l = unhtmlentities($_IO08l, $_QLo06); // UTF-8
      $_Iflj0[$_QLO0f["fieldname"]] = $_IO08l;
    }
    mysql_free_result($_QL8i1);

    $_8oiIo=array();
    reset($_Iflj0);
    foreach($_Iflj0 as $key => $_QltJO)  {
       $_8oiIo[] = array($key => $_QltJO);
    }

    $_8oCJ8 = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_8oiIo),
       "SessionId" => session_id(),
       "ItemsData" => $_8oiIo
    );
    _JJJAJ($_8oCJ8);

    exit;
  }

  # get group id from name
  # Params MailingListId
  # Params GroupName
  if($Action == "getgroupid") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _JJL0F("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _JJODO(intval($_POST["MailingListId"]), $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f, $FormId);
    if($_I8I6o == "" || $_QljJi == "" || $_IfJ66 == "") {
      _JJL0F("Mailinglist not found.", 9995);
      exit;
    }

    if(!isset($_POST["GroupName"]) || $_POST["GroupName"] == "") {
      _JJL0F("GroupName not found, GroupName missing.", 9994);
      exit;
    }

    $_QLfol = "SELECT id FROM $_QljJi WHERE Name="._LRAFO($_POST["GroupName"]);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1 && mysql_num_rows($_QL8i1) > 0) {
       $_QLO0f = mysql_fetch_row($_QL8i1);
       $_8oCJ8 = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "GroupsId" => $_QLO0f[0],
          "SessionId" => session_id(),
       );
       _JJJAJ($_8oCJ8);
    } else {
      _JJL0F("Group not found", 9993);
    }

    exit;
  }

  # count recipients
  # Params MailingListId
  # Params GroupsId or 0
  if($Action == "getrecipientcount") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _JJL0F("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _JJODO(intval($_POST["MailingListId"]), $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f, $FormId);
    if($_I8I6o == "" || $_QljJi == "" || $_IfJ66 == "") {
      _JJL0F("Mailinglist not found.", 9995);
      exit;
    }

    $_I1OoI = array();
    $_I1OoI[] = "COUNT($_I8I6o.id)";

    $_QLfol = "SELECT ".join(",", $_I1OoI)." FROM $_I8I6o";

    $_QLfol .= " LEFT JOIN `$_I8tfQ` ON `$_I8tfQ`.`u_EMail` = `$_I8I6o`.`u_EMail`";
    $_QLfol .= " LEFT JOIN `$_jjj8f` ON `$_jjj8f`.`u_EMail` = `$_I8I6o`.`u_EMail`";

    if (isset($_POST["GroupsId"]) && $_POST["GroupsId"] != "" && $_POST["GroupsId"] > 0 ) {
      $_QLfol .= " LEFT JOIN $_IfJ66 ON $_I8I6o.id=$_IfJ66.Member_id";
    }

    $_QLfol .= " WHERE ($_I8I6o.IsActive=1 AND $_I8I6o.SubscriptionStatus<>'OptInConfirmationPending')";
    $_QLfol .= " AND `$_I8tfQ`.`u_EMail` IS NULL AND `$_jjj8f`.`u_EMail` IS NULL ";

    if (isset($_POST["GroupsId"]) && $_POST["GroupsId"] != "" && $_POST["GroupsId"] > 0 )
      $_QLfol .= " AND $_IfJ66.groups_id = ".intval($_POST["GroupsId"]);


    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);

    $_8oCJ8 = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => $_QLO0f[0],
       "SessionId" => session_id(),
    );
    _JJJAJ($_8oCJ8);

    exit;
  }

  # list of recipients
  # Params MailingListId
  # Params GroupsId or 0
  # Params Start
  # Params Limit
  # Params Fields
  if($Action == "getrecipients") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _JJL0F("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _JJODO(intval($_POST["MailingListId"]), $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f, $FormId);
    if($_I8I6o == "" || $_QljJi == "" || $_IfJ66 == "") {
      _JJL0F("Mailinglist not found.", 9995);
      exit;
    }
    if( !isset($_POST["Language"]) || $_POST["Language"] == "") {
      $_POST["Language"] = "de";
    }
    if(!isset($_POST["Fields"]) || $_POST["Fields"] == "")
      $_POST["Fields"] = "*";

    if(strpos($_POST["Fields"], ",") !== true) {
      $_I1OoI = explode(',', $_POST["Fields"]);
    } else
      $_I1OoI[] = $_POST["Fields"];
    $_I1OoI[] = "id AS internalRecipientsId";

    for($_Qli6J=0;$_Qli6J<count($_I1OoI);$_Qli6J++) {
      $_I1OoI[$_Qli6J] = "`$_I8I6o`.".trim($_I1OoI[$_Qli6J]);
    }

    $_QLfol = "SELECT ".join(",", $_I1OoI)." FROM $_I8I6o";

    $_QLfol .= " LEFT JOIN `$_I8tfQ` ON `$_I8tfQ`.`u_EMail` = `$_I8I6o`.`u_EMail`";
    $_QLfol .= " LEFT JOIN `$_jjj8f` ON `$_jjj8f`.`u_EMail` = `$_I8I6o`.`u_EMail`";

    if (isset($_POST["GroupsId"]) && $_POST["GroupsId"] != "" && $_POST["GroupsId"] > 0 ) {
      $_QLfol .= " LEFT JOIN $_IfJ66 ON $_I8I6o.id=$_IfJ66.Member_id";
    }

    $_QLfol .= " WHERE ($_I8I6o.IsActive=1 AND $_I8I6o.SubscriptionStatus<>'OptInConfirmationPending')";
    $_QLfol .= " AND `$_I8tfQ`.`u_EMail` IS NULL AND `$_jjj8f`.`u_EMail` IS NULL ";

    if (isset($_POST["GroupsId"]) && $_POST["GroupsId"] != "" && $_POST["GroupsId"] > 0 )
      $_QLfol .= " AND $_IfJ66.groups_id = ".intval($_POST["GroupsId"]);

    if(isset($_POST["Start"]) && isset($_POST["Limit"]) && $_POST["Limit"] > 0) {
      $_QLfol .= " LIMIT ".intval($_POST["Start"]).", ".intval($_POST["Limit"]);
    }
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);

    $_8oiIo=array();
    while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_I0Clj = "";
      reset($_QLO0f);
      foreach($_QLO0f as $key => $_QltJO) {

         if($key == "internalRecipientsId") continue;
         if($key == "IdentString")
           $_QltJO = _LPQ8Q($_QltJO, $_QLO0f["internalRecipientsId"], intval($_POST["MailingListId"]), $FormId, $_I8I6o);

         $_QltJO = str_replace(";", "", unhtmlentities(_LCRC8($_QltJO), "utf-8"));
         if($_I0Clj == "")
           $_I0Clj = _LRBC0($_QltJO);
           else
           $_I0Clj .= ";"._LRBC0($_QltJO);
      }

      $_I0Clj = str_replace("\r\n", '\n', $_I0Clj);
      $_I0Clj = str_replace("\r", '\n', $_I0Clj);
      $_I0Clj = str_replace("\n", '\n', $_I0Clj);
      $_8oiIo[] = $_I0Clj;
    }
    mysql_free_result($_QL8i1);

    $_8oCJ8 = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_8oiIo),
       "SessionId" => session_id(),
       "ItemsData" => join(chr(14).chr(15), $_8oiIo)  // #E#F as line separator
    );
    _JJJAJ($_8oCJ8);

    exit;
  }

  # count recipients in local blocklist
  # Params MailingListId
  if($Action == "getlocalblocklistentrycount") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _JJL0F("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _JJODO($_POST["MailingListId"], $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f, $FormId);
    if($_I8I6o == "" || $_QljJi == "" || $_IfJ66 == "") {
      _JJL0F("Mailinglist not found.", 9995);
      exit;
    }

    $_QLfol = "SELECT COUNT(*) FROM $_jjj8f";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);

    $_8oCJ8 = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => $_QLO0f[0],
       "SessionId" => session_id(),
    );
    _JJJAJ($_8oCJ8);

    exit;
  }

  # list of local blocklist entries
  # Params MailingListId
  # Params Start
  # Params Limit
  if($Action == "getlocalblocklistentries") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _JJL0F("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _JJODO(intval($_POST["MailingListId"]), $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f, $FormId);
    if($_I8I6o == "" || $_QljJi == "" || $_IfJ66 == "") {
      _JJL0F("Mailinglist not found.", 9995);
      exit;
    }

    $_QLfol = "SELECT u_EMail FROM $_jjj8f";

    if(isset($_POST["Start"]) && isset($_POST["Limit"]) && $_POST["Limit"] > 0) {
      $_QLfol .= " LIMIT ".intval($_POST["Start"]).", ".intval($_POST["Limit"]);
    }
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);

    $_8oiIo=array();
    while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_I0Clj = "";
      reset($_QLO0f);
      foreach($_QLO0f as $key => $_QltJO) {
         if($_I0Clj == "")
           $_I0Clj = _LRBC0(unhtmlentities($_QltJO, "utf-8"));
           else
           $_I0Clj .= ";"._LRBC0(unhtmlentities($_QltJO, "utf-8"));
      }

      $_8oiIo[] = $_I0Clj;
    }
    mysql_free_result($_QL8i1);

    $_8oCJ8 = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_8oiIo),
       "SessionId" => session_id(),
       "ItemsData" => join(chr(14).chr(15), $_8oiIo)  // #E#F as line separator
    );
    _JJJAJ($_8oCJ8);

    exit;
  }

  # count recipients in global blocklist
  if($Action == "getglobalblocklistentrycount") {

    // Load User tables
    $_j6lIj = intval($_SESSION["OwnerUserId"]);
    if($_j6lIj == 0)
      $_j6lIj = intval($_SESSION["UserId"]);
    $_QLfol = "SELECT * FROM $_I18lo WHERE id=$_j6lIj";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_I8tfQ = $_QLO0f["GlobalBlockListTableName"];

    $_QLfol = "SELECT COUNT(*) FROM $_I8tfQ";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);

    $_8oCJ8 = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => $_QLO0f[0],
       "SessionId" => session_id(),
    );
    _JJJAJ($_8oCJ8);

    exit;
  }

  # list of global blocklist entries
  # Params Start
  # Params Limit
  if($Action == "getglobalblocklistentries") {

    // Load User tables
    $_j6lIj = intval($_SESSION["OwnerUserId"]);
    if($_j6lIj == 0)
      $_j6lIj = intval($_SESSION["UserId"]);
    $_QLfol = "SELECT * FROM $_I18lo WHERE id=$_j6lIj";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLfol = "SELECT u_EMail FROM $_I8tfQ";

    if(isset($_POST["Start"]) && isset($_POST["Limit"]) && $_POST["Limit"] > 0) {
      $_QLfol .= " LIMIT ".intval($_POST["Start"]).", ".intval($_POST["Limit"]);
    }
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);

    $_8oiIo=array();
    while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_I0Clj = "";
      reset($_QLO0f);
      foreach($_QLO0f as $key => $_QltJO) {
         if($_I0Clj == "")
           $_I0Clj = _LRBC0(unhtmlentities($_QltJO, "utf-8"));
           else
           $_I0Clj .= ";"._LRBC0(unhtmlentities($_QltJO, "utf-8"));
      }

      $_8oiIo[] = $_I0Clj;
    }
    mysql_free_result($_QL8i1);

    $_8oCJ8 = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_8oiIo),
       "SessionId" => session_id(),
       "ItemsData" => join(chr(14).chr(15), $_8oiIo)  // #E#F as line separator
    );
    _JJJAJ($_8oCJ8);

    exit;
  }

  if($Action == "updaterecipient") {

    $_IfLJj = 0;
    $MailingListId = 0;
    $FormId = 0;
    if(!empty($_POST["IdentString"])) {
       if(!_LPQEP($_POST["IdentString"], $_IfLJj, $MailingListId, $FormId)) {
         _JJL0F("Invalid IdentString.", 9990);
         exit;
       }


       $_8oLif = "id=".$MailingListId;
    }
    else
    if(!empty($_POST["EMail"]) && !empty($_POST["MailingListName"])) {

      $_8oLif = "Name="._LRAFO($_POST["MailingListName"]);

    } else {
       _JJL0F("Invalid IdentString, EMail or MailingListName.", 9989);
       exit;
    }


    $_QLfol = "SELECT $_QL88I.id, $_QL88I.MaillistTableName, $_QL88I.MailLogTableName FROM $_QL88I";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_QLfol .= " WHERE (users_id=$UserId)";
       else {
        $_QLfol .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
       }

    $_QLfol .= " AND $_8oLif";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1) {
      _JJL0F("Invalid MailingListName.", 9988);
      exit;
    }
    while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $MailingListId = $_QLO0f["id"];
      $_I8I6o = $_QLO0f["MaillistTableName"];
      $_I8jLt = $_QLO0f["MailLogTableName"];
    }
    mysql_free_result($_QL8i1);

    if($_IfLJj == 0) {
      $_QLfol = "SELECT id FROM $_I8I6o WHERE `u_EMail`="._LRAFO($_POST["EMail"]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1) {
        _JJL0F("Invalid Recipient.", 9988);
        exit;
      }
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_IfLJj = $_QLO0f["id"];
    }

    $_fji10 = $_POST["DateTime"]." - ".$_POST["MailSubject"];
    $_QLfol = "UPDATE `$_I8jLt` SET `MailLog`=CONCAT(`MailLog`, "._LRAFO($_QLl1Q.$_fji10).") WHERE `Member_id`=$_IfLJj";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);

    if(mysql_affected_rows($_QLttI) == 0) {
      $_QLfol = "INSERT INTO `$_I8jLt` SET `Member_id`=$_IfLJj, `MailLog`="._LRAFO($_fji10);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    }
    if($_QL8i1){
      $_QLfol = "UPDATE $_I8I6o SET `LastEMailSent`="._LRAFO($_POST["DateTime"])." WHERE `id`=$_IfLJj";
      mysql_query($_QLfol, $_QLttI);
    }
    if($_QL8i1) {
       $_8oCJ8 = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "SessionId" => session_id()
       );
       _JJJAJ($_8oCJ8);
    }
    else
      _JJL0F("Can't update maillog.", 9987);
    exit;
  }

  function _JJODO($_8olOf, &$_I8I6o, &$_QljJi, &$_IfJ66, &$_jjj8f, &$FormId) {
   global $_QL88I, $_QlQot, $_I18lo, $_QLttI;
   global $UserId, $OwnerUserId, $_I8tfQ;
   $_I8I6o = "";
   $_QljJi = "";
   $_IfJ66 = "";
   $FormId = 0;
   $_8olOf = intval($_8olOf);

   $_QLfol = "SELECT GlobalBlockListTableName FROM $_I18lo WHERE id=";
   if($OwnerUserId == 0) // ist es ein Admin?
      $_QLfol .= $UserId;
      else
      $_QLfol .= $OwnerUserId;
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && mysql_num_rows($_QL8i1) == 1) {
     $_QLO0f = mysql_fetch_row($_QL8i1);
     mysql_free_result($_QL8i1);
     $_I8tfQ = $_QLO0f[0];
   } else {
     _JJL0F("User not found", 9996);
     exit;
   }

   $_QLfol = "SELECT MaillistTableName, GroupsTableName, MailListToGroupsTableName, LocalBlocklistTableName, forms_id FROM $_QL88I";
   if($OwnerUserId == 0) // ist es ein Admin?
      $_QLfol .= " WHERE (users_id=$UserId)";
      else {
       $_QLfol .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
      }
   $_QLfol .= " AND $_QL88I.id=$_8olOf";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && mysql_num_rows($_QL8i1) == 1) {
     $_QLO0f = mysql_fetch_row($_QL8i1);
     $_I8I6o = $_QLO0f[0];
     $_QljJi = $_QLO0f[1];
     $_IfJ66 = $_QLO0f[2];
     $_jjj8f = $_QLO0f[3];
     $FormId = $_QLO0f[4];
     mysql_free_result($_QL8i1);
   } else {
     _JJL0F("Mailinglist not found", 9996);
     exit;
   }
  }


  function _JJL0F($_J0COJ, $_8olOL, $_8oloj = false) {
     if (!$_8oloj) {
       print "Return: ERROR\r\n";
       print "ErrorCode: $_8olOL\r\n";
       print "ErrorText: $_J0COJ\r\n";
       print "\r\n";
       flush();
       exit;
     } else {
       return array(
          "Return" => "ERROR",
          "ErrorCode" => $_8olOL,
          "ErrorText" => $_J0COJ
       );
     }
  }

  function _JJLE6($_QLJfI) {
   $_QLJfI = str_replace("\r", "", $_QLJfI);
   $_QLJfI = str_replace("\n", '\n', $_QLJfI);
   $_QLJfI = str_replace("</", '<%2F', $_QLJfI);
   return $_QLJfI;
  }

  function _JJJAJ($_8oCJ8) {
    reset($_8oCJ8);
    foreach ($_8oCJ8 as $key => $_QltJO) {
      if(!is_array($_QltJO)) {
        echo "$key: $_QltJO\r\n";
      } else {
        reset($_QltJO);

        echo "$key: ";
        for($_Qli6J=0; $_Qli6J<count($_QltJO); $_Qli6J++) {
          echo "<item value=\"$_Qli6J\">";
          if(is_array($_QltJO[$_Qli6J]) )
            foreach ($_QltJO[$_Qli6J] as $_IOLil => $_IOCjL) {
              echo "<$_IOLil>"._JJLE6($_IOCjL)."</$_IOLil>";
            }
          if(!is_array($_QltJO[$_Qli6J]))
            echo _JJLE6($_QltJO[$_Qli6J]);
          echo "</item>";
        }

      }
    }
    echo "\r\n";
    flush();
  }


?>
