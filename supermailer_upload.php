<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2017 Mirko Boeer                         #
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


   $_QJlJ0 = "SELECT id, Username, Language FROM $_Q8f1L WHERE Username="._OPQLR($_POST["Username"])." AND ";
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

   #session_register("UserId", "OwnerUserId", "Username", "ClientIP", "Language");

   $_SESSION["UserId"] = $_Q6Q1C["id"];
   $_SESSION["OwnerUserId"] = $_Q6Q1C["OwnerUserId"];
   $_SESSION["Username"] = $_Q6Q1C["Username"];
   $_SESSION["Language"] = $_Q6Q1C["Language"];
   $_SESSION["ClientIP"] = getOwnIP();

   // paths
   if($_Q6Q1C["OwnerUserId"] != 0)
     _OP0AF($_Q6Q1C["OwnerUserId"]);
     else
     _OP0AF($_Q6Q1C["id"]);

   # Output
   $_f1fOo = array(
      "Return" => "OK",
      "ErrorCode" => 0,
      "UserId" => $_Q6Q1C["id"],
      "OwnerUserId" => $_Q6Q1C["OwnerUserId"],
      "UserFilesPath" => $_jjC06,
      "UserURL" => $_jjCtI,
      "ImportFilesPath" => $_I0lQJ,
      "ExportFilesPath" => $_jji0C,
      "AttachmentsFilesPath" => $_QOCJo,
      "ImagesFilesPath" => $_QCo6j,
      "MediaFilesPath" =>$_jji0i,
      "SessionId" => session_id()
   );
   _LJ0AP($_f1fOo);

   exit;
  }

  # normal functions

  ########################## check session
  if(!ini_get("session.auto_start"))
    @session_start();
  if ( ( !isset($_SESSION["UserId"]) ) Or ( !isset($_SESSION["OwnerUserId"]) ) Or ( !isset($_SESSION["Username"]))  ) {
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
  $Language = $_SESSION["Language"];
  ###

  // paths
  if($OwnerUserId != 0)
    _OP0AF($OwnerUserId);
    else
    _OP0AF($UserId);

  // Load User tables
  $_ICoOt = $OwnerUserId;
  if($_ICoOt == 0)
    $_ICoOt = $UserId;
  $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE id=$_ICoOt";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_ICQQo = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  _OP0D0($_ICQQo);

  _OP10J($Language);
  _LQLRQ($Language);

  # check privileges
  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeTemplateCreate"]) {
      _LJ06L("You have no permissions to create new email templates.", 100001);
      exit;
    }
  }

  # Upload image
  if($Action == "uploadimage") {
    if ( isset( $_FILES['FileName'] ) && !is_null( $_FILES['FileName']['tmp_name'] ) ) {
       $_Jj0IQ = $_FILES['FileName']['name'];
       $_Jj0IQ = _LJ11Q($_Jj0IQ);

       $_f1iQI = _OBLDR($_QCo6j).$_Jj0IQ;

       if(!move_uploaded_file($_FILES['FileName']['tmp_name'], $_f1iQI) && !copy($_FILES['FileName']['tmp_name'], $_f1iQI)) {
         _LJ06L("Can't move file to $_f1iQI.", 1001);
         exit;
       }

       if($OwnerUserId != 0)
         $_jt8IL = BasePath."userfiles/".$OwnerUserId."/image/";
         else
         $_jt8IL = BasePath."userfiles/".$UserId."/image/";

       # Output
       $_f1fOo = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "FileName" => $_jt8IL.$_Jj0IQ,
          "CompleteFileNameAndPath" => $_f1iQI,
          "SessionId" => session_id()
       );
       _LJ0AP($_f1fOo);


    } else{
        _LJ06L("Filename missing.", 1000);
        exit;
    }

  }

  # Upload file
  if($Action == "uploadfile") {
    if ( isset( $_FILES['FileName'] ) && !is_null( $_FILES['FileName']['tmp_name'] ) ) {
       $_Jj0IQ = $_FILES['FileName']['name'];
       $_Jj0IQ = _LJ11Q($_Jj0IQ);

       $_f1iQI = _OBLDR($_QOCJo).$_Jj0IQ;

       if(!move_uploaded_file($_FILES['FileName']['tmp_name'], $_f1iQI) && !copy($_FILES['FileName']['tmp_name'], $_f1iQI)) {
         _LJ06L("Can't move file to $_f1iQI.", 1001);
         exit;
       }

       if($OwnerUserId != 0)
         $_jt8IL = BasePath."userfiles/".$OwnerUserId."/file/";
         else
         $_jt8IL = BasePath."userfiles/".$UserId."/file/";

       # Output
       $_f1fOo = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "FileName" => $_jt8IL.$_Jj0IQ,
          "CompleteFileNameAndPath" => $_f1iQI,
          "SessionId" => session_id()
       );
       _LJ0AP($_f1fOo);


    } else{
        _LJ06L("Filename missing.", 1000);
        exit;
    }

  }

  # Upload html
  if($Action == "uploadhtml") { // utf-8 encoded!

       $_QJCJi = $_POST["Text"];
       if($_QJCJi == "") {
         _LJ06L("Text is empty.", 1002);
         exit;
       }

       $_Q6QiO = "d.m.Y H:i:s";
       if($Language != "de") {
          $_Q6QiO = "Y-m-d H:i:s";
       }

       $_jiOiQ = 0;
       if(isset($_POST["IsWizardable"]) && intval($_POST["IsWizardable"]) == 1)
         $_jiOiQ = 1;
       $_f1iLl = "Upload ".date($_Q6QiO);
       $_QJlJ0 = "INSERT INTO $_Q66li SET CreateDate=NOW(), Name="._OPQLR( $_f1iLl ).", MailFormat='HTML', MailHTMLText="._OPQLR(CleanUpHTML($_QJCJi)).", `IsWizardable`=$_jiOiQ";
       mysql_query($_QJlJ0, $_Q61I1);
       if(mysql_error($_Q61I1) != "") {
         _LJ06L("Can't insert template, error: ".mysql_error($_Q61I1), 1003);
         exit;
       }

       # Output
       $_f1fOo = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "TemplateName" => $_f1iLl,
          "SessionId" => session_id()
       );
       _LJ0AP($_f1fOo);
  }

  # Upload text
  if($Action == "uploadtext") { // utf-8 encoded!

       $_QJCJi = $_POST["Text"];
       if($_QJCJi == "") {
         _LJ06L("Text is empty.", 1002);
         exit;
       }

       $_Q6QiO = "d.m.Y H:i:s";
       if($Language != "de") {
          $_Q6QiO = "Y-m-d H:i:s";
       }

       $_f1iLl = "Upload ".date($_Q6QiO);
       $_QJlJ0 = "INSERT INTO $_Q66li SET CreateDate=NOW(), Name="._OPQLR( $_f1iLl ).", MailFormat='PlainText', MailPlainText="._OPQLR($_QJCJi);
       mysql_query($_QJlJ0, $_Q61I1);
       if(mysql_error($_Q61I1) != "") {
         _LJ06L("Can't insert template, error: ".mysql_error($_Q61I1), 1003);
         exit;
       }

       # Output
       $_f1fOo = array(
          "Return" => "OK",
          "ErrorCode" => 0,
          "TemplateName" => $_f1iLl,
          "SessionId" => session_id()
       );
       _LJ0AP($_f1fOo);


  }


  function _LJ11Q($_Jj0IQ) {
    if(IsUTF8string($_Jj0IQ))
      $_Jj0IQ =  utf8_decode($_Jj0IQ);
    $_QJCJi = "";
    $_Jj0IQ = str_replace(" ", "_", $_Jj0IQ);
    $_Jj0IQ = str_replace("-", "_", $_Jj0IQ);
    for($_Q6llo=0; $_Q6llo<strlen($_Jj0IQ); $_Q6llo++) {
       if (
           (ord($_Jj0IQ{$_Q6llo}) >= 0x30 && ord($_Jj0IQ{$_Q6llo}) <= 0x39) ||
           (ord($_Jj0IQ{$_Q6llo}) >= 0x41 && ord($_Jj0IQ{$_Q6llo}) <= 0x5A) ||
           (ord($_Jj0IQ{$_Q6llo}) >= 0x61 && ord($_Jj0IQ{$_Q6llo}) <= 0x7A) ||
           (ord($_Jj0IQ{$_Q6llo}) == 0x5F) ||
           ($_Jj0IQ{$_Q6llo} == '.')
          ) {
            $_QJCJi = $_QJCJi . $_Jj0IQ{$_Q6llo};
          } else {
            switch(ord($_Jj0IQ{$_Q6llo})) {
              case 0xC4: $_QJCJi = $_QJCJi . "Ae";
                    break;
              case 0xDC: $_QJCJi = $_QJCJi . "Ue";
                    break;
              case 0xD6: $_QJCJi = $_QJCJi . "Oe";
                    break;
              case 0xE4: $_QJCJi = $_QJCJi . "ae";
                    break;
              case 0xFC: $_QJCJi = $_QJCJi . "ue";
                    break;
              case 0xF6: $_QJCJi = $_QJCJi . "oe";
                    break;
              case 0xDF: $_QJCJi = $_QJCJi . "ss";
                    break;
            }
          }
    }
    return $_QJCJi;
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
