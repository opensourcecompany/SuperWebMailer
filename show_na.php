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
  include_once("templates.inc.php");
  include_once("replacements.inc.php");
  include_once("mailcreate.inc.php");

  if( empty($_GET["na"]) || empty($_GET["newsletterarchive"]) || empty($_GET["nauser"]) ) {
    print $commonmsgNewsletterArchiveNotFound;
    exit;
  }

  $_GET["nauser"] = intval($_GET["nauser"]);
  $_GET["newsletterarchive"] = intval($_GET["newsletterarchive"]);
  $_QLfol = "SELECT * FROM $_I18lo WHERE id=" . $_GET["nauser"];
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    print $commonmsgNewsletterArchiveNotFound;
    exit;
  }
  $_j661I = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  if($_j661I["UserType"] != "Admin") {
    print $commonmsgNewsletterArchiveNotFound;
    exit;
  }

  $INTERFACE_LANGUAGE = $_j661I["Language"];

  _LR8AP($_j661I);
  _LRRFJ($_j661I["id"]);
  _LRPQ6($INTERFACE_LANGUAGE);
  _JQRLR($INTERFACE_LANGUAGE);
  $_8Iltt = ScriptBaseURL . "show_na.php";
//$_8Iltt = "http://localhost:8080/show_na.php";
  $_8Ill6 = "na=$_GET[na]&newsletterarchive=$_GET[newsletterarchive]&nauser=$_GET[nauser]";
  $_8Iltt .= "?" . $_8Ill6;

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  // count it
  $_QLfol = "UPDATE $_j6JfL SET OpeningsCount=OpeningsCount + 1 WHERE id=".$_GET["newsletterarchive"]." AND UniqueID="._LRAFO($_GET["na"]);
  mysql_query($_QLfol, $_QLttI);

  $_QLfol = "SELECT *, UNIX_TIMESTAMP(CreateDate) AS CreateDateUnixTime FROM $_j6JfL WHERE id=".$_GET["newsletterarchive"]." AND UniqueID="._LRAFO($_GET["na"]);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    print $commonmsgNewsletterArchiveNotFound;
    exit;
  }
  $_8j0O6 = mysql_fetch_assoc($_QL8i1);
  $_8j0O6["InfoBarSupportedTranslationLanguages"] = @unserialize($_8j0O6["InfoBarSupportedTranslationLanguages"]);
  $_8j0O6["InfoBarLinksArray"] = @unserialize($_8j0O6["InfoBarLinksArray"]);
  mysql_free_result($_QL8i1);

  $_ILI1C = InstallPath . "na";
  if(!empty($_8j0O6["TemplatesPath"]))
     $_ILI1C = $_8j0O6["TemplatesPath"];
  $_ILI1C = _LPC1C($_ILI1C);

  # campaigns_id
  $_QLfol = "SELECT campaigns_id FROM $_8j0O6[CampaignToNATableName]";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    print $commonmsgNewsletterArchiveNoCampaignsFound;
    exit;
  }
  $_ftjjC = array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    $_QLfol = "SELECT CurrentSendTableName, ArchiveTableName FROM $_QLi60 WHERE id=$_QLO0f[campaigns_id]";
    $_I1O6j = mysql_query($_QLfol, $_QLttI);
    if(!$_I1O6j || mysql_num_rows($_I1O6j) == 0) continue;
    $_I1OfI = mysql_fetch_assoc($_I1O6j);
    mysql_free_result($_I1O6j);
    $_ftjjC[$_QLO0f["campaigns_id"]] = array("CurrentSendTableName" => $_I1OfI["CurrentSendTableName"], "ArchiveTableName" => $_I1OfI["ArchiveTableName"]);
  }
  mysql_free_result($_QL8i1);

  if(isset($_GET["showRSS"]) ) {
    _JL0LP();
    exit;
  }

  if(isset($_GET["selectedYear"]) && !isset($_GET["showEntry"]) )
    $_GET["showEntry"] = 0;

  if( isset($_GET["selectedYear"]) && isset($_GET["showEntry"]) && !isset($_GET["attachmentsIndex"]) ) {
    _JOA8L(intval($_GET["selectedYear"]), intval($_GET["showEntry"]));
    exit;
  }
  
  if( isset($_GET["selectedYear"]) && isset($_GET["showEntry"]) && isset($_GET["attachmentsIndex"]) ) {
    _JOACJ(intval($_GET["selectedYear"]), intval($_GET["showEntry"]), intval($_GET["attachmentsIndex"]));
    exit;
  }
  

  _JOPC6();

  function _JOPC6() {
    global $_ILI1C, $_8j0O6, $_QLo06;

    $_QLJfI = join("", file($_ILI1C . "na_start.htm"));

    $_8j1fC = $_QLo06;
    
    $_QLJfI = _L8OF8($_QLJfI, "<na_infobarcssjs>");
    $_QLJfI = _L8L0C($_QLJfI, "/" . "*STARTPAGE*" . "/", "/" . "*STARTPAGE/" . "*" . "/"); //Obfuscator problem with comments in string
    $_QLJfI = str_replace("<!--MUST BE FIRST STYLE!-->", "", $_QLJfI);
    $_QLJfI = str_replace("/" . "*DEMO*" . "/", "", $_QLJfI);
    
    $_QLJfI = _L80DF($_QLJfI, "<NAENTRIESBLOCK>");
    $_QLJfI = _L8OF8($_QLJfI, "<INFOBAR>");
    $_QLJfI = _L80DF($_QLJfI, "<ATTACHMENTSBLOCK>");
    
    $_QLJfI = _L81BJ($_QLJfI, "<STARTPAGETITLE>", "", $_8j0O6["StartPageTitle"]);

    if(!empty($_8j0O6["StartPageLogo"])){
        $_I016j = _L81DB($_QLJfI, "<STARTPAGELOGO>");
        $_I016j = str_replace("[URL]", $_8j0O6["StartPageLogo"], $_I016j);
        $_QLJfI = _L81BJ($_QLJfI, "<STARTPAGELOGO>", "", $_I016j);
        $_QLJfI = str_replace("[STARTPAGEHEADLINE]", $_8j0O6["StartPageHeadline"], $_QLJfI);
        $_QLJfI = _L80DF($_QLJfI, "<STARTPAGEHEADLINE>");
      }
      else
        $_QLJfI = _L80DF($_QLJfI, "<STARTPAGELOGO>");
    $_QLJfI = _L81BJ($_QLJfI, "<STARTPAGEHEADLINE>", "", $_8j0O6["StartPageHeadline"]);
    
    $_QLJfI = _L81BJ($_QLJfI, "<STARTPAGESUBHEADLINE1>", "", $_8j0O6["StartPageSubHeadline1"]);
    $_QLJfI = _L81BJ($_QLJfI, "<STARTPAGESUBHEADLINE2>", "", $_8j0O6["StartPageSubHeadline2"]);
    
    $_jfLj1 = new TAltBrowserLinkInfoBarLinkType(); // < PHP 5.3
    foreach($_8j0O6["InfoBarLinksArray"] as $key => $_QltJO){
       $_jfCoo = $_8j0O6["InfoBarLinksArray"][$key];
       if($_jfCoo->LinkType == $_jfLj1->abtSubscribe){
         break;
       }  
    }     
    
    if($_8j0O6["StartPageShowSubscribeLink"] && $_jfCoo->LinkType == $_jfLj1->abtSubscribe){
        $_I016j = _L81DB($_QLJfI, "<STARTPAGESHOWSUBSCRIBELINK>");
        $_I016j = str_replace("[URL]", $_jfCoo->URL, $_I016j);
        $_I016j = str_replace("[TITLE]", $_jfCoo->Title, $_I016j);
        $_I016j = str_replace("[LINKTEXT]", $_jfCoo->Text, $_I016j);
        $_QLJfI = _L81BJ($_QLJfI, "<STARTPAGESHOWSUBSCRIBELINK>", "", $_I016j);
    }else
       $_QLJfI = _L80DF($_QLJfI, "<STARTPAGESHOWSUBSCRIBELINK>");
       

    if($_8j0O6["ShowImpressum"]){
        $_I016j = _L81DB($_QLJfI, "<STARTPAGESHOWIMPRESS>");
        $_I016j = _L81BJ($_I016j, "<IMPRESSHEADLINE>", "", $_8j0O6["ImpressumHeadline"]);

        $_j80JC = "";
        $_j80JC = unhtmlentities($_8j0O6["ImpressumText"], $_QLo06); // sanitize.inc.php htmlspecialchars()
        if(!$_8j0O6["ImpressumIsHTML"])
          $_j80JC = str_replace("\n", "<br />", $_j80JC);

        $_I016j = _L81BJ($_I016j, "<IMPRESS>", "", $_j80JC);
        $_QLJfI = _L81BJ($_QLJfI, "<STARTPAGESHOWIMPRESS>", "", $_I016j);
    }else
      $_QLJfI = _L80DF($_QLJfI, "<STARTPAGESHOWIMPRESS>");
      
      
    $lang = _LA0BA($_QLJfI);
    if($lang == "")
      $lang = $_8j0O6["InfoBarSrcLanguage"];
       
    if($_8j0O6["LinkToSWM"]){
        $_QLJfI = _L8OF8($_QLJfI, '<SuperMailerLink>');
        if($lang == "de")
          $_QLJfI = _L80DF($_QLJfI, "<SuperMailerLink_en>");
          else
          $_QLJfI = _L80DF($_QLJfI, "<SuperMailerLink_de>");
      }
      else
      $_QLJfI = _L80DF($_QLJfI, '<SuperMailerLink>');
    

    $_QLJfI = _JOBBR($_QLJfI, $lang, $_8j1fC);

    $_QLJfI = _JOCLD($_QLJfI, $lang, $_8j1fC, -1, -1, array());

    $_QLJfI = _JOCEB($_QLJfI, $_8j1fC);
    
    $_QLJfI = _L81BJ($_QLJfI, "<spacer>", "", "");

    SetHTMLHeaders($_8j1fC, false);

    print _JOF11($_QLJfI, false, $_8j1fC);
  }

   function _JOA8L($selectedYear, $showEntry){
     global $_ILI1C, $_8Iltt, $_8j0O6, $_IC18i, $_QLo06, $_IIlfi;

     $_8j1fC = $_QLo06;
     
     if($showEntry < 0)
       $showEntry = 0;

     _JOELE($selectedYear, $_8jIfj, true);
     if ($showEntry > count($_8jIfj) - 1){
       if(count($_8jIfj))
         $showEntry = count($_8jIfj) - 1;
         else{
           _JOPC6();
           return;
         }
     }

     $_8jj0l = $_8jIfj[$showEntry];

     $_I1OoI = _JOFDR();

     if(!is_array($_8j0O6["PlaceHolderReplacements"])){
       $_8j0O6["PlaceHolderReplacements"] = @unserialize($_8j0O6["PlaceHolderReplacements"]);
       if($_8j0O6["PlaceHolderReplacements"] === false)
         $_8j0O6["PlaceHolderReplacements"] = array();
     }  

     if(count($_8j0O6["PlaceHolderReplacements"]) > 0) {

        foreach($_8j0O6["PlaceHolderReplacements"] as $key => $_QltJO){
            $_I1OoI[$_QltJO["fieldname"]] = $_QltJO["value"];
        }

     }

     $_ILi8o = $_8jj0l["MailSubject"];
     $_ILi8o = _J1EBE($_I1OoI, 0, $_ILi8o, $_8j1fC, false, array());
     
     // Social media links
     $_I1OoI['AltBrowserLink_SME'] = $_8Iltt . "&showEntry=$showEntry&selectedYear=$selectedYear";
     $_I1OoI['AltBrowserLink_SME_URLEncoded'] = urlencode($_I1OoI['AltBrowserLink_SME']);
     $_I1OoI['Mail_Subject_ISO88591'] = ConvertString($_8j1fC, "ISO-8859-1", $_ILi8o, false);
     $_I1OoI['Mail_Subject_UTF8'] = ConvertString($_8j1fC, 'utf-8', $_ILi8o, false);
     $_I1OoI['Mail_Subject_ISO88591_URLEncoded'] = urlencode($_I1OoI['Mail_Subject_ISO88591']);
     $_I1OoI['Mail_Subject_UTF8_URLEncoded'] = urlencode($_I1OoI['Mail_Subject_UTF8']);
     // Social media links /
     
      if($_8jj0l["MailFormat"] == 'PlainText')
        $_8jjOI = $_8jj0l["MailPlainText"];
      if($_8jj0l["MailFormat"] != 'PlainText') {
        $_8jjOI = $_8jj0l["MailHTMLText"];
      }

      $_8jjOI = _J1EBE($_I1OoI, 0, $_8jjOI, $_8j1fC, $_8jj0l["MailFormat"] != 'PlainText', array());

      if( $_8jj0l["MailFormat"] == 'PlainText' ) {
        $_QLJfI = $_IC18i;
        
         if(strpos($_8jjOI, "\n") !== false)
            $_IoLOO = explode("\n", $_8jjOI);
            else
            $_IoLOO = explode("\r", $_8jjOI);

         $_8jjOI = str_replace("<body>", "<body>".join("<br />", $_IoLOO), $_QLJfI);
      }

     $_8jjOI = _LPFQD("<title>", "</title>", $_8jjOI, htmlspecialchars($_ILi8o, ENT_COMPAT, $_8j1fC));
     
     //ever set charset
     $_8jjOI = SetHTMLCharSet($_8jjOI, $_8j1fC);

     $_8jJ0C = join("", file($_ILI1C . "na_start.htm"));
     $_8jJ0C = str_replace("<!--MUST BE FIRST STYLE!-->", "", $_8jJ0C);
     $_8jJ0C = _L81DB($_8jJ0C, "<na_infobarcssjs>");
     $_8jJ0C = _L80DF($_8jJ0C, "/" . "*STARTPAGE*" . "/", "/" . "*STARTPAGE/" . "*" . "/"); //Obfuscator problem with comments in string
     $_8jJ0C = str_replace("/" . "*DEMO*" . "/", "", $_8jJ0C);
     $_8jJ0C = _LDL8B($_8jJ0C);

     if(stripos($_8jjOI, "</head>") !== false)
         $_8jjOI = str_ireplace("</head>", $_8jJ0C . "</head>", $_8jjOI);
         else
         $_8jjOI = $_8jJ0C . $_8jjOI;
     if(stripos($_8jjOI, "<!DOCTYPE html>") === false)
         $_8jjOI = "<!DOCTYPE html>" . $_8jjOI;

     $_QLJfI = join("", file($_ILI1C . "na_start.htm"));
     $_j8toO = _L81DB($_QLJfI, "<INFOBAR>");
     $_QLJfI = null;

     $lang = _LA0BA($_8jjOI);
     if($lang == "")
       $lang = $_8j0O6["InfoBarSrcLanguage"];

     $_j8toO = _JOBBR($_j8toO, $lang);
    
     //Attachments
     if($_8jj0l["Attachments"] != ""){
       $_8jj0l["Attachments"] = @unserialize($_8jj0l["Attachments"]);
       if($_8jj0l["Attachments"] === false)
         $_8jj0l["Attachments"] = array();
     }else
       $_8jj0l["Attachments"] = array();
       
     if(count($_8jj0l["Attachments"]) && _L81DB($_j8toO, "<ATTACHMENTSBLOCK>") != ""){
       $_IoLOO = _L81DB($_j8toO, "<ATTACHMENTSBLOCK>");
       $_j8OLI = _L81DB($_IoLOO, "<ATTACHMENTBLOCK>");
       $_j8otC = "";
      
       for($_I016j=0; $_I016j<count($_8jj0l["Attachments"]); $_I016j++){
         $_8jj0l["Attachments"][$_I016j] = CheckFileNameForUTF8($_8jj0l["Attachments"][$_I016j]);
         if(!file_exists($_IIlfi . $_8jj0l["Attachments"][$_I016j])) continue;
         $_j8otC .= $_j8OLI;
         
         if($_8j1fC == "utf-8")
           $_8j60f = htmlspecialchars(utf8_encode($_8jj0l["Attachments"][$_I016j]), ENT_COMPAT, $_8j1fC);
           else
           $_8j60f = htmlspecialchars($_8jj0l["Attachments"][$_I016j], ENT_COMPAT, $_8j1fC);
         
         $_j8otC = str_replace("[ATTACHMENTSTITLE]", $_8j60f, $_j8otC);
         $_j8otC = str_replace("[ATTACHMENTSFILENAME]", $_8j60f, $_j8otC);
         $_j8otC = str_replace("[NASCRIPTURL]", $_8Iltt, $_j8otC);
         $_j8otC = str_replace("[NAENTRYINDEX]", $showEntry, $_j8otC);
         $_j8otC = str_replace("[NAYEAR]", $selectedYear, $_j8otC);
         $_j8otC = str_replace("[NAATTACHMENTSINDEX]", $_I016j . "&rand=" . rand(0, 65535), $_j8otC);
       } 
       $_IoLOO = _L81BJ($_IoLOO, "<ATTACHMENTBLOCK>", "", $_j8otC);
       $_j8toO = _L81BJ($_j8toO, "<ATTACHMENTSBLOCK>", "", $_IoLOO);
       
       $_8jjOI = str_ireplace("</body>", $_8j0O6["InfoBarSpacer"] . "</body>", $_8jjOI);
       
     } else{ 
       $_j8toO = _L80DF($_j8toO, "<ATTACHMENTSBLOCK>");
     }   
     //Attachments /
     
     
     $_j8toO = _L81BJ($_j8toO, "<spacer>", "", $_8j0O6["InfoBarSpacer"]);

     $_j8toO = _JOCLD($_j8toO, $lang, $_8j1fC, $selectedYear, $showEntry, $_8jIfj);

     if (stripos($_8jjOI, "<body") !== false){
        $_j8oLj = substr($_8jjOI, 0, stripos($_8jjOI, "<body") + strlen("<body"));
        $_j8C8I = substr($_8jjOI, stripos($_8jjOI, "<body") +  strlen("<body"));
        $_j8oLj .= substr($_j8C8I, 0, strpos($_j8C8I, ">") + 1);
        $_j8C8I = substr($_j8C8I, strpos($_j8C8I, ">") + 1);
        $_j8C8I = $_j8toO . $_j8C8I;
        $_8jjOI = $_j8oLj.$_j8C8I;
     }


     SetHTMLHeaders($_8j1fC, false);
      
     print _JOF11($_8jjOI, $_8j0O6["RemoveMailToLinks"], $_8j1fC);
   }
  
   function _JOACJ($selectedYear, $showEntry, $attachmentsIndex){
     global $_jfOJj;
     
     if($showEntry < 0)
       $showEntry = 0;
     if($attachmentsIndex < 0)  
       $attachmentsIndex = 0;

     _JOELE($selectedYear, $_8jIfj, true);
     if ($showEntry > count($_8jIfj) - 1){
       _JOPC6();
       return;
     }

     $_8jj0l = $_8jIfj[$showEntry];

     if($_8jj0l["Attachments"] != ""){
       $_8jj0l["Attachments"] = @unserialize($_8jj0l["Attachments"]);
       if($_8jj0l["Attachments"] === false)
         $_8jj0l["Attachments"] = array();
     }else
       $_8jj0l["Attachments"] = array();
       
     if(count($_8jj0l["Attachments"])){
       if($attachmentsIndex > count($_8jj0l["Attachments"]) - 1){
         _JOPC6();
         return;
       } 
       
       $_jfO0t = "Location: " . $_jfOJj . "file/" . utf8_encode( CheckFileNameForUTF8( $_8jj0l["Attachments"][$attachmentsIndex] ) );
       
       header("Content-Type: text/html; charset=utf-8");
       header($_jfO0t, true, 301);
       die;
     }else  
       _JOPC6();
   }

   function _LQOLL($_QLoli, $_8jfIQ, $_jfCoo, $_8jfL8=false){
     $_ILJjL = _L81DB($_QLoli, $_8jfIQ);
     $_ILJjL = str_replace('[URL]', $_jfCoo->URL, $_ILJjL);
     $_ILJjL = str_replace('[TITLE]', $_jfCoo->Title, $_ILJjL);
     $_ILJjL = str_replace('[LINKTEXT]',  $_jfCoo->Text, $_ILJjL);
     if(!$_8jfL8)
       return _L81BJ($_QLoli, $_8jfIQ, "", $_ILJjL);
       else
       return _L8Q6B($_QLoli, $_8jfIQ, "", $_ILJjL);
   }

   function _LDL8B($_QLoli){
    global $_8j0O6;
    $_jfLO0 = _L81DB($_QLoli, "<style>");
    
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
        
        $_j8Ioj = strpos($_j8QJ8[$_Qli6J], '/*' . $_8j0O6["InfoBarSchemeColorName"] );
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
    
    $_jfLO0 = join("\r\n", $_j8QJ8);
    $_j8QJ8 = null;
    $_QLoli = _L81BJ($_QLoli, "<style>", "", "<style>" . $_jfLO0 . "</style>", true);
    $_jfLO0 = null;
    $_QLoli = _L80DF($_QLoli, "<!--COLORSCHEME", "/COLORSCHEME-->");
    return $_QLoli;
   }
   
   function _JOBBR($_QLoli, $_8j8Oi = "de", $_8j1fC="utf-8"){
      global $_8Iltt;
      global $_ILI1C, $_8j0O6;

      $_8j1fC = strtolower($_8j1fC);

      $_QLoli = _LDL8B($_QLoli);
      

      $_jfLj1 = new TAltBrowserLinkInfoBarLinkType(); // < PHP 5.3
      $_j8J81 = "<SOCIALLINK>" . _L81DB($_QLoli, "<SOCIALLINK>") . "</SOCIALLINK>";
      $_8j8i8 = "";
      foreach($_8j0O6["InfoBarLinksArray"] as $key => $_jfCoo){

        // Build entities
         if($_8j1fC != "utf-8"){
            $_jfCoo->Title = UTF8ToEntities( $_jfCoo->Title );
            $_jfCoo->Text = UTF8ToEntities( $_jfCoo->Text );
         }     

         switch($_jfCoo->LinkType){
            case $_jfLj1->abtSubscribe: 
                          if($_jfCoo->Checked)
                             $_QLoli = _LQOLL($_QLoli, "<SUBSCRIBELINK>", $_jfCoo);
                             else
                             $_QLoli = _L80DF($_QLoli, "<SUBSCRIBELINK>");
                          break;
            case $_jfLj1->abtUnsubscribe: 
                          $_QLoli = _L80DF($_QLoli, "<UNSUBSCRIBELINK>");
                          break;
            case $_jfLj1->abtFacebook: 
                          if($_jfCoo->Checked){
                            $_8j8i8 .= $_j8J81; 
                            $_8j8i8 = _LQOLL($_8j8i8, "<SOCIALLINK>", $_jfCoo);
                          }
                          break;
            case $_jfLj1->abtTwitter: 
                          if($_jfCoo->Checked){
                            $_8j8i8 .= $_j8J81; 
                            $_8j8i8 = _LQOLL($_8j8i8, "<SOCIALLINK>", $_jfCoo);
                          }
                          break;
            case $_jfLj1->abtArchieve: 
                          $_QLoli = _L80DF($_QLoli, "<ARCHIEVELINK>");
                          break;
            case $_jfLj1->abtRSS: 
                          if($_jfCoo->Checked){
                            $_jfCoo->URL = $_8Iltt . "&showRSS";
                            $_QLoli = _LQOLL($_QLoli, "<RSSFEEDLINK>", $_jfCoo);
                          }else 
                            $_QLoli = _L80DF($_QLoli, "<RSSFEEDLINK>");
                          break;
            case $_jfLj1->abtTranslate: 
                           if($_jfCoo->Checked){
                             $_j8tiJ = _L8QJA($_QLoli, '<TRANSLATE_ITEM>', '</TRANSLATE_ITEM>');
                             $_j8OOQ = '';
                             for($_QliOt=0; $_QliOt<count($_8j0O6["InfoBarSupportedTranslationLanguages"]); $_QliOt++){
                               $_j8OOC = $_8j0O6["InfoBarSupportedTranslationLanguages"][$_QliOt];
                               if($_8j8Oi == $_j8OOC["code"]) continue;
                               
                               if($_8j1fC == "utf-8")
                                  $_j8Oit = $_j8OOC["Name"];
                                  else
                                  $_j8Oit = UTF8ToEntities($_j8OOC["Name"]);
                                 
                               $_j8OOQ .= $_j8tiJ;
                               $_j8OOQ = str_replace('[TITLE]', $_j8Oit, $_j8OOQ);
                               $_j8OOQ = str_replace('[LINKTEXT]', $_j8Oit, $_j8OOQ);
                               $_j8OOQ = str_replace('[LANGUAGE]', $_j8OOC["code"], $_j8OOQ);
                               $_j8OOQ = str_replace('[SRCLANGUAGE]', $_8j8Oi, $_j8OOQ);
                             }

                             $_QLoli = str_replace('<TRANSLATE_ITEMS />', $_j8OOQ, $_QLoli);

                             $_QLoli = _LQOLL($_QLoli, "<TRANSLATE_BLOCK>", $_jfCoo);
                           }else{
                             $_QLoli = _L80DF($_QLoli, "<TRANSLATE_BLOCK>");  
                           }
                          break;
            case $_jfLj1->abtHome: 
                          if($_jfCoo->Checked){
                            $_jfCoo->URL = $_8Iltt;
                            $_QLoli = _LQOLL($_QLoli, "<HOMELINK>", $_jfCoo);
                          }else 
                            $_QLoli = _L80DF($_QLoli, "<HOMELINK>");
                          break;
            case $_jfLj1->abtAttachments:                 
                         if($_jfCoo->Checked){
                           $_QLoli = _LQOLL($_QLoli, "<ATTACHMENTSBLOCK>", $_jfCoo, true);
                         }else{
                           $_QLoli = _L80DF($_QLoli, "<ATTACHMENTSBLOCK>"); 
                         }
                         
                         break;
         }  
      }
      $_QLoli = _L81BJ($_QLoli, "<SOCIALLINK>", "", $_8j8i8);


      return $_QLoli;
   }
  
   function _JOCLD($_j8toO, $lang, $_8j1fC, $selectedYear, $showEntry, $_8jIfj){
     global $_8Iltt;
     global $_8j0O6, $_QLo06;


     $_I016j = _L81DB($_j8toO, "<NAYEARSBLOCK>");
     $_8jtOO = array();
     _JODEJ($_8jtOO);

     if($selectedYear == -1 && $showEntry == -1 && !count($_8jtOO)){
       return _L80DF($_j8toO, "<NAYEARSBLOCK>");
     }

     if($selectedYear == -1 && $showEntry == -1){
       $selectedYear = $_8jtOO[0];
     }

     $_8jOIt = _L81DB($_I016j, "<NAYEAR_ITEM>");
     $_I016j = _L80DF($_I016j, "<NAYEAR_ITEM>");

     if($_8j0O6["ShowSelectedEntryFirst"]){
       $_66Lfi = $_8jOIt;
       $_66Lfi = str_replace("[NAYEARPrefix]", $_8j0O6["YearsPrefix"], $_66Lfi);
       $_66Lfi = str_replace("[NAYEAR]", $selectedYear, $_66Lfi);
       $_66Lfi = str_replace("[NASCRIPTURL]", $_8Iltt, $_66Lfi);
       if( $showEntry > -1 ) // not on StartPage
          $_66Lfi = str_replace('class="Year"', 'class="selectedYear"', $_66Lfi);
     }else
      $_66Lfi = "";

     for($_Qli6J=0; $_Qli6J<count($_8jtOO); $_Qli6J++){
       if($_8j0O6["ShowSelectedEntryFirst"] && $_8jtOO[$_Qli6J] == $selectedYear) continue;
       $_8jOJL = $_8jOIt;
       $_8jOJL = str_replace("[NAYEARPrefix]", $_8j0O6["YearsPrefix"], $_8jOJL);
       $_8jOJL = str_replace("[NAYEAR]", $_8jtOO[$_Qli6J], $_8jOJL);
       $_8jOJL = str_replace("[NASCRIPTURL]", $_8Iltt, $_8jOJL);
       if(!$_8j0O6["ShowSelectedEntryFirst"] && $_8jtOO[$_Qli6J] == $selectedYear)
         $_8jOJL = str_replace('class="Year"', 'class="selectedYear"', $_8jOJL);
       $_66Lfi .= $_8jOJL;
     }

     $_I016j = str_replace("<NAYEAR_ITEMS />", $_66Lfi, $_I016j);

     $_I016j = str_replace("[NAYEARPrefix]", $_8j0O6["YearsPrefix"], $_I016j);
     $_I016j = str_replace("[NAYEAR]", $selectedYear, $_I016j);

     $_j8toO = _L81BJ($_j8toO, "<NAYEARSBLOCK>", "", $_I016j);

     //

     if($showEntry == -1){ // StartPage
       return $_j8toO;
     }

     $_I016j = _L81DB($_j8toO, "<NAENTRIESBLOCK>");
     $_8jo0J = _L81DB($_I016j, "<NA_ITEM>");
     $_I016j = _L80DF($_I016j, "<NA_ITEM>");


     if(!isset($_8jIfj) || !count($_8jIfj))
        _JOELE($selectedYear, $_8jIfj, true);

     $_8jj0l = $_8jIfj[$showEntry];

     $_I016j = _JOD1E($_I016j, $selectedYear, $showEntry, $_8jj0l, $_8j1fC);

     if($_8j0O6["ShowSelectedEntryFirst"]){
       $_66Lfi = _JOD1E($_8jo0J, $selectedYear, $showEntry, $_8jj0l, $_8j1fC);
       $_66Lfi = str_replace('class="Entry"', 'class="selectedEntry"', $_66Lfi);
     }else
       $_66Lfi = "";


     for($_Qli6J=0; $_Qli6J<count($_8jIfj); $_Qli6J++){
       if($_8j0O6["ShowSelectedEntryFirst"] && $_Qli6J == $showEntry) continue;
       $_8jOJL = $_8jo0J;
       $_8jj0l = $_8jIfj[$_Qli6J];
       $_8jOJL = _JOD1E($_8jOJL, $selectedYear, $_Qli6J, $_8jj0l, $_8j1fC);
       if(!$_8j0O6["ShowSelectedEntryFirst"] && $_Qli6J == $showEntry)
         $_8jOJL = str_replace('class="Entry"', 'class="selectedEntry"', $_8jOJL);
       $_66Lfi .= $_8jOJL;
     }

     $_I016j = str_replace("<NA_ITEMS />", $_66Lfi, $_I016j);


     $_j8toO = _L81BJ($_j8toO, "<NAENTRIESBLOCK>", "", $_I016j);

     return $_j8toO;
   }
  
   function _JOCEB($_QLoli, $_8j1fC){
     global $_8j0O6;
     
     $_ftICi = $_8j0O6["MaxLatestEntriesOnStartPage"];

     if($_ftICi < 1)
       $_ftICi = 3;

     $_8jtOO = array();
     _JODEJ($_8jtOO);
     if(count($_8jtOO) == 0)
       return _L80DF($_QLoli, "<STARTPAGELATESTENTRIESBLOCK>");

     $_8jojf = array();

     for($_Qli6J=0; $_Qli6J<count($_8jtOO) && count($_8jojf) < $_ftICi; $_Qli6J++){
       _JOELE($_8jtOO[$_Qli6J], $_8jIfj, true);
       for($_QliOt=0; $_QliOt<count($_8jIfj) && count($_8jojf) < $_ftICi; $_QliOt++)
         $_8jojf[] = array("Year" => $_8jtOO[$_Qli6J], "EntryIndex" => $_QliOt, "Entry" => $_8jIfj[$_QliOt]);
     }

     $_I016j = _L81DB($_QLoli, "<STARTPAGELATESTENTRIESBLOCK>");
     $_jJjQi = _L81DB($_I016j, "<LATESTENTRIESB_ITEM>");
     $_68tQ1 = "";
     for($_Qli6J=0; $_Qli6J<count($_8jojf); $_Qli6J++){
       $_68tQ1 .= $_jJjQi;

       $_8jj0l = $_8jojf[$_Qli6J]["Entry"];

       $_68tQ1 = _JOD1E($_68tQ1, $_8jojf[$_Qli6J]["Year"], $_8jojf[$_Qli6J]["EntryIndex"], $_8jj0l, $_8j1fC);
     }

     $_I016j = _L80DF($_I016j, "<LATESTENTRIESB_ITEM>");
     $_I016j = str_replace("<LATESTENTRIESB_ITEMS />", $_68tQ1, $_I016j);

     $_QLoli = _L81BJ($_QLoli, "<STARTPAGELATESTENTRIESBLOCK>", "", $_I016j);

     return $_QLoli;
   }

   function _JOD1E($_8jofO, $_8jotO, $_8jCjj, $_8jj0l, $_8j1fC) {
      global $_8Iltt, $ShortDateFormat;
      global $_8j0O6, $_QLo06;

      $Date = date($ShortDateFormat, $_8jj0l["StartSendDateTimeUNIXTIME"]);
      $_ILi8o = $_8jj0l["MailSubject"];

      $_I1OoI = _JOFDR();

      if(!is_array($_8j0O6["PlaceHolderReplacements"])){
        $_8j0O6["PlaceHolderReplacements"] = @unserialize($_8j0O6["PlaceHolderReplacements"]);
        if($_8j0O6["PlaceHolderReplacements"] === false)
          $_8j0O6["PlaceHolderReplacements"] = array();
      }  

      if(count($_8j0O6["PlaceHolderReplacements"]) > 0) {

         foreach($_8j0O6["PlaceHolderReplacements"] as $key => $_QltJO){
             $_I1OoI[$_QltJO["fieldname"]] = $_QltJO["value"];
         }

      }

      $_ILi8o = $_8jj0l["MailSubject"];
      $_ILi8o = _J1EBE($_I1OoI, 0, $_ILi8o, $_8j1fC, false, array());
      $_ILi8o = htmlspecialchars($_ILi8o, ENT_COMPAT, $_8j1fC);

      $_8jofO = str_replace("[NASCRIPTURL]", $_8Iltt, $_8jofO);

      $_8jofO = str_replace("[NAENTRYINDEX]", $_8jCjj, $_8jofO);
      $_8jofO = str_replace("[NAYEAR]", $_8jotO, $_8jofO);
      $_8jofO = str_replace("[NAENTRYPrefix]", UTF8ToEntities( $_8j0O6["NewsletterEntryPrefix"] ), $_8jofO);
      $_8jofO = str_replace("[NAENTRYDATE]", $Date, $_8jofO);
      $_8jofO = str_replace("[NAENTRYSUBJECT]", $_ILi8o, $_8jofO);

      return $_8jofO;
    }
  
