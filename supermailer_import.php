<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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
  $_f1jLo = 0x100;

  if    (

     ( (!isset($_POST["AppName"])) || ($_POST["AppName"] == "") ) ||
     ( (!isset($_POST["AppVersion"])) || ($_POST["AppVersion"] == "") ) ||
     ( (!isset($_POST["ClientVersion"])) || ($_POST["ClientVersion"] == "") ) ||
     ( (!isset($_POST["Action"])) || ($_POST["Action"] == "") )

     )
  {
    _LJ06L("Login failed, unknown or missing parameters.", 9999);
    exit;
  }

  if ($_POST["ClientVersion"] < $_f1jLo) {
    _LJ06L("The client software is too old. Update it with online update function.", 9998);
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
     _LJ06L("Login failed, unknown or missing parameters.", 9999);
     exit;
   }


   $_QJlJ0 = "SELECT id, Username FROM $_Q8f1L WHERE Username="._OPQLR($_POST["Username"])." AND ";
   $_QJlJ0 .= "IF(LENGTH(Password) < 80, Password=PASSWORD("._OPQLR($_POST["Password"]).")".", SUBSTRING(Password, 81)=PASSWORD("."CONCAT(SUBSTRING(Password, 1, 80), "._OPQLR($_POST["Password"]).") )".")";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

   if ( (!$_Q60l1) || (mysql_num_rows($_Q60l1) == 0) ) {
     _LJ06L("Username / Password incorrect.", 9997);
     exit;
   }

   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   // is it a user than we need the owner_id
   $_QJlJ0 = "SELECT owner_id FROM $_QLtQO WHERE users_id=$_Q6Q1C[id]";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if ( ($_Q60l1) && (mysql_num_rows($_Q60l1) > 0) ) {
     $_QllO8 = mysql_fetch_row($_Q60l1);
     mysql_free_result($_Q60l1);
     $_Q6Q1C["OwnerUserId"] = $_QllO8[0];
   } else {
     $_Q6Q1C["OwnerUserId"] = 0;
   }

   # ignore errors if session.auto_start = 1
   if(!ini_get("session.auto_start"))
     session_start();

   #session_register("UserId", "OwnerUserId", "Username", "ClientIP");

   $_SESSION["UserId"] = $_Q6Q1C["id"];
   $_SESSION["OwnerUserId"] = $_Q6Q1C["OwnerUserId"];
   $_SESSION["Username"] = $_Q6Q1C["Username"];
   $_SESSION["ClientIP"] = getOwnIP();


   # Output
   $_f1fOo = array(
      "Return" => "OK",
      "ErrorCode" => 0,
      "UserId" => $_Q6Q1C["id"],
      "OwnerUserId" => $_Q6Q1C["OwnerUserId"],
      "SessionId" => session_id()
   );
   _LJ0AP($_f1fOo);

   exit;
  }

  # normal functions

  ########################## check session
  if(!ini_get("session.auto_start"))
    @session_start();
  if ( ( !isset($_SESSION["UserId"]) ) Or ( !isset($_SESSION["OwnerUserId"]) ) Or ( !isset($_SESSION["Username"])) ) {
    _LJ06L("Login incorrect or Session expired.", 100000);
    exit;
  }
  ##########################

  # logout
  # params none
  if($Action == "logout") {
    session_destroy();
    # Output
    $_f1fOo = array(
       "Return" => "OK",
       "ErrorCode" => 0
    );
    _LJ0AP($_f1fOo);

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
    $_QJlJ0 = "SELECT DISTINCT {} FROM $_Q60QL";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_QJlJ0 .= " WHERE (users_id=$UserId)";
       else {
        $_QJlJ0 .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
       }

    // List of MailingLists SQL query
    $_Q68ff = str_replace("{}", "id, Name", $_QJlJ0);
    $_Q68ff .= " ORDER BY Name ASC";

    $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
    $_f18Q0=array();
    while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_f18Q0[] = $_Q6Q1C;
    }
    mysql_free_result($_Q60l1);

    $_f1fOo = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_f18Q0),
       "SessionId" => session_id(),
       "ItemsData" => $_f18Q0
    );
    _LJ0AP($_f1fOo);

    exit;
  }
  #

  # list groups of mailinglist
  # Params MailingListId
  if($Action == "getmailinglistgroups") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _LJ06L("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _LLF8C(intval($_POST["MailingListId"]), $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo, $FormId);
    if($_QlQC8 == "" || $_Q6t6j == "" || $_QLI68 == "") {
      _LJ06L("Mailinglist not found.", 9995);
      exit;
    }

    $_QJlJ0 = "SELECT id, Name FROM $_Q6t6j";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_f18Q0=array();
    while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_f18Q0[] = $_Q6Q1C;
    }
    mysql_free_result($_Q60l1);

    $_f1fOo = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_f18Q0),
       "SessionId" => session_id(),
       "ItemsData" => $_f18Q0
    );
    _LJ0AP($_f1fOo);

    exit;
  }

  # list of fieldnames
  # Params MailingListId
  # Params Language
  if($Action == "getfieldnames") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _LJ06L("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    if( !isset($_POST["Language"]) || $_POST["Language"] == "") {
      $_POST["Language"] = "de";
    }
    _LLF8C(intval($_POST["MailingListId"]), $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo, $FormId);
    if($_QlQC8 == "" || $_Q6t6j == "" || $_QLI68 == "") {
      _LJ06L("Mailinglist not found.", 9995);
      exit;
    }

    $_QLLjo = array();

    $_QJlJ0 = "SHOW COLUMNS FROM $_QlQC8";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if ($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
        while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
           foreach ($_Q6Q1C as $key => $_Q6ClO) {
              if ($key == "Field")  {
                 if(strpos($_Q6ClO, "u_") !== false || $_Q6ClO == "DateOfSubscription" || $_Q6ClO == "DateOfOptInConfirmation" || $_Q6ClO == "IPOnSubscription" || $_Q6ClO == "IdentString") // only user fields
                   $_QLLjo[$_Q6ClO] = $_Q6ClO;
                 break;
              }
           }
        }
        mysql_free_result($_Q60l1);
    }

    $_QJlJ0 = "SELECT fieldname, text FROM $_Qofjo WHERE language=". _OPQLR($_POST["Language"]);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      // no blanks & -
      $_I11oJ = str_replace(" ", "_", $_Q6Q1C["text"]);
      $_I11oJ = str_replace("-", "_", $_I11oJ);
      $_I11oJ = unhtmlentities($_I11oJ, $_Q6QQL); // UTF-8
      $_QLLjo[$_Q6Q1C["fieldname"]] = $_I11oJ;
    }
    mysql_free_result($_Q60l1);

    $_f18Q0=array();
    reset($_QLLjo);
    foreach($_QLLjo as $key => $_Q6ClO)  {
       $_f18Q0[] = array($key => $_Q6ClO);
    }

    $_f1fOo = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_f18Q0),
       "SessionId" => session_id(),
       "ItemsData" => $_f18Q0
    );
    _LJ0AP($_f1fOo);

    exit;
  }

  # get group id from name
  # Params MailingListId
  # Params GroupName
  if($Action == "getgroupid") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _LJ06L("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _LLF8C(intval($_POST["MailingListId"]), $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo, $FormId);
    if($_QlQC8 == "" || $_Q6t6j == "" || $_QLI68 == "") {
      _LJ06L("Mailinglist not found.", 9995);
      exit;
    }

    if(!isset($_POST["GroupName"]) || $_POST["GroupName"] == "") {
      _LJ06L("GroupName not found, GroupName missing.", 9994);
      exit;
    }

    $_QJlJ0 = "SELECT id FROM $_Q6t6j WHERE Name="._OPQLR($_POST["GroupName"]);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1 && mysql_num_rows($_Q60l1) > 0) {
       $_Q6Q1C = mysql_fetch_row($_Q60l1);
       $_f1fOo = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "GroupsId" => $_Q6Q1C[0],
          "SessionId" => session_id(),
       );
       _LJ0AP($_f1fOo);
    } else {
      _LJ06L("Group not found", 9993);
    }

    exit;
  }

  # count recipients
  # Params MailingListId
  # Params GroupsId or 0
  if($Action == "getrecipientcount") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _LJ06L("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _LLF8C(intval($_POST["MailingListId"]), $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo, $FormId);
    if($_QlQC8 == "" || $_Q6t6j == "" || $_QLI68 == "") {
      _LJ06L("Mailinglist not found.", 9995);
      exit;
    }

    $_Q8otJ = array();
    $_Q8otJ[] = "COUNT($_QlQC8.id)";

    $_QJlJ0 = "SELECT ".join(",", $_Q8otJ)." FROM $_QlQC8";

    $_QJlJ0 .= " LEFT JOIN `$_Ql8C0` ON `$_Ql8C0`.`u_EMail` = `$_QlQC8`.`u_EMail`";
    $_QJlJ0 .= " LEFT JOIN `$_ItCCo` ON `$_ItCCo`.`u_EMail` = `$_QlQC8`.`u_EMail`";

    if (isset($_POST["GroupsId"]) && $_POST["GroupsId"] != "" && $_POST["GroupsId"] > 0 ) {
      $_QJlJ0 .= " LEFT JOIN $_QLI68 ON $_QlQC8.id=$_QLI68.Member_id";
    }

    $_QJlJ0 .= " WHERE ($_QlQC8.IsActive=1 AND $_QlQC8.SubscriptionStatus<>'OptInConfirmationPending')";
    $_QJlJ0 .= " AND `$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL ";

    if (isset($_POST["GroupsId"]) && $_POST["GroupsId"] != "" && $_POST["GroupsId"] > 0 )
      $_QJlJ0 .= " AND $_QLI68.groups_id = ".intval($_POST["GroupsId"]);


    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);

    $_f1fOo = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => $_Q6Q1C[0],
       "SessionId" => session_id(),
    );
    _LJ0AP($_f1fOo);

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
      _LJ06L("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _LLF8C(intval($_POST["MailingListId"]), $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo, $FormId);
    if($_QlQC8 == "" || $_Q6t6j == "" || $_QLI68 == "") {
      _LJ06L("Mailinglist not found.", 9995);
      exit;
    }
    if( !isset($_POST["Language"]) || $_POST["Language"] == "") {
      $_POST["Language"] = "de";
    }
    if(!isset($_POST["Fields"]) || $_POST["Fields"] == "")
      $_POST["Fields"] = "*";

    if(strpos($_POST["Fields"], ",") !== true) {
      $_Q8otJ = explode(',', $_POST["Fields"]);
    } else
      $_Q8otJ[] = $_POST["Fields"];
    $_Q8otJ[] = "id AS internalRecipientsId";

    for($_Q6llo=0;$_Q6llo<count($_Q8otJ);$_Q6llo++) {
      $_Q8otJ[$_Q6llo] = "`$_QlQC8`.".trim($_Q8otJ[$_Q6llo]);
    }

    $_QJlJ0 = "SELECT ".join(",", $_Q8otJ)." FROM $_QlQC8";

    $_QJlJ0 .= " LEFT JOIN `$_Ql8C0` ON `$_Ql8C0`.`u_EMail` = `$_QlQC8`.`u_EMail`";
    $_QJlJ0 .= " LEFT JOIN `$_ItCCo` ON `$_ItCCo`.`u_EMail` = `$_QlQC8`.`u_EMail`";

    if (isset($_POST["GroupsId"]) && $_POST["GroupsId"] != "" && $_POST["GroupsId"] > 0 ) {
      $_QJlJ0 .= " LEFT JOIN $_QLI68 ON $_QlQC8.id=$_QLI68.Member_id";
    }

    $_QJlJ0 .= " WHERE ($_QlQC8.IsActive=1 AND $_QlQC8.SubscriptionStatus<>'OptInConfirmationPending')";
    $_QJlJ0 .= " AND `$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL ";

    if (isset($_POST["GroupsId"]) && $_POST["GroupsId"] != "" && $_POST["GroupsId"] > 0 )
      $_QJlJ0 .= " AND $_QLI68.groups_id = ".intval($_POST["GroupsId"]);

    if(isset($_POST["Start"]) && isset($_POST["Limit"]) && $_POST["Limit"] > 0) {
      $_QJlJ0 .= " LIMIT ".intval($_POST["Start"]).", ".intval($_POST["Limit"]);
    }
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

    $_f18Q0=array();
    while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_QfoQo = "";
      reset($_Q6Q1C);
      foreach($_Q6Q1C as $key => $_Q6ClO) {

         if($key == "internalRecipientsId") continue;
         if($key == "IdentString")
           $_Q6ClO = _OA81R($_Q6ClO, $_Q6Q1C["internalRecipientsId"], intval($_POST["MailingListId"]), $FormId, $_QlQC8);

         $_Q6ClO = str_replace(";", "", $_Q6ClO);
         if($_QfoQo == "")
           $_QfoQo = _OPQJR(unhtmlentities($_Q6ClO, "utf-8"));
           else
           $_QfoQo .= ";"._OPQJR(unhtmlentities($_Q6ClO, "utf-8"));
      }

      $_QfoQo = str_replace("\r\n", '\n', $_QfoQo);
      $_QfoQo = str_replace("\r", '\n', $_QfoQo);
      $_QfoQo = str_replace("\n", '\n', $_QfoQo);
      $_f18Q0[] = $_QfoQo;
    }
    mysql_free_result($_Q60l1);

    $_f1fOo = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_f18Q0),
       "SessionId" => session_id(),
       "ItemsData" => join(chr(14).chr(15), $_f18Q0)  // #E#F as line separator
    );
    _LJ0AP($_f1fOo);

    exit;
  }

  # count recipients in local blocklist
  # Params MailingListId
  if($Action == "getlocalblocklistentrycount") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _LJ06L("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _LLF8C($_POST["MailingListId"], $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo, $FormId);
    if($_QlQC8 == "" || $_Q6t6j == "" || $_QLI68 == "") {
      _LJ06L("Mailinglist not found.", 9995);
      exit;
    }

    $_QJlJ0 = "SELECT COUNT(*) FROM $_ItCCo";

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);

    $_f1fOo = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => $_Q6Q1C[0],
       "SessionId" => session_id(),
    );
    _LJ0AP($_f1fOo);

    exit;
  }

  # list of local blocklist entries
  # Params MailingListId
  # Params Start
  # Params Limit
  if($Action == "getlocalblocklistentries") {
    if( !isset($_POST["MailingListId"]) || $_POST["MailingListId"] == "") {
      _LJ06L("Mailinglist not found, MailingListId missing.", 9995);
      exit;
    }
    _LLF8C(intval($_POST["MailingListId"]), $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo, $FormId);
    if($_QlQC8 == "" || $_Q6t6j == "" || $_QLI68 == "") {
      _LJ06L("Mailinglist not found.", 9995);
      exit;
    }

    $_QJlJ0 = "SELECT u_EMail FROM $_ItCCo";

    if(isset($_POST["Start"]) && isset($_POST["Limit"]) && $_POST["Limit"] > 0) {
      $_QJlJ0 .= " LIMIT ".intval($_POST["Start"]).", ".intval($_POST["Limit"]);
    }
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

    $_f18Q0=array();
    while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_QfoQo = "";
      reset($_Q6Q1C);
      foreach($_Q6Q1C as $key => $_Q6ClO) {
         if($_QfoQo == "")
           $_QfoQo = _OPQJR(unhtmlentities($_Q6ClO, "utf-8"));
           else
           $_QfoQo .= ";"._OPQJR(unhtmlentities($_Q6ClO, "utf-8"));
      }

      $_f18Q0[] = $_QfoQo;
    }
    mysql_free_result($_Q60l1);

    $_f1fOo = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_f18Q0),
       "SessionId" => session_id(),
       "ItemsData" => join(chr(14).chr(15), $_f18Q0)  // #E#F as line separator
    );
    _LJ0AP($_f1fOo);

    exit;
  }

  # count recipients in global blocklist
  if($Action == "getglobalblocklistentrycount") {

    // Load User tables
    $_ICoOt = intval($_SESSION["OwnerUserId"]);
    if($_ICoOt == 0)
      $_ICoOt = intval($_SESSION["UserId"]);
    $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE id=$_ICoOt";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_Ql8C0 = $_Q6Q1C["GlobalBlockListTableName"];

    $_QJlJ0 = "SELECT COUNT(*) FROM $_Ql8C0";

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);

    $_f1fOo = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => $_Q6Q1C[0],
       "SessionId" => session_id(),
    );
    _LJ0AP($_f1fOo);

    exit;
  }

  # list of global blocklist entries
  # Params Start
  # Params Limit
  if($Action == "getglobalblocklistentries") {

    // Load User tables
    $_ICoOt = intval($_SESSION["OwnerUserId"]);
    if($_ICoOt == 0)
      $_ICoOt = intval($_SESSION["UserId"]);
    $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE id=$_ICoOt";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "SELECT u_EMail FROM $_Ql8C0";

    if(isset($_POST["Start"]) && isset($_POST["Limit"]) && $_POST["Limit"] > 0) {
      $_QJlJ0 .= " LIMIT ".intval($_POST["Start"]).", ".intval($_POST["Limit"]);
    }
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

    $_f18Q0=array();
    while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_QfoQo = "";
      reset($_Q6Q1C);
      foreach($_Q6Q1C as $key => $_Q6ClO) {
         if($_QfoQo == "")
           $_QfoQo = _OPQJR(unhtmlentities($_Q6ClO, "utf-8"));
           else
           $_QfoQo .= ";"._OPQJR(unhtmlentities($_Q6ClO, "utf-8"));
      }

      $_f18Q0[] = $_QfoQo;
    }
    mysql_free_result($_Q60l1);

    $_f1fOo = array(
       "Return" => "OK",
       "ErrorCode" => 0,
       "ItemsCount" => count($_f18Q0),
       "SessionId" => session_id(),
       "ItemsData" => join(chr(14).chr(15), $_f18Q0)  // #E#F as line separator
    );
    _LJ0AP($_f1fOo);

    exit;
  }

  if($Action == "updaterecipient") {

    $_QLitI = 0;
    $MailingListId = 0;
    $FormId = 0;
    if(!empty($_POST["IdentString"])) {
       if(!_OA8LE($_POST["IdentString"], $_QLitI, $MailingListId, $FormId)) {
         _LJ06L("Invalid IdentString.", 9990);
         exit;
       }


       $_f1OQ0 = "id=".$MailingListId;
    }
    else
    if(!empty($_POST["EMail"]) && !empty($_POST["MailingListName"])) {

      $_f1OQ0 = "Name="._OPQLR($_POST["MailingListName"]);

    } else {
       _LJ06L("Invalid IdentString, EMail or MailingListName.", 9989);
       exit;
    }


    $_QJlJ0 = "SELECT $_Q60QL.id, $_Q60QL.MaillistTableName, $_Q60QL.MailLogTableName FROM $_Q60QL";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_QJlJ0 .= " WHERE (users_id=$UserId)";
       else {
        $_QJlJ0 .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
       }

    $_QJlJ0 .= " AND $_f1OQ0";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1) {
      _LJ06L("Invalid MailingListName.", 9988);
      exit;
    }
    while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $MailingListId = $_Q6Q1C["id"];
      $_QlQC8 = $_Q6Q1C["MaillistTableName"];
      $_QljIQ = $_Q6Q1C["MailLogTableName"];
    }
    mysql_free_result($_Q60l1);

    if($_QLitI == 0) {
      $_QJlJ0 = "SELECT id FROM $_QlQC8 WHERE `u_EMail`="._OPQLR($_POST["EMail"]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1) {
        _LJ06L("Invalid Recipient.", 9988);
        exit;
      }
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_QLitI = $_Q6Q1C["id"];
    }

    $_JL6of = $_POST["DateTime"]." - ".$_POST["MailSubject"];
    $_QJlJ0 = "UPDATE `$_QljIQ` SET `MailLog`=CONCAT(`MailLog`, "._OPQLR($_Q6JJJ.$_JL6of).") WHERE `Member_id`=$_QLitI";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

    if(mysql_affected_rows($_Q61I1) == 0) {
      $_QJlJ0 = "INSERT INTO `$_QljIQ` SET `Member_id`=$_QLitI, `MailLog`="._OPQLR($_JL6of);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    }
    if($_Q60l1){
      $_QJlJ0 = "UPDATE $_QlQC8 SET `LastEMailSent`="._OPQLR($_POST["DateTime"])." WHERE `id`=$_QLitI";
      mysql_query($_QJlJ0, $_Q61I1);
    }
    if($_Q60l1) {
       $_f1fOo = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "SessionId" => session_id()
       );
       _LJ0AP($_f1fOo);
    }
    else
      _LJ06L("Can't update maillog.", 9987);
    exit;
  }

  function _LLF8C($_f1ol6, &$_QlQC8, &$_Q6t6j, &$_QLI68, &$_ItCCo, &$FormId) {
   global $_Q60QL, $_Q6fio, $_Q8f1L, $_Q61I1;
   global $UserId, $OwnerUserId, $_Ql8C0;
   $_QlQC8 = "";
   $_Q6t6j = "";
   $_QLI68 = "";
   $FormId = 0;
   $_f1ol6 = intval($_f1ol6);

   $_QJlJ0 = "SELECT GlobalBlockListTableName FROM $_Q8f1L WHERE id=";
   if($OwnerUserId == 0) // ist es ein Admin?
      $_QJlJ0 .= $UserId;
      else
      $_QJlJ0 .= $OwnerUserId;
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && mysql_num_rows($_Q60l1) == 1) {
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     mysql_free_result($_Q60l1);
     $_Ql8C0 = $_Q6Q1C[0];
   } else {
     _LJ06L("User not found", 9996);
     exit;
   }

   $_QJlJ0 = "SELECT MaillistTableName, GroupsTableName, MailListToGroupsTableName, LocalBlocklistTableName, forms_id FROM $_Q60QL";
   if($OwnerUserId == 0) // ist es ein Admin?
      $_QJlJ0 .= " WHERE (users_id=$UserId)";
      else {
       $_QJlJ0 .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
      }
   $_QJlJ0 .= " AND $_Q60QL.id=$_f1ol6";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && mysql_num_rows($_Q60l1) == 1) {
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     $_QlQC8 = $_Q6Q1C[0];
     $_Q6t6j = $_Q6Q1C[1];
     $_QLI68 = $_Q6Q1C[2];
     $_ItCCo = $_Q6Q1C[3];
     $FormId = $_Q6Q1C[4];
     mysql_free_result($_Q60l1);
   } else {
     _LJ06L("Mailinglist not found", 9996);
     exit;
   }
  }


  function _LJ06L($_jj0JO, $_f1CCi, $_f1ClI = false) {
     if (!$_f1ClI) {
       print "Return: ERROR\r\n";
       print "ErrorCode: $_f1CCi\r\n";
       print "ErrorText: $_jj0JO\r\n";
       print "\r\n";
       flush();
       exit;
     } else {
       return array(
          "Return" => "ERROR",
          "ErrorCode" => $_f1CCi,
          "ErrorText" => $_jj0JO
       );
     }
  }

  function _LJ08E($_QJCJi) {
   $_QJCJi = str_replace("\r", "", $_QJCJi);
   $_QJCJi = str_replace("\n", '\n', $_QJCJi);
   $_QJCJi = str_replace("</", '<%2F', $_QJCJi);
   return $_QJCJi;
  }

  function _LJ0AP($_f1fOo) {
    reset($_f1fOo);
    foreach ($_f1fOo as $key => $_Q6ClO) {
      if(!is_array($_Q6ClO)) {
        echo "$key: $_Q6ClO\r\n";
      } else {
        reset($_Q6ClO);

        echo "$key: ";
        for($_Q6llo=0; $_Q6llo<count($_Q6ClO); $_Q6llo++) {
          echo "<item value=\"$_Q6llo\">";
          if(is_array($_Q6ClO[$_Q6llo]) )
            foreach ($_Q6ClO[$_Q6llo] as $_I1i8O => $_I1L81) {
              echo "<$_I1i8O>"._LJ08E($_I1L81)."</$_I1i8O>";
            }
          if(!is_array($_Q6ClO[$_Q6llo]))
            echo _LJ08E($_Q6ClO[$_Q6llo]);
          echo "</item>";
        }

      }
    }
    echo "\r\n";
    flush();
  }


?>
