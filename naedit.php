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
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");


  // Boolean fields of form
  $_I01C0 = Array ("UserDefinedColorsAndFonts", "LinkToSWM",
                      "ShowImpressum", "ImpressumIsHTML", "RemoveMailToLinks", "SortOrderNewToOld", "rssshowAll");

  $_I01lt = Array ();

  $errors = array();
  $_I0600 = "";

  if(isset($_POST['NAEditBtn'])) // Formular speichern?
    $_IC1ol = intval($_POST['NAListId']);
  else
    if(!empty($_POST['OneNAListId']))
      $_IC1ol = intval($_POST['OneNAListId']);
      else
      $_IC1ol = 0;

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if($_IC1ol && !$_QJojf["PrivilegeNewsletterArchiveEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if(!$_IC1ol && !$_QJojf["PrivilegeNewsletterArchiveCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }


  if(isset($_POST['NAEditBtn'])) { // Formular speichern?

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';
    if ( (!isset($_POST['EMailings'])) || count($_POST['EMailings']) == 0 )
      $errors[] = 'EMailings';

    if ( isset($_POST["UserDefinedColorsAndFonts"]) ) {
      $_6QiQC = array("BackgroundColor", "FontName", "FontColor", "FontStyle", "FontSize", "link", "alink", "vlink", "H1FontSize", "NavBackgroundColor", "NavFontName", "NavFontColor", "NavFontStyle", "NavFontSize", "EntryFontSize");
      foreach($_6QiQC as $key) {
        if(empty($_POST[$key])) {
           $errors[] = $key;
        }
      }
    }

    if(!empty($_POST["ShowImpressum"]))
      if(empty($_POST["ImpressumText"]))
         unset($_POST["ShowImpressum"]);

    if(count($errors) > 0)
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      else {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];

        $_II1Ot = $_POST;
        _OFDFJ($_IC1ol, $_II1Ot);
        if($_IC1ol != 0)
           $_POST['NAListId'] = $_IC1ol;
      }

  }

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000832"], $_I0600, 'naedit', 'naedit_snipped.htm');

  $_QJCJi = str_replace ('name="NAListId"', 'name="NAListId" value="'.$_IC1ol.'"', $_QJCJi);
  if(isset($_POST["PageSelected"]))
     $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);


  #### campaigns
  $_QJlJ0 = "SELECT id, Name FROM $_Q6jOo";
  if($OwnerUserId != 0) // kein Admin?
    {
      $_QJlJ0 = "SELECT $_Q6jOo.id, $_Q6jOo.Name FROM $_Q6jOo LEFT JOIN $_Q60QL ON $_Q60QL.id=$_Q6jOo.maillists_id";
      $_QJlJ0 .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";

    }

  $_QJlJ0 .= " ORDER BY $_Q6jOo.Name";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q8otJ=array();
  $_6QLo6 = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
     $_6QLo6 .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:EMAILINGS>", "</SHOW:EMAILINGS>", $_6QLo6);
  #### campaigns END

  //Placeholders

  #### normal placeholders
  $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q8otJ=array();
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
   $_Q8otJ[$_Q6Q1C["fieldname"]] = $_Q6Q1C["text"];
  }
  mysql_free_result($_Q60l1);
  # defaults
  foreach ($_IIQI8 as $key => $_Q6ClO)
   $_Q8otJ[$key] = $resourcestrings[$INTERFACE_LANGUAGE][$key];

  #### special newsletter unsubscribe placeholders
  $_Ij0oj = array_merge($_III0L, $_jQt18, $_Ij18l);
  reset($_Ij0oj);
  foreach ($_Ij0oj as $key => $_Q6ClO)
    $_Q8otJ[$key] = $resourcestrings[$INTERFACE_LANGUAGE][$key];

  $_Q8otJ["MembersAge"] = $resourcestrings[$INTERFACE_LANGUAGE]["MembersAge"];

  // functions
  $_QJlJ0 = "SELECT Name FROM $_I88i8 ORDER BY Name";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
   $_Q8otJ[$_Q6Q1C["Name"]] = $_Q6Q1C["Name"];
  }
  mysql_free_result($_Q60l1);

  $_QfOij = "";
  $_IIJi1 = _OP81D($_QJCJi, "<LIST:REPLACEMENTS>", "</LIST:REPLACEMENTS>");

  reset($_Q8otJ);
  $_I16jJ = "";
  foreach ($_Q8otJ as $key => $_Q6ClO){
    $_I16jJ .= '<option value="'.$key.'">'.$_Q6ClO.'</option>';
  }

  for($_Q6llo=0; $_Q6llo<20; $_Q6llo++){
     $_Q66jQ = $_IIJi1;
     $_Q66jQ = str_replace('"fieldname"', '"fieldname['.$_Q6llo.']"', $_Q66jQ);
     $_Q66jQ = str_replace('"replacementtext"', '"replacementtext['.$_Q6llo.']"', $_Q66jQ);
     $_Q66jQ = _OPR6L($_Q66jQ, "<fieldnames>", "</fieldnames>", $_I16jJ);
     $_QfOij .= $_Q66jQ;
  }
  $_QJCJi = _OPR6L($_QJCJi, "<LIST:REPLACEMENTS>", "</LIST:REPLACEMENTS>", $_QfOij);


  if($_IC1ol != 0) { // Laden
    $_QJlJ0 = "SELECT *, UNIX_TIMESTAMP(CreateDate) AS UCreateDate FROM $_IC1lt WHERE id=$_IC1ol";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_6QlQJ=mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    $_IC1ol = $_6QlQJ["id"];
    $_6QlQJ['OneNAListId'] = $_IC1ol;
    if($INTERFACE_LANGUAGE != "de")
      $_6QlQJ["CREATEDATE"] = strftime("%c", $_6QlQJ["UCreateDate"]);
     else
      $_6QlQJ["CREATEDATE"] = strftime("%d.%m.%Y %H:%M", $_6QlQJ["UCreateDate"]);

    $_QJlJ0 = "SELECT campaigns_id FROM $_6QlQJ[CampaignToNATableName]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Qot0C = array();
    while($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1))
      $_Qot0C[] = $_Q6Q1C["campaigns_id"];
    if($_Q60l1)
      mysql_free_result($_Q60l1);
    $_6QlQJ["EMailings"] = $_Qot0C;

    // remove boolean fields
    for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++)
       if(!$_6QlQJ[$_I01C0[$_Q6llo]])
          unset($_6QlQJ[$_I01C0[$_Q6llo]]);

    $_6QlQJ["PlaceHolderReplacements"] = @unserialize($_6QlQJ["PlaceHolderReplacements"]);
    if($_6QlQJ["PlaceHolderReplacements"] === false)
      $_6QlQJ["PlaceHolderReplacements"] = array();

  } else {
    $_6QlQJ = $_POST;

    if(!isset($_6QlQJ["CREATEDATE"]))
       $_6QlQJ["CREATEDATE"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEW"];
    if(!isset($_6QlQJ["CampaignToNATableName"]))
       $_6QlQJ["CampaignToNATableName"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEW"];
    if(!isset($_6QlQJ["OpeningsCount"]))
       $_6QlQJ["OpeningsCount"] = 0;

    // default values
    if(!isset($_6QlQJ["NAListId"]) && !isset($_6QlQJ["OneNAListId"]) ) {

       $_QJlJ0 = "SHOW COLUMNS FROM $_IC1lt";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_IJQQI = false;
       while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
          if($_Q6Q1C["Field"] == "CampaignToNATableName") {
             $_IJQQI = true;
             continue;
          }
          if($_IJQQI) {
            if( stripos($_Q6Q1C["Type"], "tiny") !== false && $_Q6Q1C["Default"] == 0 ) {
              continue;
            }

            if($_Q6Q1C["Default"] != "NULL") {
              $_6QlQJ[$_Q6Q1C["Field"]] = $_Q6Q1C["Default"];
            }

            if(isset($resourcestrings[$INTERFACE_LANGUAGE]["rsNA".$_Q6Q1C["Field"]]))
              $_6QlQJ[$_Q6Q1C["Field"]] = $resourcestrings[$INTERFACE_LANGUAGE]["rsNA".$_Q6Q1C["Field"]];


          }
       }
       mysql_free_result($_Q60l1);
       $_6QlQJ["ImpressumIsHTML"] = 1;

    }

  }

  if(isset($_6QlQJ["PlaceHolderReplacements"]) && count($_6QlQJ["PlaceHolderReplacements"]) > 0) {
     //    $_6I0ij[] = array("fieldname" => $_Q6ClO, "value" => $_Q6ClO);
     foreach($_6QlQJ["PlaceHolderReplacements"] as $key => $_Q6ClO){
         $_6QlQJ['fieldname['.$key.']'] = $_Q6ClO["fieldname"];
         $_6QlQJ['replacementtext['.$key.']'] = $_Q6ClO["value"];
     }
  }

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:CREATEDATE>", "</SHOW:CREATEDATE>", $_6QlQJ["CREATEDATE"] );
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:INTERNALTABLENAME>", "</SHOW:INTERNALTABLENAME>", $_6QlQJ["CampaignToNATableName"] );
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:OPENINGSCOUNT>", "</SHOW:OPENINGSCOUNT>", $_6QlQJ["OpeningsCount"]);

  $_QJCJi = _OPFJA($errors, $_6QlQJ, $_QJCJi);
  $_QJCJi = _LJ81E($_QJCJi);

  $_II6C6 = "";

  $_QJCJi = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_II6C6, $_QJCJi);
  print $_QJCJi;

  function _OFDFJ(&$_IC1ol, $_Qi8If) {
    global $_IC1lt, $_I01C0, $_I01lt, $Username, $UserId, $_Q61I1;

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM $_IC1lt";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }
    if (mysql_num_rows($_Q60l1) > 0) {
        while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
           foreach ($_Q6Q1C as $key => $_Q6ClO) {
              if($key == "Field") {
                 $_QLLjo[] = $_Q6ClO;
                 break;
              }
           }
        }
        mysql_free_result($_Q60l1);
    }


    // new entry?
    if($_IC1ol == 0) {
      $_6QlLC = _OALO0(TablePrefix.$Username."_".trim($_POST['Name'])."_c_na_ref");
      $_POST["CampaignToNATableName"] = $_6QlLC;
      $_Qi8If["CampaignToNATableName"] = $_6QlLC;

      $_QJlJ0 = "INSERT INTO $_IC1lt SET CreateDate=NOW(), `CampaignToNATableName`="._OPQLR($_6QlLC);
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
      $_Q6Q1C=mysql_fetch_row($_Q60l1);
      $_IC1ol = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);

      $key = md5(sprintf("%d %d %s %s", $_IC1ol, $UserId, date("r"), $_IC1lt));
      $_QJlJ0 = "UPDATE $_IC1lt SET UniqueID="._OPQLR($key)." WHERE id=$_IC1ol";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);

      $_Ij6Io = join("", file(_O68A8()."newsletterarchive.sql"));
      $_Ij6Io = str_replace('`TABLE_CAMPAIGNTONEWSLETTERARCHIVE`', "`$_6QlLC`", $_Ij6Io);

      $_Ij6il = explode(";", $_Ij6Io);

      for($_Q6llo=0; $_Q6llo<count($_Ij6il); $_Q6llo++) {
        if(trim($_Ij6il[$_Q6llo]) == "") continue;
        $_Q60l1 = mysql_query($_Ij6il[$_Q6llo]." CHARSET=utf8", $_Q61I1);
        if(!$_Q60l1)
          $_Q60l1 = mysql_query($_Ij6il[$_Q6llo], $_Q61I1);
        if(!$_Q60l1){
          _OAL8F($_Ij6il[$_Q6llo]);
        }
      }

    }


    $_QJlJ0 = "UPDATE $_IC1lt SET ";
    $_I1l61 = array();
    for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
      $key = $_QLLjo[$_Q6llo];
      if ( isset($_Qi8If[$_QLLjo[$_Q6llo]]) ) {
        if(in_array($key, $_I01C0))
          if( $_Qi8If[$key] == "1" || intval($_Qi8If[$key]) == 0 )
             $_I1l61[] = "`$key`=1";
             else
              ;
        else {
           $_I1l61[] = "`$key`="._OPQLR(trim($_Qi8If[$key]));
        }
      } else {
         if(in_array($key, $_I01C0)) {
           $key = $_QLLjo[$_Q6llo];
           $_I1l61[] = "`$key`=0";
         } else {
           if(in_array($key, $_I01lt)) {
             $key = $_QLLjo[$_Q6llo];
             $_I1l61[] = "`$key`=0";
           }
         }
      }
    }

    $_QJlJ0 .= join(", ", $_I1l61);

    $_6I0ij = array();
    if(!empty($_Qi8If["PlaceHoldersAction"]) && $_Qi8If["PlaceHoldersAction"] == 2 && !empty($_Qi8If["fieldname"])){
       foreach($_Qi8If["fieldname"] as $key => $_Q6ClO){
         if(empty($_Q6ClO) || $_Q6ClO == "none") continue;
         $_6I1QI = "";
         if(!empty($_Qi8If["replacementtext"][$key]))
             $_6I1QI = $_Qi8If["replacementtext"][$key];
         $_6I0ij[] = array("fieldname" => $_Q6ClO, "value" => $_6I1QI);
       }
    }
    $_QJlJ0 .= ", PlaceHolderReplacements="._OPQLR(serialize($_6I0ij));

    // to show it
    $_POST["PlaceHolderReplacements"] = $_6I0ij;

    $_QJlJ0 .= " WHERE id=$_IC1ol";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }


    if(!isset($_6QlLC))
      $_6QlLC = $_Qi8If["CampaignToNATableName"];

    // *********** EMailings
    $_QJlJ0 = "DELETE FROM $_6QlLC";
    mysql_query($_QJlJ0, $_Q61I1);
    reset($_Qi8If["EMailings"]);
    $_QJlJ0 = "INSERT INTO `$_6QlLC` (`campaigns_id` ) VALUES ";
    $_I1l61 = array();
    foreach($_Qi8If["EMailings"] as $key => $_Q6ClO) {
       $_Q6ClO = intval($_Q6ClO);
       if($_Q6ClO == 0) continue;
       $_I1l61[] = "($_Q6ClO)";
    }
    if(count($_I1l61) > 0) {
      $_QJlJ0 .= join(", ", $_I1l61);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (!$_Q60l1) {
          _OAL8F($_QJlJ0);
          exit;
      }
    }




  }
?>
