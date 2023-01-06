<?php
/*
Plugin Name: WordPress Newsletteranmeldung und Newsletterabmeldung fuer SuperWebMailer
Plugin URI: http://www.superwebmailer.de/wordpress
Description: Newsletteranmeldungen und Newsletterabmeldungen mit SuperWebMailer fuer WordPress
Version: 1.0.3
Author: Mirko Boeer
Author URI: http://www.superwebmailer.de/
*/

error_reporting(0);

// register_activation_hook needs global
global $DEFAULT_INTERFACE_LANGUAGE, $fieldnames, $resourcestrings;

$DEFAULT_INTERFACE_LANGUAGE = "de";

 $fieldnames = array(
  "de" => array(
          'u_EMail' => 'E-Mail-Adresse',
          'u_EMailFormat' => 'E-Mail-Format',
          'u_CustomerNo' => 'Kundennummer',
          'u_Firm' => 'Firma',
          'u_Gender' => 'Geschlecht',
          'u_Salutation' => 'Anrede',
          'u_Profession' => 'Akademischer Grad',
          'u_FirstName' => 'Vorname',
          'u_MiddleName' => '2. Vorname',
          'u_LastName' => 'Nachname',
          'u_CellNumber' => 'Mobilfunknummer',
          'u_Birthday' => 'Geburtsdatum',
          'u_MessengerICQ' => 'Messenger ICQ',
          'u_MessengerMSN' => 'Messenger MSN',
          'u_MessengerYAHOO' => 'Messenger YAHOO',
          'u_MessengerAOL' => 'Messenger AOL',
          'u_MessengerOther' => 'Messenger anderer',
          'u_PrivateStreet' => 'Stra&szlig;e',
          'u_PrivateZIPCode' => 'PLZ',
          'u_PrivateCity' => 'Stadt',
          'u_PrivateState' => 'Bundesland',
          'u_PrivateCountry' => 'Land',
          'u_PrivateWebsite' => 'Webseite',
          'u_PrivateTelephone' => 'Telefonnummer',
          'u_PrivateFax' => 'Faxnummer',
          'u_BusinessStreet' => 'Stra&szlig;e gesch&auml;ftlich',
          'u_BusinessZIPCode' => 'PLZ gesch&auml;ftlich',
          'u_BusinessCity' => 'Stadt gesch&auml;ftlich',
          'u_BusinessState' => 'Bundesland gesch&auml;ftlich',
          'u_BusinessCountry' => 'Land gesch&auml;ftlich',
          'u_BusinessWebsite' => 'Webseite gesch&auml;ftlich',
          'u_BusinessTelephone' => 'Telefonnummer',
          'u_BusinessFax' => 'Faxnummer',
          'u_BusinessPosition' => 'Position',
          'u_BusinessDepartment' => 'Abteilung',
          'u_Comments' => 'Kommentare',
          'u_Username' => 'Benutzername',
          'u_Password' => 'Kennwort',
          'u_Language' => 'Sprache',
          'u_UserFieldString1' => 'Zeichenkette 1',
          'u_UserFieldString2' => 'Zeichenkette 2',
          'u_UserFieldString3' => 'Zeichenkette 3',
          'u_UserFieldInt1' => 'Ganzzahl 1',
          'u_UserFieldInt2' => 'Ganzzahl 2',
          'u_UserFieldInt3' => 'Ganzzahl 3',
          'u_UserFieldBool1' => 'Logisches Feld 1',
          'u_UserFieldBool2' => 'Logisches Feld 2',
          'u_UserFieldBool3' => 'Logisches Feld 3',
          'u_PersonalizedTracking' => 'Personalisiertes Tracking erlaubt'
          ),

  "en" => array(
          'u_EMail' => 'Email address',
          'u_EMailFormat' => 'Email format',
          'u_CustomerNo' => 'Customer number',
          'u_Firm' => 'Firm',
          'u_Gender' => 'Gender',
          'u_Salutation' => 'Salutation',
          'u_Profession' => 'Profession',
          'u_FirstName' => 'First name',
          'u_MiddleName' => 'Middle name',
          'u_LastName' => 'Last name',
          'u_CellNumber' => 'Cell number',
          'u_Birthday' => 'Date of Birth',
          'u_MessengerICQ' => 'Messenger ICQ',
          'u_MessengerMSN' => 'Messenger MSN',
          'u_MessengerYAHOO' => 'Messenger YAHOO',
          'u_MessengerAOL' => 'Messenger AOL',
          'u_MessengerOther' => 'Messenger other',
          'u_PrivateStreet' => 'Street',
          'u_PrivateZIPCode' => 'ZIP code',
          'u_PrivateCity' => 'City',
          'u_PrivateState' => 'State',
          'u_PrivateCountry' => 'Country',
          'u_PrivateWebsite' => 'Webpage',
          'u_PrivateTelephone' => 'Phone',
          'u_PrivateFax' => 'Fax',
          'u_BusinessStreet' => 'Street business',
          'u_BusinessZIPCode' => 'ZIP code business',
          'u_BusinessCity' => 'City business',
          'u_BusinessState' => 'State business',
          'u_BusinessCountry' => 'Country business',
          'u_BusinessWebsite' => 'Webpage business',
          'u_BusinessTelephone' => 'Phone number',
          'u_BusinessFax' => 'Fax',
          'u_BusinessPosition' => 'Position',
          'u_BusinessDepartment' => 'Department',
          'u_Comments' => 'Comments',
          'u_Username' => 'Username',
          'u_Password' => 'Password',
          'u_Language' => 'Language',
          'u_UserFieldString1' => 'String 1',
          'u_UserFieldString2' => 'String 2',
          'u_UserFieldString3' => 'String 3',
          'u_UserFieldInt1' => 'Integer 1',
          'u_UserFieldInt2' => 'Integer 2',
          'u_UserFieldInt3' => 'Integer 3',
          'u_UserFieldBool1' => 'Boolean field 1',
          'u_UserFieldBool2' => 'Boolean field 2',
          'u_UserFieldBool3' => 'Boolean field 3',
          'u_PersonalizedTracking' => 'Personalized tracking allowed'
          )
 );


 $resourcestrings = array( "de" =>
                                  array(
                                        "000000" => "",

                                        "000069" => '<option value="invisible">unsichtbar</option><option value="visible">sichtbar, optional</option>',
                                        "000070" => '<option value="visiblerequired">sichtbar, Pflichtfeld</option>',

                                        "man"  => "m&auml;nnlich",
                                        "woman"  => "weiblich",

                                        "MR"  => "Herr",
                                        "MRS"  => "Frau",
                                        "FIRM"  => "Firma",

                                        "TRUE"  => "WAHR",
                                        "FALSE"  => "FALSCH",

                                        "HTML"  => "HTML",
                                        "PLAINTEXT"  => "Text",
                                        "ERROR" => "Fehler",

                                        "CHANGESSAVED" => "Die &Auml;nderungen wurden gespeichert.",

                                        'wpswm_widget_title' => 'Newsletteranmeldung',
                                        'wpswm_msg_emailaddressinvalid' => "<p>Die E-Mail-Adresse ist nicht korrekt.</p>",
                                        'wpswm_form_header' => "<a name=\"wpswmnl\"></a><div class=\"widget module\">Hier k&ouml;nnen Sie sich zum Newsletter anmelden.",
                                        'wpswm_form_subscribe' => "anmelden",
                                        'wpswm_form_unsubscribe' => "abmelden",
                                        'wpswm_form_submit_btn' => "Absenden"

                                       ),

                             "en" =>
                                  array(
                                        "000000" => "",

                                        "000069" => '<option value="invisible">invisible</option><option value="visible">visible, optional</option>',
                                        "000070" => '<option value="visiblerequired">visible, required field</option>',

                                        "man"  => "male",
                                        "woman"  => "female",

                                        "MR"  => "MR",
                                        "MRS"  => "MRS",
                                        "FIRM"  => "Firm",

                                        "TRUE"  => "TRUE",
                                        "FALSE"  => "FALSE",

                                        "HTML"  => "HTML",
                                        "PLAINTEXT"  => "Plain text",

                                        "ERROR" => "Error",

                                        "CHANGESSAVED" => "Changes was saved.",

                                        'wpswm_widget_title' => 'Newsletter subscription',
                                        'wpswm_msg_emailaddressinvalid' => "<p>Email address incorrect.</p>",
                                        'wpswm_form_header' => "<a name=\"wpswmnl\"></a><div class=\"widget module\">Here you can subscribe to our newsletter.",
                                        'wpswm_form_subscribe' => "subscribe",
                                        'wpswm_form_unsubscribe' => "unsubscribe",
                                        'wpswm_form_submit_btn' => "Submit"

                                       )
                         );


