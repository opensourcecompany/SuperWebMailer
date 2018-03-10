<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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
  include_once("newslettersubunsub_ops.inc.php");
  include_once("PEAR/URL.php");

  $_I0600 = "";
  // Boolean fields of form
  $_I01C0 = Array();
  $_I01lt = Array();
  $errors = array();

  if(isset($_POST['FormId'])) // Formular speichern?
    $_I8jJ8 = intval($_POST['FormId']);
  else
    if ( isset($_POST['OneFormListId']) )
       $_I8jJ8 = intval($_POST['OneFormListId']);
  if(!isset($_I8jJ8)) {
    include_once("browseforms.php");
    exit;
  }

  if(isset($_POST['FormId']))
    $_POST['FormId'] = intval($_POST['FormId']);
  if(isset($_POST['OneFormListId']))
    $_POST['OneFormListId'] = intval($_POST['OneFormListId']);

  if (isset($_GET["MailingListId"]) )
     $OneMailingListId = intval($_GET["MailingListId"]);
     else
     if(isset($_POST['OneMailingListId']))
       $OneMailingListId = intval($_POST['OneMailingListId']);
     else
      if(isset($_POST['MailingListId']))
        $OneMailingListId = intval($_POST['MailingListId']);
        else {
          include_once("browseforms.php");
          exit;
        }

  if(isset($_POST['OneMailingListId']))
    $_POST['OneMailingListId'] = intval($_POST['OneMailingListId']);
  if(isset($_POST['MailingListId']))
    $_POST['MailingListId'] = intval($_POST['MailingListId']);

  if(!_OCJCC($OneMailingListId)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  // mailinglist data
  $_QJlJ0 = "SELECT Name, FormsTableName, GroupsTableName, forms_id FROM $_Q60QL WHERE id=$OneMailingListId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6Q1C = mysql_fetch_array($_Q60l1);
  $_I8JQO = $_Q6Q1C["Name"];
  $_QLI8o = $_Q6Q1C["FormsTableName"];
  $_Q6t6j = $_Q6Q1C["GroupsTableName"];
  $_jlI8i = $_Q6Q1C["forms_id"] == $_I8jJ8;
  mysql_free_result($_Q60l1);

  // form data
  $_QJlJ0 = "SELECT $_QLI8o.*, $_QLo0Q.*, $_Q880O.Theme FROM $_QLI8o LEFT JOIN $_QLo0Q ON $_QLo0Q.id=$_QLI8o.messages_id LEFT JOIN $_Q880O ON $_Q880O.id=$_QLI8o.ThemesId WHERE $_QLI8o.id=$_I8jJ8";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_j6ioL = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);
  $_Iijft = $_j6ioL["OverrideSubUnsubURL"];

  if(isset($_SERVER['HTTP_REFERER'])){
    $_jlj0f = new Net_URL($_SERVER['HTTP_REFERER'], false);

    $_jljJL = new Net_URL(ScriptBaseURL, false);

    if($_jlj0f->host != $_jljJL->host)
      $_I0600 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ScriptURLDifferentFromReferer"], $AppName, $_jlj0f->host ." <> ". $_jljJL->host);
      else
      if($_jlj0f->protocol != $_jljJL->protocol)
          $_I0600 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ScriptURLDifferentFromReferer"], $AppName, $_jlj0f->protocol."://".$_jlj0f->host ." <> ". $_jljJL->protocol."://".$_jljJL->host);

    $_jlj0f = null;
    $_jljJL = null;
  }


  if(isset($_POST["HTMLFormNextBtn"])) {
     $_Qi8If = $_POST;
     $_Qi8If["MailingListId"] = $OneMailingListId;
     $_Qi8If["FormId"] = $_I8jJ8;
     _O8AR8($_Qi8If);
     if($_Qi8If["InternalForm"] == 1) {
        $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_I8JQO." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000072"], $_I0600, 'formcode', 'formcode_internal_snipped.htm');
        $_Qi8If["INTERNAL_LINK"] = (!empty($_Iijft) ? $_Iijft.$_jjiCt : $_jjLO0)."?ML=$OneMailingListId&F=$_I8jJ8";

        if($_Qi8If["FormType"] == "Edit")
          $_Qi8If["INTERNAL_LINK"] .= "&HTMLForm=editform";
        if($_Qi8If["FormType"] == "UnSub")
          $_Qi8If["INTERNAL_LINK"] .= "&HTMLForm=unsubform";
        if($_Qi8If["FormType"] == "SubSub")
          $_Qi8If["INTERNAL_LINK"] .= "&HTMLForm=subform";

        $_QJCJi = str_replace('http://INTERNAL_LINK', $_Qi8If["INTERNAL_LINK"], $_QJCJi);
       }
       else {
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_I8JQO." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000073"], $_I0600, 'formcode', 'formcode_external_snipped.htm');
         $HTMLForm = "";
         if($_Qi8If["FormType"] == "Edit")
           $HTMLForm = "editform";
         if($_Qi8If["FormType"] == "UnSub")
           $HTMLForm = "unsubform";
         if($_Qi8If["FormType"] == "SubSub")
           $HTMLForm = "subform";

         $_jlJf6 = _L0OPJ($_Qi8If, $_j6ioL, $HTMLForm, true);
         $_jlJf6 = substr($_jlJf6, strpos($_jlJf6, "<form"));
         $_jlJf6 = substr($_jlJf6, 0, strpos($_jlJf6, "</form>") + 7);
         $_jlJf6 = str_replace('name="MailingListId"', 'name="MailingListId" value="'.$OneMailingListId.'" ', $_jlJf6);
         $_jlJf6 = str_replace('name="FormId"', 'name="FormId" value="'.$_I8jJ8.'" ', $_jlJf6);
         $_jlJf6 = str_replace('<input type="hidden" name="HTMLForm" />', '', $_jlJf6);
         $_jlJf6 = str_replace('id="SubscribeUnsubscribeForm" ', '', $_jlJf6);
         $_jlJf6 = str_replace('name="SubscribeUnsubscribeForm" ', '', $_jlJf6);
         $_jlJf6 = str_replace('class="FormTable" ', '', $_jlJf6);
         $_jlJf6 = str_replace('class="SubscribeColumn"', '', $_jlJf6);
         $_jlJf6 = str_replace('class="SubscribeColumn SubscribeAction"', '', $_jlJf6);
         $_jlJf6 = str_replace('class="SubscribeAction"', '', $_jlJf6);
         $_jlJf6 = str_replace('class="SubscribeBtnTD"', '', $_jlJf6);
         $_jlJf6 = str_replace(' style="max-width: 420px;"', '', $_jlJf6);
         $_jlJf6 = str_replace('<fieldset>', '<input type="hidden" name="FormEncoding" value="'.$_Qi8If["ExternalFormEncoding"].'" />', $_jlJf6);
         $_jlJf6 = str_replace('</fieldset>', '', $_jlJf6);
         $_jlJf6 = str_replace('action="./defaultnewsletter.php"', 'action="'.(!empty($_Iijft) ? $_Iijft.$_jjlQ0 : $_jJ088).'"', $_jlJf6);
         if($HTMLForm == "")
           $_jlJf6 = str_replace('name="Action" value="subscribe"', 'name="Action" value="subscribe" checked="checked"', $_jlJf6);
         $_jlJf6 = str_replace('./captcha/', ScriptBaseURL.'captcha/', $_jlJf6);
         if($HTMLForm == "editform")
            $_jlJf6 = str_replace('"Action"', '"Action" value="edit"', $_jlJf6);

         while(!strpos($_jlJf6, "\t") === false) {
           $_jlJf6 = str_replace("\t", '', $_jlJf6);
         }
         while(!strpos($_jlJf6, "  ") === false) {
           $_jlJf6 = str_replace("  ", ' ', $_jlJf6);
         }
         while(!strpos($_jlJf6, $_Q6JJJ) === false) {
           $_jlJf6 = str_replace($_Q6JJJ, '', $_jlJf6);
         }

         while(!strpos($_jlJf6, ">  <") === false) {
           $_jlJf6 = str_replace(">  <", '><', $_jlJf6);
         }
         while(!strpos($_jlJf6, "> <") === false) {
           $_jlJf6 = str_replace("> <", '><', $_jlJf6);
         }
         $_Q8otJ = explode(">", $_jlJf6);
         for($_Q6llo=0;$_Q6llo<count($_Q8otJ);$_Q6llo++) {
           if(trim($_Q8otJ[$_Q6llo]) == "")
             unset($_Q8otJ[$_Q6llo]);
             else
             $_Q8otJ[$_Q6llo] = ltrim($_Q8otJ[$_Q6llo]).">";
         }
         $_jlJf6 = join($_Q6JJJ, $_Q8otJ).$_Q6JJJ;


         if($_j6ioL["UseCaptcha"]) {
             $_QJCJi = _OP6PQ($_QJCJi, "<TABLE:NOT_CAPTCHA>", "</TABLE:NOT_CAPTCHA>");

             $_jlJf6 = '<?php'.
             $_Q6JJJ.
             'header(\'P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"\');'.$_Q6JJJ.
             '@session_cache_limiter("public");'.$_Q6JJJ.
             'if(!ini_get("session.auto_start")) {@session_start();}'.$_Q6JJJ.
             'require_once "'.InstallPath.'captcha/require/config.php";'.$_Q6JJJ.
             'require_once "'.InstallPath.'captcha/require/crypt.class.php";'.$_Q6JJJ.
             '$GLOBALS["crypt_class"] = new crypt_class();'.$_Q6JJJ.
             '?>'.$_Q6JJJ.
             $_jlJf6;

           }
           else
           if($_j6ioL["UseReCaptcha"]) {
             $_QJCJi = _OP6PQ($_QJCJi, "<TABLE:CAPTCHA>", "</TABLE:CAPTCHA>");

             /*recaptchav1  $_jlJf6 = 'php'.
             $_Q6JJJ.
             'require_once "'.InstallPath.'captcha/recaptcha/recaptchalib.php";'.$_Q6JJJ.
             ''.$_Q6JJJ.
             $_jlJf6; */

             if(strpos($_jlJf6, "recaptcha/api.js") === false) {
                $_jlJf6 = '<script src="https://www.google.com/recaptcha/api.js"></script>'.$_Q6JJJ.$_jlJf6;
             }
           } else
             $_QJCJi = _OP6PQ($_QJCJi, "<TABLE:CAPTCHA>", "</TABLE:CAPTCHA>");

         $_jlJf6 = str_replace("<CAPTCHA:INTERNAL>", "", $_jlJf6);
         $_jlJf6 = str_replace("</CAPTCHA:INTERNAL>", "", $_jlJf6);
         $_jlJf6 = str_replace("<TABLE:NOT_CAPTCHA>", "", $_jlJf6);
         $_jlJf6 = str_replace("</TABLE:NOT_CAPTCHA>", "", $_jlJf6);
         $_jlJf6 = str_replace("<TABLE:CAPTCHA>", "", $_jlJf6);
         $_jlJf6 = str_replace("</TABLE:CAPTCHA>", "", $_jlJf6);

         $_Qi8If["HTMLCode"] = htmlentities( $_jlJf6 );
       }

       $_Qi8If["UNSUBSCRIBE_LINK_HTML"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkHTML"], (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?ML=$OneMailingListId&F=$_I8jJ8&EMail=[EMail]");
       $_Qi8If["UNSUBSCRIBE_LINK_TEXT"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkTEXT"], (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?ML=$OneMailingListId&F=$_I8jJ8&EMail=[EMail]");

       $_Qi8If["UNSUBSCRIBE_LINK_HTML_NAME"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkHTML"], (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?ML=". rawurlencode($_I8JQO) ."&F=$_I8jJ8&EMail=[EMail]");
       $_Qi8If["UNSUBSCRIBE_LINK_TEXT_NAME"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkTEXT"], (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?ML=". rawurlencode($_I8JQO) ."&F=$_I8jJ8&EMail=[EMail]");

       $_Qi8If["UNSUBSCRIBE_LINK_HTML_IDENTSTRING"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkHTML"], (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?key=[IdentString]");
       $_Qi8If["UNSUBSCRIBE_LINK_TEXT_IDENTSTRING"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkTEXT"], (!empty($_Iijft) ? $_Iijft.$_jjlC6 : $_jJ1Il)."?key=[IdentString]");

       $_Qi8If["EDIT_LINK_HTML_IDENTSTRING"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditLinkHTML"], (!empty($_Iijft) ? $_Iijft.$_jjiCt : $_jjLO0)."?key=[IdentString]&HTMLForm=editform&ML=$OneMailingListId&F=$_I8jJ8");
       $_Qi8If["EDIT_LINK_TEXT_IDENTSTRING"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditLinkTEXT"], (!empty($_Iijft) ? $_Iijft.$_jjiCt : $_jjLO0)."?key=[IdentString]&HTMLForm=editform&ML=$OneMailingListId&F=$_I8jJ8");

       $_Qi8If["EDIT_LINK_HTML_EMAIL"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditLinkHTML"], (!empty($_Iijft) ? $_Iijft.$_jjiCt : $_jjLO0)."?u_EMail=[EMail]&HTMLForm=editform&ML=$OneMailingListId&F=$_I8jJ8");
       $_Qi8If["EDIT_LINK_TEXT_EMAIL"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditLinkTEXT"], (!empty($_Iijft) ? $_Iijft.$_jjiCt : $_jjLO0)."?u_EMail=[EMail]&HTMLForm=editform&ML=$OneMailingListId&F=$_I8jJ8");

  } else {
     $_Qi8If = $_j6ioL;
     $_Qi8If["MailingListId"] = $OneMailingListId;
     $_Qi8If["FormId"] = $_I8jJ8;

     $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_I8JQO." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000071"], $_I0600, 'formcode', 'formcode1_snipped.htm');

     // mail encodings
     $_Q6ICj = "";
     if ( function_exists('iconv') || function_exists('mb_convert_encoding') ) {
       reset($_Qo8OO);
       foreach($_Qo8OO as $key => $_Q6ClO) {
          $_Q6ICj .= '<option value="'.$key.'">'.$_Q6ClO.'</option>'.$_Q6JJJ;
       }
     }
     $_QJCJi = _OPR6L($_QJCJi, "<MAILENCODINGS>", "</MAILENCODINGS>", $_Q6ICj);
     //

  }

  if(!isset($_Qi8If["FormType"]))
    $_Qi8If["FormType"] = "SubUnSub";

  $_QJCJi = _OPFJA($errors, $_Qi8If, $_QJCJi);

  $_QJCJi = str_replace('?MailingListId=', '?MailingListId='.$OneMailingListId, $_QJCJi);
  $_QJCJi = str_replace('PRODUCTAPPNAME', $AppName, $_QJCJi);

  print $_QJCJi;

  function _O8AR8($_Qi8If) {
    global $_QLI8o, $_I8jJ8;
    global $_I01C0, $_I01lt, $_Q61I1;

    $_QLLjo = array();
    _OAJL1($_QLI8o, $_QLLjo);

    $_QJlJ0 = "UPDATE `$_QLI8o` SET ";

    $_I1l61 = array();
    for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
      $key = $_QLLjo[$_Q6llo];
      if ( isset($_Qi8If[$_QLLjo[$_Q6llo]]) ) {
        if(in_array($key, $_I01C0))
          if( $_Qi8If[$key] == "1" || intval($_Qi8If[$key]) == 0 ) {
             $_I1l61[] = "`$key`=1";
             }
             else
              ;
        else {
           $_I1l61[] = "`$key`="._OPQLR(rtrim($_Qi8If[$key]));
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
    $_QJlJ0 .= " WHERE `id`=".intval($_I8jJ8);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
  }

?>
