<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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
  include_once("mailcreate.inc.php");
  include_once("mail.php");
  include_once("mailer.php");
  include_once("rss2emailreplacements.inc.php");
  include_once("replacements.inc.php");

   //http://localhost/swm/browser.php?key=twitter-9-1&rid=1B_02_04_0C
  if(!isset($_GET["key"]) || !isset($_GET["rid"])) {
    _LQJ1R();
    exit;
  }

  if($_GET["rid"] == "smp") {
    _LQJAF();
    exit;
  }
  
  $_I1OoI = explode("_", $_GET["rid"]);
  if(count($_I1OoI) < 4) {
    _LQJ1R();
    exit;
  }
  $_jfQLo = hexdec($_I1OoI[0]);
  $_jfIoi = hexdec($_I1OoI[1]);
  $ResponderType = hexdec($_I1OoI[2]);
  $ResponderId = hexdec($_I1OoI[3]);
  $_jfj1I = 0;
  if(count($_I1OoI) > 4){
     if($_I1OoI[4][0] == "x" && hexdec(substr($_I1OoI[4], 1)))
       $_jfj1I = hexdec(substr($_I1OoI[4], 1));
      }
  $OwnerUserId = 0;
  $UserId = $_jfIoi;

  if($_jfQLo == 0 && $ResponderType == 6) { // DistributionList
    print '<p>Test-E-Mail, Text kann nicht angezeigt werden.</p>';
    print "<p>Test email, text can't be displayed.</p>";
    exit;
  }

  // load user

  $_QLfol = "SELECT * FROM $_I18lo WHERE id=$UserId";
  $_jfJ0C = mysql_query($_QLfol, $_QLttI);
  if($_jfJ0C && mysql_num_rows($_jfJ0C) > 0) {
    $_j661I = mysql_fetch_array($_jfJ0C);

    $UserId = $_j661I["id"];
    $OwnerUserId = 0;
    $Username = $_j661I["Username"];
    $UserType = $_j661I["UserType"];
    $INTERFACE_THEMESID = $_j661I["ThemesId"];
    $INTERFACE_LANGUAGE = $_j661I["Language"];

    $_QLfol = "SELECT Theme FROM $_I1tQf WHERE id=$INTERFACE_THEMESID";
    $_I1O6j = mysql_query($_QLfol, $_QLttI);
    $_I1OfI = mysql_fetch_row($_I1O6j);
    $INTERFACE_STYLE = $_I1OfI[0];
    mysql_free_result($_I1O6j);

    _LR8AP($_j661I);

    _LRRFJ($UserId);

    _LRPQ6($INTERFACE_LANGUAGE);

    _JQRLR($INTERFACE_LANGUAGE);

    mysql_free_result($_jfJ0C);
  }

  //


  $_IfLJj = 0;
  $MailingListId = 0;
  $FormId = 0;

  _LPQEP($_GET["key"], $_IfLJj, $MailingListId, $FormId);

  // twitter starts with twitter
  if(strpos($_GET["key"], "twitter") !== false && strpos($_GET["key"], "twitter") == 0){
    $_IfLJj = -1;
  }

  // social media
  if(strpos($_GET["key"], "sme") !== false && strpos($_GET["key"], "sme") == 0){
    $_IfLJj = -1;
  }

  if($_IfLJj == 0 || $MailingListId == 0 || $FormId == 0) {
    _LQJ1R();
    exit;
  }

  if($_jfj1I == 0)
    $_jfj1I = $FormId;


  $_jfJJ0 = _LPOD8($ResponderType);

  if($_jfJJ0 == "") {
   _LQJ1R();
   exit;
  }

  // Responder Type Name
  $ResponderType = _LPORA($ResponderType);

  $_QLfol = "SELECT $_jfJJ0 FROM $_I18lo WHERE id=$_jfIoi";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
    _LQJ1R();
    exit;
  } else {
    $_QLO0f = mysql_fetch_row($_QL8i1);
    $_jfJJ0 = $_QLO0f[0];
    mysql_free_result($_QL8i1);
  }

  if($ResponderType == "Campaign") {
    if($_jfQLo != 0) # no test mail
      $_QLfol = "SELECT ArchiveTableName, LinksTableName, TrackLinks, TrackLinksByRecipient, TrackEMailOpenings, TrackEMailOpeningsByRecipient, TrackEMailOpeningsImageURL, TrackEMailOpeningsByRecipientImageURL, PersonalizeEMails FROM $_jfJJ0 WHERE id=$ResponderId";
      else
      $_QLfol = "SELECT * FROM $_jfJJ0 WHERE id=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
      _LQJ1R();
      exit;
    } else {
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      $_jfJJ1 = $_QLO0f["ArchiveTableName"];
      unset($_QLO0f["ArchiveTableName"]);
      $_jf6Qi = $_QLO0f;
      mysql_free_result($_QL8i1);
    }

    if($_jfQLo != 0) { # no test mail
      $_QLfol = "SELECT * FROM $_jfJJ1 WHERE SendStat_id=$_jfQLo";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
        _LQJ1R();
        exit;
      } else {
        $_jf6Qi = array_merge(mysql_fetch_assoc($_QL8i1), $_jf6Qi);
        mysql_free_result($_QL8i1);
      }
    }
  }
   else if($ResponderType == "FollowUpResponder") {
     $_QLfol = "SELECT `FUMailsTableName`, PersonalizeEMails, TrackLinks, TrackLinksByRecipient, TrackEMailOpenings, TrackEMailOpeningsByRecipient, TrackingIPBlocking FROM $_jfJJ0 WHERE id=$ResponderId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
       _LQJ1R();
       exit;
     } else {
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       $_jIt0L = $_QLO0f["FUMailsTableName"];
       unset($_QLO0f["FUMailsTableName"]);
       $_jf6Qi = $_QLO0f;
       mysql_free_result($_QL8i1);
     }

     $_QLfol = "SELECT * FROM $_jIt0L WHERE id=$_jfQLo";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
       _LQJ1R();
       exit;
     } else {
       $_jf6Qi = array_merge(mysql_fetch_assoc($_QL8i1), $_jf6Qi);
       mysql_free_result($_QL8i1);
     }

   } else if($ResponderType == "BirthdayResponder") {
     $_QLfol = "SELECT * FROM $_jfJJ0 WHERE id=$ResponderId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
       _LQJ1R();
       exit;
     } else {
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       $_jf6Qi = $_QLO0f;
       mysql_free_result($_QL8i1);
     }
   } else if($ResponderType == "RSS2EMailResponder") {
     $_QLfol = "SELECT * FROM $_jfJJ0 WHERE id=$ResponderId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
       _LQJ1R();
       exit;
     } else {
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       $_jf6Qi = $_QLO0f;
       mysql_free_result($_QL8i1);
     }
   } else
  if($ResponderType == "DistributionList") {
    if($_jfQLo != 0) # no test mail
      $_QLfol = "SELECT `CurrentSendTableName`, LinksTableName, TrackLinks, TrackLinksByRecipient, TrackEMailOpenings, TrackEMailOpeningsByRecipient, TrackEMailOpeningsImageURL, TrackEMailOpeningsByRecipientImageURL, PersonalizeEMails FROM $_jfJJ0 WHERE id=$ResponderId";
      else
      $_QLfol = "SELECT * FROM $_jfJJ0 WHERE id=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
      _LQJ1R();
      exit;
    } else {
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      $_jf6Qi = $_QLO0f;
      mysql_free_result($_QL8i1);
    }

    if($_jfQLo != 0) { # no test mail
      $_QLfol = "SELECT `$_IjCfJ`.* FROM `$_IjCfJ` LEFT JOIN `$_jf6Qi[CurrentSendTableName]` ON `$_jf6Qi[CurrentSendTableName]`.`distriblistentry_id`=`$_IjCfJ`.`id` WHERE `$_jf6Qi[CurrentSendTableName]`.`id`=$_jfQLo";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 ) {
        _LQJ1R();
        exit;
      } else {
        $_jf6O1 = mysql_fetch_assoc($_QL8i1);

        if(substr($_jf6O1["MailPlainText"], 0, 4) == "xb64"){
           $_jf6O1["MailPlainText"] = base64_decode( substr($_jf6O1["MailPlainText"], 4) );
        }

        if(substr($_jf6O1["MailHTMLText"], 0, 4) == "xb64"){
           $_jf6O1["MailHTMLText"] = base64_decode( substr($_jf6O1["MailHTMLText"], 4) );
        }

        $_jf6Qi["UseInternalText"] = $_jf6O1["UseInternalText"];
        $_jf6Qi["ExternalTextURL"] = $_jf6O1["ExternalTextURL"];

        $_jf6Qi["MailFormat"] = $_jf6O1["MailFormat"];
        $_jf6Qi["MailPriority"] = $_jf6O1["MailPriority"];
        $_jf6Qi["MailEncoding"] = $_jf6O1["MailEncoding"];
        $_jf6Qi["MailSubject"] = $_jf6O1["MailSubject"];
        $_jf6Qi["MailPlainText"] = $_jf6O1["MailPlainText"];
        $_jf6Qi["MailHTMLText"] = $_jf6O1["MailHTMLText"];
        $_jf6Qi["Attachments"] = $_jf6O1["Attachments"];

        mysql_free_result($_QL8i1);
        unset($_jf6Qi["CurrentSendTableName"]);
      }
    }
  }


  $_QLfol = "SELECT MaillistTableName, FormsTableName FROM $_QL88I WHERE id=$MailingListId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    _LQJ1R();
    exit;
  } else {
    $_QLO0f = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
  }
  $_IfJoo = $_QLO0f["FormsTableName"];
  $_I8I6o = $_QLO0f["MaillistTableName"];
  $_jf6Qi = array_merge($_QLO0f, $_jf6Qi);

  $_QLfol = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL`";
  if(defined("SWM")){ // in SML ever disabled
    $_QLfol .= ", `InfoBarActive`, `InfoBarSchemeColorName`, `InfoBarSrcLanguage`, `InfoBarSpacer`, `InfoBarSupportedTranslationLanguages`, `InfoBarLinksArray`";
  }
  $_QLfol .= " FROM `$_QLO0f[FormsTableName]` WHERE id=$_jfj1I";
  $_jjl0t = mysql_query($_QLfol, $_QLttI);
  if($_jjl0t) {
    $_I8fol = mysql_fetch_assoc($_jjl0t);
    $_j1IIf = $_I8fol["OverrideSubUnsubURL"];
    $_jfffC = $_I8fol["OverrideTrackingURL"];
    mysql_free_result($_jjl0t);
  } else{
    $_j1IIf = "";
    $_jfffC = "";
  }

  if(!isset($_I8fol["InfoBarActive"]))
     $_I8fol["InfoBarActive"] = false;

  if(!empty($_GET["Overlay"]) && $_I8fol["InfoBarActive"])
    $_I8fol["InfoBarActive"] = false;

  if(isset($_GET["socialshare"]))  
     $_I8fol["InfoBarActive"] = false;
  
  $_QLfol = "";
  $_jjtQf = "`$_I8I6o`.`id`=$_IfLJj AND `$_I8I6o`.`IdentString`="._LRAFO($_GET["key"]);
  switch ($ResponderType) {
    case "BirthdayResponder":
      $_jf8JI =
          'TO_DAYS(

              DATE_ADD(
             `u_Birthday`,
                  INTERVAL
                    (YEAR( CURRENT_DATE() ) - YEAR(`u_Birthday`) +
                      IF( DATE_FORMAT(CURRENT_DATE(), "%m%d") > DATE_FORMAT(`u_Birthday`, "%m%d"), 1, 0 )
             )
                    YEAR)
             )
             -
             TO_DAYS( CURRENT_DATE() )
             AS `Days_to_Birthday`';

      $_QLfol = "SELECT `$_I8I6o`.*, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MembersAge, $_jf8JI FROM `$_I8I6o` WHERE $_jjtQf";
    break;
    case "Campaign":
      $_QLfol = "SELECT `$_I8I6o`.*, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MembersAge FROM `$_I8I6o` WHERE $_jjtQf";
    break;
    case "DistributionList":
      $_QLfol = "SELECT `$_I8I6o`.*, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MembersAge FROM `$_I8I6o` WHERE $_jjtQf";
    break;
    default:
      $_QLfol = "SELECT `$_I8I6o`.* FROM `$_I8I6o` WHERE $_jjtQf";
  }

  if($_QLfol == "") {
    _LQJ1R();
    exit;
  }

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if( !$_QL8i1 || mysql_num_rows($_QL8i1) == 0 || isset($_GET["socialshare"]) ) { //*ALTBROWSERLINKURLANONYM*
    // recipient removed
    $_Iflj0 = array();
    _L8FPJ($_I8I6o, $_Iflj0, array());
    reset($_Iflj0);
    foreach($_Iflj0 as $key => $_QltJO) {
      $_jf8if[$key] = "";
    }
    $_jf8if["u_Gender"] = "undefined";
    $_jf8if["MembersAge"] = "";

  } else {
    $_jf8if = mysql_fetch_assoc($_QL8i1);
  }
  if($_QL8i1)
     mysql_free_result($_QL8i1);

  // mail class
  $_j10IJ = new _LEFO8(mtAltBrowserLinkMail);
  $errors = array();
  $_I816i = array();
  $_j108i = "";
  $_j10O1 = "";
  $_jf6Qi["AltBrowserLink"] = 1;
  $_jf6Qi["CurrentSendId"] = $_jfQLo;
  $_jf6Qi["SenderFromAddress"] = "";
  $_jf6Qi["SenderFromName"] = "";
  $_jf6Qi["ReplyToEMailAddress"] = "";
  $_jf6Qi["ReturnPathEMailAddress"] = "";
  $_jf6Qi["CcEMailAddresses"] = "";
  $_jf6Qi["BCcEMailAddresses"] = "";
  $_jf6Qi["ReturnReceipt"] = 0;
  $_jf6Qi["OverrideSubUnsubURL"] = $_j1IIf;
  $_jf6Qi["OverrideTrackingURL"] = $_jfffC;
  // recipients groups for unsubscribe link
  if(!empty($_GET["RG"]))
     $_jf6Qi["GroupIds"] = $_GET["RG"];
  // overlay
  if(!empty($_GET["Overlay"])){
    // overlay ever utf-8
    $_jf6Qi["MailEncoding"] = "utf-8";
    // don't count openings
    $_jf6Qi["TrackEMailOpenings"] = 0;
    $_jf6Qi["TrackEMailOpeningsByRecipient"] = 0;
    // replace all internal placeholders
    reset($_jft6l);
    foreach($_jft6l as $key){
      $_jf6Qi["MailSubject"] = str_replace("[$key]", "", $_jf6Qi["MailSubject"]);
      $_jf6Qi["MailPlainText"] = str_replace("[$key]", "", $_jf6Qi["MailPlainText"]);
      $_jf6Qi["MailHTMLText"] = str_replace("[$key]", "", $_jf6Qi["MailHTMLText"]);
    }
  }

  if($_I8fol["InfoBarActive"] && isset($_GET["attachmentsIndex"]) && intval($_GET["attachmentsIndex"]) > -1){
    $_GET["attachmentsIndex"] = intval($_GET["attachmentsIndex"]);
    $Attachments = @unserialize($_jf6Qi["Attachments"]);
    if($Attachments === false)
      $Attachments = array();
    if($_GET["attachmentsIndex"] < count($Attachments)){
       $_jfO0t = "Location: " . $_jfOJj . "file/" . utf8_encode( CheckFileNameForUTF8( $Attachments[$_GET["attachmentsIndex"] ] ) );
       
       header("Content-Type: text/html; charset=utf-8");
       header($_jfO0t, true, 301);
       die;
    }
  }
  
  // GoogleAnalytics
  if(!empty($_GET["utm_source"])) {
    $_jf6Qi["GoogleAnalyticsActive"] = 1;
    $_jf6Qi["GoogleAnalytics_utm_source"] = !empty($_GET["utm_source"]) ? $_GET["utm_source"] : "";
    $_jf6Qi["GoogleAnalytics_utm_medium"] = !empty($_GET["utm_medium"]) ? $_GET["utm_medium"] : "";
    $_jf6Qi["GoogleAnalytics_utm_term"] = !empty($_GET["utm_term"]) ? $_GET["utm_term"] : "";
    $_jf6Qi["GoogleAnalytics_utm_content"] = !empty($_GET["utm_content"]) ? $_GET["utm_content"] : "";
    $_jf6Qi["GoogleAnalytics_utm_campaign"] = !empty($_GET["utm_campaign"]) ? $_GET["utm_campaign"] : "";
  }

  ###################
  // special for RSS2EMailResponder we must inserting RSS feed entries and refreshing tracking links
  if($ResponderType == "RSS2EMailResponder") {
      $_QLfol = "SELECT `New_RSS_Entries` FROM `$_jf6Qi[ML_RM_RefTableName]` WHERE `Member_id`=$_IfLJj";
      $_jfOfQ = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_jfOfQ) == 0){
        _LQJ1R();
        exit;
      }
      $_jfOfC = mysql_fetch_assoc($_jfOfQ);
      mysql_free_result($_jfOfQ);
      # other entries?
      $_jfoIC = true;
      $_jfo8L = true;

      if($_jfo8L || $_jfoIC) {    # from cron_sendengine.inc.php
         $_jfOfC["New_RSS_Entries"] = @unserialize($_jfOfC["New_RSS_Entries"]);
         if($_jfOfC["New_RSS_Entries"] === false)
            $_jfOfC["New_RSS_Entries"] = array();
         if(!is_array($_jf6Qi["LastRSSFeedContent"])) {
            $_jf6Qi["LastRSSFeedContent"] = @unserialize(base64_decode($_jf6Qi["LastRSSFeedContent"]));
            if($_jf6Qi["LastRSSFeedContent"] === false) {
               $_jf6Qi["LastRSSFeedContent"] = array();
               $_jf6Qi["LastRSSFeedContent"]["ITEMS"] = array();
            }
         }
         _JQ8RF($_jf6Qi["MailHTMLText"], $_jf6Qi["MailSubject"], $_jfOfC["New_RSS_Entries"], $_jf6Qi["LastRSSFeedContent"], $_jf6Qi["EverSendLastRSSFeedMaxEntries"]);

         // auto create plaintext part
         $_jf6Qi["MailPlainText"] = _LC6CP(_LBDA8 ( $_jf6Qi["MailHTMLText"], $_QLo06 ));

      }
  }
  ###################

  ################### InfoBar
  if($_I8fol["InfoBarActive"]){
    $_jfC6J = array(); //not possible/saved
    _LQLO1($_I8fol, $_jf6Qi["MailHTMLText"], $_jf6Qi["MailEncoding"], $_jf6Qi["Attachments"], $_jfC6J);
  }  
  ###################
  
  if(_LEJE8($_j10IJ, $_j108i, $_j10O1, true, $_jf6Qi, $_jf8if, $MailingListId, $FormId, $_jfj1I, $errors, $_I816i, array(), $ResponderId, $ResponderType)) {
    $_QLJfI = $_j10O1;
    $_QLJfI = str_replace(InstallPath, ScriptBaseURL, $_QLJfI);

    // overlay
    if(!empty($_GET["Overlay"])){
      // insert javascript and CSS for overlay
      $_QLJfI = str_ireplace("</head>", '<script type="text/javascript" src="js/jquery-latest.min.js"></script><script type="text/javascript" src="js/common.js"></script><script type="text/javascript" src="js/stats_overlay.js"></script><link type="text/css" rel="stylesheet" href="css/stats_overlay.css" /></head>', $_QLJfI);
      $_QLJfI = _LJA6C($_QLJfI);
    }

    SetHTMLHeaders($_jf6Qi["MailEncoding"]);
    // Prevent the browser from caching the result.
    // Date in the past
    @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
    // always modified
    @header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
    // HTTP/1.1
    @header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0') ;
    @header('Cache-Control: post-check=0, pre-check=0', false) ;
    // HTTP/1.0
    @header('Pragma: no-cache') ;

    $_QLJfI = _L80DF($_QLJfI, "[AltBrowserLink_begin]", "[AltBrowserLink_end]");
    $_QLJfI = _L80DF($_QLJfI, "<AltBrowserLink_begin>", "<AltBrowserLink_end>");
    $_QLJfI = _L80DF($_QLJfI, "<AltBrowserLink>");
    $_QLJfI = _L80DF($_QLJfI, "<!--AltBrowserLink_begin//-->", "<!--AltBrowserLink_end//-->");
    print $_QLJfI;
    exit;
  }

  function _LQOLL($_j1IO0, $_jfCoo){
    $_ILJjL = $_j1IO0;
    $_ILJjL = str_replace('[URL]', $_jfCoo->URL, $_ILJjL);
    $_ILJjL = str_replace('[TITLE]', $_jfCoo->Title, $_ILJjL);
    $_ILJjL = str_replace('[LINKTEXT]', $_jfCoo->Text, $_ILJjL);
    return $_ILJjL;
  }

  function _LQLO1($_I8fol, &$_IoQi6, $_Ioolt, $Attachments = "", $_jfC6J = array(), $_jfCif = false, $_jfiC0 = true){
      global $_QLl1Q, $ShortDateFormat, $resourcestrings, $INTERFACE_LANGUAGE, $_QLo06, $_IIlfi, $_jfilQ, $_jf8if;
      if($_I8fol["InfoBarActive"]){
        $_I8fol["InfoBarSupportedTranslationLanguages"] = @unserialize($_I8fol["InfoBarSupportedTranslationLanguages"]);
        $_I8fol["InfoBarLinksArray"] = @unserialize($_I8fol["InfoBarLinksArray"]);
    
        if($Attachments != ""){
          $Attachments = @unserialize($Attachments);
          if($Attachments === false)
              $Attachments = array();
        }else
          $Attachments = array();
         

        // add pers attachments
        reset($_jfC6J);
        foreach($_jfC6J as $key => $_QltJO){
          if($_QltJO <> 'null')
            $Attachments[] = utf8_encode($_QltJO);
        }
          
        
        $_Ioolt = strtolower($_Ioolt);
        
        // Build entities
        if($_Ioolt != "utf-8")
          foreach($_I8fol["InfoBarLinksArray"] as $key => $_QltJO){
             $_jfCoo = $_I8fol["InfoBarLinksArray"][$key];
             $_I8fol["InfoBarLinksArray"][$key]->Title = UTF8ToEntities( $_I8fol["InfoBarLinksArray"][$key]->Title );
             $_I8fol["InfoBarLinksArray"][$key]->Text = UTF8ToEntities( $_I8fol["InfoBarLinksArray"][$key]->Text );
          }     

        $_jfLj1 = new TAltBrowserLinkInfoBarLinkType(); // < PHP 5.3

        if($_jfiC0){
          $_jfLO0 = join("", file(InstallPath . "altbrowserlink/_AltBrowserLinkStyle.txt"));
          $_jflfj = join("", file(InstallPath . "altbrowserlink/_AltBrowserLinkHTML.txt"));  
          $_jflo0 = join("", file(InstallPath . "altbrowserlink/_AltBrowserLinkScript.txt")); 
        }else{
          // preview NA
          $_IJL6o = InstallPath . 'na';
          if(!empty($_I8fol["TemplatesPath"]))
            $_IJL6o = InstallPath . $_I8fol["TemplatesPath"]; 
          $_IJL6o = _LPC1C($_IJL6o) . "na_start.htm";
          $_jflfj = join("", file($_IJL6o));
          $_jfLO0 = _L8QJA($_jflfj, "<na_infobarcssjs>")  ;
          $_jflo0 = _L8QJA($_jfLO0, "<script>");
          $_jflo0 = "<script>" . $_jflo0 . "</script>";

          $_jflfj = _LRFCO("<ul>", "<ul><CONTENTHERE />", $_jflfj);
          
          $_jflfj = _L81BJ($_jflfj, '<STARTPAGETITLE>', '', UTF8ToEntities($_I8fol["StartPageTitle"]));

          if(!empty($_I8fol["StartPageLogo"]))
           $_jflfj = _L80DF($_jflfj, '<STARTPAGEHEADLINE>');
          $_jflfj = _L81BJ($_jflfj, '<STARTPAGEHEADLINE>', '', UTF8ToEntities( $_I8fol["StartPageLogo"]));

          if(!empty($_I8fol["StartPageLogo"]))
            $_jflfj = _L8O0L($_jflfj, '<STARTPAGELOGO>', '[URL]', $_I8fol["StartPageLogo"]);
            else
            $_jflfj = _L80DF($_jflfj, '<STARTPAGELOGO>');

          $_jflfj = _L81BJ($_jflfj, '<STARTPAGESUBHEADLINE1>', '', UTF8ToEntities( $_I8fol["StartPageSubHeadline1"]));
          $_jflfj = _L81BJ($_jflfj, '<STARTPAGESUBHEADLINE2>', '', UTF8ToEntities( $_I8fol["StartPageSubHeadline2"]));
        
          if(!empty($_I8fol["ShowImpressum"]))
             $_jflfj = _L80DF($_jflfj, '<STARTPAGESHOWIMPRESS>');
             else
             $_jflfj = _L81BJ($_jflfj, '<IMPRESSHEADLINE>', '', UTF8ToEntities( $_I8fol["ImpressumHeadline"]));

          $_j80JC = "";
          if(!empty($_I8fol["ShowImpressum"])){
            $_j80JC = unhtmlentities($_I8fol["ImpressumText"], $_QLo06); // sanitize.inc.php htmlspecialchars()
            if(!$_I8fol["ImpressumIsHTML"])
               $_j80JC = str_replace("\n", "<br />", $_j80JC);
          }
          $_jflfj = _L81BJ($_jflfj, '<IMPRESS>', '', UTF8ToEntities( $_j80JC ));
          
          if(!$_I8fol["LinkToSWM"])
             $_jflfj = _L80DF($_jflfj, '<SuperMailerLink>');
          if($INTERFACE_LANGUAGE == "de")   
           $_jflfj = _L80DF($_jflfj, '<SuperMailerLink_en>');
           else
           $_jflfj = _L80DF($_jflfj, '<SuperMailerLink_de>');
          $_jflfj = _L81BJ($_jflfj, "<spacer>", "", $_I8fol["InfoBarSpacer"]);

          foreach($_I8fol["InfoBarLinksArray"] as $key => $_QltJO){
             $_jfCoo = $_I8fol["InfoBarLinksArray"][$key];
             if($_jfCoo->LinkType == $_jfLj1->abtSubscribe)
               break;
          }     

          if(isset($_I8fol["StartPageShowSubscribeLink"]) && isset($_jfCoo) && $_jfCoo->LinkType == $_jfLj1->abtSubscribe && $_jfCoo->Checked && $_jfCoo->URL != "" ){
            $_j80ij = _L81DB($_jflfj, '<STARTPAGESHOWSUBSCRIBELINK>');
            $_j80ij = str_replace("[URL]", $_jfCoo->URL, $_j80ij);
            $_j80ij = str_replace("[TITLE]", $_jfCoo->Title, $_j80ij);
            $_j80ij = str_replace("[LINKTEXT]", $_jfCoo->Text, $_j80ij);
            $_jflfj = _L81BJ($_jflfj, '<STARTPAGESHOWSUBSCRIBELINK>', "", $_j80ij);
          }
          else
            $_jflfj = _L80DF($_jflfj, '<STARTPAGESHOWSUBSCRIBELINK>');
         $_jflfj = str_replace("[NASCRIPTURL]", "", $_jflfj);   
         $_jflfj = str_replace("[NAENTRYINDEX]", "1", $_jflfj);   
         $_jflfj = str_replace("[NAYEAR]", date("Y"), $_jflfj);   
        }

        if(!$_jfCif)
          $_jflo0 = str_replace('/*DEMO*/', "", $_jflo0);
        
        $_jfLO0 = _L80DF($_jfLO0, '<!--COLORSCHEME', '/COLORSCHEME-->');
        
        $_j816Q = _LA0BA($_IoQi6);
        if($_j816Q == ""){
          if(isset($_GET["ContentLanguage"]) && $_GET["ContentLanguage"] != ""){
            $_j816Q = $_GET["ContentLanguage"];
            $_j816Q = strtolower( preg_replace( '/[^a-z]+/', '', strtolower( $_j816Q ) ) );
            if(strlen($_j816Q) > 3)
              $_j816Q = substr($_j816Q, 0, 3);
          }  
          else
            $_j816Q = $_I8fol["InfoBarSrcLanguage"];
        }  
        
        $_j816L = _LBLDQ();
        foreach($_j816L as $_j8QJ6 => $_QltJO){
          break;
        }
        
        $_j8QJ8 = explode("\n", $_jfLO0);
        for($_Qli6J=0;$_Qli6J<count($_j8QJ8); $_Qli6J++){
          $_j8QJ8[$_Qli6J] = rtrim($_j8QJ8[$_Qli6J]);
          $_j8Qto = strpos($_j8QJ8[$_Qli6J], '/*' . $_j8QJ6 );
          if( $_j8Qto !== false && strpos($_j8QJ8[$_Qli6J], '*/') !== false ){
            
            $_j8IjI = substr($_j8QJ8[$_Qli6J], $_j8Qto);
            $_j8IjI = substr($_j8IjI, strpos($_j8IjI, ' '));
            $_j8IjI = trim( substr($_j8IjI, 0, strpos($_j8IjI, '*/') ) );
            
            $_j8Ioj = strpos($_j8QJ8[$_Qli6J], '/*' . $_I8fol["InfoBarSchemeColorName"] );
            if( $_j8Ioj !== false && strpos($_j8QJ8[$_Qli6J], '*/') !== false ){
              $_j8j08 = substr($_j8QJ8[$_Qli6J], $_j8Ioj);
              $_j8j08 = substr($_j8j08, strpos($_j8j08, ' '));
              $_j8j08 = trim( substr($_j8j08, 0, strpos($_j8j08, '*/') ) );
            }else{
             // ever black
             $_j8j08 = $_j8IjI;
            }
            
            // remove all commented styles
            $_j8QJ8[$_Qli6J] = substr($_j8QJ8[$_Qli6J], 0, strpos($_j8QJ8[$_Qli6J], '/*') - 1 );
            $_j8QJ8[$_Qli6J] = str_replace($_j8IjI, $_j8j08, $_j8QJ8[$_Qli6J]);
            
          }
        }
        
        $_jfLO0 = join($_QLl1Q, $_j8QJ8);
        $_j8QJ8 = null;
        
        $_j8j86 = _L8QJA($_jflfj, '<SUBSCRIBELINK>', '</SUBSCRIBELINK>');
        $_j8JQl = _L8QJA($_jflfj, '<UNSUBSCRIBELINK>', '</UNSUBSCRIBELINK>');
        $_j8J81 = _L8QJA($_jflfj, '<SOCIALLINK>', '</SOCIALLINK>');
        $_j86ji = _L8QJA($_jflfj, '<ARCHIEVELINK>', '</ARCHIEVELINK>');
        $_j86Cj = _L8QJA($_jflfj, '<RSSFEEDLINK>', '</RSSFEEDLINK>');
        $_j8f8O = _L8QJA($_jflfj, '<TRANSLATE_BLOCK>', '</TRANSLATE_BLOCK>');
        
//NA preview
        $_j88fo = _L8QJA($_jflfj, '<HOMELINK>', '</HOMELINK>');
        $_j88L0 = _L8QJA($_jflfj, '<NAYEARSBLOCK>', '</NAYEARSBLOCK>');
        $_j8t88 = _L8QJA($_jflfj, '<NAENTRIESBLOCK>', '</NAENTRIESBLOCK>');
        
        
        $_j8toO = '';
        foreach($_I8fol["InfoBarLinksArray"] as $key => $_QltJO){
           $_jfCoo = $_I8fol["InfoBarLinksArray"][$key];
           if(!$_jfCoo->Checked){ 
             if($_jfLj1->abtAttachments == $_jfCoo->LinkType)
                $_jflfj = _L80DF($_jflfj, '<ATTACHMENTSBLOCK>');
             continue;
           }  
           switch($_jfCoo->LinkType){
            case $_jfLj1->abtSubscribe: 
                           $_j8toO .= _LQOLL($_j8j86, $_jfCoo);
                          break;
            case $_jfLj1->abtUnsubscribe: 
                           if(!isset($_jf8if) || !isset($_jf8if["id"]) || intval($_jf8if["id"]) == 0)
                           $_j8toO .= "";
                           else
                           $_j8toO .= _LQOLL($_j8JQl, $_jfCoo);
                          break;
            case $_jfLj1->abtFacebook: 
                           $_j8toO .= _LQOLL($_j8J81, $_jfCoo);
                          break;
            case $_jfLj1->abtTwitter: 
                           $_j8toO .= _LQOLL($_j8J81, $_jfCoo);
                          break;
            case $_jfLj1->abtArchieve: 
                           $_j8toO .= _LQOLL($_j86ji, $_jfCoo);
                          break;
            case $_jfLj1->abtRSS: 
                           $_j8toO .= _LQOLL($_j86Cj, $_jfCoo);
                    break;
            case $_jfLj1->abtTranslate: 
                           $_j8tiJ = _L8QJA($_j8f8O, '<TRANSLATE_ITEM>', '</TRANSLATE_ITEM>');
                           $_j8OOQ = '';
                           for($_QliOt=0; $_QliOt<count($_I8fol["InfoBarSupportedTranslationLanguages"]); $_QliOt++){
                             $_j8OOC = $_I8fol["InfoBarSupportedTranslationLanguages"][$_QliOt];
                             if($_j816Q == $_j8OOC["code"]) continue;
                             
                             if($_Ioolt == "utf-8")
                                $_j8Oit = $_j8OOC["Name"];
                                else
                                $_j8Oit = UTF8ToEntities($_j8OOC["Name"]);
                               
                             $_j8OOQ .= $_j8tiJ;
                             $_j8OOQ = str_replace('[TITLE]', $_j8Oit, $_j8OOQ);
                             $_j8OOQ = str_replace('[LINKTEXT]', $_j8Oit, $_j8OOQ);
                             $_j8OOQ = str_replace('[LANGUAGE]', $_j8OOC["code"], $_j8OOQ);
                             $_j8OOQ = str_replace('[SRCLANGUAGE]', $_j816Q, $_j8OOQ);
                           }

                           $_j8f8O = str_replace('<TRANSLATE_ITEMS />', $_j8OOQ, $_j8f8O);

                           $_j8toO .= _LQOLL($_j8f8O, $_jfCoo);
                          break;
            case $_jfLj1->abtAttachments:                 
                         if(count($Attachments)){

                           $_IoLOO = _L81DB($_jflfj, "<ATTACHMENTSBLOCK>");
                           $_IoLOO = _LQOLL($_IoLOO, $_jfCoo);
                           $_j8OLI = _L81DB($_IoLOO, "<ATTACHMENTBLOCK>");
                           $_j8otC = "";
                          
                           for($_I016j=0; $_I016j<count($Attachments); $_I016j++){
                             $Attachments[$_I016j] = CheckFileNameForUTF8($Attachments[$_I016j]);
                             if(!@file_exists($_IIlfi . $Attachments[$_I016j]) && !$_jfCif) continue;
                             $_j8otC .= $_j8OLI;
                             
                             if($_Ioolt == "utf-8")
                               $Attachments[$_I016j] = htmlspecialchars(utf8_encode($Attachments[$_I016j]), ENT_COMPAT, $_Ioolt); 
                               else
                               $Attachments[$_I016j] = htmlspecialchars($Attachments[$_I016j], ENT_COMPAT, $_Ioolt); 
                             $_j8otC = str_replace("[ATTACHMENTSTITLE]", $Attachments[$_I016j], $_j8otC);
                             $_j8otC = str_replace("[ATTACHMENTSFILENAME]", $Attachments[$_I016j], $_j8otC);
                             $_j8otC = str_replace("[SCRIPTURL]", $_jfilQ . "?key=" . $_GET["key"] . "&rid=" . $_GET["rid"], $_j8otC);
                             $_j8otC = str_replace("[ATTACHMENTSINDEX]", $_I016j . "&rand=" . rand(0, 65535), $_j8otC);
                           } 
                           $_IoLOO = _L81BJ($_IoLOO, "<ATTACHMENTBLOCK>", "", $_j8otC);
                           $_jflfj = _L81BJ($_jflfj, "<ATTACHMENTSBLOCK>", "", $_IoLOO);
                           
                           $_IoQi6 = str_ireplace("</body>", $_I8fol["InfoBarSpacer"] . "</body>", $_IoQi6);
                           
                         }else
                           $_jflfj = _L80DF($_jflfj, '<ATTACHMENTSBLOCK>');
                         break;
                          
//NA preview
            case $_jfLj1->abtHome: 
                         $_jfCoo->URL = "StartPage";
                         $_j8toO .= _LQOLL($_j88fo, $_jfCoo);
                       break;
            case $_jfLj1->abtYears: 
                         $_j88L0 = str_replace('[NAYEARPrefix]', UTF8ToEntities($_I8fol["YearsPrefix"]), $_j88L0);
                         $_j88L0 = str_replace('[NAYEAR]', date("Y"), $_j88L0);
                         $_j8toO .= $_j88L0;
                         break;
            case $_jfLj1->abtEntries: 
                         $_j8toO .= $_j8t88;
                         $_j8toO = str_replace('[NAENTRYPrefix]', UTF8ToEntities($_I8fol["NewsletterEntryPrefix"]), $_j8toO);
                         $_j8toO = str_replace('[NAENTRYDATE]', date($ShortDateFormat), $_j8toO);
                         $_j8toO = str_replace('[NAENTRYSUBJECT]', $resourcestrings[$INTERFACE_LANGUAGE]["rsNASampleSubject"], $_j8toO);


                         $_jflfj = _L8O0L($_jflfj, '<STARTPAGELATESTENTRIESBLOCK>', '[NAENTRYPrefix]', UTF8ToEntities($_I8fol["NewsletterEntryPrefix"]));
                         $_jflfj = _L8O0L($_jflfj, '<STARTPAGELATESTENTRIESBLOCK>', '[NAENTRYDATE]', date($ShortDateFormat));
                         $_jflfj = _L8O0L($_jflfj, '<STARTPAGELATESTENTRIESBLOCK>', '[NAENTRYSUBJECT]', $resourcestrings[$INTERFACE_LANGUAGE]["rsNASampleSubject"]);
                         break;
            }
        }
        
        if($_jfiC0){
          $_jflfj = $_I8fol["InfoBarSpacer"] . str_replace('<CONTENTHERE />', $_j8toO, $_jflfj);
          
          $_QLoli = $_IoQi6;
          if(stripos($_QLoli, "<body") !== false) {
            $_j8oLj = substr($_QLoli, 0, stripos($_QLoli, "<body") + strlen("<body"));
            $_j8C8I = substr($_QLoli, stripos($_QLoli, "<body") +  strlen("<body"));
            $_j8oLj .= substr($_j8C8I, 0, strpos($_j8C8I, ">") + 1);
            $_j8C8I = substr($_j8C8I, strpos($_j8C8I, ">") + 1);
            $_j8C8I = $_jflfj . $_j8C8I;
            $_QLoli = $_j8oLj.$_j8C8I;
            $_j8oLj = null;
            $_j8C8I = null;
          }else
            $_QLoli = $_jflfj . $_QLoli;
        }else{
          $_QLoli = str_replace('<CONTENTHERE />', $_j8toO, $_jflfj);
          $_QLoli = str_replace('<head>', '<head>' . '<script>function DemoLinks(){ try{var links = document.querySelectorAll("A");}catch(e){var links = document.getElementsByTagName("A");} for(var i=0; i<links.length; i++){links[i].onclick = function() {alert("' . htmlspecialchars($_GET["samplePreview"], ENT_COMPAT, $_Ioolt) . '");return false;};}}</script>', $_QLoli);
        }

        $_QLoli = _L80R1('</head>', $_jfLO0 . $_jflo0 . '</head>', $_QLoli);
          
        $_IoQi6 = $_QLoli;
      }
  }

  function _LQJ1R() {
    global $_IC18i, $_QLo06;

    $_QLJfI = '<p>Text nicht gefunden!</p>';
    $_QLJfI .= '<p>Text not found!</p>';
    $_QLJfI = str_replace('</body', $_QLJfI . '</body', $_IC18i);
    $_QLJfI = str_replace('</head', '<meta http-equiv="Content-Type" content="text/html; charset=' . $_QLo06 . '" />' . '</head', $_QLJfI);
    
    SetHTMLHeaders($_QLo06);
    print $_QLJfI;
    
    exit;
  }

  function _LQJAF() {
    global $_IC18i, $_QLo06, $_QLttI, $_QL88I, $resourcestrings, $INTERFACE_LANGUAGE;
    
    $_jfiC0 = isset($_GET["MailingListId"]) && isset($_GET["FormId"]);

    if(isset($_GET["samplePreview"])){
      if($_jfiC0)
        $_QLJfI = '<p align="left"><font face="Arial" size="3">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.At vero eos et accusam et justo duo dolores et ea rebum.</font></p>';
        else
        $_QLJfI = "";
      include_once("sessioncheck.inc.php");
      global $_j6JfL;
    }  
    else{
      $_QLJfI = '<p>Text in Serien-E-Mail-Vorschau nicht verf&uuml;gbar.</p>';
      $_QLJfI .= '<p>Text in serial email preview not available.</p>';
    }
    
    $_QLJfI = str_replace('</body', $_QLJfI . '</body', $_IC18i);
    $_QLJfI = str_replace('</head', '<meta http-equiv="Content-Type" content="text/html; charset=' . $_QLo06 . '" />' . '</head', $_QLJfI);

    if(isset($_GET["samplePreview"])){
      
      if($_jfiC0){
        $_QLJfI = _LPFQD("<title>", "</title>", $_QLJfI, htmlspecialchars($_GET["samplePreview"], ENT_COMPAT, $_QLo06) );
        $_QLJfI = str_replace('<head>', '<head>' . '<script>function DemoLinks(){ try{var links = document.querySelectorAll("A");}catch(e){var links = document.getElementsByTagName("A");} for(var i=0; i<links.length; i++){links[i].onclick = function() {alert("' . htmlspecialchars($_GET["samplePreview"], ENT_COMPAT, $_QLo06) . '");return false;};}}</script>', $_QLJfI);
        $_QLfol = "SELECT FormsTableName FROM $_QL88I WHERE id=" . intval($_GET["MailingListId"]);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        $_QLO0f = mysql_fetch_array($_QL8i1);
        $_IfJoo = $_QLO0f["FormsTableName"];
        mysql_free_result($_QL8i1);
        
        $_QLfol= "SELECT * FROM `$_IfJoo` WHERE id=" . intval($_GET["FormId"]);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        $_I8fol = mysql_fetch_array($_QL8i1);
        mysql_free_result($_QL8i1);
      }else{
        $_QLfol= "SELECT * FROM `$_j6JfL` WHERE id=" . intval($_GET["NAListId"]);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        $_I8fol = mysql_fetch_array($_QL8i1);
        mysql_free_result($_QL8i1);
        $_GET["InfoBarActive"] = true;
        $_QLO0f = $_I8fol;
      }
      
      $ML["InfoBarSupportedTranslationLanguages"] = @unserialize($_QLO0f["InfoBarSupportedTranslationLanguages"]);
      $ML["InfoBarLinksArray"] = @unserialize($_QLO0f["InfoBarLinksArray"]);
      if( !isset($ML) || !isset($ML["InfoBarSupportedTranslationLanguages"]) || !isset($ML["InfoBarLinksArray"]) || $ML["InfoBarSupportedTranslationLanguages"] === false || $ML["InfoBarLinksArray"] === false)
         if($_jfiC0)
           _LBLPC($ML["InfoBarSupportedTranslationLanguages"], $ML["InfoBarLinksArray"]);
           else
           _LBJ6B($ML["InfoBarSupportedTranslationLanguages"], $ML["InfoBarLinksArray"]);
         
      $_jfLj1 = new TAltBrowserLinkInfoBarLinkType(); // < PHP 5.3
      
      $_POST = $_GET;
        
      // from formedit.php/naedit.php  
      foreach($ML["InfoBarLinksArray"] as $_Qli6J => $_QltJO){

       if(!$_jfiC0 && ($_QltJO->LinkType == $_jfLj1->abtUnsubscribe || $_QltJO->LinkType == $_jfLj1->abtArchieve))
          continue; // not used here

       $_jfCoo = new TAltBrowserLinkInfoBarLink();
       $_jfCoo->LinkType = $_QltJO->LinkType;
       $_jfCoo->Checked = isset($_POST["Link"]) && isset($_POST["Link"][$_jfCoo->LinkType]);
       
       $_jfCoo->URL = isset($_POST["URL"]) && isset($_POST["URL"][$_jfCoo->LinkType]) ? $_POST["URL"][$_jfCoo->LinkType] : "";
       $_jfCoo->Title = isset($_POST["Title"]) && isset($_POST["Title"][$_jfCoo->LinkType]) ? $_POST["Title"][$_jfCoo->LinkType] : "";
       $_jfCoo->Text = isset($_POST["Text"]) && isset($_POST["Text"][$_jfCoo->LinkType]) ? $_POST["Text"][$_jfCoo->LinkType] : "";

       $_jfCoo->internalCaption = $ML["InfoBarLinksArray"][$_Qli6J]->internalCaption;
       if(empty($_jfCoo->URL))
         $_jfCoo->URL = $ML["InfoBarLinksArray"][$_Qli6J]->URL;
       if(empty($_jfCoo->Title))
         $_jfCoo->Title = $ML["InfoBarLinksArray"][$_Qli6J]->Title;
       if(empty($_jfCoo->Text))
         $_jfCoo->Text = $ML["InfoBarLinksArray"][$_Qli6J]->Text;
       
       $_j8LoO[] = $_jfCoo;
      }
      
      $_POST["InfoBarLinksArray"] = serialize($_j8LoO);
      _LBLPR($_j8l18);
      $_POST["InfoBarSupportedTranslationLanguages"] = serialize($_j8l18);
     
      
      _LQLO1($_POST, $_QLJfI, $_QLo06, serialize(array($resourcestrings[$INTERFACE_LANGUAGE]["rsNASampleAttachment"])), array(), true, $_jfiC0);
      $_QLJfI = str_replace('/*DEMO*/', 'DemoLinks();', $_QLJfI);
    }  
    SetHTMLHeaders($_QLo06);
    print $_QLJfI;
    exit;
  }
?>