function wpswm_show_subunsubform() {
   global $fieldnames, $resourcestrings;
   $wpswm_fields = (get_option('wpswm_form_fields'));
   $swm_INTERFACE_LANGUAGE = get_option('wpswm_INTERFACE_LANGUAGE');
   if($wpswm_fields != "" && @unserialize($wpswm_fields) !== false) {
      $wpswm_fields = @unserialize($wpswm_fields);
      if(!is_array($wpswm_fields))
        $wpswm_fields = array("u_EMail" => "visiblerequired");
      }
      else
      $wpswm_fields = array("u_EMail" => "visiblerequired");
   $add_poweredbylink = get_option("wpswm_poweredbylink");

   $html = get_option('wpswm_form_header');
   $html .= '<form action="#wpswmnl" method="post">'."\n";

   reset($wpswm_fields);
   foreach($wpswm_fields as $key => $value){
     if($value == "invisible") continue;
     $html .= '<p class="wpswm_form_label"><br />';

     $html .= $fieldnames[$swm_INTERFACE_LANGUAGE][$key];
     if($value == "visiblerequired")
        $html .= "*: ";
        else
        $html .= ": ";

     $html .= '<br />';

     if($key == "u_Gender") {
        $html .= ' <input type="radio" name="u_Gender" value="m" checked="checked" />&nbsp;'.$resourcestrings[$swm_INTERFACE_LANGUAGE]["man"];
        $html .= '  <br />';
        $html .= ' <input type="radio" name="u_Gender" value="w" />&nbsp;'.$resourcestrings[$swm_INTERFACE_LANGUAGE]["woman"];
     } else
     if($key == "u_Salutation") {
        $html .= ' <select name="u_Salutation" size="1" style="max-width: 420px;">';
        $html .= '   <option>---</option>';
        $html .= '   <option>'.$resourcestrings[$swm_INTERFACE_LANGUAGE]["MR"].'</option>';
        $html .= '   <option>'.$resourcestrings[$swm_INTERFACE_LANGUAGE]["MRS"].'</option>';
        $html .= '   <option>'.$resourcestrings[$swm_INTERFACE_LANGUAGE]["FIRM"].'</option>';
        $html .= ' </select>';
     } else
     if($key == "u_UserFieldBool1" || $key == "u_UserFieldBool2" || $key == "u_UserFieldBool3" || $key == "u_PersonalizedTracking"){
         $html .= '    <select name="'.$key.'" size="1" style="max-width: 420px;">';
         $html .= '      <option value="0">'.$resourcestrings[$swm_INTERFACE_LANGUAGE]["FALSE"].'</option>';
         $html .= '      <option value="1">'.$resourcestrings[$swm_INTERFACE_LANGUAGE]["TRUE"].'</option>';
         $html .= '    </select>';
     } else
     if($key == "u_EMailFormat") {
       $html .= '<select name="u_EMailFormat" size="1">';
       $html .= '<option value="HTML">'.$resourcestrings[$swm_INTERFACE_LANGUAGE]["HTML"].'</option>';
       $html .= '<option value="PlainText">'.$resourcestrings[$swm_INTERFACE_LANGUAGE]["PLAINTEXT"].'</option>';
       $html .= '</select>';
     } else
        $html .= '<input type="text" name="'.$key.'" id="'.$key.'" class="wpswm_form_txt" />';
     $html .= '</p>';
   }



   $html .= '<p class="wpswm_form_label">';
   $html .= '<input type="radio" name="wpswm_action" id="wpswm_action1" class="wpswm_form_radio" value="subscribe" checked="checked" /> '.stripslashes(get_option('wpswm_form_subscribe'));
   $html .= '<br />';
   $html .= '<input type="radio" name="wpswm_action" id="wpswm_action2" class="wpswm_form_radio" value="unsubscribe" /> '.stripslashes(get_option('wpswm_form_unsubscribe')).'</p>';


   if(get_option('wpswm_privacypolicyurl') != ""){
     $swm_PrivacyPolicyAccepted_Required = (int) stripslashes(get_option('wpswm_PrivacyPolicyAccepted_Required'));

     $html .= '<p class="wpswm_form_label">';

     if($swm_PrivacyPolicyAccepted_Required){
       $html .= '<input type="checkbox" name="wpswm_PrivacyPolicyAccepted" id="wpswm_PrivacyPolicyAccepted" value="1" />';
       $html .= '<label for="wpswm_PrivacyPolicyAccepted">';
     }

     $text = get_option('wpswm_privacypolicytext');
     if($text != ""){
       $text = str_replace('[PrivacyPolicyURL]', stripslashes(get_option('wpswm_privacypolicyurl')), $text);
       $html .= $text;
     }
     else
       $html .= 'Mit Angabe meiner Daten und Absenden der Anmeldung erkl&auml;re ich mich einverstanden, den hier bestellten Newsletter per E-Mail zu erhalten. Meine Daten werden nicht an Dritte weitergegeben. Dieses Einverst&auml;ndnis kann ich jederzeit widerrufen. Weitere ausf&uuml;hrliche Informationen in der <a href="' . stripslashes(get_option('wpswm_privacypolicyurl')) . '" target="_blank">Datenschutzerkl&auml;rung</a>';

     if($swm_PrivacyPolicyAccepted_Required)
       $html .= '</label>';
     $html .= '</p>';
     $html .= '<br />';
   }

   $html .= '<p class="wpswm_form_label"><input type="submit" value="' . get_option('wpswm_form_submit_btn');
   $html .= '" class="wpswm_form_btn" /></p>' . "\n";
   $html .= "</form>\n\n";
   if ($add_poweredbylink) {
      $html .= '<font size="1">Powered by PHP Newsletter Script <a href="https://www.superwebmailer.de/" title="PHP Newsletter Script SuperWebMailer">SuperWebMailer</a></font>';
   }

   echo $html;
}