function _JODEJ(&$_8jtOO) {
  global $_ftjjC, $_QLttI, $_8j0O6;

  if(!is_array($_8jtOO))
    $_8jtOO = array();
  
  reset($_ftjjC);
  foreach($_ftjjC as $_J0LQQ => $_QltJO) {
    if(!is_int($_J0LQQ) || !is_array($_QltJO)) continue;
    $_QLfol = "SELECT DISTINCT YEAR(StartSendDateTime) As AYear FROM $_QltJO[CurrentSendTableName] WHERE `Campaigns_id`=$_J0LQQ ORDER BY AYear DESC";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_row($_QL8i1)) {
       if(!in_array($_QLO0f[0], $_8jtOO))
          $_8jtOO[] = $_QLO0f[0];
    }
    mysql_free_result($_QL8i1);
  }
  if($_8j0O6["SortOrderNewToOld"])
    rsort($_8jtOO, SORT_NUMERIC);
    else
    sort($_8jtOO, SORT_NUMERIC);
}

function _JOELE($_8ji06, &$_8jIfj, $_8jit1 = false) {
  global $_ftjjC, $_j01CJ, $_QLo60, $_8j0O6, $_QLttI;

  $_8ji06 = intval($_8ji06);
  reset($_ftjjC);
  foreach($_ftjjC as $_J0LQQ => $_QltJO) {
    if(!is_int($_J0LQQ) || !is_array($_QltJO)) continue; // for newer PHP versions or a bug in PHP, added SubjectGenerator is enumerated, but should not 
    $_QLfol = "SELECT *, DATE_FORMAT(StartSendDateTime, $_j01CJ) AS StartSendDateTimeFormated, UNIX_TIMESTAMP(StartSendDateTime) AS StartSendDateTimeUNIXTIME, YEAR(StartSendDateTime) As AYear, MONTH(StartSendDateTime) As AMonth, DAYOFMONTH(StartSendDateTime) As ADay FROM $_QltJO[CurrentSendTableName] LEFT JOIN $_QltJO[ArchiveTableName] ON $_QltJO[ArchiveTableName].SendStat_id=$_QltJO[CurrentSendTableName].id WHERE `Campaigns_id`=$_J0LQQ AND YEAR(StartSendDateTime)=$_8ji06";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_QLO0f["Campaigns_id"] = $_J0LQQ;
      if( !isset($_ftjjC["SubjectGenerator"])  )
        $_ftjjC["SubjectGenerator"] = new SubjectGenerator($_QLO0f["MailSubject"]);
        else
        $_ftjjC["SubjectGenerator"]->_LECC8($_QLO0f["MailSubject"], true);
      $_QLO0f["MailSubject"] = $_ftjjC["SubjectGenerator"]->_LEEPA(-1); // first entry
      $_8jIfj[$_QLO0f["StartSendDateTime"]] = $_QLO0f;
    }
    mysql_free_result($_QL8i1);
  }
  if($_8j0O6["SortOrderNewToOld"])
    krsort($_8jIfj);
    else
    ksort($_8jIfj);
  if($_8jit1){
    $_ffo11 = array();
    foreach($_8jIfj as $key => $_QltJO) {
      $_ffo11[] = $_QltJO;
    }
    $_8jIfj = $_ffo11;
  }
}

