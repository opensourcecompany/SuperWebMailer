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
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

  if(!isset($_GET["id"]))
    $_GET["id"] = 0;

  $_GET["id"] = intval($_GET["id"]);

  if($_GET["id"] > 0 && $OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeTemplateBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_6iJlo = true;

  $_6i6Ot = empty($_GET["TemplateFileName"]) ? "" : trim($_GET["TemplateFileName"]);;
  $_6i6LC = empty($_GET["COLORSCHEME_SELECT_CB"]) ? "" : trim($_GET["COLORSCHEME_SELECT_CB"]);
  
  if(strpos($_6i6Ot, "id=") !== false && strpos($_6i6Ot, "id=") == 0){
    $_GET["id"] = intval(substr($_6i6Ot, 3));
  }else
    if(strpos($_6i6Ot, "CampaignListId=") !== false && strpos($_6i6Ot, "CampaignListId=") == 0){
        $_GET["CampaignListId"] = intval(substr($_6i6Ot, 15));
    }  
  
  if( $_GET["id"] > 0 ) {

    if($OwnerUserId == 0)
      $_QLfol = "SELECT MailHTMLText FROM $_Ql10t WHERE MailFormat <> 'PlainText' AND id=$_GET[id]";
      else
      $_QLfol = "SELECT MailHTMLText FROM $_Ql10t LEFT JOIN $_Ql18I ON templates_id=id WHERE ((`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId)) AND id=$_GET[id] AND MailFormat <> 'PlainText'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_num_rows($_QL8i1) == 0){
        $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
        $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
        print $_QLJfI;
        exit;
    }

    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_6iJlo = (trim($_QLO0f["MailHTMLText"]) == "") || strpos($_QLO0f["MailHTMLText"], "</head>") === false;
    if(!$_6iJlo)
      $_6i6Ot = "id=$_GET[id]";
  } else {

    if(isset($_GET["CampaignListId"])) {
      $_GET["CampaignListId"] = intval($_GET["CampaignListId"]);

      if($OwnerUserId != 0) {
        $_QLJJ6 = _LPALQ($UserId);
        if(!$_QLJJ6["PrivilegeCampaignEdit"]) {
          $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
          $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
          print $_QLJfI;
          exit;
        }
      }

      $_QLfol = "SELECT `WizardHTMLText` AS `MailHTMLText` FROM `$_QLi60` WHERE `MailEditType`='Wizard' AND id=".$_GET["CampaignListId"];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      if(mysql_num_rows($_QL8i1)) {
         $_QLO0f = mysql_fetch_assoc($_QL8i1);
         mysql_free_result($_QL8i1);
         $_6iJlo = (trim($_QLO0f["MailHTMLText"]) == "") || strpos($_QLO0f["MailHTMLText"], "</head>") === false;
      }
      
      $_6i6Ot = "CampaignListId=$_GET[CampaignListId]";

    }else 
      if(isset($_GET["BirthdayresponderListId"])){

            $_GET["BirthdayresponderListId"] = intval($_GET["BirthdayresponderListId"]);

            if($OwnerUserId != 0) {
              $_QLJJ6 = _LPALQ($UserId);
              if(!$_QLJJ6["PrivilegeBirthdayMailsEdit"]) {
                $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
                $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
                print $_QLJfI;
                exit;
              }
            }

            $_QLfol = "SELECT `WizardHTMLText` AS `MailHTMLText` FROM `$_ICo0J` WHERE `MailEditType`='Wizard' AND id=".$_GET["BirthdayresponderListId"];
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
            if(mysql_num_rows($_QL8i1)) {
               $_QLO0f = mysql_fetch_assoc($_QL8i1);
               mysql_free_result($_QL8i1);
               $_6iJlo = (trim($_QLO0f["MailHTMLText"]) == "") || strpos($_QLO0f["MailHTMLText"], "</head>") === false;
            }
            
            $_6i6Ot = "BirthdayresponderListId=$_GET[BirthdayresponderListId]";

      }else
      if( isset($_GET["FUResponderListId"]) && isset($_GET["FUResponderMailItemId"]) ){

            $_GET["FUResponderListId"] = intval($_GET["FUResponderListId"]);
            $_GET["FUResponderMailItemId"] = intval($_GET["FUResponderMailItemId"]);

            if($OwnerUserId != 0) {
              $_QLJJ6 = _LPALQ($UserId);
              if(!$_QLJJ6["PrivilegeFUMMailsEdit"]) {
                $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
                $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
                print $_QLJfI;
                exit;
              }
            }

            $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `id`=".$_GET["FUResponderListId"];
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
            $_QLO0f = mysql_fetch_assoc($_QL8i1);
            $_jIt0L = $_QLO0f["FUMailsTableName"];
            mysql_free_result($_QL8i1);

            $_QLfol = "SELECT `WizardHTMLText` AS `MailHTMLText` FROM `$_jIt0L` WHERE `MailEditType`='Wizard' AND id=".$_GET["FUResponderMailItemId"];
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
            if(mysql_num_rows($_QL8i1)) {
               $_QLO0f = mysql_fetch_assoc($_QL8i1);
               mysql_free_result($_QL8i1);
               $_6iJlo = (trim($_QLO0f["MailHTMLText"]) == "") || strpos($_QLO0f["MailHTMLText"], "</head>") === false;
            }
            
            $_6i6Ot = "FUResponderListId=$_GET[FUResponderListId]&FUResponderMailItemId=$_GET[FUResponderMailItemId]";
      }

    if($_6iJlo) {
      if(file_exists(InstallPath . 'newsletter_templates/Wizard-Sample (0).htm')){
        $_QLO0f["MailHTMLText"] = join("", file(InstallPath . 'newsletter_templates/Wizard-Sample (0).htm'));
        $_QLO0f["MailHTMLText"] = SetHTMLCharSet($_QLO0f["MailHTMLText"], $_QLo06, false);
        $_QLO0f["MailHTMLText"] = str_replace('src="', 'src="newsletter_templates/', $_QLO0f["MailHTMLText"]);
        $_6i6Ot = "id=0"; 
      }else{
        $_QLO0f["MailHTMLText"] = "<!DOCTYPE html><html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><style><!-- html,body{font-family: Arial; font-size: 1em} --></style></head><body bgcolor=\"#FFFFFF\"><div id=\"no_template_loaded\">".$resourcestrings[$INTERFACE_LANGUAGE]["NoTemplateLoaded"]."<hr /></div>".'<div align="center"><table border="0" cellpadding="0" width="600"><tr><td width="100%" align="left"><repeater editable="true"><multiline></repeater></td></tr></table></div>'."</body></html>";
        $_QLO0f["MailHTMLText"] = str_replace("<multiline>", "<div><multiline>".$resourcestrings[$INTERFACE_LANGUAGE]["NoTemplateLoadedHint"]."<br />"."</multiline></div>", $_QLO0f["MailHTMLText"]);
      }  
    } else {
      $_QLO0f["MailHTMLText"] = SetHTMLCharSet($_QLO0f["MailHTMLText"], $_QLo06, false);
    }
  }

  $_QLO0f["MailHTMLText"] = _LDL18($_QLO0f["MailHTMLText"], $_6i6LC, $_6i6Ot);
  
  // Prevent the browser from caching the result.
  // Date in the past
  @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
  // always modified
  @header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
  // HTTP/1.1
  @header('Cache-Control: no-store, no-cache, must-revalidate') ;
  @header('Cache-Control: post-check=0, pre-check=0', false) ;
  // HTTP/1.0
  @header('Pragma: no-cache') ;

  // Set the response format.
  @header( 'Content-Type: text/html; charset='.$_QLo06 ) ;


                $_6ifof =  '<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
                                                        <link rel="stylesheet" href="ipe/css/inplace_edit.css" type="text/css" />
                                                        <script type="text/javascript" src="ipe/js/inplace_edit.js"></script>
        <script type="text/javascript" src="js/jquery-latest.min.js"></script>
        <script type="text/javascript" src="js/common.js"></script>
                                                        <script type="text/javascript" src="ipe/js/jquery-ui.min.js"></script>
                                                        <link rel="stylesheet" href="ipe/css/redmond/jquery-ui-1.8.14.custom.css" type="text/css" />';

  if(strpos($_QLO0f["MailHTMLText"], "ipe/js/inplace_edit.js") === false || strpos($_QLO0f["MailHTMLText"], "js/common.js") === false )
    $_QLO0f["MailHTMLText"] = str_replace("</head>", $_6ifof . $_QLl1Q . "</head>", $_QLO0f["MailHTMLText"]);

  // special for default template
  if(strpos($_QLO0f["MailHTMLText"], "COLORSCHEME_SELECT_CB") !== false && strpos($_QLO0f["MailHTMLText"], "COLORSCHEME_SELECT_REMOVE") !== false){

     $_Iljoj='<script id="COLORSCHEME_SELECT_SCRIPT">
      $(document).ready(function(){
        if(document.getElementById("COLORSCHEME_SELECT_CB")) document.getElementById("COLORSCHEME_SELECT_CB").onchange=function(){document.getElementById("COLORSCHEME_SELECT_FORM").submit();}
        if(document.getElementById("COLORSCHEME_SELECT_REMOVE")) document.getElementById("COLORSCHEME_SELECT_REMOVE").onclick=function(){document.getElementById("COLORSCHEME_SELECT").remove();document.getElementById("COLORSCHEME_SELECT_SCRIPT").remove();}
      });
      </script>
     ';

     $_QLO0f["MailHTMLText"] = str_replace("</head>", $_Iljoj . $_QLl1Q . "</head>", $_QLO0f["MailHTMLText"]);
  }  

  print _LJA6C($_QLO0f["MailHTMLText"]);

 function _LDOEC(&$_6i80Q){
    global $INTERFACE_LANGUAGE;
    $_j816L = array();
    $_Ift08 = $_6i80Q;  
    $_Ift08 = _L81DB($_Ift08, "<!--COLORSCHEME", "/COLORSCHEME-->");
    $_6i80Q = _L80DF($_6i80Q, "<!--COLORSCHEME", "/COLORSCHEME-->");
    
    if($_Ift08 == "") return array();
    
    $_Ift08 = explode("\n", $_Ift08);
    for($_Qli6J=0; $_Qli6J<count($_Ift08); $_Qli6J++){
      $_Ift08[$_Qli6J] = str_replace(" ", "", trim($_Ift08[$_Qli6J]));
      if($_Ift08[$_Qli6J] == "") continue;
      $_IoLOO = explode("=", $_Ift08[$_Qli6J]);
      if(count($_IoLOO) < 2) continue;
      
      if($_IoLOO[1][strlen($_IoLOO[1]) - 1] == ";")
        $_IoLOO[1] = substr($_IoLOO[1], 0, strlen($_IoLOO[1]) - 1);
      
      if($INTERFACE_LANGUAGE == "de")
        $_j816L[$_IoLOO[0]] = $_IoLOO[1];
        else
        $_j816L[$_IoLOO[0]] = $_IoLOO[0];
    }
    if(!count($_j816L))
      $_j816L["grey"] = "grey";
    return $_j816L;  
 }

  function _LDL18($_6i80Q, $_6i6LC, $_6i6Ot){
    $_ILJjL = $_6i80Q; 
    if($_6i6Ot == "") return $_ILJjL;
    
    $_j816L = _LDOEC($_ILJjL);
    if(!count($_j816L)) return $_ILJjL;
    
    $_6i8Jj = _L81DB($_ILJjL, '<!--COLORSCHEME_SELECT-->', '<!--/COLORSCHEME_SELECT-->');
    if($_6i8Jj == '') return $_ILJjL;
    
    $_6i8Co = '';

    foreach($_j816L as $key => $_QltJO){
      $_6i8Co .= $_6i8Jj;
      $_6i8Co = str_replace('%colorText%', $_QltJO, $_6i8Co);
      $_6i8Co = str_replace('%colorValue%', $key, $_6i8Co);
      if ($_6i6LC == $key)
        $_6i8Co = str_replace('value="' . $_6i6LC . '"', 'value="' . $_6i6LC . '" selected="selected"', $_6i8Co);
    }
    
    $_ILJjL = _L81BJ($_ILJjL, '<!--COLORSCHEME_SELECT-->', '<!--/COLORSCHEME_SELECT-->', $_6i8Co);

    $_ILJjL = str_replace('name="TemplateFileName"', 'name="TemplateFileName" value="' . $_6i6Ot . '"', $_ILJjL);

    if ($_6i6LC != '')
       $_ILJjL = _LDL8B($_ILJjL, $_6i6LC, $_j816L);
       else{
        foreach($_j816L as $key => $_QltJO){ 
          $_ILJjL = _LDL8B($_ILJjL, $key, $_j816L);
          break;
        }   
       }
    return $_ILJjL;   
  }
  
  function _LDL8B($_6IoCL, $_6itIJ, $_j816L){
    global $_QLl1Q;

    if ($_6itIJ == ''){
      $_ILJjL = $_6IoCL;
      return $_ILJjL;
    }
    $_j8QJ8 = array();
    $_6itJo = array();
    //try
       $_6itCL = _L81DB($_6IoCL, '<style', '</style>');
       
      if ($_6itCL == ''){
        $_ILJjL = $_6IoCL;
        return $_ILJjL;
      }
       
      $_6itCL = '<style' . substr($_6itCL, 0, strpos($_6itCL, '>') + 1 );
       
       $_QLJfI = _L81DB($_6IoCL, $_6itCL, '</style>');
       if(strpos($_QLJfI, $_QLl1Q) !== false)
          $_j8QJ8 = explode($_QLl1Q, $_QLJfI);
          else{
            if(strpos($_QLJfI, "\n") !== false){
              $_QLJfI = str_replace("\r", "", $_QLJfI);
              $_j8QJ8 = explode("\n", $_QLJfI);
            }else{
              $_j8QJ8 = explode("\r", $_QLJfI);
            }
          }
       $_QLJfI = ""; 
       foreach($_j816L as $key => $_QltJO){
         $_6iO6j = $key;
         break;
       }

       for($_Qli6J=0; $_Qli6J<count($_j8QJ8); $_Qli6J++){
         if (strpos($_j8QJ8[$_Qli6J], '/*' . $_6iO6j) !== false && strpos($_j8QJ8[$_Qli6J], '*/') !== false){ 
            $_6iOCJ = substr($_j8QJ8[$_Qli6J], strpos($_j8QJ8[$_Qli6J], '/*' . $_6iO6j));
            $_6iOCJ = substr($_6iOCJ, strpos($_6iOCJ, ' '));
            $_6iOCJ = trim( substr($_6iOCJ, 0, strpos($_6iOCJ, '*/') ) );

            if (strpos($_j8QJ8[$_Qli6J], '/*' . $_6itIJ) !== false && strpos($_j8QJ8[$_Qli6J], '*/') !== false){
              $_j8j08 = substr($_j8QJ8[$_Qli6J], strpos($_j8QJ8[$_Qli6J], '/*' . $_6itIJ));
              $_j8j08 = substr($_j8j08, strpos($_j8j08, ' '));
              $_j8j08 = trim( substr($_j8j08, 0, strpos($_j8j08, '*/') ) );
            }
            else{
               // ever firstStyle
               $_j8j08 = $_6iOCJ;
            }

            $_6ioj8 = trim(substr($_j8QJ8[$_Qli6J], 0, strpos($_j8QJ8[$_Qli6J], ':') ));
            $_6iC0J = _LDJOD($_Qli6J, $_j8QJ8);

            // remove all commented styles
            $_j8QJ8[$_Qli6J] = substr($_j8QJ8[$_Qli6J], 0, strpos($_j8QJ8[$_Qli6J], '/*') - 1);
            $_j8QJ8[$_Qli6J] = str_replace($_6iOCJ, $_j8j08, $_j8QJ8[$_Qli6J]);

            $_6iC6L = explode(',', $_6iC0J);
            for($_QliOt=0; $_QliOt < count($_6iC6L); $_QliOt++){
              $_6iC6L[$_QliOt] = trim($_6iC6L[$_QliOt]);
              if ($_6iC6L[$_QliOt] == '') continue;
              $_6itJo[] = sprintf('%s;%s;%s;%s', $_6iC6L[$_QliOt], $_6ioj8, $_6iOCJ, $_j8j08);
            }
         }
       }

      $_ILJjL = _L8Q6B($_6IoCL, $_6itCL, '</style>', join($_QLl1Q, $_j8QJ8));
      if (count($_6itJo)){
       /*
          body;color;#4e4a47;#000000
          table;color;#4e4a47;#000000
          td;color;#4e4a47;#000000
          a;color;#4e4a47;#000000
          span;color;#4e4a47;#000000
          div;color;#4e4a47;#000000
          select;color;#4e4a47;#000000
          a;color;#4a4a4a;#000000
          .AltBrowserLinkText;color;#4e4a47;#000000
          .AltBrowserLink;color;#4a4a4a;#000000
          .subheadline;background-color;#c0c0c0;#000000
          .subheadline;color;#4e4a47;#FFFFFF
          .imprint;background-color;#4a4a4a;#000000
          .imprintText;color;#FFFFFE;#FFFFFF
          .imprintLink;color;#c0c0c0;#c0c0c0
          a.orderButton;color;#FFFFFF;#FFFFFF
          a.orderButton;border;#4a4a4a 10px solid;#000000 10px solid
          a.orderButton;background-color;#4a4a4a;#4a4a4a
          td.content;color;#4e4a47;#000000
       */

       $_ILL61 = substr($_ILJjL, strpos($_ILJjL, '</head>'));

       $_6iCCi = explode('<', $_ILL61);
       
       for($_Qli6J=0; $_Qli6J<count($_6itJo); $_Qli6J++){
         $_6ii1l = explode(';', $_6itJo[$_Qli6J]);
         if (strpos($_6ii1l[0], '.') === false && strpos($_6ii1l[0], '#') === false){ 
           // it's a HTML Tag
           for($_QliOt=0; $_QliOt<count($_6iCCi); $_QliOt++){
             if (stripos($_6iCCi[$_QliOt] . ' ', $_6ii1l[0]) !== false && stripos($_6iCCi[$_QliOt] . ' ', $_6ii1l[0]) == 0){
               $_6j11L = $_6iCCi[$_QliOt];

               $_6j11L = _LD61C($_6j11L, $_6ii1l);

               $_6iCCi[$_QliOt] = $_6j11L;
             }
           }
         } // If (Pos('.', CSS[0]) = 0) And (Pos('#', CSS[0]) = 0) Then
         else
          if (strpos($_6ii1l[0], '.') !== false && strpos($_6ii1l[0], '.') == 0){
           // it's a class=
          for($_QliOt=0; $_QliOt<count($_6iCCi); $_QliOt++){
             $_QliLj = _LDJLD('class', $_6iCCi[$_QliOt]);
             if ($_QliLj == '') continue;

             _LD6JL($_QliLj);

             $_6j11L = strtolower(substr($_6ii1l[0], 1));

             if ($_QliLj == '"' . $_6j11L . '"' ||  // class="name"
                strpos(' ' . $_QliLj . ' ', $_6j11L) > 0 ||  // class=" name "
                strpos(' ' . $_QliLj . '"', $_6j11L) > 0 || // class=" name"
                strpos('"' . $_QliLj . ' ', $_6j11L) > 0  // class="name "
                )
             {
               $_6j11L = $_6iCCi[$_QliOt];

               $_6j11L = _LD61C($_6j11L, $_6ii1l);

               $_6iCCi[$_QliOt] = $_6j11L;
             }
           }
          } // If (Pos('.', CSS[0]) = 1) Then
          else
           if (strpos($_6ii1l[0], '#') !== false && strpos($_6ii1l[0], '#') == 0){
              // it's a id=
             for($_QliOt=0; $_QliOt<count($_6iCCi); $_QliOt++){
               $id = _LDJLD('id', $_6iCCi[$_QliOt]);
               if (id == '') continue;

               while (strpos($id, '" ') !== false)
                 $id = str_replace('" ', '"', $id);
               while (strpos($id, ' "') !== false)
                 $id = str_replace(' "', '"', $id);

               $_6j11L = strtolower(substr($_6ii1l[0], 1));

               if ($id == '"' . $_6j11L . '"')   // id="id"
               {
                 $_6j11L = $_6iCCi[$_QliOt];

                 $_6j11L = _LD61C($_6j11L, $_6ii1l);

                 $_6iCCi[$_QliOt] = $_6j11L;
               }
             }
           } // If (Pos('#', CSS[0]) = 1) Then
           else
           if (strpos($_6ii1l[0], '.') !== false ){
             // it's a <tag>.<className>
             $_j8OLI = explode('.', $_6ii1l[0]);
             for($_QliOt=0; $_QliOt<count($_6iCCi); $_QliOt++){
               if ( stripos($_6iCCi[$_QliOt] . ' ', $_j8OLI[0]) !== false && stripos($_6iCCi[$_QliOt] . ' ', $_j8OLI[0]) == 0){

                 $_QliLj = _LDJLD('class', $_6iCCi[$_QliOt]);
                 if ($_QliLj == '') continue;
                 _LD6JL($_QliLj);

                 $_6j11L = strtolower($_j8OLI[1]);

                 if ($_QliLj == '"' . $_6j11L . '"' ||  // class="name"
                    strpos(' ' . $_QliLj . ' ', $_6j11L) > 0 ||  // class=" name "
                    strpos(' ' . $_QliLj . '"', $_6j11L) > 0 || // class=" name"
                    strpos('"' . $_QliLj . ' ', $_6j11L) > 0  // class="name "
                    )
                 {
                   $_6j11L = $_6iCCi[$_QliOt];

                   $_6j11L = _LD61C($_6j11L, $_6ii1l);

                   $_6iCCi[$_QliOt] = $_6j11L;
                 }

               }
             }
           }

       } // For I

       $_ILL61 = join('<', $_6iCCi);
       
       $_ILJjL = substr($_ILJjL, 0, strpos($_ILJjL, '</head>')  ) . $_ILL61;
       
      } 

      

    /*finally
      Style.Free;
      ElementsStyle.Free;
    end; */

    return $_ILJjL;
    
  }
  
  function _LDJOD($_6iifC, $_j8QJ8){
    $_ILJjL = '';
    $_6ii8C = -1;
    for($_Qli6J = $_6iifC; $_Qli6J >= 0; $_Qli6J--){
      if(strpos($_j8QJ8[$_Qli6J], '{') !== false){
        $_6ii8C = $_Qli6J;
        break;
      }
    }

    if($_6ii8C == -1) return $_ILJjL;

    /*we support ONLY

     body, table, td, a, span, div, select {

     NOT

     body,
     table,
     td,
     a,
     span,
     div,
     select {

    } */
    $_ILJjL = trim(substr($_j8QJ8[$_6ii8C], 0, strpos($_j8QJ8[$_6ii8C], '{') ));
    return $_ILJjL;
  }

  function _LDJLD($_6iiLI, $_6IoCL){
    $_QlOjt = stripos($_6IoCL, $_6iiLI . '="');
    if ($_QlOjt !== false){
       $_ILJjL = substr($_6IoCL, $_QlOjt);
       $_ILJjL = substr($_ILJjL, 0, strpos($_ILJjL, '"', strlen($_6iiLI . '="') + 1) + 1 );
    }
    else
     $_ILJjL = '';
    return $_ILJjL; 
  }

  function _LD61C($_6IoCL, $_6ii1l){

     $_6iL88 = array(' ', Ord(9), "\r", "\n");

     $_ILJjL = $_6IoCL;

     // HTML color="" bgcolor=""
     if ( strtolower($_6ii1l[1]) == 'color'){
          for($_Jto6L = 0; $_Jto6L < count($_6iL88); $_Jto6L++)
             $_ILJjL = str_replace($_6iL88[$_Jto6L] . 'color="' . $_6ii1l[2] . '"', $_6iL88[$_Jto6L] . 'color="' . $_6ii1l[3] . '"', $_ILJjL);
        }
        else
          for($_Jto6L = 0; $_Jto6L < count($_6iL88); $_Jto6L++){
            $_ILJjL = str_replace($_6iL88[$_Jto6L] . 'bgcolor="' . $_6ii1l[2] . '"', $_6iL88[$_Jto6L] . 'bgcolor="' . $_6ii1l[3] . '"', $_ILJjL);
            $_ILJjL = str_replace($_6iL88[$_Jto6L] . 'strokecolor="' . $_6ii1l[2] . '"', $_6iL88[$_Jto6L] . 'strokecolor="' . $_6ii1l[3] . '"', $_ILJjL);
            $_ILJjL = str_replace($_6iL88[$_Jto6L] . 'fillcolor="' . $_6ii1l[2] . '"', $_6iL88[$_Jto6L] . 'fillcolor="' . $_6ii1l[3] . '"', $_ILJjL);
          }

     $_jJo0t = _LDJLD('style', $_ILJjL);
     if ($_jJo0t != ""){
       $_6il8j = $_jJo0t;

       $_jJo0t = substr($_jJo0t, strpos($_jJo0t, '"') + 1);
       $_jJo0t = substr($_jJo0t, 0, strpos($_jJo0t, '"') );

       // text-align: left; margin: 10px; padding: 12px
       $_IoLOO = explode(';', $_jJo0t);

       if (count($_IoLOO) <= 1) return $_ILJjL;
       for($_Qli6J=0; $_Qli6J<count($_IoLOO); $_Qli6J++){
         if($_IoLOO[$_Qli6J] == "") 
           unset($_IoLOO[$_Qli6J]);
       }

       foreach($_IoLOO as $_Qli6J => $_QltJO){
         $_j8OLI = explode(':', $_IoLOO[$_Qli6J]);
         if (count($_j8OLI) <> 2) continue;
         $_j8OLI[0] = trim($_j8OLI[0]);
         if ( $_6ii1l[1] == $_j8OLI[0] || strtolower($_6ii1l[1]) == strtolower($_j8OLI[0]) ){
           $_IoLOO[$_Qli6J] = $_6ii1l[1] . ': ' . $_6ii1l[3];
         }
       }                        
       $_jJo0t = 'style="' . join(';', $_IoLOO) . ';"';

       $_ILJjL = str_replace($_6il8j, $_jJo0t, $_ILJjL);
       }

       return $_ILJjL;
       
     }

  function _LD6JL(&$_QliLj){
   while (strpos($_QliLj, '  ') !== false)
     $_QliLj = str_replace('  ', ' ', $_QliLj);

   $_QliLj = substr($_QliLj, strpos($_QliLj, '"'));
   $_QliLj = strtolower(substr($_QliLj, 0, strpos($_QliLj, '"', 1) + 1));

   while (strpos($_QliLj, '" ') !== false)
     $_QliLj = str_replace('" ', '"', $_QliLj);
   while (strpos($_QliLj, ' "') !== false)
     $_QliLj = str_replace(' "', '"', $_QliLj);
  }
  
?>