/*function quote($s) {
  $s = str_replace ('"', '\"', $s);
  $s = str_replace ("'", "\\'", $s);
  $s = '"'.$s.'"';
  return $s;
} */

function wpswm_is_spam($str) {
  if ( preg_match("/from\:/i",$str) || preg_match("/to\:/i",$str) || preg_match("/cc\:/i",$str) || preg_match("/bcc\:/i",$str) )
    return 1;
     else
     return 0;
}

function wpswm_CheckEMail($email) {
 if (strpos($email, "@") === False)
   return 0;
 $s = substr($email, strpos($email, "@"), strlen($email));
 if (count(explode(".", $s)) < 2)
   return 0;

 if (!(strpos($email, "\n") === False))
   return 0;

 if (!(strpos($email, "\r") === False))
   return 0;

 if (!(strpos($email, ",") === False))
   return 0;

 if (!(strpos($email, ";") === False))
   return 0;

 if ( preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email) ){
        return 1;
   }else {
        return 0;
   }

}

function wpswm_subscribe() {
   global $resourcestrings;
   $swm_INTERFACE_LANGUAGE = get_option('wpswm_INTERFACE_LANGUAGE');

   $ShowSubUnSubForm = true;
   if(!empty( $_POST['u_EMail'] ) ) {
      $_POST['wpswm_email'] = trim($_POST['u_EMail']);
      $values = array_merge($_POST, $_GET);
      $text = "";
      foreach($values as $key => $value) {
        $text = "$key $value";
      }
      if (wpswm_is_spam($text)) {
        echo stripslashes(get_option('wpswm_form_header'));
        echo stripslashes(get_option('wpswm_msg_emailaddressinvalid'));
        wpswm_show_subunsubform();
        exit;
      }

      $email = stripslashes($_POST['u_EMail']);
      if (!wpswm_CheckEMail($email)) {
         echo stripslashes(get_option('wpswm_form_header'));
         echo stripslashes(get_option('wpswm_msg_emailaddressinvalid'));
         wpswm_show_subunsubform();
         exit;
      }

      $swm_nl_url = stripslashes(get_option('wpswm_nl_url'));
      $swm_maillist_id = stripslashes(get_option('wpswm_maillist_id'));
      $swm_form_id = stripslashes(get_option('wpswm_form_id'));
      $swm_formencoding = stripslashes(get_option('wpswm_formencoding'));

      $dataString = sprintf("MailingListId=%s&FormId=%s&FormEncoding=%s", $swm_maillist_id, $swm_form_id, urlencode($swm_formencoding) );
      if(isset($_POST["wpswm_PrivacyPolicyAccepted"]))
         $dataString .= "&PrivacyPolicyAccepted=" . $_POST["wpswm_PrivacyPolicyAccepted"];

      $errnum = 0;
      $errstr = "";
      if($_POST["wpswm_action"] == "subscribe") {
        $dataString .= "&Action=subscribe";
        reset($_POST);
        foreach($_POST as $key => $value) {
          if(strpos($key, "u_") !== false)
            $dataString .= "&$key=".url_encode($value);
        }
        $result = fsockPost($swm_nl_url, $dataString, $errnum, $errstr);
      }
      if($_POST["wpswm_action"] == "unsubscribe") {
        $dataString .= "&Action=unsubscribe";
        reset($_POST);
        foreach($_POST as $key => $value) {
          if(strpos($key, "u_") !== false)
            $dataString .= "&$key=".urlencode($value);
        }
        $result = fsockPost($swm_nl_url, $dataString, $errnum, $errstr);
      }
      if($errnum != 0) {
        print $resourcestrings[$swm_INTERFACE_LANGUAGE]["ERROR"].": $errnum - $errstr";
      } else{
        $text = join("", $result);
        $header = substr($text, 0, strpos($text, "\r\n\r\n"));
        $text = substr($text, strpos($text, "\r\n\r\n") + 4);
        if(stripos($header, "transfer-encoding: chunked") !== false)
         print http_chunked_decode($text);
        else
         print $text;
        $ShowSubUnSubForm = false;
      }
   }

   if($ShowSubUnSubForm) {
     wpswm_show_subunsubform();
     echo stripslashes(get_option('wpswm_form_footer'));
   }
}

