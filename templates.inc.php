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
 include_once("securitycheck.inc.php");
 include_once("savedoptions.inc.php");

 function _JJAQE($_8CCLt, $_8Cit6 = true){
   $_I1OoI = @file(_LOC8P().$_8CCLt);
   if($_I1OoI) {
      $_QLoli = join("", $_I1OoI);
      unset($_I1OoI);
     }
     else
     $_QLoli = join("", file(InstallPath._LOC8P().$_8CCLt));

   if(strpos($_QLoli, 'js_localization.php') === false){
     $_QLoli = str_replace('<head>', '<head><script type="text/javascript" src="js_localization.php"></script>', $_QLoli);
   }
   if(strpos($_QLoli, 'jquery-latest.min.js') === false){
     $_QLoli = str_replace('</head>', '<script type="text/javascript" src="js/jquery-latest.min.js"></script>'.'</head>', $_QLoli);
   }
   if(strpos($_QLoli, 'common.js') === false){
     $_QLoli = str_replace('</head>', '<script language="javascript" type="text/javascript" src="js/common.js"></script>'.'</head>', $_QLoli);
   }
   return $_8Cit6 ? _LJA6C($_QLoli) : $_QLoli;
 }

 function GetMainTemplate($_8CiO0, $UserType, $_8Cioo, $_8CiCQ, $_8Cil8, $_8CLtL, $_8Cl1I, $_8ClIt, $_8Cllt = "", $_foQ6I = "utf-8") {
   global $_Ij6Lj, $AppName, $_QLo06, $INTERFACE_LANGUAGE, $resourcestrings;
   global $_foo18, $_8i0Ol, $_8i1tf;
   global $SHOW_LOGGEDINUSER, $SHOW_SUPPORTLINKS, $SHOW_SHOWCOPYRIGHT, $SHOW_PRODUCTVERSION, $SHOW_TOOLTIPS, $_SESSION;
   global $_JQI6L, $OwnerOwnerUserId, $UserId, $OwnerUserId, $_J18oI, $_jJtt8;
   global $_QLttI;

   if($_8Cioo == "" || $UserType == "none") {
     $_8CiO0 = false;
     $_8CiCQ = false;
   }

   $_QLoli = _JJAQE("main.htm", false);

   $_8i1OJ = _JJAQE("$_8ClIt", false);

   if(strpos($_8i1OJ, "<html") === false)
     $_8iQ1Q = true;
     else {
       $_8iQ1Q = false;
       $_QLoli = $_8i1OJ;
       $_8i1OJ = "";
     }

   if($_8Cllt == "")
     $_8Cllt = "$_8Cil8";

   if(defined("ShowUserNameInTitlebar") && $_8Cioo != "")
     $_QLoli = str_replace("<title>", "<title>".$_8Cioo." - ". $_8Cllt, $_QLoli);
   else
     $_QLoli = str_replace("<title>", "<title>".$AppName." - ". $_8Cllt, $_QLoli);

   if($_8Cioo == "" || $UserType == "none") {
     $_QLoli = _L81BJ($_QLoli, "<SHOW:PRODUCTVERSION>", "</SHOW:PRODUCTVERSION>", "&nbsp;" );
     $SHOW_SUPPORTLINKS = false;
     $SHOW_SHOWCOPYRIGHT = false;
   }

   if($_8Cl1I != "DISABLED") {
       $_QLoli = str_replace('name="HelpTopic"', 'name="HelpTopic" value="'.$_8Cl1I.'"', $_QLoli);
     }
     else {
       $_QLoli = _L81BJ($_QLoli, "<SHOWHIDE:HELPTOPIC>", "</SHOWHIDE:HELPTOPIC>", "");
     }

   _JJCCF($_QLoli);

   if(!$SHOW_SUPPORTLINKS) {
     $_QLoli = _L80DF($_QLoli, "<SHOW:SUPPORTLINKS>", "</SHOW:SUPPORTLINKS>");
   }

   if(!$SHOW_SHOWCOPYRIGHT) {
     $_QLoli = _L80DF($_QLoli, "<SHOW:SHOWCOPYRIGHT>", "</SHOW:SHOWCOPYRIGHT>");
   }

   if(!$SHOW_PRODUCTVERSION) {
     $_QLoli = _L80DF($_QLoli, "<SHOW:PRODUCTVERSION>", "</SHOW:PRODUCTVERSION>");
   }

   if(!$_8CiO0) {
     $_QLoli = _L80DF($_QLoli, "<ENABLE:SYSTEMMENU>", "</ENABLE:SYSTEMMENU>");
   }

   # login screen disable all
   if($_8ClIt == "login_snipped.htm") {
     $_QLoli = _L80DF($_QLoli, "<SHOW:SUPPORTLINKS>", "</SHOW:SUPPORTLINKS>");
     $_QLoli = _L80DF($_QLoli, "<SHOW:SHOWCOPYRIGHT>", "</SHOW:SHOWCOPYRIGHT>");
   }

   // snipped insert
   if($_8iQ1Q)
      $_QLoli = _L81BJ($_QLoli, "<CONTAINER:CONTENT>", "</CONTAINER:CONTENT>", $_8i1OJ);

   if ( $UserType != "SuperAdmin"  ) {
     $_QLoli = _L80DF($_QLoli, "<ISUSER:SUPERADMIN>", "</ISUSER:SUPERADMIN>");
   }

   if ( ($UserType != "SuperAdmin") && ($UserType != "Admin") ) {
     $_QLoli = _L80DF($_QLoli, "<ISUSER:ADMINISTRATOR>", "</ISUSER:ADMINISTRATOR>");
   }

   if ( ($UserType != "SuperAdmin") && ($UserType != "Admin") && ($UserType != "User") ){
     $_QLoli = _L80DF($_QLoli, "<ISUSER:USER>", "</ISUSER:USER>");
   }

   if ( (!$SHOW_LOGGEDINUSER) || ($_8Cioo == "") ) {
     $_QLoli = _L80DF($_QLoli, "<SHOWHIDE:LOGGEDINUSER>", "</SHOWHIDE:LOGGEDINUSER>");
   }

   // SuperAdmin doesn't need mainmenu
   if ( ($UserType == "SuperAdmin") )
     $_8CiCQ = false;

   $_QLoli = _L81BJ($_QLoli, "<LABEL:LOGGEDINUSER>", "</LABEL:LOGGEDINUSER>", $_8Cioo );

   if(!$_8CiCQ) {
     $_QLoli = _L80DF($_QLoli, "<ENABLE:MAINMENU>", "</ENABLE:MAINMENU>");
   }

   if($_8Cil8 == "") {
     $_QLoli = _L80DF($_QLoli, "<SHOWHIDE:PAGETOPIC>", "</SHOWHIDE:PAGETOPIC>");
   } else {
     $_QLoli = _L81BJ($_QLoli, "<LABEL:PAGETOPIC>", "</LABEL:PAGETOPIC>", $_8Cil8 );
   }


   if($_8CLtL == "") {
     $_QLoli = _L80DF($_QLoli, "<SHOWHIDE:ERRORTOPIC>", "</SHOWHIDE:ERRORTOPIC>");
   } else {
     $_QLoli = _L81BJ($_QLoli, "<LABEL:ERRORMESSAGETEXT>", "</LABEL:ERRORMESSAGETEXT>", $_8CLtL );
   }

   if(!$SHOW_TOOLTIPS) {
     $_QLoli = _L80DF($_QLoli, "<SHOWHIDE:TOOLTIPS>", "</SHOWHIDE:TOOLTIPS>");
   }

   if( isset($_SESSION["ProductLogoURL"]) && trim($_SESSION["ProductLogoURL"]) != "") {
     $_QLoli = _L81BJ($_QLoli, "<SHOWHIDE:ProductLogo>", "</SHOWHIDE:ProductLogo>", '<img src="'.$_SESSION["ProductLogoURL"].'" class="normalimage" alt="" />');
   } else if(!defined("Setup") && ($_8ClIt == "login_snipped.htm" || $_8ClIt == "pw_reminder_snipped.htm" || $_8ClIt == "pw_reminder_sendpw_snipped.htm" || $_8ClIt == "pw_reminder_notsendpw_snipped.htm" || $_8ClIt == "login2fa1_snipped.htm")
    && ( ($_8iI00 = _JOLQE("ProductLogoURL")) != "") && !defined("DEMO") ) {
     $_QLoli = _L81BJ($_QLoli, "<SHOWHIDE:ProductLogo>", "</SHOWHIDE:ProductLogo>", '<img src="'.$_8iI00.'" class="normalimage" alt="" />');
   }

   if(!defined("SWM")) {
     $_QLoli = _L80DF($_QLoli, "<IS:SWM>", "</IS:SWM>");
   }
   if(!defined("SML")) {
     $_QLoli = _L80DF($_QLoli, "<IS:SML>", "</IS:SML>");
   }

   if(!$_8CiCQ || defined("Setup")) {
     $_QLoli = _L80DF($_QLoli, "<SHOWHIDE:EVALUATIONHEADER>", "</SHOWHIDE:EVALUATIONHEADER>");
   } else {
     if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
        $_QLoli = _L81BJ($_QLoli, "<LABEL:EVALUATIONHEADER>", "</LABEL:EVALUATIONHEADER>", $resourcestrings[$INTERFACE_LANGUAGE]["999998"] );
     } else
        if(defined("DEMO") || defined("SimulateMailSending"))
          $_QLoli = _L81BJ($_QLoli, "<LABEL:EVALUATIONHEADER>", "</LABEL:EVALUATIONHEADER>", $resourcestrings[$INTERFACE_LANGUAGE]["999997"] );
          else
          $_QLoli = _L80DF($_QLoli, "<SHOWHIDE:EVALUATIONHEADER>", "</SHOWHIDE:EVALUATIONHEADER>");
   }

   // Security check
   if(!$_8CiCQ && !defined("Setup")) {

     $_8iIj6 = false;
     if ($_8Cioo == "") { // Login Screen
       if( _JO6D0() ) {
          $_QLoli = _L81BJ($_QLoli, "<LABEL:WARNHEADER>", "</LABEL:WARNHEADER>", $resourcestrings[$INTERFACE_LANGUAGE]["ConfigFilesWriteable"]);
          $_8iIj6 = true;
       }
     }

     // only if dashboard is shown
     if(!$_8iIj6 && $_8ClIt == 'dashboard_snipped.htm' ) {
       if (!_JORB8() ) {

          if ( ($UserType == "SuperAdmin") )
             $_8ijjI = InstallPath."userfiles/";
             else
             $_8ijjI = $_J18oI;

          $_QLoli = _L81BJ($_QLoli, "<LABEL:WARNHEADER>", "</LABEL:WARNHEADER>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UserPathsNotWriteable"], $_8ijjI) );
          $_8iIj6 = true;
       }
     }

     if(!$_8iIj6)
        $_QLoli = _L80DF($_QLoli, "<SHOWHIDE:WARNHEADER>", "</SHOWHIDE:WARNHEADER>");
   } else {

      $_8iIj6 = false;
#     if( _JO6D0() ) {
#        $_QLoli = _L81BJ($_QLoli, "<LABEL:WARNHEADER>", "</LABEL:WARNHEADER>", $resourcestrings[$INTERFACE_LANGUAGE]["ConfigFilesWriteable"]);
#        $_8iIj6 = true;
#     }

     // only if dashboard is shown
     if(!$_8iIj6 && $_8ClIt == 'dashboard_snipped.htm' ) {
       if (!_JORB8() ) {
          $_QLoli = _L81BJ($_QLoli, "<LABEL:WARNHEADER>", "</LABEL:WARNHEADER>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UserPathsNotWriteable"], $_J18oI) );
          $_8iIj6 = true;
       }
     }

     if(!$_8iIj6)
        $_QLoli = _L80DF($_QLoli, "<SHOWHIDE:WARNHEADER>", "</SHOWHIDE:WARNHEADER>");
   }
   // Security check

   if( ($_8CiCQ || defined("CampaignLiveSending") || defined("OnlineUpdate")) && !defined("Setup") && $SHOW_LOGGEDINUSER && $_8iQ1Q && $_8Cioo != "" ) {
     $_QLfol = "SELECT COUNT(id) FROM `$_jJtt8` WHERE `To_users_id`=$UserId AND `IsReaded`=0";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_8iJIf = 0;
     if($_QL8i1) {
       $_QLO0f = mysql_fetch_row($_QL8i1);
       mysql_free_result($_QL8i1);
       $_8iJIf = $_QLO0f[0];
     }
     $_QLoli = _L81BJ($_QLoli, "<LOCALMESSAGES_COUNT>", "</LOCALMESSAGES_COUNT>", $_8iJIf);
   }

   // remove ControlTags

   for($_Qli6J=0; $_Qli6J < count($_JQI6L); $_Qli6J++) {
     $_QLoli = _L8OF8($_QLoli, $_JQI6L[$_Qli6J]);
   }

   // privilegs
   if( /*($OwnerUserId != 0 || $UserType == "SuperAdmin") &&*/ !defined("Setup") && $_8iQ1Q && ( strpos($_QLoli, '<div class="PageContainer">') !== false ) ) { // only for snippeds!
     $_ICIIQ = substr($_QLoli, strpos($_QLoli, '<div class="PageContainer">'));
     $_QLoli = substr($_QLoli, 0, strpos($_QLoli, '<div class="PageContainer">') - 1);

     if($UserType != "SuperAdmin" && $UserType != "Admin") {
       $_QLoli = _JJB1L($_QLoli, "settings_branding.php");
     }
     
     if($UserType != "SuperAdmin") {
       $_QLoli = _JJB1L($_QLoli, "settings_authsettings.php");
     }

     if($UserType != "SuperAdmin" && $UserType != "Admin") {
       $_QLoli = _JJB1L($_QLoli, "browseoutqueue.php");
     }

     if($UserId){
       $_QLJJ6 = _LPALQ($UserId);
       if(!$_QLJJ6["PrivilegePageBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browsepages.php");
       }
       if(!$_QLJJ6["PrivilegeMessageBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browsemessages.php");
       }
       if(!$_QLJJ6["PrivilegeMTABrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browsemtas.php");
       }
       if(!$_QLJJ6["PrivilegeInboxBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browseinboxes.php");
       }
       if(!$_QLJJ6["PrivilegeAutoImportBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browseimports.php");
       }
       if(!$_QLJJ6["PrivilegeOptionsEdit"]) {
         $_QLoli = _JJB1L($_QLoli, "settings_preferences.php");
       }
       if(!$_QLJJ6["PrivilegeFormBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browseforms.php");
       }

       if(!$_QLJJ6["PrivilegeDbRepair"]) {
         $_QLoli = _JJB1L($_QLoli, "settings_db.php");
       }
       if(!$_QLJJ6["PrivilegeSystemTest"]) {
         $_QLoli = _JJB1L($_QLoli, "settings_test.php");
       }
       if(!$_QLJJ6["PrivilegeViewProcessLog"]) {
         $_QLoli = _JJB1L($_QLoli, "stat_processlog.php");
       }
       if(!$_QLJJ6["PrivilegeCron"]) {
         $_QLoli = _JJB1L($_QLoli, "settings_cron.php");
       }

       if(!$_QLJJ6["PrivilegeLocalMessageBrowse"]) {
         $_QLoli = _L80DF($_QLoli, '<SHOWHIDE:LOCALMESSAGES>', '</SHOWHIDE:LOCALMESSAGES>');
         $_QLoli = _JJB1L($_QLoli, "browselocalmessages.php");
         $_QLoli = _JJB1L($_QLoli, "javascript:LocalMessagesBrowse()");
       } else{
         $_QLoli = _L8OF8($_QLoli, '<SHOWHIDE:LOCALMESSAGES>');
       }

       if(!$_QLJJ6["PrivilegeUserBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browseusers.php");
         $_QLoli = _JJC0E($_QLoli, "browseusers.php");
       }

       if(!$_QLJJ6["PrivilegeMailingListCreate"]) {
         $_QLoli = _JJB1L($_QLoli, "mailinglistcreate.php");
       }

       if(!$_QLJJ6["PrivilegeMailingListBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browsemailinglists.php");
         $_QLoli = _JJB1L($_QLoli, "searchrecipients.php");
       }

       if(!$_QLJJ6["PrivilegeRecipientCreate"]) {
         $_QLoli = _JJB1L($_QLoli, "recipientedit.php");
       }
       if(!$_QLJJ6["PrivilegeRecipientBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browsercpts.php");
         $_QLoli = _JJB1L($_QLoli, "searchrecipients.php");
       }

       if(!$_QLJJ6["PrivilegeLocalBlockListRecipientBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browseblmembers.php?action=local");
         $_QLoli = _JJB1L($_QLoli, "browsedomainblmembers.php?action=local");
         $_QLoli = _JJB1L($_QLoli, "ml_remove_recipients_by_blocklists.php");
       }
       if(!$_QLJJ6["PrivilegeGlobalBlockListRecipientBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browseblmembers.php?action=global");
         $_QLoli = _JJB1L($_QLoli, "browsedomainblmembers.php?action=global");
         $_QLoli = _JJB1L($_QLoli, "ml_remove_recipients_by_blocklists.php");
       }

       if(!$_QLJJ6["PrivilegeImportBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "importrecipients.php");
       }
       if(!$_QLJJ6["PrivilegeExportBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "exportrecipients.php");
       }

       if(!$_QLJJ6["PrivilegeMLSubUnsubStatBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "mailinglistsubunsubstat.php");
       }
       if(!$_QLJJ6["PrivilegeAllMLStatBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "showstatsummary.php");
       }

       if(!$_QLJJ6["PrivilegeMailsSentStatBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "stat_sentmails.php");
       }

       if(!$_QLJJ6["PrivilegeAutoImportBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "browseautoimports.php");
       }

       if(!$_QLJJ6["PrivilegeFunctionBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "javascript:FunctionsOpen('null', 'null', false)");
       }

       if(!$_QLJJ6["PrivilegeTargetGroupsBrowse"]) {
         $_QLoli = _JJB1L($_QLoli, "javascript:TargetGroupsOpen()");
       }

       if(!$_QLJJ6["PrivilegeOnlineUpdate"]) {
         $_QLoli = _JJB1L($_QLoli, "onlineupdate.php");
         $_QLoli = _JJC0E($_QLoli, "onlineupdate.php");
       }
       
       // SML start
       if( defined("SML") && isset($_QLJJ6["PrivilegeDistribListCreate"])) {
         if(!$_QLJJ6["PrivilegeDistribListBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browsedistriblists.php");
           $_QLoli = _JJB1L($_QLoli, "distriblistcreate.php");
         }

         if(!$_QLJJ6["PrivilegeDistribListCreate"]) {
           $_QLoli = _JJB1L($_QLoli, "distriblistcreate.php");
         }

         if(!$_QLJJ6["PrivilegeViewDistribListLog"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_distriblistlog.php");
         }

         if(!$_QLJJ6["PrivilegeViewDistribListTrackingStat"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_respondertracking.php?ResponderType=DistributionList");
         }

         if($OwnerOwnerUserId == 0x41){
            $_QLoli = _JJB1L($_QLoli, "browsedistriblists.php");
            $_ICIIQ = _JJC0E($_ICIIQ, "browsedistriblists.php");

            $_QLoli = _JJB1L($_QLoli, "stat_distriblistlog.php");
            $_ICIIQ = _JJC0E($_ICIIQ, "stat_distriblistlog.php");

            $_QLoli = _JJB1L($_QLoli, "browseoutqueue.php");
            $_ICIIQ = _JJB1L($_ICIIQ, "browseoutqueue.php");

            $_QLoli = _JJB1L($_QLoli, "distriblistcreate.php");
            $_ICIIQ = _JJB1L($_ICIIQ, "distriblistcreate.php");
         }

       }

       // SWM start
       if( defined("SWM") && isset($_QLJJ6["PrivilegeAutoresponderBrowse"])) {

         if(!$_QLJJ6["PrivilegeNewsletterArchiveBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browsenas.php");
         }

         if(!$_QLJJ6["PrivilegeTemplateBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browsetemplates.php");
         }

         if(!$_QLJJ6["PrivilegeAutoresponderBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browseautoresponders.php");
         }

         if(!$_QLJJ6["PrivilegeViewAutoresponderLog"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_autoresponderlog.php");
         }

         if(!$_QLJJ6["PrivilegeViewAutoresponderTrackingStat"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_respondertracking.php?ResponderType=AutoResponder");
         }

         if(!$_QLJJ6["PrivilegeFUResponderBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browsefuresponders.php");
         }

         if(!$_QLJJ6["PrivilegeViewFUResponderLog"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_furesponderlog.php");
         }

         if(!$_QLJJ6["PrivilegeViewFUResponderTrackingStat"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_respondertracking.php?ResponderType=FollowUpResponder");
         }

         if(!$_QLJJ6["PrivilegeBirthdayMailsBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browsebirthdayresponders.php");
         }

         if(!$_QLJJ6["PrivilegeBirthdayMailsBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browsebirthdaymails.php");
         }

         if(!$_QLJJ6["PrivilegeViewBirthdayMailsLog"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_birthdayresponderlog.php");
         }

         if(!$_QLJJ6["PrivilegeViewBirthdayMailsTrackingStat"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_respondertracking.php?ResponderType=BirthdayResponder");
         }

         if(!$_QLJJ6["PrivilegeRSS2EMailMailsBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browserss2emailresponders.php");
         }

         if(!$_QLJJ6["PrivilegeRSS2EMailMailsBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browserss2emailmails.php");
         }

         if(!$_QLJJ6["PrivilegeViewRSS2EMailMailsLog"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_rss2emailresponderlog.php");
         }

         if(!$_QLJJ6["PrivilegeViewRSS2EMailMailsTrackingStat"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_respondertracking.php?ResponderType=RSS2EMailResponder");
         }

         if(!$_QLJJ6["PrivilegeEventMailsBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browseeventmails.php");
         }

         if(!$_QLJJ6["PrivilegeViewEventMailsLog"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_eventresponderlog.php");
         }

         if(!$_QLJJ6["PrivilegeViewEventMailsTrackingStat"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_respondertracking.php?ResponderType=EventResponder");
         }

         if(!$_QLJJ6["PrivilegeCampaignBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browsecampaigns.php");
           $_QLoli = _JJB1L($_QLoli, "campaigncreate.php");
           $_QLoli = _JJB1L($_QLoli, "browsesplittests.php");
           $_QLoli = _JJB1L($_QLoli, "splittestcreate.php");
         }

         if(!$_QLJJ6["PrivilegeCampaignCreate"]) {
           $_QLoli = _JJB1L($_QLoli, "campaigncreate.php");
           $_QLoli = _JJB1L($_QLoli, "splittestcreate.php");
         }

         if(!$_QLJJ6["PrivilegeViewCampaignLog"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_campaignlog.php");
           $_QLoli = _JJB1L($_QLoli, "stat_splittestlog.php");
         }

         if(!$_QLJJ6["PrivilegeViewCampaignTrackingStat"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_campaigntracking.php");
           $_QLoli = _JJB1L($_QLoli, "stat_splittesttracking.php");
         }

         if(!$_QLJJ6["PrivilegeSMSCampaignBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browsesmscampaigns.php");
           $_QLoli = _JJB1L($_QLoli, "smscampaigncreate.php");
         }

         if(!$_QLJJ6["PrivilegeSMSCampaignCreate"]) {
           $_QLoli = _JJB1L($_QLoli, "smscampaigncreate.php");
         }

         if(!$_QLJJ6["PrivilegeViewSMSCampaignLog"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_smscampaignlog.php");
         }

         if(!$_QLJJ6["PrivilegeDistribListBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "browsedistriblists.php");
           $_QLoli = _JJB1L($_QLoli, "distriblistcreate.php");
         }

         if(!$_QLJJ6["PrivilegeDistribListCreate"]) {
           $_QLoli = _JJB1L($_QLoli, "distriblistcreate.php");
         }

         if(!$_QLJJ6["PrivilegeViewDistribListLog"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_distriblistlog.php");
         }

         if(!$_QLJJ6["PrivilegeViewDistribListTrackingStat"]) {
           $_QLoli = _JJB1L($_QLoli, "stat_respondertracking.php?ResponderType=DistributionList");
         }

         if(!$_QLJJ6["PrivilegeTextBlockBrowse"]) {
           $_QLoli = _JJB1L($_QLoli, "javascript:TextBlocksOpen('null', 'null', false)");
         }

     } // if($UserId)
       
       if($UserType != "SuperAdmin" && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90) ){
          $_QLoli = _JJB1L($_QLoli, "browseusers.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "browseusers.php");
       }

       if($OwnerOwnerUserId == 90){
          $_QLoli = _JJB1L($_QLoli, "stat_respondertracking.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "stat_respondertracking.php");
          $_QLoli = _JJB1L($_QLoli, "stat_autoresponderlog.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "stat_autoresponderlog.php");
          $_QLoli = _JJB1L($_QLoli, "stat_birthdayresponderlog.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "stat_birthdayresponderlog.php");
          $_QLoli = _JJB1L($_QLoli, "stat_furesponderlog.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "stat_furesponderlog.php");
          $_QLoli = _JJB1L($_QLoli, "stat_rss2emailresponderlog.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "stat_rss2emailresponderlog.php");
          $_QLoli = _JJB1L($_QLoli, "stat_distriblistlog.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "stat_distriblistlog.php");

          $_QLoli = _JJB1L($_QLoli, "browseautoresponders.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "browseautoresponders.php");
          $_QLoli = _JJB1L($_QLoli, "browsebirthdayresponders.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "browsebirthdayresponders.php");
          $_QLoli = _JJB1L($_QLoli, "browsefuresponders.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "browsefuresponders.php");
          $_QLoli = _JJB1L($_QLoli, "browserss2emailresponders.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "browserss2emailresponders.php");

          $_QLoli = _JJB1L($_QLoli, "browsedistriblists.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "browsedistriblists.php");

          $_QLoli = _JJB1L($_QLoli, "distriblistcreate.php");
          $_ICIIQ = _JJC0E($_ICIIQ, "distriblistcreate.php");

          $_QLoli = _JJB1L($_QLoli, '"./browsetemplates.php" id="Responder"');

          $_6It18 = '<li><a href="#">Responder</a>';
          $_6ItL1 = '</li>';
          if (strpos($_QLoli, $_6It18) !== false ) {
            $_6IOQj = substr($_QLoli, 0, strpos($_QLoli, $_6It18));
            $_QLoli = substr($_QLoli, strpos($_QLoli, $_6It18) + strlen($_6It18));
            $_6IOj1 = substr($_QLoli, strpos($_QLoli, $_6ItL1) + strlen($_6ItL1));
            $_QLoli = $_6IOQj.$_6IOj1;
          }

       }

     }

     if($UserType != "SuperAdmin" && !defined("Setup")){
        $_8I1IO = _JOLQE('OptionsCronJobOptionsOnlyAsSuperAdmin');
        if($_8I1IO) {
          $_QLoli = _JJB1L($_QLoli, "settings_preferences.php");
          $_QLoli = _JJB1L($_QLoli, "stat_processlog.php");
          $_QLoli = _JJB1L($_QLoli, "settings_cron.php");
        }
     }

     $_QLoli .= $_ICIIQ;
   }

   /* falls notwendig muesste man das noch einbauen
   $_QLoli = str_replace ("PHPSESSIONIDVARNAME", "$PHPSESSIONIDVARNAME=".session_id(), $_QLoli);
   */

   if(!empty($_foQ6I))
     SetHTMLHeaders($_foQ6I);

   if($_8ClIt == "session_error_snipped.htm" || $_8ClIt == "common_error_page.htm" || $_8ClIt == "login_snipped.htm" || $_8ClIt == "pw_reminder_snipped.htm" || $_8ClIt == "logout_snipped.htm" || $_8ClIt == "formcode_external_snipped.htm" || $_8ClIt == "login2fa1_snipped.htm" )
     return $_QLoli;
   else
     return _LJA6C($_QLoli);
 }

 function _JJB1L($_QLoli, $_8iJ88) {
    $_I016j = strpos ($_QLoli, $_8iJ88);
    while($_I016j !== false) {
      // search vor <a
      $_jJjQi = strpos_reverse($_QLoli, "<", $_I016j);
      $_Ql0fO = substr($_QLoli, 0, $_jJjQi);
      $_6joLQ = substr($_QLoli, $_jJjQi + 1);
      $_66JoO = strpos($_6joLQ, "</a>");
      $_6joLQ = substr($_6joLQ, $_66JoO + 4);

      // remove <li
      $_jJjQi = strpos_reverse($_Ql0fO, "<li", strlen($_Ql0fO));
      $_Ql0fO = substr($_Ql0fO, 0, $_jJjQi);

      // remove </li>
      $_jJjQi = strpos($_6joLQ, "</li>");
      $_6joLQ = substr($_6joLQ, $_jJjQi + 5);


      $_QLoli = $_Ql0fO.$_6joLQ;
      $_I016j = strpos ($_QLoli, $_8iJ88);
    }
    return $_QLoli;
 }

 function _JJC0E($_QLoli, $_8iJ88) {
    $_I016j = strpos ($_QLoli, ' href="'.$_8iJ88);
    if($_I016j === false)
      $_I016j = strpos ($_QLoli, ' href="./'.$_8iJ88);
    while($_I016j !== false) {
      // search vor <a
      $_jJjQi = strpos_reverse($_QLoli, "<", $_I016j);
      $_Ql0fO = substr($_QLoli, 0, $_jJjQi);
      $_6joLQ = substr($_QLoli, $_jJjQi + 1);
      $_66JoO = strpos($_6joLQ, "</a>");
      $_6joLQ = substr($_6joLQ, $_66JoO + 4);

      $_QLoli = $_Ql0fO.$_6joLQ;
      $_I016j = strpos ($_QLoli, ' href="'.$_8iJ88);
      if($_I016j === false)
        $_I016j = strpos ($_QLoli, ' href="./'.$_8iJ88);
    }
    return $_QLoli;
 }

 function _JJC1E($_QLoli, $_8iJ88) {
    $_I016j = strpos ($_QLoli, 'name="'.$_8iJ88);
    while($_I016j !== false) {
      // search vor <input
      $_jJjQi = strpos_reverse($_QLoli, "<", $_I016j);
      $_Ql0fO = substr($_QLoli, 0, $_jJjQi);
      $_6joLQ = substr($_QLoli, $_jJjQi + 1);
      $_66JoO = strpos($_6joLQ, " />");
      $_6joLQ = substr($_6joLQ, $_66JoO + 3);

      $_QLoli = $_Ql0fO.$_6joLQ;
      $_I016j = strpos ($_QLoli, 'name="'.$_8iJ88);
    }
    return $_QLoli;
 }

 function _JJCRD($_QLoli, $_8iJ88) {
    $_I016j = strpos ($_QLoli, 'value="'.$_8iJ88);
    while($_I016j !== false) {
      // search vor <input
      $_jJjQi = strpos_reverse($_QLoli, "<", $_I016j);
      $_Ql0fO = substr($_QLoli, 0, $_jJjQi);
      $_6joLQ = substr($_QLoli, $_jJjQi + 1);
      $_66JoO = strpos($_6joLQ, "</option>");
      $_6joLQ = substr($_6joLQ, $_66JoO + 9);

      $_QLoli = $_Ql0fO.$_6joLQ;
      $_I016j = strpos ($_QLoli, 'value="'.$_8iJ88);
    }
    return $_QLoli;
 }

 function _JJCCF(&$_QLoli) {
   global $_Ij6Lj, $AppName;
   global $_foo18, $_8i0Ol, $_8i1tf;
   // footer
   $_QLoli = str_replace("PRODUCTURL", $_foo18, $_QLoli);
   $_QLoli = str_replace("PRODUCTAPPNAME", $AppName, $_QLoli);
   $_QLoli = str_replace("PRODUCTURL", $_8i0Ol, $_QLoli);
   $_8iJoI = $_8i1tf;
   if(date("Y") != $_8iJoI)
     $_8iJoI .= " - ".date("Y");
   $_QLoli = str_replace("<!--PRODUCTCOPYRIGHTYEAR-->", $_8iJoI, $_QLoli);
   $_QLoli = _L81BJ($_QLoli, "<LABEL:PRODUCTVERSION>", "</LABEL:PRODUCTVERSION>", $_Ij6Lj);
   $_QLoli = str_replace("SCRIPTBASEURL", ScriptBaseURL, $_QLoli);
   // footer END
   return $_QLoli;
 }

 function _JJDJC($_QLoli, $_8i6tl) {
   return str_replace('for="' . $_8i6tl . '"', 'for="' . $_8i6tl . '" disabled="disabled" style="cursor: default;"', $_QLoli);
 }
 
?>
