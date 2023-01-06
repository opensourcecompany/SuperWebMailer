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

  // Boolean fields of form
  $_ItI0o = Array ('ForceRedirect');

  $_ItIti = Array ();

  $errors = array();
  $_jfQ0i = 0;

  if(isset($_POST['PageId'])) // Formular speichern?
    $_jfQ0i = intval($_POST['PageId']);
  else
    if ( isset($_POST['OnePageListId']) )
       $_jfQ0i = intval($_POST['OnePageListId']);

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if($_jfQ0i == 0 && !$_QLJJ6["PrivilegePageCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($_jfQ0i != 0 && !$_QLJJ6["PrivilegePageEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";

  if(isset($_POST['SubmitBtn'])) { // Formular speichern?

    if( isset($_POST["HTMLPage"]) )
       $_POST["HTMLPage"] = CleanUpHTML( $_POST["HTMLPage"], false ); // JavaScript allowed

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';
    //
    if ( (isset($_POST['RedirectURL'])) && ($_POST['RedirectURL'] != "") && ( !_L86B8($_POST['RedirectURL']) ) )
      $errors[] = 'RedirectURL';
    if ( (isset($_POST['PageType'])) && ($_POST['PageType'] != "") )
       $_POST['Type'] = $_POST['PageType'];
    if ( (isset($_POST['Type'])) && ($_POST['Type'] == "") )
      $errors[] = 'Type';
    //

    if ( (isset($_POST['RedirectURL']) && $_POST['RedirectURL'] == "") || (!isset($_POST['RedirectURL']) ) )
       if( trim( unhtmlentities( @strip_tags ( $_POST["HTMLPage"] ), $_QLo06 ) ) == "")
          $errors[] = 'HTMLPage';

    if(count($errors) > 0) {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      }
      else {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
        $_IoLOO = $_POST;
        _J0B1A($_jfQ0i, $_IoLOO);
        if($_jfQ0i != 0)
           $_POST["PageId"] = $_jfQ0i;
      }
  }

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000032"], $_Itfj8, 'pageedit', 'pageedit_snipped.htm');

  $_QLJfI = str_replace ('name="PageId"', 'name="PageId" value="'.$_jfQ0i.'"', $_QLJfI);
  $_QLJfI = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QLJfI);

  # Page laden
  $_fCQCJ = 0;
  if(isset($_POST['SubmitBtn'])) { // Formular speichern?
    $ML = $_POST;

  } else {
    if($_jfQ0i > 0) {
      $_QLfol= "SELECT * FROM $_jfQtI WHERE id=$_jfQ0i";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $ML=mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $ML["HTMLPage"] = FixCKEditorStyleProtectionForCSS($ML["HTMLPage"]);
      if(!$ML["ForceRedirect"])
        unset($ML["ForceRedirect"]);
    } else {
     $ML = array();
     $ML["HTMLPage"] = $_IC18i;
    }
  }

  if($_jfQ0i > 0) {
      // get all table FormsTable
      $_IlJ61 = array();
      $_IlJlC = "SELECT FormsTableName, SubscriptionType, UnsubscriptionType FROM $_QL88I";
      if($OwnerUserId == 0) // ist es ein Admin?
         $_IlJlC .= " WHERE (users_id=$UserId)";
         else {
          $_IlJlC .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
         }


      $_I1O6j = mysql_query($_IlJlC, $_QLttI);
      while ($_I1OfI = mysql_fetch_row($_I1O6j)) {
        $_IlJ61[] = array(
                                     "FormsTableName" => $_I1OfI[0],
                                     "SubscriptionType" => $_I1OfI[1],
                                     "UnsubscriptionType" => $_I1OfI[2],
                                   );
      }
      mysql_free_result($_I1O6j);

      // referenzen vorhanden?
      for($_Qli6J=0; $_Qli6J<count($_IlJ61); $_Qli6J++) {
        $_QLfol = "SELECT COUNT(*) FROM ".$_IlJ61[$_Qli6J]["FormsTableName"]." WHERE SubscribeErrorPage=$_jfQ0i OR UnsubscribeErrorPage=$_jfQ0i OR SubscribeConfirmationPage=$_jfQ0i OR EditAcceptedPage=$_jfQ0i OR EditConfirmationPage=$_jfQ0i OR EditRejectPage=$_jfQ0i OR EditErrorPage=$_jfQ0i OR UnsubscribeConfirmationPage=$_jfQ0i OR `UnsubscribeBridgePage`=$_jfQ0i OR `RFUSurveyConfirmationPage`=$_jfQ0i OR ('".$_IlJ61[$_Qli6J]["SubscriptionType"]."'='DoubleOptIn' AND SubscribeAcceptedPage=$_jfQ0i) OR ('".$_IlJ61[$_Qli6J]["UnsubscriptionType"]."'='DoubleOptOut' AND UnsubscribeAcceptedPage=$_jfQ0i)";
        $_jjJfo = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        $_jj6L6 = mysql_fetch_row($_jjJfo);
        $_fCQCJ += $_jj6L6[0];
        mysql_free_result($_jjJfo);
        if($_fCQCJ > 0) break;
      }
  }

  if(isset($ML["Type"]))
    $ML["PageType"] = $ML["Type"];


  #### normal placeholders

  $_I1OoI=array();
  if(!isset($ML["PageType"]) || ($ML["PageType"] != 'RFUSurveyConfirmation')  ){
    $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
     $_I1OoI[] =  sprintf("new Array('[%s]', '%s')", $_QLO0f["fieldname"], $_QLO0f["text"]);
    }
    mysql_free_result($_QL8i1);

    # defaults
    foreach ($_Iol8t as $key => $_QltJO)
      $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  } else {
    # defaults
    foreach ($_Iol8t as $key => $_QltJO)
      if($key == "DateShort" || $key == "DateLong" || $key == "Time")
        $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
  }

  # ReasonsForUnsubscriptionSurveyPlaceholders
  if(!isset($ML["PageType"]) || ($ML["PageType"] == 'Unsubscribe')  )
    foreach ($_JQofl as $key => $_QltJO)
      $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

  $_QLJfI = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);

  // ####

  $_QLJfI = _L8AOB($errors, $ML, $_QLJfI);

  $_ICI0L = "";
  if($_fCQCJ > 0) {
     $_ICI0L .= "document.getElementById('Type').disabled = true;$_QLl1Q";
     $_QLJfI = str_replace("<IF:NOTCANCHANGE>", "", $_QLJfI);
     $_QLJfI = str_replace("</IF:NOTCANCHANGE>", "", $_QLJfI);
  } else{
    $_QLJfI = _L80DF($_QLJfI, "<IF:NOTCANCHANGE>", "</IF:NOTCANCHANGE>");
  }
  # show Warnlabel, we cannot mark fckeditor in red because this will be saved
  if(count($errors) > 0) {
    if(in_array('HTMLPage', $errors))
       $_ICI0L .= "document.getElementById('HTMLPageWarnLabel').style.display = '';$_QLl1Q";
  }
  if($_ICI0L != "")
    $_QLJfI = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_ICI0L, $_QLJfI);

  print $_QLJfI;

  function _J0B1A(&$_jfQ0i, $_I6tLJ) {
    global $_jfQtI, $_ItI0o, $_ItIti, $_QLttI;

    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM $_jfQtI";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }
    if (mysql_num_rows($_QL8i1) > 0) {
        while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           foreach ($_QLO0f as $key => $_QltJO) {
              if($key == "Field") {
                 $_Iflj0[] = $_QltJO;
                 break;
              }
           }
        }
        mysql_free_result($_QL8i1);
    }

    // new entry?
    if($_jfQ0i == 0) {
      $_QLfol = "INSERT INTO $_jfQtI (CreateDate) VALUES(NOW())";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
      $_QLO0f=mysql_fetch_array($_QL8i1);
      $_jfQ0i = $_QLO0f[0];
      mysql_free_result($_QL8i1);
    }


    $_QLfol = "UPDATE $_jfQtI SET ";
    $_Io01j = array();
    for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
      $key = $_Iflj0[$_Qli6J];
      if ( isset($_I6tLJ[$_Iflj0[$_Qli6J]]) ) {
        if(in_array($key, $_ItI0o))
          if( $_I6tLJ[$key] == "1" || intval($_I6tLJ[$key]) == 0 )
             $_Io01j[] = "`$key`=1";
             else
              ;
        else {
           $_Io01j[] = "`$key`="._LRAFO(trim($_I6tLJ[$key]))."";
        }
      } else {
         if(in_array($key, $_ItI0o)) {
           $key = $_Iflj0[$_Qli6J];
           $_Io01j[] = "`$key`=0";
         } else {
           if(in_array($key, $_ItIti)) {
             $key = $_Iflj0[$_Qli6J];
             $_Io01j[] = "`$key`=0";
           }
         }
      }
    }

    $_QLfol .= join(", ", $_Io01j);
    $_QLfol .= " WHERE id=$_jfQ0i";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }

  }

?>