if(!function_exists("IsUtf8String")){
  function IsUtf8String( $s ) {
      $ptrASCII  = '[\x00-\x7F]';
      $ptr2Octet = '[\xC2-\xDF][\x80-\xBF]';
      $ptr3Octet = '[\xE0-\xEF][\x80-\xBF]{2}';
      $ptr4Octet = '[\xF0-\xF4][\x80-\xBF]{3}';
      $ptr5Octet = '[\xF8-\xFB][\x80-\xBF]{4}';
      $ptr6Octet = '[\xFC-\xFD][\x80-\xBF]{5}';
      $result = preg_match("/^($ptrASCII|$ptr2Octet|$ptr3Octet|$ptr4Octet|$ptr5Octet|$ptr6Octet)*$/s", $s);

      if($result)
        $result = (utf8_decode($s) != "");

      return $result;
  }

  function url_encode($string){
     if(!IsUtf8String($string))
       return urlencode(utf8_encode($string));
       else
       return urlencode($string);
  }
}

function wpswm_install() {
   global $DEFAULT_INTERFACE_LANGUAGE, $resourcestrings;
   // Standardwerte
   $blogname = get_option('blogname');
   add_option('wpswm_widget_title', $resourcestrings[$DEFAULT_INTERFACE_LANGUAGE]['wpswm_widget_title']);
   add_option('wpswm_poweredbylink', "1");

   add_option('wpswm_msg_emailaddressinvalid', $resourcestrings[$DEFAULT_INTERFACE_LANGUAGE]['wpswm_msg_emailaddressinvalid']);

   add_option('wpswm_form_header', $resourcestrings[$DEFAULT_INTERFACE_LANGUAGE]['wpswm_form_header']);
   add_option('wpswm_form_footer', "</div>");

   add_option('wpswm_form_subscribe', $resourcestrings[$DEFAULT_INTERFACE_LANGUAGE]['wpswm_form_subscribe']);
   add_option('wpswm_form_unsubscribe', $resourcestrings[$DEFAULT_INTERFACE_LANGUAGE]['wpswm_form_unsubscribe']);

   add_option('wpswm_form_submit_btn', $resourcestrings[$DEFAULT_INTERFACE_LANGUAGE]['wpswm_form_submit_btn']);

   add_option('wpswm_nl_url', 'http://...nl.php');
   add_option('wpswm_maillist_id', '1');
   add_option('wpswm_form_id', '1');
   add_option('wpswm_formencoding', 'utf-8');

   add_option('wpswm_INTERFACE_LANGUAGE', $DEFAULT_INTERFACE_LANGUAGE);
   add_option('wpswm_privacypolicyurl', '');
   add_option('wpswm_PrivacyPolicyAccepted_Required', '0');
   add_option('wpswm_privacypolicytext', 'Mit Angabe meiner Daten und Absenden der Anmeldung erkl&auml;re ich mich einverstanden, den hier bestellten Newsletter per E-Mail zu erhalten. Meine Daten werden nicht an Dritte weitergegeben. Dieses Einverst&auml;ndnis kann ich jederzeit widerrufen. Weitere ausf&uuml;hrliche Informationen in der <a href="[PrivacyPolicyURL]" target="_blank">Datenschutzerkl&auml;rung</a>');
}