function _JOF11($_QLoli, $_8jLJ6 = false, $_8j1fC = "utf-8") {
 global $_8j0O6, $_QLl1Q;

 $_QLoli = _L80DF($_QLoli, "[AltBrowserLink_begin]", "[AltBrowserLink_end]");
 $_QLoli = _L80DF($_QLoli, "<!--AltBrowserLink_begin//-->", "<!--AltBrowserLink_end//-->");

 if(!empty($_8j0O6["HeadDescription"]))
   $_QLoli = _LRFCO("</head>", '<meta name="description" content="'.$_8j0O6["HeadDescription"].'">'.$_QLl1Q.'</head>', $_QLoli);

 if(!empty($_8j0O6["HeadKeywords"]))
   $_QLoli = _LRFCO("</head>", '<meta name="keywords" content="'.$_8j0O6["HeadKeywords"].'">'.$_QLl1Q.'</head>', $_QLoli);

 if(!empty($_8j0O6["HeadAutor"])){
   $_QLoli = _LRFCO("</head>", '<meta name="author" content="'.$_8j0O6["HeadAutor"].'">'.$_QLl1Q.'</head>', $_QLoli);
   $_QLoli = _LRFCO("</head>", '<meta name="copyright" content="'.$_8j0O6["HeadAutor"].'">'.$_QLl1Q.'</head>', $_QLoli);
 }
 
 if($_8jLJ6) {
   $_IjQI8 = array();
   _LAO86($_QLoli, $_IjQI8);
   for($_Qli6J=0; $_Qli6J<count($_IjQI8); $_Qli6J++)
      $_QLoli = str_replace($_IjQI8[$_Qli6J], "mailto:noemail", $_QLoli);
 }


 return $_QLoli;
}

