<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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
  include_once(PEAR_PATH . "URL.php");

  $_Itfj8 = "";
  // Boolean fields of form
  $_ItI0o = Array();
  $_ItIti = Array();
  $errors = array();

  if(isset($_POST['FormId'])) // Formular speichern?
    $_jQ1il = intval($_POST['FormId']);
  else
    if ( isset($_POST['OneFormListId']) )
       $_jQ1il = intval($_POST['OneFormListId']);
  if(!isset($_jQ1il)) {
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

  if(!_LAEJL($OneMailingListId)){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  // mailinglist data
  $_QLfol = "SELECT Name, FormsTableName, GroupsTableName, forms_id FROM $_QL88I WHERE id=$OneMailingListId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  $_jQQOO = $_QLO0f["Name"];
  $_IfJoo = $_QLO0f["FormsTableName"];
  $_QljJi = $_QLO0f["GroupsTableName"];
  $_6008l = $_QLO0f["forms_id"] == $_jQ1il;
  mysql_free_result($_QL8i1);

  // form data
  $_QLfol = "SELECT $_IfJoo.*, $_Ifi1J.*, $_I1tQf.Theme FROM $_IfJoo LEFT JOIN $_Ifi1J ON $_Ifi1J.id=$_IfJoo.messages_id LEFT JOIN $_I1tQf ON $_I1tQf.id=$_IfJoo.ThemesId WHERE $_IfJoo.id=$_jQ1il";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Jj08l = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);
  $_j1IIf = $_Jj08l["OverrideSubUnsubURL"];

  if(isset($_SERVER['HTTP_REFERER'])){
    $url = new Net_URL($_SERVER['HTTP_REFERER'], false);

    $_600tt = new Net_URL(ScriptBaseURL, false);

    if($url->host != $_600tt->host)
      $_Itfj8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ScriptURLDifferentFromReferer"], $AppName, $url->host ." <> ". $_600tt->host);
      else
      if($url->protocol != $_600tt->protocol)
          $_Itfj8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["ScriptURLDifferentFromReferer"], $AppName, $url->protocol."://".$url->host ." <> ". $_600tt->protocol."://".$_600tt->host);

    $url = null;
    $_600tt = null;
  }


  if(isset($_POST["HTMLFormNextBtn"])) {
     $_I6tLJ = $_POST;
     $_I6tLJ["MailingListId"] = $OneMailingListId;
     $_I6tLJ["FormId"] = $_jQ1il;
     _L6FEC($_I6tLJ);
     if($_I6tLJ["InternalForm"] == 1) {
        $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_jQQOO." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000072"], $_Itfj8, 'formcode', 'formcode_internal_snipped.htm');
        $_I6tLJ["INTERNAL_LINK"] = (!empty($_j1IIf) ? $_j1IIf.$_J1tCf : $_J1OIO)."?ML=$OneMailingListId&F=$_jQ1il";

        if($_I6tLJ["FormType"] == "Edit")
          $_I6tLJ["INTERNAL_LINK"] .= "&HTMLForm=editform";
        if($_I6tLJ["FormType"] == "UnSub")
          $_I6tLJ["INTERNAL_LINK"] .= "&HTMLForm=unsubform";
        if($_I6tLJ["FormType"] == "SubSub")
          $_I6tLJ["INTERNAL_LINK"] .= "&HTMLForm=subform";

        $_QLJfI = str_replace('http://INTERNAL_LINK', $_I6tLJ["INTERNAL_LINK"], $_QLJfI);
       }
       else {
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_jQQOO." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000073"], $_Itfj8, 'formcode', 'formcode_external_snipped.htm');
         $_QLJfI = _LJA6C($_QLJfI, true);
         $HTMLForm = "";
         if($_I6tLJ["FormType"] == "Edit")
           $HTMLForm = "editform";
         if($_I6tLJ["FormType"] == "UnSub")
           $HTMLForm = "unsubform";
         if($_I6tLJ["FormType"] == "SubSub")
           $HTMLForm = "subform";

         $_601IC = _J0Q81($_I6tLJ, $_Jj08l, $HTMLForm, true, $_I6tLJ["ExternalFormEncoding"]);
         $_601IC = substr($_601IC, strpos($_601IC, "<form"));
         $_601IC = substr($_601IC, 0, strpos($_601IC, "</form>") + 7);
         $_601IC = str_replace('name="MailingListId"', 'name="MailingListId" value="'.$OneMailingListId.'" ', $_601IC);
         $_601IC = str_replace('name="FormId"', 'name="FormId" value="'.$_jQ1il.'" ', $_601IC);
         $_601IC = str_replace('<input type="hidden" name="HTMLForm" />', '', $_601IC);
         $_601IC = str_replace('id="SubscribeUnsubscribeForm" ', '', $_601IC);
         $_601IC = str_replace('name="SubscribeUnsubscribeForm" ', '', $_601IC);
         $_601IC = str_replace('class="FormTable" ', '', $_601IC);
         $_601IC = str_replace('class="SubscribeColumn"', '', $_601IC);
         $_601IC = str_replace('class="SubscribeColumn SubscribeAction"', '', $_601IC);
         $_601IC = str_replace('class="SubscribeAction"', '', $_601IC);
         $_601IC = str_replace('class="SubscribeBtnTD"', '', $_601IC);
         $_601IC = str_replace('class="PrivacyPolicyTD"', '', $_601IC);
         $_601IC = str_replace(' class="PrivacyPolicy"', '', $_601IC);
         $_601IC = str_replace(' id="PrivacyPolicy"', '', $_601IC);
         $_601IC = str_replace(' style="max-width: 420px;"', '', $_601IC);
         $_601IC = str_replace('style="font-size: inherit;"', '', $_601IC);
         $_601IC = str_replace('<fieldset>', '<input type="hidden" name="FormEncoding" value="'.$_I6tLJ["ExternalFormEncoding"].'" />', $_601IC);
         $_601IC = str_replace('</fieldset>', '', $_601IC);
         $_601IC = str_replace('action="./defaultnewsletter.php"', 'action="'.(!empty($_j1IIf) ? $_j1IIf.$_J1OLl : $_J1Cf8).'"', $_601IC);
         $_601IC = _LA8F6($_601IC);
         if($HTMLForm == "")
           $_601IC = str_replace('name="Action" value="subscribe"', 'name="Action" value="subscribe" checked="checked"', $_601IC);
         $_601IC = str_replace('./captcha/', ScriptBaseURL.'captcha/', $_601IC);
         if($HTMLForm == "editform")
            $_601IC = str_ireplace('name="Action"', 'name="Action" value="edit"', $_601IC);

         while(!strpos($_601IC, "\t") === false) {
           $_601IC = str_replace("\t", '', $_601IC);
         }
         while(!strpos($_601IC, "  ") === false) {
           $_601IC = str_replace("  ", ' ', $_601IC);
         }
         while(!strpos($_601IC, $_QLl1Q) === false) {
           $_601IC = str_replace($_QLl1Q, '', $_601IC);
         }

         while(!strpos($_601IC, ">  <") === false) {
           $_601IC = str_replace(">  <", '><', $_601IC);
         }
         while(!strpos($_601IC, "> <") === false) {
           $_601IC = str_replace("> <", '><', $_601IC);
         }
         $_I1OoI = explode(">", $_601IC);
         for($_Qli6J=0;$_Qli6J<count($_I1OoI);$_Qli6J++) {
           if(trim($_I1OoI[$_Qli6J]) == "")
             unset($_I1OoI[$_Qli6J]);
             else
             $_I1OoI[$_Qli6J] = ltrim($_I1OoI[$_Qli6J]).">";
         }
         $_601IC = join($_QLl1Q, $_I1OoI).$_QLl1Q;


         if($_Jj08l["UseCaptcha"]) {
             $_QLJfI = _L80DF($_QLJfI, "<TABLE:NOT_CAPTCHA>", "</TABLE:NOT_CAPTCHA>");

             $_601IC = '<?php'.
             $_QLl1Q.
             '@session_cache_limiter("public");'.$_QLl1Q.
             'if(!ini_get("session.auto_start")) {@session_start();}'.$_QLl1Q.
             'header(\'P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"\');'.$_QLl1Q.
             'require_once "'.InstallPath.'captcha/require/config.php";'.$_QLl1Q.
             'require_once "'.InstallPath.'captcha/require/crypt.class.php";'.$_QLl1Q.
             '$GLOBALS["crypt_class"] = new crypt_class();'.$_QLl1Q.
             '?>'.$_QLl1Q.
             $_601IC;

           }
           else
           if($_Jj08l["UseReCaptcha"]) {
             $_QLJfI = _L80DF($_QLJfI, "<TABLE:CAPTCHA>", "</TABLE:CAPTCHA>");

             /*recaptchav1  $_601IC = 'php'.
             $_QLl1Q.
             'require_once "'.InstallPath.'captcha/recaptcha/recaptchalib.php";'.$_QLl1Q.
             ''.$_QLl1Q.
             $_601IC; */

             if(strpos($_601IC, "recaptcha/api.js") === false) {
                $_601IC = '<script src="https://www.google.com/recaptcha/api.js"></script>'.$_QLl1Q.$_601IC;
             }
           } 
           else
           if($_Jj08l["UseReCaptchav3"]) {
             $_QLJfI = _L80DF($_QLJfI, "<TABLE:CAPTCHA>", "</TABLE:CAPTCHA>");
             
             $_Iljoj = '<script src="https://www.google.com/recaptcha/api.js?render=' . $_Jj08l["PublicReCaptchaKey"] . '"></script>';

             $_601IC .= $_QLl1Q.$_Iljoj;

             $_Iljoj = '';

             $_Iljoj .= '<script><!--' . $_QLl1Q. '
                function ReCaptchaOnSubmit(){
                  grecaptcha.ready(function() {' . $_QLl1Q. '
                      grecaptcha.execute("' . $_Jj08l["PublicReCaptchaKey"] . '", {action: "newslettersubunsub"}).then(function(token) {' . $_QLl1Q. '
                        document.getElementById("g-recaptcha-response").value=token; ' . $_QLl1Q. '
                      });' . $_QLl1Q. '
                  });' . $_QLl1Q. '

                  return true;
                }
                var forms = document.getElementsByTagName("form");
                for(var j=0; j<forms.length; j++) {
                  forms[j].onsubmit = ReCaptchaOnSubmit;
                }
                ReCaptchaOnSubmit();
                
                --></script>';
             $_601IC .= $_QLl1Q.$_Iljoj;

           } 
            else
               $_QLJfI = _L80DF($_QLJfI, "<TABLE:CAPTCHA>", "</TABLE:CAPTCHA>");

         $_601IC = str_replace("<CAPTCHA:INTERNAL>", "", $_601IC);
         $_601IC = str_replace("</CAPTCHA:INTERNAL>", "", $_601IC);
         $_601IC = str_replace("<TABLE:NOT_CAPTCHA>", "", $_601IC);
         $_601IC = str_replace("</TABLE:NOT_CAPTCHA>", "", $_601IC);
         $_601IC = str_replace("<TABLE:CAPTCHA>", "", $_601IC);
         $_601IC = str_replace("</TABLE:CAPTCHA>", "", $_601IC);

         $_I6tLJ["HTMLCode"] = htmlentities( $_601IC );
       }

       $_I6tLJ["UNSUBSCRIBE_LINK_HTML"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkHTML"], (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?ML=$OneMailingListId&F=$_jQ1il&EMail=[EMail]");
       $_I6tLJ["UNSUBSCRIBE_LINK_TEXT"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkTEXT"], (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?ML=$OneMailingListId&F=$_jQ1il&EMail=[EMail]");

       $_I6tLJ["UNSUBSCRIBE_LINK_HTML_NAME"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkHTML"], (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?ML=". rawurlencode($_jQQOO) ."&F=$_jQ1il&EMail=[EMail]");
       $_I6tLJ["UNSUBSCRIBE_LINK_TEXT_NAME"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkTEXT"], (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?ML=". rawurlencode($_jQQOO) ."&F=$_jQ1il&EMail=[EMail]");

       $_I6tLJ["UNSUBSCRIBE_LINK_HTML_IDENTSTRING"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkHTML"], (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?key=[IdentString]");
       $_I6tLJ["UNSUBSCRIBE_LINK_TEXT_IDENTSTRING"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultUnsSubscribeLinkTEXT"], (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?key=[IdentString]");

       $_I6tLJ["EDIT_LINK_HTML_IDENTSTRING"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditLinkHTML"], (!empty($_j1IIf) ? $_j1IIf.$_J1tCf : $_J1OIO)."?key=[IdentString]&HTMLForm=editform&ML=$OneMailingListId&F=$_jQ1il");
       $_I6tLJ["EDIT_LINK_TEXT_IDENTSTRING"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditLinkTEXT"], (!empty($_j1IIf) ? $_j1IIf.$_J1tCf : $_J1OIO)."?key=[IdentString]&HTMLForm=editform&ML=$OneMailingListId&F=$_jQ1il");

       $_I6tLJ["EDIT_LINK_HTML_EMAIL"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditLinkHTML"], (!empty($_j1IIf) ? $_j1IIf.$_J1tCf : $_J1OIO)."?u_EMail=[EMail]&HTMLForm=editform&ML=$OneMailingListId&F=$_jQ1il");
       $_I6tLJ["EDIT_LINK_TEXT_EMAIL"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DefaultEditLinkTEXT"], (!empty($_j1IIf) ? $_j1IIf.$_J1tCf : $_J1OIO)."?u_EMail=[EMail]&HTMLForm=editform&ML=$OneMailingListId&F=$_jQ1il");

  } else {
     $_I6tLJ = $_Jj08l;
     $_I6tLJ["MailingListId"] = $OneMailingListId;
     $_I6tLJ["FormId"] = $_jQ1il;

     $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_jQQOO." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000071"], $_Itfj8, 'formcode', 'formcode1_snipped.htm');

     // mail encodings
     $_QLoli = "";
     if ( iconvExists || mbfunctionsExists ) {
       reset($_Ijt8j);
       foreach($_Ijt8j as $key => $_QltJO) {
          $_QLoli .= '<option value="'.$key.'">'.$_QltJO.'</option>'.$_QLl1Q;
       }
     }
     $_QLJfI = _L81BJ($_QLJfI, "<MAILENCODINGS>", "</MAILENCODINGS>", $_QLoli);
     //

  }

  if(!isset($_I6tLJ["FormType"]))
    $_I6tLJ["FormType"] = "SubUnSub";

  $_QLJfI = _L8AOB($errors, $_I6tLJ, $_QLJfI);

  $_QLJfI = str_replace('?MailingListId=', '?MailingListId='.$OneMailingListId, $_QLJfI);
  $_QLJfI = str_replace('PRODUCTAPPNAME', $AppName, $_QLJfI);

  $_QLJfI = str_replace("<TABLE:NOT_CAPTCHA>", "", $_QLJfI);
  $_QLJfI = str_replace("</TABLE:NOT_CAPTCHA>", "", $_QLJfI);
  $_QLJfI = str_replace("<TABLE:CAPTCHA>", "", $_QLJfI);
  $_QLJfI = str_replace("</TABLE:CAPTCHA>", "", $_QLJfI);

  print $_QLJfI;

  function _L6FEC($_I6tLJ) {
    global $_IfJoo, $_jQ1il;
    global $_ItI0o, $_ItIti, $_QLttI;

    $_Iflj0 = array();
    _L8EOB($_IfJoo, $_Iflj0);

    $_QLfol = "UPDATE `$_IfJoo` SET ";

    $_Io01j = array();
    for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
      $key = $_Iflj0[$_Qli6J];
      if ( isset($_I6tLJ[$_Iflj0[$_Qli6J]]) ) {
        if(in_array($key, $_ItI0o))
          if( $_I6tLJ[$key] == "1" || intval($_I6tLJ[$key]) == 0 ) {
             $_Io01j[] = "`$key`=1";
             }
             else
              ;
        else {
           $_Io01j[] = "`$key`="._LRAFO(rtrim($_I6tLJ[$key]));
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
    $_QLfol .= " WHERE `id`=".intval($_jQ1il);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
  }

?>