function wpswm_options() {
  global $fieldnames, $resourcestrings;

  $poweredbylink = get_option('wpswm_poweredbylink');

  $msg_emailaddressinvalid = stripslashes(get_option('wpswm_msg_emailaddressinvalid'));

  $form_header = stripslashes(get_option('wpswm_form_header'));
  $form_footer = stripslashes(get_option('wpswm_form_footer'));
  $form_subscribe = stripslashes(get_option('wpswm_form_subscribe'));
  $form_unsubscribe = stripslashes(get_option('wpswm_form_unsubscribe'));

  $form_submit_btn = stripslashes(get_option('wpswm_form_submit_btn'));

  $swm_nl_url = stripslashes(get_option('wpswm_nl_url'));
  $swm_maillist_id = stripslashes(get_option('wpswm_maillist_id'));
  $swm_form_id = stripslashes(get_option('wpswm_form_id'));
  $swm_formencoding = stripslashes(get_option('wpswm_formencoding'));

  $swm_INTERFACE_LANGUAGE = stripslashes(get_option('wpswm_INTERFACE_LANGUAGE'));
  $swm_privacypolicyurl = stripslashes(get_option('wpswm_privacypolicyurl'));
  $swm_PrivacyPolicyAccepted_Required = stripslashes(get_option('wpswm_PrivacyPolicyAccepted_Required'));
  $swm_privacypolicytext = stripslashes(get_option('wpswm_privacypolicytext'));

  $swm_fields = get_option('wpswm_form_fields');
  if($swm_fields != "" && @unserialize($swm_fields) !== false) {
     $swm_fields = unserialize($swm_fields);
     }
     else
     $swm_fields = array("u_EMail" => "visiblerequired");

  if( isset($_POST['wpswm_SAVEind']) && $_POST['wpswm_SAVEind'] == 'SUPERWEBMAILERsaveNLSUB' ) {
      $poweredbylink = (int) isset($_POST['wpswm_poweredbylink']);

      $msg_emailaddressinvalid = stripslashes($_POST['wpswm_msg_emailaddressinvalid']);

      $form_header = stripslashes($_POST['wpswm_form_header']);
      $form_footer = stripslashes($_POST['wpswm_form_footer']);
      $form_subscribe = stripslashes($_POST['wpswm_form_subscribe']);
      $form_unsubscribe = stripslashes($_POST['wpswm_form_unsubscribe']);

      $form_submit_btn = stripslashes($_POST['wpswm_form_submit_btn']);

      $swm_nl_url = stripslashes($_POST['wpswm_nl_url']);

      $swm_maillist_id = stripslashes($_POST['wpswm_maillist_id']);
      $swm_form_id = stripslashes($_POST['wpswm_form_id']);
      $swm_formencoding = stripslashes($_POST['wpswm_formencoding']);

      $swm_INTERFACE_LANGUAGE = stripslashes($_POST['wpswm_INTERFACE_LANGUAGE']);
      $swm_privacypolicyurl = stripslashes($_POST['wpswm_privacypolicyurl']);
      if(!empty($swm_privacypolicyurl))
        $swm_privacypolicytext = stripslashes($_POST['wpswm_privacypolicytext']);

      if(isset($_POST['wpswm_PrivacyPolicyAccepted_Required']))
        $swm_PrivacyPolicyAccepted_Required = (int)stripslashes($_POST['wpswm_PrivacyPolicyAccepted_Required']);
        else
        $swm_PrivacyPolicyAccepted_Required = 0;

      if( isset($_POST['wpswm_fields']) && is_array($_POST['wpswm_fields'] ))
         $swm_fields = $_POST['wpswm_fields'];
         else
         $swm_fields = array();

      update_option('wpswm_poweredbylink', $poweredbylink);

      update_option('wpswm_msg_emailaddressinvalid', $msg_emailaddressinvalid);

      update_option('wpswm_form_header', $form_header);
      update_option('wpswm_form_footer', $form_footer);
      update_option('wpswm_form_subscribe', $form_subscribe);
      update_option('wpswm_form_unsubscribe', $form_unsubscribe);

      update_option('wpswm_form_fields', serialize($swm_fields));
      update_option('wpswm_form_submit_btn', $form_submit_btn);

      update_option('wpswm_nl_url', $swm_nl_url);
      update_option('wpswm_maillist_id', $swm_maillist_id);
      update_option('wpswm_form_id', $swm_form_id);
      update_option('wpswm_formencoding', $swm_formencoding);

      update_option('wpswm_INTERFACE_LANGUAGE', $swm_INTERFACE_LANGUAGE);
      update_option('wpswm_privacypolicyurl', $swm_privacypolicyurl);
      update_option('wpswm_PrivacyPolicyAccepted_Required', $swm_PrivacyPolicyAccepted_Required);
      if(isset($swm_privacypolicytext))
        update_option('wpswm_privacypolicytext', $swm_privacypolicytext);


      echo '<div id="message" class="updated fade"><p><strong>';
      _e($resourcestrings[$swm_INTERFACE_LANGUAGE]['CHANGESSAVED'], 'wpswm_domain');
      echo '</strong></p></div>';
  }
?>

<div class="wrap">
<style>
  .form-table td {vertical-align: top;}
</style>

<script>
  function EnableFields(){
   var enable = document.getElementById("wpswm_privacypolicyurl").value.trim() != "";
   document.getElementById("wpswm_privacypolicytext").disabled = !enable;
   document.getElementById("wpswm_PrivacyPolicyAccepted_Required").disabled = !enable;
  }
</script>

  <h2>Einstellungen f&uuml;r die Newsletteranmeldung</h2>
<form method="post" action="">
    <input type="hidden" name="wpswm_SAVEind" value="SUPERWEBMAILERsaveNLSUB" />


    <table width="99%" class="form-table">
      <tr valign="top">
        <td colspan="2"><h3>Allgemein</h3></td>
      </tr>



      <tr valign="top">
        <td>Sprache der Feldbezeichner:</td>
        <td>
          <select name="wpswm_INTERFACE_LANGUAGE" id="wpswm_INTERFACE_LANGUAGE">
             <option value="de" <?php if($swm_INTERFACE_LANGUAGE == "de") echo 'selected="selected"'; ?>>Deutsch/German</option>
             <option value="en" <?php if($swm_INTERFACE_LANGUAGE == "en") echo 'selected="selected"'; ?>>English/Englisch</option>
          </select>
          <br /><br />
        </td>
      </tr>

      <tr valign="top">
        <td>URL zur eigenen Datenschutzerkl&auml;rung: </td>
        <td>
          <input type="text" name="wpswm_privacypolicyurl" id="wpswm_privacypolicyurl" value="<?php echo $swm_privacypolicyurl; ?>" size="70" onchange="EnableFields()" onclick="EnableFields()" onkeyup="EnableFields()" />
          <br />
           Die Datenschutzerkl&auml;rung sollte im Anmeldeformular immer angezeigt werden.
             <br />
             <br />
        </td>
      </tr>

      <tr valign="top">
        <td valign="top">Hinweistext zur Verwendung und Speicherung der Daten im Anmelde-/&Auml;ndern-Formular:</td>
        <td>
          <textarea name="wpswm_privacypolicytext" id="wpswm_privacypolicytext" rows="4" cols="70"><?php echo $swm_privacypolicytext; ?></textarea>
          <br /><br />
          Sie k&ouml;nnen den Platzhalter [PrivacyPolicyURL] im Text verwenden, an der Stelle wird die URL zur eigenen Datenschutzerkl&auml;rung eingef&uuml;gt.
        </td>
      </tr>

      <tr valign="top">
        <td><label for="wpswm_PrivacyPolicyAccepted_Required">Zustimmung zur eigenen Datenschutzerkl&auml;rung soll Pflichtfeld sein:</label></td>
        <td>
          <input type="checkbox" name="wpswm_PrivacyPolicyAccepted_Required" id="wpswm_PrivacyPolicyAccepted_Required" value="1" <?php echo $swm_PrivacyPolicyAccepted_Required ? 'checked="checked"' : ""; ?> />
          <br />
        </td>
      </tr>

      <tr valign="top">
        <td>SuperWebMailer http(s)://-Aufruf des Scripts nl.php:</td>
        <td>
          <input type="text" name="wpswm_nl_url" id="wpswm_nl_url" value="<?php echo $swm_nl_url; ?>" size="70" />
          <br /><br />
        </td>
      </tr>

      <tr valign="top">
        <td>SuperWebMailer Mailinglisten-ID:</td>
        <td>
          <input type="text" name="wpswm_maillist_id" id="wpswm_maillist_id" value="<?php echo $swm_maillist_id; ?>" size="10" />
          <br /><br />
        </td>
      </tr>

      <tr valign="top">
        <td>SuperWebMailer Formular-ID:</td>
        <td>
          <input type="text" name="wpswm_form_id" id="wpswm_form_id" value="<?php echo $swm_form_id; ?>" size="10" />
          <br /><br />
        </td>
      </tr>

      <tr valign="top">
        <td>Codierung dieser Seite:</td>
        <td>

          <select size="1" name="wpswm_formencoding" id="wpswm_formencoding">
            <option value="utf-8" <?php if($swm_formencoding == "utf-8") print 'checked="checked"' ?>>UTF-8</option>
            <option value="iso-8859-1" <?php if($swm_formencoding == "iso-8859-1") print 'checked="checked"' ?>>iso-8859-1</option>
          </select>

          <br /><br />
        </td>
      </tr>

      <tr valign="top">
        <td><label for="wpswm_poweredbylink">Powered by SuperWebMailer&quot;-Link zeigen:</label></td>
        <td>
          <input type="checkbox" name="wpswm_poweredbylink" id="wpswm_poweredbylink" value="1"<?php echo $poweredbylink ? " checked=\"checked\"" : "";?> />
          <br /><br />
       </td>
      </tr>
      <tr valign="top">
        <td colspan="2">&nbsp;<br /></td>
      </tr>


      <tr valign="top">
        <td colspan="2"><h3>Meldung bei syntaktisch inkorrekter E-Mail-Adresse</h3></td>
      </tr>


      <tr valign="top">
        <td>E-Mail-Adresse fehlerhaft:</td>
        <td>
          <input type="text" name="wpswm_msg_emailaddressinvalid" id="wpswm_msg_emailaddressinvalid" value="<?php echo $msg_emailaddressinvalid; ?>" size="70" />
          <br /><br />
        </td>
      </tr>

      <tr valign="top">
        <td colspan="2">&nbsp;<br /></td>
      </tr>

      <tr valign="top">
        <td colspan="2"><h3>Anzuzeigende Felder und Bezeichnungen</h3></td>
      </tr>

      <tr valign="top">
        <td>Text f&uuml;r das Anmeldeformular:</td>
        <td>
          <textarea name="wpswm_form_header" id="wpswm_form_header" rows="4" cols="70"><?php echo $form_header; ?></textarea>
          <br /><br />
        </td>
      </tr>
      <tr valign="top">
        <td>Text am Ende des Anmeldeformulars:</td>
        <td>
          <textarea name="wpswm_form_footer" id="wpswm_form_footer" rows="2" cols="70"><?php echo $form_footer; ?></textarea>
          <br /><br />
        </td>
      </tr>
      <tr valign="top">
        <td>Bezeichnung f&uuml;r Auswahl &quot;Anmelden&quot;:</td>
        <td>
          <input type="text" name="wpswm_form_subscribe" id="wpswm_form_fld16" value="<?php echo $form_subscribe; ?>" size="70" maxlength="64" />
          <br /><br />
        </td>
      </tr>

      <tr valign="top">
        <td>Bezeichnung f&uuml;r Auswahl &quot;Abmelden&quot;:</td>
        <td>
          <input type="text" name="wpswm_form_unsubscribe" id="wpswm_form_fld17" value="<?php echo $form_unsubscribe; ?>" size="70" maxlength="64" />
          <br /><br />
        </td>
      </tr>
      <tr valign="top">
        <td>Bezeichnung der An-/abmelde-Schaltfl&auml;che:</td>
        <td>
          <input type="text" name="wpswm_form_submit_btn" id="wpswm_form_submit_btn" value="<?php echo $form_submit_btn; ?>" size="70" maxlength="64" />
          <br /><br />
        </td>
      </tr>

      <tr valign="top">
        <td colspan="2">&nbsp;<br /></td>
      </tr>
    </table>

    <table width="99%" class="form-table">

      <tr valign="top">
        <td colspan="4">
          <h3>Zus&auml;tzliche Felder</h3>
        </td>
      </tr>

      <tr valign="top">
        <td colspan="4">
         W&auml;hlen Sie die Felder aus, die im Formular dargestellt werden sollen. Beachten Sie dabei, dass im
         SuperWebMailer <b>definierte Pflichtfelder ebenfalls in diesem Formular gew&auml;hlt werden m&uuml;ssen.</b>
        </td>
      </tr>

      <?php
       $TableRow = '<tr valign="top">
         <td class="LabelColumn">
         <!--FIELD1-->
        </td>
         <td>
           <!--VALUE1-->
         </td>
         <td>
         &nbsp;</td>
         <td class="LabelColumn">
         <!--FIELD2-->
        </td>
         <td>
         <!--VALUE2-->
         </td>
       </tr>';

        $html = "";
        $i=1;
        $isb = false;
        $temp = "";
        foreach($fieldnames[$swm_INTERFACE_LANGUAGE] as $key => $value) {
          if($i == 1)
             $temp = $TableRow;
          $row["text"] = $value;
          $row["fieldname"] = $key;
          $temp = str_replace('<!--FIELD'.$i.'-->', $row["text"], $temp);
          if($row["fieldname"] != "u_EMail") {
            $option = 0;
            if(isset($swm_fields[$row["fieldname"]])) {
              if($swm_fields[$row["fieldname"]] == "invisible")
                $option = 0;
                else
                if($swm_fields[$row["fieldname"]] == "visible")
                  $option = 1;
                  else
                  if($swm_fields[$row["fieldname"]] == "visiblerequired")
                    $option = 2;
            }
            $x = $resourcestrings[$swm_INTERFACE_LANGUAGE]["000069"].$resourcestrings[$swm_INTERFACE_LANGUAGE]["000070"];
            if($option == 0)
                $x = str_replace('value="invisible"', 'value="invisible" selected="selected"', $x);
               else
               if($option == 1)
                  $x = str_replace('value="visible"', 'value="visible" selected="selected"', $x);
                  else
                  $x = str_replace('value="visiblerequired"', 'value="visiblerequired" selected="selected"', $x);

            $temp = str_replace('<!--VALUE'.$i.'-->', '<select name="wpswm_fields['.$row["fieldname"].']" size="1">'.$x.'</select>', $temp);
            }
            else
            $temp = str_replace('<!--VALUE'.$i.'-->', '<select name="wpswm_fields['.$row["fieldname"].']" size="1">'.$resourcestrings[$swm_INTERFACE_LANGUAGE]["000070"].'</select>', $temp);
          $i++;
          if($i>2) {
            $i=1;
            $html .= $temp;
            $temp = "";
          }
        }
        if($temp != "")
          $html .= $temp;

        print $html;
      ?>



    </table>

<p class="submit">
  <input type="submit" name="Submit" value="Einstellungen speichern" />
</p>
</form>
<script>EnableFields()</script>
</div>


<?php
 }


