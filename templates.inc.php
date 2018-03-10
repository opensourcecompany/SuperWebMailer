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
 include_once("securitycheck.inc.php");
 include_once("savedoptions.inc.php");

 function GetMainTemplate($_fQOlQ, $UserType, $_fQoJi, $_fQC1O, $_fQCO8, $_fQif8, $_fQLfj, $_fQl1j, $_fQliQ = "", $_6jOOo = "utf-8") {
   global $_QoJ8j, $AppName, $_Q6QQL, $INTERFACE_LANGUAGE, $resourcestrings;
   global $_fI061, $_fI1J6, $_fIQ1t;
   global $SHOW_LOGGEDINUSER, $SHOW_SUPPORTLINKS, $SHOW_SHOWCOPYRIGHT, $SHOW_PRODUCTVERSION, $SHOW_TOOLTIPS, $_SESSION;
   global $_jJf6o, $OwnerOwnerUserId, $UserId, $OwnerUserId, $_jjC06, $_Io680;
   global $_Q61I1;

   if($_fQoJi == "" || $UserType == "none") {
     $_fQOlQ = false;
     $_fQC1O = false;
   }

   $_Q8otJ = @file(_O68QF()."main.htm");
   if($_Q8otJ) {
      $_Q6ICj = join("", $_Q8otJ);
      unset($_Q8otJ);
     }
     else
     $_Q6ICj = join("", file(InstallPath._O68QF()."main.htm"));

   $_Q8otJ = @file(_O68QF()."$_fQl1j");
   if($_Q8otJ)
     $_fIQIO = join("", $_Q8otJ);
     else
     $_fIQIO = join("", file(InstallPath._O68QF()."$_fQl1j"));

   if(strpos($_fIQIO, "<html") === false)
     $_fIQfI = true;
     else {
       $_fIQfI = false;
       $_Q6ICj = $_fIQIO;
       $_fIQIO = "";
     }

   if($_fQliQ == "")
     $_fQliQ = "$_fQCO8";

   if(defined("ShowUserNameInTitlebar") && $_fQoJi != "")
     $_Q6ICj = str_replace("<title>", "<title>".$_fQoJi." - ". $_fQliQ, $_Q6ICj);
   else
     $_Q6ICj = str_replace("<title>", "<title>".$AppName." - ". $_fQliQ, $_Q6ICj);

   if($_fQoJi == "" || $UserType == "none") {
     $_Q6ICj = _OPR6L($_Q6ICj, "<SHOW:PRODUCTVERSION>", "</SHOW:PRODUCTVERSION>", "&nbsp;" );
     $SHOW_SUPPORTLINKS = false;
     $SHOW_SHOWCOPYRIGHT = false;
   }

   if($_fQLfj != "DISABLED") {
       $_Q6ICj = str_replace('name="HelpTopic"', 'name="HelpTopic" value="'.$_fQLfj.'"', $_Q6ICj);
     }
     else {
       $_Q6ICj = _OPR6L($_Q6ICj, "<SHOWHIDE:HELPTOPIC>", "</SHOWHIDE:HELPTOPIC>", "");
     }

   _LJ81E($_Q6ICj);

   if(!$SHOW_SUPPORTLINKS) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOW:SUPPORTLINKS>", "</SHOW:SUPPORTLINKS>");
   }

   if(!$SHOW_SHOWCOPYRIGHT) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOW:SHOWCOPYRIGHT>", "</SHOW:SHOWCOPYRIGHT>");
   }

   if(!$SHOW_PRODUCTVERSION) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOW:PRODUCTVERSION>", "</SHOW:PRODUCTVERSION>");
   }

   if(!$_fQOlQ) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<ENABLE:SYSTEMMENU>", "</ENABLE:SYSTEMMENU>");
   }

   # login screen disable all
   if($_fQl1j == "login_snipped.htm") {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOW:SUPPORTLINKS>", "</SHOW:SUPPORTLINKS>");
     $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOW:SHOWCOPYRIGHT>", "</SHOW:SHOWCOPYRIGHT>");
   }

   // snipped insert
   if($_fIQfI)
      $_Q6ICj = _OPR6L($_Q6ICj, "<CONTAINER:CONTENT>", "</CONTAINER:CONTENT>", $_fIQIO);

   if ( $UserType != "SuperAdmin"  ) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<ISUSER:SUPERADMIN>", "</ISUSER:SUPERADMIN>");
   }

   if ( ($UserType != "SuperAdmin") && ($UserType != "Admin") ) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<ISUSER:ADMINISTRATOR>", "</ISUSER:ADMINISTRATOR>");
   }

   if ( ($UserType != "SuperAdmin") && ($UserType != "Admin") && ($UserType != "User") ){
     $_Q6ICj = _OP6PQ($_Q6ICj, "<ISUSER:USER>", "</ISUSER:USER>");
   }

   if ( (!$SHOW_LOGGEDINUSER) || ($_fQoJi == "") ) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOWHIDE:LOGGEDINUSER>", "</SHOWHIDE:LOGGEDINUSER>");
   }

   // SuperAdmin doesn't need mainmenu
   if ( ($UserType == "SuperAdmin") )
     $_fQC1O = false;

   $_Q6ICj = _OPR6L($_Q6ICj, "<LABEL:LOGGEDINUSER>", "</LABEL:LOGGEDINUSER>", $_fQoJi );

   if(!$_fQC1O) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<ENABLE:MAINMENU>", "</ENABLE:MAINMENU>");
   }

   if($_fQCO8 == "") {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOWHIDE:PAGETOPIC>", "</SHOWHIDE:PAGETOPIC>");
   } else {
     $_Q6ICj = _OPR6L($_Q6ICj, "<LABEL:PAGETOPIC>", "</LABEL:PAGETOPIC>", $_fQCO8 );
   }


   if($_fQif8 == "") {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOWHIDE:ERRORTOPIC>", "</SHOWHIDE:ERRORTOPIC>");
   } else {
     $_Q6ICj = _OPR6L($_Q6ICj, "<LABEL:ERRORMESSAGETEXT>", "</LABEL:ERRORMESSAGETEXT>", $_fQif8 );
   }

   if(!$SHOW_TOOLTIPS) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOWHIDE:TOOLTIPS>", "</SHOWHIDE:TOOLTIPS>");
   }

   if( isset($_SESSION["ProductLogoURL"]) && $_SESSION["ProductLogoURL"] != "") {
     $_Q6ICj = _OPR6L($_Q6ICj, "<SHOWHIDE:ProductLogo>", "</SHOWHIDE:ProductLogo>", '<img src="'.$_SESSION["ProductLogoURL"].'" class="normalimage" alt="" />');
   } else if(!defined("Setup") && ($_fQl1j == "login_snipped.htm" || $_fQl1j == "pw_reminder_snipped.htm" || $_fQl1j == "pw_reminder_sendpw_snipped.htm" || $_fQl1j == "pw_reminder_notsendpw_snipped.htm")
    && ( ($_fIQtL = _LQDLR("ProductLogoURL")) != "") && !defined("DEMO") ) {
     $_Q6ICj = _OPR6L($_Q6ICj, "<SHOWHIDE:ProductLogo>", "</SHOWHIDE:ProductLogo>", '<img src="'.$_fIQtL.'" class="normalimage" alt="" />');
   }

   if(!defined("SWM")) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<IS:SWM>", "</IS:SWM>");
   }
   if(!defined("SML")) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<IS:SML>", "</IS:SML>");
   }

   if(!$_fQC1O || defined("Setup")) {
     $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOWHIDE:EVALUATIONHEADER>", "</SHOWHIDE:EVALUATIONHEADER>");
   } else {
     if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
        $_Q6ICj = _OPR6L($_Q6ICj, "<LABEL:EVALUATIONHEADER>", "</LABEL:EVALUATIONHEADER>", $resourcestrings[$INTERFACE_LANGUAGE]["999998"] );
     } else
        if(defined("DEMO") || defined("SimulateMailSending"))
          $_Q6ICj = _OPR6L($_Q6ICj, "<LABEL:EVALUATIONHEADER>", "</LABEL:EVALUATIONHEADER>", $resourcestrings[$INTERFACE_LANGUAGE]["999997"] );
          else
          $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOWHIDE:EVALUATIONHEADER>", "</SHOWHIDE:EVALUATIONHEADER>");
   }

   // Security check
   if(!$_fQC1O && !defined("Setup")) {

     $_fII16 = false;
     if ($_fQoJi == "") { // Login Screen
       if( _LO0BO() ) {
          $_Q6ICj = _OPR6L($_Q6ICj, "<LABEL:WARNHEADER>", "</LABEL:WARNHEADER>", $resourcestrings[$INTERFACE_LANGUAGE]["ConfigFilesWriteable"]);
          $_fII16 = true;
       }
     }

     // only if dashboard is shown
     if(!$_fII16 && $_fQl1j == 'dashboard_snipped.htm' ) {
       if (!_LO0BL() ) {

          if ( ($UserType == "SuperAdmin") )
             $_fIIO6 = InstallPath."userfiles/";
             else
             $_fIIO6 = $_jjC06;

          $_Q6ICj = _OPR6L($_Q6ICj, "<LABEL:WARNHEADER>", "</LABEL:WARNHEADER>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UserPathsNotWriteable"], $_fIIO6) );
          $_fII16 = true;
       }
     }

     if(!$_fII16)
        $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOWHIDE:WARNHEADER>", "</SHOWHIDE:WARNHEADER>");
   } else {

      $_fII16 = false;
#     if( _LO0BO() ) {
#        $_Q6ICj = _OPR6L($_Q6ICj, "<LABEL:WARNHEADER>", "</LABEL:WARNHEADER>", $resourcestrings[$INTERFACE_LANGUAGE]["ConfigFilesWriteable"]);
#        $_fII16 = true;
#     }

     // only if dashboard is shown
     if(!$_fII16 && $_fQl1j == 'dashboard_snipped.htm' ) {
       if (!_LO0BL() ) {
          $_Q6ICj = _OPR6L($_Q6ICj, "<LABEL:WARNHEADER>", "</LABEL:WARNHEADER>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["UserPathsNotWriteable"], $_jjC06) );
          $_fII16 = true;
       }
     }

     if(!$_fII16)
        $_Q6ICj = _OP6PQ($_Q6ICj, "<SHOWHIDE:WARNHEADER>", "</SHOWHIDE:WARNHEADER>");
   }
   // Security check

   if( ($_fQC1O || defined("CampaignLiveSending")) && !defined("Setup") && $SHOW_LOGGEDINUSER && $_fIQfI && $_fQoJi != "" ) {
     $_QJlJ0 = "SELECT COUNT(id) FROM `$_Io680` WHERE `To_users_id`=$UserId AND `IsReaded`=0";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_fIIoC = 0;
     if($_Q60l1) {
       $_Q6Q1C = mysql_fetch_row($_Q60l1);
       mysql_free_result($_Q60l1);
       $_fIIoC = $_Q6Q1C[0];
     }
     $_Q6ICj = _OPR6L($_Q6ICj, "<LOCALMESSAGES_COUNT>", "</LOCALMESSAGES_COUNT>", $_fIIoC);
   }

   // remove ControlTags

   for($_Q6llo=0; $_Q6llo < count($_jJf6o); $_Q6llo++) {
     $_Q6ICj = _OPPRR($_Q6ICj, $_jJf6o[$_Q6llo]);
   }

   // privilegs
   if( /*($OwnerUserId != 0 || $UserType == "SuperAdmin") &&*/ !defined("Setup") && $_fIQfI && ( strpos($_Q6ICj, '<div class="PageContainer">') !== false ) ) { // only for snippeds!
     $_IIf8o = substr($_Q6ICj, strpos($_Q6ICj, '<div class="PageContainer">'));
     $_Q6ICj = substr($_Q6ICj, 0, strpos($_Q6ICj, '<div class="PageContainer">') - 1);

     if($UserType != "SuperAdmin") {
       $_Q6ICj = _LJJDQ($_Q6ICj, "settings_branding.php");
     }

     if($UserType != "SuperAdmin" && $UserType != "Admin") {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browseoutqueue.php");
     }

     $_QJojf = _OBOOC($UserId);
     if(!$_QJojf["PrivilegePageBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browsepages.php");
     }
     if(!$_QJojf["PrivilegeMessageBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browsemessages.php");
     }
     if(!$_QJojf["PrivilegeMTABrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browsemtas.php");
     }
     if(!$_QJojf["PrivilegeInboxBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browseinboxes.php");
     }
     if(!$_QJojf["PrivilegeAutoImportBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browseimports.php");
     }
     if(!$_QJojf["PrivilegeOptionsEdit"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "settings_preferences.php");
     }
     if(!$_QJojf["PrivilegeFormBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browseforms.php");
     }

     if(!$_QJojf["PrivilegeDbRepair"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "settings_db.php");
     }
     if(!$_QJojf["PrivilegeSystemTest"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "settings_test.php");
     }
     if(!$_QJojf["PrivilegeViewProcessLog"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "stat_processlog.php");
     }
     if(!$_QJojf["PrivilegeCron"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "settings_cron.php");
     }

     if(!$_QJojf["PrivilegeLocalMessageBrowse"]) {
       $_Q6ICj = _OP6PQ($_Q6ICj, '<SHOWHIDE:LOCALMESSAGES>', '</SHOWHIDE:LOCALMESSAGES>');
       $_Q6ICj = _LJJDQ($_Q6ICj, "browselocalmessages.php");
       $_Q6ICj = _LJJDQ($_Q6ICj, "javascript:LocalMessagesBrowse()");
     } else{
       $_Q6ICj = _OPPRR($_Q6ICj, '<SHOWHIDE:LOCALMESSAGES>');
     }

     if(!$_QJojf["PrivilegeUserBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browseusers.php");
       $_Q6ICj = _LJ6RJ($_Q6ICj, "browseusers.php");
     }

     if(!$_QJojf["PrivilegeMailingListCreate"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "mailinglistcreate.php");
     }

     if(!$_QJojf["PrivilegeMailingListBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browsemailinglists.php");
       $_Q6ICj = _LJJDQ($_Q6ICj, "searchrecipients.php");
     }

     if(!$_QJojf["PrivilegeRecipientCreate"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "recipientedit.php");
     }
     if(!$_QJojf["PrivilegeRecipientBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browsercpts.php");
       $_Q6ICj = _LJJDQ($_Q6ICj, "searchrecipients.php");
     }

     if(!$_QJojf["PrivilegeLocalBlockListRecipientBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browseblmembers.php?action=local");
       $_Q6ICj = _LJJDQ($_Q6ICj, "browsedomainblmembers.php?action=local");
       $_Q6ICj = _LJJDQ($_Q6ICj, "ml_remove_recipients_by_blocklists.php");
     }
     if(!$_QJojf["PrivilegeGlobalBlockListRecipientBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browseblmembers.php?action=global");
       $_Q6ICj = _LJJDQ($_Q6ICj, "browsedomainblmembers.php?action=global");
       $_Q6ICj = _LJJDQ($_Q6ICj, "ml_remove_recipients_by_blocklists.php");
     }

     if(!$_QJojf["PrivilegeImportBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "importrecipients.php");
     }
     if(!$_QJojf["PrivilegeExportBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "exportrecipients.php");
     }

     if(!$_QJojf["PrivilegeMLSubUnsubStatBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "mailinglistsubunsubstat.php");
     }
     if(!$_QJojf["PrivilegeAllMLStatBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "showstatsummary.php");
     }

     if(!$_QJojf["PrivilegeMailsSentStatBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "stat_sentmails.php");
     }

     if(!$_QJojf["PrivilegeAutoImportBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "browseautoimports.php");
     }

     if(!$_QJojf["PrivilegeFunctionBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "javascript:FunctionsOpen('null', 'null', false)");
     }

     if(!$_QJojf["PrivilegeTargetGroupsBrowse"]) {
       $_Q6ICj = _LJJDQ($_Q6ICj, "javascript:TargetGroupsOpen()");
     }

     // SML start
     if( defined("SML") && isset($_QJojf["PrivilegeDistribListCreate"])) {
       if(!$_QJojf["PrivilegeDistribListBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browsedistriblists.php");
         $_Q6ICj = _LJJDQ($_Q6ICj, "distriblistcreate.php");
       }

       if(!$_QJojf["PrivilegeDistribListCreate"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "distriblistcreate.php");
       }

       if(!$_QJojf["PrivilegeViewDistribListLog"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_distriblistlog.php");
       }

       if(!$_QJojf["PrivilegeViewDistribListTrackingStat"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_respondertracking.php?ResponderType=DistributionList");
       }

       if($OwnerOwnerUserId == 0x41){
          $_Q6ICj = _LJJDQ($_Q6ICj, "browsedistriblists.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "browsedistriblists.php");

          $_Q6ICj = _LJJDQ($_Q6ICj, "stat_distriblistlog.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "stat_distriblistlog.php");

          $_Q6ICj = _LJJDQ($_Q6ICj, "browseoutqueue.php");
          $_IIf8o = _LJJDQ($_IIf8o, "browseoutqueue.php");

          $_Q6ICj = _LJJDQ($_Q6ICj, "distriblistcreate.php");
          $_IIf8o = _LJJDQ($_IIf8o, "distriblistcreate.php");
       }

     }

     // SWM start
     if( defined("SWM") && isset($_QJojf["PrivilegeAutoresponderBrowse"])) {

       if(!$_QJojf["PrivilegeNewsletterArchiveBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browsenas.php");
       }

       if(!$_QJojf["PrivilegeTemplateBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browsetemplates.php");
       }

       if(!$_QJojf["PrivilegeAutoresponderBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browseautoresponders.php");
       }

       if(!$_QJojf["PrivilegeViewAutoresponderLog"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_autoresponderlog.php");
       }

       if(!$_QJojf["PrivilegeViewAutoresponderTrackingStat"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_respondertracking.php?ResponderType=AutoResponder");
       }

       if(!$_QJojf["PrivilegeFUResponderBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browsefuresponders.php");
       }

       if(!$_QJojf["PrivilegeViewFUResponderLog"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_furesponderlog.php");
       }

       if(!$_QJojf["PrivilegeViewFUResponderTrackingStat"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_respondertracking.php?ResponderType=FollowUpResponder");
       }

       if(!$_QJojf["PrivilegeBirthdayMailsBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browsebirthdayresponders.php");
       }

       if(!$_QJojf["PrivilegeBirthdayMailsBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browsebirthdaymails.php");
       }

       if(!$_QJojf["PrivilegeViewBirthdayMailsLog"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_birthdayresponderlog.php");
       }

       if(!$_QJojf["PrivilegeViewBirthdayMailsTrackingStat"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_respondertracking.php?ResponderType=BirthdayResponder");
       }

       if(!$_QJojf["PrivilegeRSS2EMailMailsBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browserss2emailresponders.php");
       }

       if(!$_QJojf["PrivilegeRSS2EMailMailsBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browserss2emailmails.php");
       }

       if(!$_QJojf["PrivilegeViewRSS2EMailMailsLog"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_rss2emailresponderlog.php");
       }

       if(!$_QJojf["PrivilegeViewRSS2EMailMailsTrackingStat"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_respondertracking.php?ResponderType=RSS2EMailResponder");
       }

       if(!$_QJojf["PrivilegeEventMailsBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browseeventmails.php");
       }

       if(!$_QJojf["PrivilegeViewEventMailsLog"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_eventresponderlog.php");
       }

       if(!$_QJojf["PrivilegeViewEventMailsTrackingStat"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_respondertracking.php?ResponderType=EventResponder");
       }

       if(!$_QJojf["PrivilegeCampaignBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browsecampaigns.php");
         $_Q6ICj = _LJJDQ($_Q6ICj, "campaigncreate.php");
         $_Q6ICj = _LJJDQ($_Q6ICj, "browsesplittests.php");
         $_Q6ICj = _LJJDQ($_Q6ICj, "splittestcreate.php");
       }

       if(!$_QJojf["PrivilegeCampaignCreate"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "campaigncreate.php");
         $_Q6ICj = _LJJDQ($_Q6ICj, "splittestcreate.php");
       }

       if(!$_QJojf["PrivilegeViewCampaignLog"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_campaignlog.php");
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_splittestlog.php");
       }

       if(!$_QJojf["PrivilegeViewCampaignTrackingStat"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_campaigntracking.php");
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_splittesttracking.php");
       }

       if(!$_QJojf["PrivilegeSMSCampaignBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browsesmscampaigns.php");
         $_Q6ICj = _LJJDQ($_Q6ICj, "smscampaigncreate.php");
       }

       if(!$_QJojf["PrivilegeSMSCampaignCreate"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "smscampaigncreate.php");
       }

       if(!$_QJojf["PrivilegeViewSMSCampaignLog"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_smscampaignlog.php");
       }

       if(!$_QJojf["PrivilegeDistribListBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "browsedistriblists.php");
         $_Q6ICj = _LJJDQ($_Q6ICj, "distriblistcreate.php");
       }

       if(!$_QJojf["PrivilegeDistribListCreate"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "distriblistcreate.php");
       }

       if(!$_QJojf["PrivilegeViewDistribListLog"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_distriblistlog.php");
       }

       if(!$_QJojf["PrivilegeViewDistribListTrackingStat"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "stat_respondertracking.php?ResponderType=DistributionList");
       }

       if(!$_QJojf["PrivilegeTextBlockBrowse"]) {
         $_Q6ICj = _LJJDQ($_Q6ICj, "javascript:TextBlocksOpen('null', 'null', false)");
       }

       if($UserType != "SuperAdmin" && ($OwnerOwnerUserId <= 65 || $OwnerOwnerUserId == 90) ){
          $_Q6ICj = _LJJDQ($_Q6ICj, "browseusers.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "browseusers.php");
       }

       if($OwnerOwnerUserId == 90){
          $_Q6ICj = _LJJDQ($_Q6ICj, "stat_respondertracking.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "stat_respondertracking.php");
          $_Q6ICj = _LJJDQ($_Q6ICj, "stat_autoresponderlog.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "stat_autoresponderlog.php");
          $_Q6ICj = _LJJDQ($_Q6ICj, "stat_birthdayresponderlog.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "stat_birthdayresponderlog.php");
          $_Q6ICj = _LJJDQ($_Q6ICj, "stat_furesponderlog.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "stat_furesponderlog.php");
          $_Q6ICj = _LJJDQ($_Q6ICj, "stat_rss2emailresponderlog.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "stat_rss2emailresponderlog.php");
          $_Q6ICj = _LJJDQ($_Q6ICj, "stat_distriblistlog.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "stat_distriblistlog.php");

          $_Q6ICj = _LJJDQ($_Q6ICj, "browseautoresponders.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "browseautoresponders.php");
          $_Q6ICj = _LJJDQ($_Q6ICj, "browsebirthdayresponders.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "browsebirthdayresponders.php");
          $_Q6ICj = _LJJDQ($_Q6ICj, "browsefuresponders.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "browsefuresponders.php");
          $_Q6ICj = _LJJDQ($_Q6ICj, "browserss2emailresponders.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "browserss2emailresponders.php");

          $_Q6ICj = _LJJDQ($_Q6ICj, "browsedistriblists.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "browsedistriblists.php");

          $_Q6ICj = _LJJDQ($_Q6ICj, "distriblistcreate.php");
          $_IIf8o = _LJ6RJ($_IIf8o, "distriblistcreate.php");

          $_Q6ICj = _LJJDQ($_Q6ICj, '"./browsetemplates.php" id="Responder"');

          $_JQQI6 = '<li><a href="#">Responder</a>';
          $_JQQOi = '</li>';
          if (strpos($_Q6ICj, $_JQQI6) !== false ) {
            $_JQI0f = substr($_Q6ICj, 0, strpos($_Q6ICj, $_JQQI6));
            $_Q6ICj = substr($_Q6ICj, strpos($_Q6ICj, $_JQQI6) + strlen($_JQQI6));
            $_JQII8 = substr($_Q6ICj, strpos($_Q6ICj, $_JQQOi) + strlen($_JQQOi));
            $_Q6ICj = $_JQI0f.$_JQII8;
          }

       }

     }

     if($UserType != "SuperAdmin" && !defined("Setup")){
        $_6O18o = _LQDLR('OptionsCronJobOptionsOnlyAsSuperAdmin');
        if($_6O18o) {
          $_Q6ICj = _LJJDQ($_Q6ICj, "settings_preferences.php");
          $_Q6ICj = _LJJDQ($_Q6ICj, "stat_processlog.php");
          $_Q6ICj = _LJJDQ($_Q6ICj, "settings_cron.php");
        }
     }

     $_Q6ICj .= $_IIf8o;
   }

   /* falls notwendig muesste man das noch einbauen
   $_Q6ICj = str_replace ("PHPSESSIONIDVARNAME", "$PHPSESSIONIDVARNAME=".session_id(), $_Q6ICj);
   */

   if(!empty($_6jOOo))
     SetHTMLHeaders($_6jOOo);

   return $_Q6ICj;
 }

 function _LJJDQ($_Q6ICj, $_fIjoQ) {
    $_QllO8 = strpos ($_Q6ICj, $_fIjoQ);
    while($_QllO8 !== false) {
      // search vor <a
      $_Io0l8 = strpos_reverse($_Q6ICj, "<", $_QllO8);
      $_Q66jQ = substr($_Q6ICj, 0, $_Io0l8);
      $_JQCIj = substr($_Q6ICj, $_Io0l8 + 1);
      $_JjjC6 = strpos($_JQCIj, "</a>");
      $_JQCIj = substr($_JQCIj, $_JjjC6 + 4);

      // remove <li
      $_Io0l8 = strpos_reverse($_Q66jQ, "<li", strlen($_Q66jQ));
      $_Q66jQ = substr($_Q66jQ, 0, $_Io0l8);

      // remove </li>
      $_Io0l8 = strpos($_JQCIj, "</li>");
      $_JQCIj = substr($_JQCIj, $_Io0l8 + 5);


      $_Q6ICj = $_Q66jQ.$_JQCIj;
      $_QllO8 = strpos ($_Q6ICj, $_fIjoQ);
    }
    return $_Q6ICj;
 }

 function _LJ6RJ($_Q6ICj, $_fIjoQ) {
    $_QllO8 = strpos ($_Q6ICj, ' href="'.$_fIjoQ);
    if($_QllO8 === false)
      $_QllO8 = strpos ($_Q6ICj, ' href="./'.$_fIjoQ);
    while($_QllO8 !== false) {
      // search vor <a
      $_Io0l8 = strpos_reverse($_Q6ICj, "<", $_QllO8);
      $_Q66jQ = substr($_Q6ICj, 0, $_Io0l8);
      $_JQCIj = substr($_Q6ICj, $_Io0l8 + 1);
      $_JjjC6 = strpos($_JQCIj, "</a>");
      $_JQCIj = substr($_JQCIj, $_JjjC6 + 4);

      $_Q6ICj = $_Q66jQ.$_JQCIj;
      $_QllO8 = strpos ($_Q6ICj, ' href="'.$_fIjoQ);
      if($_QllO8 === false)
        $_QllO8 = strpos ($_Q6ICj, ' href="./'.$_fIjoQ);
    }
    return $_Q6ICj;
 }

 function _LJ6B1($_Q6ICj, $_fIjoQ) {
    $_QllO8 = strpos ($_Q6ICj, 'name="'.$_fIjoQ);
    while($_QllO8 !== false) {
      // search vor <input
      $_Io0l8 = strpos_reverse($_Q6ICj, "<", $_QllO8);
      $_Q66jQ = substr($_Q6ICj, 0, $_Io0l8);
      $_JQCIj = substr($_Q6ICj, $_Io0l8 + 1);
      $_JjjC6 = strpos($_JQCIj, " />");
      $_JQCIj = substr($_JQCIj, $_JjjC6 + 3);

      $_Q6ICj = $_Q66jQ.$_JQCIj;
      $_QllO8 = strpos ($_Q6ICj, 'name="'.$_fIjoQ);
    }
    return $_Q6ICj;
 }

 function _LJRLJ($_Q6ICj, $_fIjoQ) {
    $_QllO8 = strpos ($_Q6ICj, 'value="'.$_fIjoQ);
    while($_QllO8 !== false) {
      // search vor <input
      $_Io0l8 = strpos_reverse($_Q6ICj, "<", $_QllO8);
      $_Q66jQ = substr($_Q6ICj, 0, $_Io0l8);
      $_JQCIj = substr($_Q6ICj, $_Io0l8 + 1);
      $_JjjC6 = strpos($_JQCIj, "</option>");
      $_JQCIj = substr($_JQCIj, $_JjjC6 + 9);

      $_Q6ICj = $_Q66jQ.$_JQCIj;
      $_QllO8 = strpos ($_Q6ICj, 'value="'.$_fIjoQ);
    }
    return $_Q6ICj;
 }

 function _LJ81E(&$_Q6ICj) {
   global $_QoJ8j, $AppName;
   global $_fI061, $_fI1J6, $_fIQ1t;
   // footer
   $_Q6ICj = str_replace("PRODUCTURL", $_fI061, $_Q6ICj);
   $_Q6ICj = str_replace("PRODUCTAPPNAME", $AppName, $_Q6ICj);
   $_Q6ICj = str_replace("PRODUCTURL", $_fI1J6, $_Q6ICj);
   $_fIJf8 = $_fIQ1t;
   if(strftime("%Y") != $_fIJf8)
     $_fIJf8 .= " - ".strftime("%Y");
   $_Q6ICj = str_replace("<!--PRODUCTCOPYRIGHTYEAR-->", $_fIJf8, $_Q6ICj);
   $_Q6ICj = _OPR6L($_Q6ICj, "<LABEL:PRODUCTVERSION>", "</LABEL:PRODUCTVERSION>", $_QoJ8j);
   $_Q6ICj = str_replace("SCRIPTBASEURL", ScriptBaseURL, $_Q6ICj);
   // footer END
   return $_Q6ICj;
 }

?>