function _JOFDR() {
  global $_Ij8oL, $INTERFACE_LANGUAGE, $_Iol8t, $_IolCJ, $_jlJ1o, $_ICiQ1, $_jQ68I, $_jQf81, $_Ij08l, $_QLttI;
  #### normal placeholders
  $_QLfol = "SELECT fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_I1OoI=array();
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
   $_I1OoI[$_QLO0f["fieldname"]] = "";
  }
  mysql_free_result($_QL8i1);
  # defaults
  foreach ($_Iol8t as $key => $_QltJO)
   $_I1OoI[$key] = "";

  // functions
  $_QLfol = "SELECT Name FROM $_jQ68I ORDER BY Name";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
   $_I1OoI[$_QLO0f["Name"]] = "";
  }
  mysql_free_result($_QL8i1);

  // textblocks
  $_QLfol = "SELECT Name FROM $_jQf81 ORDER BY Name";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  while($_QLO0f=mysql_fetch_array($_QL8i1)) {
   $_I1OoI[$_QLO0f["Name"]] = "";
  }
  mysql_free_result($_QL8i1);

  #### special newsletter unsubscribe placeholders
  $_ICCIo = array_merge($_IolCJ, $_jlJ1o, $_ICiQ1, $_Ij08l);
  reset($_ICCIo);
  foreach ($_ICCIo as $key => $_QltJO)
    $_I1OoI[$key] = "";

  $_I1OoI["MembersAge"] = "";

  return $_I1OoI;
}