function wpswm_widget_init() {
  global $wp_version;

  if (!function_exists('register_sidebar_widget')) {
    return;
  }

  function wpswm_widget($args) {
    extract($args);
    echo $before_widget . $before_title;
    echo get_option('wpswm_widget_title');
    echo $after_title;
    wpswm_subscribe();
    echo $after_widget;
  }

  function wpswm_widget_control() {
    $title = get_option('wpswm_widget_title');
    if ( isset($_POST['wpswm_submit_hidden']) ) {
      $title = stripslashes($_POST['wpswm_widget_title']);
      update_option('wpswm_widget_title', $title );
    }
    echo '<p>Bezeichnung:<input style="width: 200px;" type="text" value="';
    echo $title . '" name="wpswm_widget_title" id="wpswm_widget_title" /></p>';
    echo '<input type="hidden" id="wpswm_submit_hidden" name="wpswm_submit_hidden" value="1" />';
  }

  $width = 300;
  $height = 100;
  if ( $wp_version == '2.2' || (!function_exists( 'wp_register_sidebar_widget' ))) {
     register_sidebar_widget('WP SWM Newsletteran-/abmeldung', 'wpswm_widget');
     register_widget_control('WP SWM Newsletteran-/abmeldung', 'wpswm_widget_control', $width, $height);
  } else {
     $size = array('width' => $width, 'height' => $height);
     $class = array('classname' => 'wpswm_subscribe');
     wp_register_sidebar_widget('wpswmnl', 'WP SWM Newsletteran-/abmeldung', 'wpswm_widget', $class);
     wp_register_widget_control('wpswmnl', 'WP SWM Newsletteran-/abmeldung', 'wpswm_widget_control', $size);
  }
  if (function_exists('register_sidebar_module')) {
     $class = array('classname' => 'wpswm_subscribe');
     register_sidebar_module('WP SWM Newsletteran-/abmeldung', 'wpswm_widget', '', $class);
     register_sidebar_module_control('WP SWM Newsletteran-/abmeldung', 'wpswm_widget_control');
  }
}

