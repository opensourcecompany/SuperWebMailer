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
  $_I01C0 = Array ('ForceRedirect');

  $_I01lt = Array ();

  $errors = array();
  $_ICljo = 0;

  if(isset($_POST['PageId'])) // Formular speichern?
    $_ICljo = intval($_POST['PageId']);
  else
    if ( isset($_POST['OnePageListId']) )
       $_ICljo = intval($_POST['OnePageListId']);

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if($_ICljo == 0 && !$_QJojf["PrivilegePageCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($_ICljo != 0 && !$_QJojf["PrivilegePageEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";

  if(isset($_POST['SubmitBtn'])) { // Formular speichern?

    if( isset($_POST["HTMLPage"]) )
       $_POST["HTMLPage"] = CleanUpHTML( $_POST["HTMLPage"], false ); // JavaScript allowed

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';
    //
    if ( (isset($_POST['RedirectURL'])) && ($_POST['RedirectURL'] != "") && ( !_OPCCE($_POST['RedirectURL']) ) )
      $errors[] = 'RedirectURL';
    if ( (isset($_POST['PageType'])) && ($_POST['PageType'] != "") )
       $_POST['Type'] = $_POST['PageType'];
    if ( (isset($_POST['Type'])) && ($_POST['Type'] == "") )
      $errors[] = 'Type';
    //

    if ( (isset($_POST['RedirectURL']) && $_POST['RedirectURL'] == "") || (!isset($_POST['RedirectURL']) ) )
       if( trim( unhtmlentities( @strip_tags ( $_POST["HTMLPage"] ), $_Q6QQL ) ) == "")
          $errors[] = 'HTMLPage';

    if(count($errors) > 0) {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      }
      else {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
        $_II1Ot = $_POST;
        _L0B8Q($_ICljo, $_II1Ot);
        if($_ICljo != 0)
           $_POST["PageId"] = $_ICljo;
      }
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000032"], $_I0600, 'pageedit', 'pageedit_snipped.htm');

  $_QJCJi = str_replace ('name="PageId"', 'name="PageId" value="'.$_ICljo.'"', $_QJCJi);
  $_QJCJi = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QJCJi);

  # Page laden
  $_6JJj6 = 0;
  if(isset($_POST['SubmitBtn'])) { // Formular speichern?
    $ML = $_POST;

  } else {
    if($_ICljo > 0) {
      $_QJlJ0= "SELECT * FROM $_ICljl WHERE id=$_ICljo";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $ML=mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $ML["HTMLPage"] = FixCKEditorStyleProtectionForCSS($ML["HTMLPage"]);
      if(!$ML["ForceRedirect"])
        unset($ML["ForceRedirect"]);
    } else {
     $ML = array();
    }
  }

  if($_ICljo > 0) {
      // get all table FormsTable
      $_I6jtI = array();
      $_I6jtf = "SELECT FormsTableName, SubscriptionType, UnsubscriptionType FROM $_Q60QL";
      if($OwnerUserId == 0) // ist es ein Admin?
         $_I6jtf .= " WHERE (users_id=$UserId)";
         else {
          $_I6jtf .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
         }


      $_Q8Oj8 = mysql_query($_I6jtf, $_Q61I1);
      while ($_Q8OiJ = mysql_fetch_row($_Q8Oj8)) {
        $_I6jtI[] = array(
                                     "FormsTableName" => $_Q8OiJ[0],
                                     "SubscriptionType" => $_Q8OiJ[1],
                                     "UnsubscriptionType" => $_Q8OiJ[2],
                                   );
      }
      mysql_free_result($_Q8Oj8);

      // referenzen vorhanden?
      for($_Q6llo=0; $_Q6llo<count($_I6jtI); $_Q6llo++) {
        $_QJlJ0 = "SELECT COUNT(*) FROM ".$_I6jtI[$_Q6llo]["FormsTableName"]." WHERE SubscribeErrorPage=$_ICljo OR UnsubscribeErrorPage=$_ICljo OR SubscribeConfirmationPage=$_ICljo OR EditAcceptedPage=$_ICljo OR EditConfirmationPage=$_ICljo OR EditRejectPage=$_ICljo OR EditErrorPage=$_ICljo OR UnsubscribeConfirmationPage=$_ICljo OR `UnsubscribeBridgePage`=$_ICljo OR `RFUSurveyConfirmationPage`=$_ICljo OR ('".$_I6jtI[$_Q6llo]["SubscriptionType"]."'='DoubleOptIn' AND SubscribeAcceptedPage=$_ICljo) OR ('".$_I6jtI[$_Q6llo]["UnsubscriptionType"]."'='DoubleOptOut' AND UnsubscribeAcceptedPage=$_ICljo)";
        $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
        $_IO08Q = mysql_fetch_row($_ItlJl);
        $_6JJj6 += $_IO08Q[0];
        mysql_free_result($_ItlJl);
        if($_6JJj6 > 0) break;
      }
  }

  if(isset($ML["Type"]))
    $ML["PageType"] = $ML["Type"];


  #### normal placeholders

  $_Q8otJ=array();
  if(!isset($ML["PageType"]) || ($ML["PageType"] != 'RFUSurveyConfirmation')  ){
    $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
     $_Q8otJ[] =  sprintf("new Array('[%s]', '%s')", $_Q6Q1C["fieldname"], $_Q6Q1C["text"]);
    }
    mysql_free_result($_Q60l1);

    # defaults
    foreach ($_IIQI8 as $key => $_Q6ClO)
      $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  } else {
    # defaults
    foreach ($_IIQI8 as $key => $_Q6ClO)
      if($key == "DateShort" || $key == "DateLong" || $key == "Time")
        $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  }

  # ReasonsForUnsubscriptionSurveyPlaceholders
  if(!isset($ML["PageType"]) || ($ML["PageType"] == 'Unsubscribe')  )
    foreach ($_jJl6I as $key => $_Q6ClO)
      $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  $_QJCJi = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);

  // ####

  $_QJCJi = _OPFJA($errors, $ML, $_QJCJi);

  $_II6C6 = "";
  if($_6JJj6 > 0) {
     $_II6C6 .= "document.getElementById('Type').disabled = true;$_Q6JJJ";
     $_QJCJi = str_replace("<IF:NOTCANCHANGE>", "", $_QJCJi);
     $_QJCJi = str_replace("</IF:NOTCANCHANGE>", "", $_QJCJi);
  } else{
    $_QJCJi = _OP6PQ($_QJCJi, "<IF:NOTCANCHANGE>", "</IF:NOTCANCHANGE>");
  }
  # show Warnlabel, we cannot mark fckeditor in red because this will be saved
  if(count($errors) > 0) {
    if(in_array('HTMLPage', $errors))
       $_II6C6 .= "document.getElementById('HTMLPageWarnLabel').style.display = '';$_Q6JJJ";
  }
  if($_II6C6 != "")
    $_QJCJi = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_II6C6, $_QJCJi);

  print $_QJCJi;

  function _L0B8Q(&$_ICljo, $_Qi8If) {
    global $_ICljl, $_I01C0, $_I01lt, $_Q61I1;

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM $_ICljl";
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
    if($_ICljo == 0) {
      $_QJlJ0 = "INSERT INTO $_ICljl (CreateDate) VALUES(NOW())";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
      $_Q6Q1C=mysql_fetch_array($_Q60l1);
      $_ICljo = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);
    }


    $_QJlJ0 = "UPDATE $_ICljl SET ";
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
           $_I1l61[] = "`$key`="._OPQLR(trim($_Qi8If[$key]))."";
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
    $_QJlJ0 .= " WHERE id=$_ICljo";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }

  }

?>