function _JL0LP(){
 global $_8j0O6, $_QLl1Q, $INTERFACE_LANGUAGE, $_8Iltt, $_QLo06, $_IIlfi;

 if(!$_8j0O6["rssshowAll"]){
   $_8jIfj = array();
   if(!count($_8jIfj))
   _JOELE(date("Y"), $_8jIfj);
     _JOELE(date("Y") - 1, $_8jIfj);
 } else{
   $_8jtOO = array();
   _JODEJ($_8jtOO);
   $_8jIfj = array();
   for($_Qli6J=0; $_Qli6J<count($_8jtOO); $_Qli6J++) {
     $_8jj0l = array();
     _JOELE($_8jtOO[$_Qli6J], $_8jj0l);
     $_Ift08 = 0;
     foreach($_8jj0l as $key => $_QltJO) {
       $_QltJO["internalSortId"] = $_Ift08;
       $_Ift08++;
       $_8jj0l[$key] = $_QltJO;
     }

     $_8jIfj = array_merge($_8jIfj, $_8jj0l);
   }
 }

 $_I1OoI = @file(_LOC8P()."rsstemplate.xml");
 if($_I1OoI)
   $_QLJfI = join("", $_I1OoI);
   else
   $_QLJfI = join("", file(InstallPath._LOC8P()."rsstemplate.xml"));

 $_JlQio = "<item>"._L81DB($_QLJfI, "<item>", "</item>")."</item>";
 $_QLJfI = _L81BJ($_QLJfI, "<item>", "</item>", "<!--RSSFEED-->");

 $_QLJfI = _L8Q6J($_QLJfI, "<title>", "</title>", "<![CDATA[".$_8j0O6["rssTitle"]."]]>");
 $_QLJfI = _L8Q6J($_QLJfI, "<link>", "</link>", "<![CDATA[". $_8j0O6["rssLinkURL"]."]]>" );
 $_QLJfI = _L8Q6J($_QLJfI, "<description>", "</description>", "<![CDATA[".$_8j0O6["rssDescription"]."]]>");
 $_QLJfI = _L8Q6J($_QLJfI, "<language>", "</language>", "$INTERFACE_LANGUAGE");
 $_QLJfI = _L8Q6J($_QLJfI, "<copyright>", "</copyright>", "<![CDATA[".$_8j0O6["rssCopyright"]."]]>");
 $_QLJfI = _L8Q6J($_QLJfI, "<pubDate>", "</pubDate>", date("r", $_8j0O6["CreateDateUnixTime"]));

 $_I1OoI = _JOFDR();

 if(!is_array($_8j0O6["PlaceHolderReplacements"])){
   $_8j0O6["PlaceHolderReplacements"] = @unserialize($_8j0O6["PlaceHolderReplacements"]);
   if($_8j0O6["PlaceHolderReplacements"] === false)
     $_8j0O6["PlaceHolderReplacements"] = array();
 }  

 if(count($_8j0O6["PlaceHolderReplacements"]) > 0) {

    foreach($_8j0O6["PlaceHolderReplacements"] as $key => $_QltJO){
        $_I1OoI[$_QltJO["fieldname"]] = $_QltJO["value"];
    }

 }


 $_jQ0OL = "";
 # sort creates new keys
 $_Ift08 = 0;
 foreach($_8jIfj as $key => $_QltJO) {
   $_8jj0l = $_QltJO;
   if(isset($_QltJO["internalSortId"]))
     $_Ift08 = $_QltJO["internalSortId"];

   if($_8jj0l["Attachments"] != ""){
     $_8jj0l["Attachments"] = @unserialize($_8jj0l["Attachments"]);
     if($_8jj0l["Attachments"] === false)
       $_8jj0l["Attachments"] = array();
   }else
     $_8jj0l["Attachments"] = array();

   // problems FTP upload rsstemplate.xml, it can be CRLF, CR or LF
   if(_L81DB($_JlQio, "<enclosure", "/>" . $_QLl1Q) != "")
     $_8jl06 = $_QLl1Q;
     else
     if(_L81DB($_JlQio, "<enclosure", "/>" . "\n") != "")
        $_8jl06 = "\n";
        else
         $_8jl06 = "\r";

   if(!count($_8jj0l["Attachments"])){ 
     $_Ql0fO = _L80DF($_JlQio, "<enclosure", "/>" . $_8jl06);
   }
   else{
     $_8jlt6 = "<enclosure" . _L81DB($_JlQio, "<enclosure", "/>". $_8jl06) . "/>" . $_8jl06;
     $_Ql0fO = _L81BJ($_JlQio, "<enclosure", "/>", chr(255) . "ENCLOSURE" . chr(255));
   }  
   
   $_ILi8o = $_8jj0l["MailSubject"];

   if($_8jj0l["MailFormat"] == 'PlainText')
     $_8jjOI = $_8jj0l["MailPlainText"];
   if($_8jj0l["MailFormat"] != 'PlainText') {
     $_8jjOI = $_8jj0l["MailHTMLText"];
     $_8jjOI = _L80DF($_8jjOI, "<style", "</style>"); // CSS in RSS not allowed
     $_8jjOI = _LPFQD("<title>", "</title>", $_8jjOI, $_ILi8o);

     $_JiI11 = array();
     GetInlineFiles($_8jjOI, $_JiI11, true);
     for($_Qli6J=0; $_Qli6J< count($_JiI11); $_Qli6J++) {
       if(!@file_exists($_JiI11[$_Qli6J])) {
         $_IJL6o = _LPBCC(WebsiteURL).$_JiI11[$_Qli6J];
         $_8jjOI = str_replace($_JiI11[$_Qli6J], $_IJL6o, $_8jjOI);
       }
     }

   }
   $_ILi8o = $_8jj0l["MailSubject"];
   $_ILi8o = _J1EBE($_I1OoI, 0, $_ILi8o, $_QLo06, false, array());

   // Social media links
   $_I1OoI['AltBrowserLink_SME'] = $_8Iltt.""."&showRSS=1";
   $_I1OoI['AltBrowserLink_SME_URLEncoded'] = urlencode($_I1OoI['AltBrowserLink_SME']);
   $_I1OoI['Mail_Subject_ISO88591'] = ConvertString($_QLo06, "ISO-8859-1", $_ILi8o, false);
   $_I1OoI['Mail_Subject_UTF8'] = $_ILi8o;
   $_I1OoI['Mail_Subject_ISO88591_URLEncoded'] = urlencode($_I1OoI['Mail_Subject_ISO88591']);
   $_I1OoI['Mail_Subject_UTF8_URLEncoded'] = urlencode($_I1OoI['Mail_Subject_UTF8']);
   // Social media links /

   $_8jjOI = _J1EBE($_I1OoI, 0, $_8jjOI, $_QLo06, $_8jj0l["MailFormat"] != 'PlainText', array());

   $_8jjOI = str_replace('"'.$_8jj0l["MailEncoding"].'"', '"'.$_QLo06.'"', $_8jjOI);

   $_Ql0fO = _L8Q6J($_Ql0fO, "<title>", "</title>", "<![CDATA[".htmlspecialchars($_ILi8o, ENT_COMPAT, $_QLo06)."]]>");
   $_Ql0fO = _L8Q6J($_Ql0fO, "<link>", "</link>", "<![CDATA[" . $_8Iltt . '&showEntry=' . $_Ift08 . '&selectedYear=' . $_QltJO["AYear"] . "&showContent=".rand(1, 1024) . "]]>" );
   $_Ql0fO = _L8Q6J($_Ql0fO, '<guid isPermaLink="false">', "</guid>", md5($_8jj0l["StartSendDateTimeUNIXTIME"]));
   $_Ql0fO = _L8Q6J($_Ql0fO, "<pubDate>", "</pubDate>", date("r", $_8jj0l["StartSendDateTimeUNIXTIME"]));
   $_Ql0fO = _L8Q6J($_Ql0fO, "<description>", "</description>", "<![CDATA[".$_8jjOI."]]>" );
   $_Ql0fO = _L8Q6J($_Ql0fO, "<description>", "</description>", "<![CDATA[".$_8jjOI."]]>" );
   
   $_8jlLL = "";
   for($_I016j=0; $_I016j<count($_8jj0l["Attachments"]); $_I016j++){
     if(!file_exists($_IIlfi . $_8jj0l["Attachments"][$_I016j])) continue;
     $_8jlLL .= $_8jlt6;
     $URL = str_replace("&", "&amp;", $_8Iltt . '&showEntry=' . $_Ift08 . '&selectedYear=' . $_QltJO["AYear"] . "&attachmentsIndex=" . $_I016j);
     $_8jlLL = str_replace("ENCLOSUREURL", $URL, $_8jlLL);
     $_8jlLL = str_replace("ENCLOSURESIZE", @filesize($_IIlfi . $_8jj0l["Attachments"][$_I016j]), $_8jlLL);
     $_8J0OJ = @mime_content_type($_IIlfi . $_8jj0l["Attachments"][$_I016j]);
     if($_8J0OJ === false)
       $_8J0OJ = _LALJ6($_8jj0l["Attachments"][$_I016j]);
     $_8jlLL = str_replace("ENCLOSURETYPE", $_8J0OJ, $_8jlLL);
   }
   $_Ql0fO = str_replace(chr(255) . "ENCLOSURE" . chr(255), $_8jlLL, $_Ql0fO);
   
   $_jQ0OL .= $_QLl1Q.$_Ql0fO;
   $_Ift08++;
 }
 $_QLJfI = str_replace("<!--RSSFEED-->", $_jQ0OL, $_QLJfI);

 $_QLJfI = _L80DF($_QLJfI, "[AltBrowserLink_begin]", "[AltBrowserLink_end]");
 $_QLJfI = _L80DF($_QLJfI, "<!--AltBrowserLink_begin//-->", "<!--AltBrowserLink_end//-->");

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

 // Set the response format.
 @header( 'Content-Type: text/xml; charset='.$_QLo06 ) ;
 print $_QLJfI;
}

?>