function wpswm_add_to_menu() {
  add_options_page('WP Newsletteran-/abmeldung Einstellungen', 'WP SWM Newsletteran-/abmeldung', 7, __FILE__, 'wpswm_options');
}

register_activation_hook(__FILE__, 'wpswm_install');
add_action('admin_menu', 'wpswm_add_to_menu');
add_action('init', 'wpswm_widget_init');


if(!function_exists("fsockPost")){

  //posts transaction data using fsockopen.
  function fsockPost($url, $dataString, &$errnum, &$errstr) {

    $info = array();

    //Parse url
    $web=parse_url($url);

    //build post string
    $postdata = $dataString;

    //Set the port number
    $ssl = "";
    if ($web["scheme"] == "https") { $web["port"]="443";  $ssl="ssl://"; } else { $web["port"]="80"; }

    //Create HTTP connection
    $fp=fsockopen($ssl . $web["host"], $web["port"], $errnum, $errstr, 300);

    //Error checking
    if(!$fp)
     {
       return "ERROR: ".$info;
     }
     //Post Data
     else {

        fputs($fp, "POST $web[path] HTTP/1.1\r\n");
        fputs($fp, "Host: $web[host]\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ".strlen($postdata)."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $postdata . "\r\n\r\n");

        if(function_exists("stream_set_timeout") && function_exists("stream_set_blocking") && function_exists("stream_get_meta_data") )  {
           stream_set_blocking($fp, TRUE);
           stream_set_timeout($fp, 20);
           $socksinfo = stream_get_meta_data($fp);
           while ((!feof($fp)) && (!$socksinfo['timed_out'])) {
            $info[] = fgets($fp, 1024);
            $socksinfo = stream_get_meta_data($fp);
           }
         } else {
           sleep(2);
           while (!feof($fp)) {
            $info[] = fgets($fp, 1024);
           }
         }

        //close fp - we are done with it
        fclose($fp);
     }
     return $info;
  }

  function fsockGet($url, $data, &$errnum, &$errstr) {
    $info = array();

    //Parse url
    $web=parse_url($url);

    //build get string
    $getdata = $data;

    //Set the port number
    $ssl = "";
    if ($web["scheme"] == "https") { $web["port"]="443";  $ssl="ssl://"; } else { $web["port"]="80"; }

    //Create HTTP connection
    $fp=fsockopen($ssl . $web["host"], $web["port"], $errnum, $errstr, 300);

    //Error checking
    if(!$fp)
     {
       return $info;
     }
     //GET Data
     else {
  //         print("GET $web[path]?$getdata HTTP/1.1\r\n");
  //         print("Host: $web[host]\r\n");
  //         print("Connection: close\r\n\r\n");
  //         exit;

        fputs($fp, "GET $web[path]?$getdata HTTP/1.1\r\n");
        fputs($fp, "Host: $web[host]\r\n");
        fputs($fp, "Connection: close\r\n\r\n");

        //loop through the response from the server
        while(!feof($fp)) { $info[]=@fgets($fp, 1024); }

        //close fp - we are done with it
        fclose($fp);
     }
     return $info;
  }

  function fsockGetWithoutResult($url, $data) {

    $errnum = 0;
    $errstr = "";

    //Parse url
    $web=parse_url($url);

    //build get string
    $getdata = $data;

    //Set the port number
    if ($web["scheme"] == "https") { $web["port"]="443";  $ssl="ssl://"; } else { $web["port"]="80"; }

    //Create HTTP connection
    $fp=@fsockopen($ssl . $web["host"], $web["port"], $errnum, $errstr, 300);

    //Error checking
    if(!$fp)
     {
       return;
     }
     //GET Data
     else {
  //         print("GET $web[path]?$getdata HTTP/1.1\r\n");
  //         print("Host: $web[host]\r\n");
  //         print("Connection: close\r\n\r\n");
  //         exit;

        fputs($fp, "GET $web[path]?$getdata HTTP/1.1\r\n");
        fputs($fp, "Host: $web[host]\r\n");
        fputs($fp, "Connection: close\r\n\r\n");

        //loop through the response from the server
        //while(!feof($fp)) { $info[]=@fgets($fp, 1024); }

        //close fp - we are done with it
        fclose($fp);
     }
     return;
  }
}

if (!function_exists('http_chunked_decode')) {
    /**
     * dechunk an http 'transfer-encoding: chunked' message
     *
     * @param string $chunk the encoded message
     * @return string the decoded message.  If $chunk wasn't encoded properly it will be returned unmodified.
     */
    function http_chunked_decode($chunk) {
        $pos = 0;
        $len = strlen($chunk);
        $dechunk = null;

        while(($pos < $len)
            && ($chunkLenHex = substr($chunk,$pos, ($newlineAt = strpos($chunk,"\n",$pos+1))-$pos)))
        {
            if (! is_hex($chunkLenHex)) {
                trigger_error('Value is not properly chunk encoded', E_USER_WARNING);
                return $chunk;
            }

            $pos = $newlineAt + 1;
            $chunkLen = hexdec(rtrim($chunkLenHex,"\r\n"));
            $dechunk .= substr($chunk, $pos, $chunkLen);
            $pos = strpos($chunk, "\n", $pos + $chunkLen) + 1;
        }
        return $dechunk;
    }

    /**
     * determine if a string can represent a number in hexadecimal
     *
     * @param string $hex
     * @return boolean true if the string is a hex, otherwise false
     */
    function is_hex($hex) {
        // regex is for weenies
        $hex = strtolower(trim(ltrim($hex,"0")));
        if (empty($hex)) { $hex = 0; };
        $dec = hexdec($hex);
        return ($hex == dechex($dec));
    }

}

# end of script
?>
