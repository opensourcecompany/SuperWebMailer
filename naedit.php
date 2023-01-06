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


  // Boolean fields of form
  $_ItI0o = Array ("ShowSelectedEntryFirst", "StartPageShowSubscribeLink", "LinkToSWM",
                      "ShowImpressum", "ImpressumIsHTML", "RemoveMailToLinks", "SortOrderNewToOld", "rssshowAll");

  $_ItIti = Array ();

  $errors = array();
  $_Itfj8 = "";

  if(isset($_POST['NAEditBtn'])) // Formular speichern?
    $_j6jLo = intval($_POST['NAListId']);
  else
    if(!empty($_POST['OneNAListId']))
      $_j6jLo = intval($_POST['OneNAListId']);
      else
      $_j6jLo = 0;

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if($_j6jLo && !$_QLJJ6["PrivilegeNewsletterArchiveEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if(!$_j6jLo && !$_QLJJ6["PrivilegeNewsletterArchiveCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }


  if(isset($_POST['NAEditBtn'])) { // Formular speichern?

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';
    if ( (!isset($_POST['EMailings'])) || count($_POST['EMailings']) == 0 ){
      $errors[] = 'EMailings';
      $errors[] = 'EMailingsScrollbox';
    }  

    if(isset($_POST["TemplatesPath"]) && trim($_POST["TemplatesPath"]) != ""){
      $_POST["TemplatesPath"] = trim($_POST["TemplatesPath"]);
      $_POST["TemplatesPath"] = _LPC1C($_POST["TemplatesPath"]);
      if(!file_exists(InstallPath . $_POST["TemplatesPath"] . "na_start.htm" )){
        $errors[] = 'TemplatesPath';
      }
    }else
      if(isset($_POST["TemplatesPath"]))
        unset($_POST["TemplatesPath"]);
        
    if(!isset($_POST["MaxLatestEntriesOnStartPage"]))    
      $_POST["MaxLatestEntriesOnStartPage"] = 3;
    $_POST["MaxLatestEntriesOnStartPage"] = intval($_POST["MaxLatestEntriesOnStartPage"]);
    if($_POST["MaxLatestEntriesOnStartPage"] < 0)
      $_POST["MaxLatestEntriesOnStartPage"] = 3;
      
    if(!isset($_POST["InfoBarSchemeColorName"]) || trim($_POST["InfoBarSchemeColorName"]) == "" )
      $errors[] = 'InfoBarSchemeColorName';
    if(!isset($_POST["InfoBarSrcLanguage"]) || trim($_POST["InfoBarSrcLanguage"]) == "" )
      $errors[] = 'InfoBarSrcLanguage';
    if(isset($_POST["InfoBarSpacer"]))  
      $_POST["InfoBarSpacer"] = trim($_POST["InfoBarSpacer"]);
      else
      $_POST["InfoBarSpacer"] = "";
    if(isset($_POST["Link"])){
      foreach($_POST["Link"] as $key => $_QltJO){
       if( !isset($_POST["URL"][$key]) || trim($_POST["URL"][$key]) == "" ) 
          $errors[] = "URL[" . $key . "]";
      }
    }else
     $errors[] = 'Link';
     
    if($_j6jLo > 0){
      // take saved variant as default
      $_QLfol= "SELECT InfoBarSupportedTranslationLanguages, InfoBarLinksArray FROM `$_j6JfL` WHERE id=$_j6jLo";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      
      $ML["InfoBarSupportedTranslationLanguages"] = @unserialize($_QLO0f["InfoBarSupportedTranslationLanguages"]);
      $ML["InfoBarLinksArray"] = @unserialize($_QLO0f["InfoBarLinksArray"]);
    }
    if( !isset($ML) || !isset($ML["InfoBarSupportedTranslationLanguages"]) || !isset($ML["InfoBarLinksArray"]) || $ML["InfoBarSupportedTranslationLanguages"] === false || $ML["InfoBarLinksArray"] === false || count($ML["InfoBarLinksArray"]) < 2)
       _LBJ6B($ML["InfoBarSupportedTranslationLanguages"], $ML["InfoBarLinksArray"]);

    $_jfLj1 = new TAltBrowserLinkInfoBarLinkType(); // < PHP 5.3
    
    foreach($ML["InfoBarLinksArray"] as $_Qli6J => $_QltJO){
     
     if($_QltJO->LinkType == $_jfLj1->abtUnsubscribe || $_QltJO->LinkType == $_jfLj1->abtArchieve)
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
     
     $_j8LoO[$_QltJO->LinkType] = $_jfCoo;
    }
    
    $_POST["InfoBarLinksArray"] = serialize($_j8LoO);
    _LBLPR($_j8l18);
    $_POST["InfoBarSupportedTranslationLanguages"] = serialize($_j8l18);

    if(isset($_POST["Link"]))
      unset($_POST["Link"]);
    if(isset($_POST["URL"]))
      unset($_POST["URL"]);
    if(isset($_POST["Title"]))
      unset($_POST["Title"]);
    if(isset($_POST["Text"]))
      unset($_POST["Text"]);

    if(!empty($_POST["ShowImpressum"]))
      if(empty($_POST["ImpressumText"]))
         unset($_POST["ShowImpressum"]);

    if(count($errors) > 0)
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      else {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];

        $_IoLOO = $_POST;
        _LFD6P($_j6jLo, $_IoLOO);
        if($_j6jLo != 0)
           $_POST['NAListId'] = $_j6jLo;
      }

  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000832"], $_Itfj8, 'naedit', 'naedit_snipped.htm');

  $_QLJfI = str_replace ('name="NAListId"', 'name="NAListId" value="'.$_j6jLo.'"', $_QLJfI);
  if(isset($_POST["PageSelected"]))
     $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);


  #### campaigns
  $_QLfol = "SELECT id, Name FROM $_QLi60";
  if($OwnerUserId != 0) // kein Admin?
    {
      $_QLfol = "SELECT DISTINCT $_QLi60.id, $_QLi60.Name FROM $_QLi60 LEFT JOIN $_QL88I ON $_QL88I.id=$_QLi60.maillists_id";
      $_QLfol .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";

    }

  $_QLfol .= " ORDER BY $_QLi60.Name";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_I1OoI = array();
  $_ftjjC = "";

  $_Ql0fO = _L81DB($_QLJfI, "<SHOW:EMAILINGS>");
  
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    $_ftjjC .= $_Ql0fO;
    $_ftjjC = _L81BJ($_ftjjC, "<EMailingsId>", "", $_QLO0f["id"]);
    $_ftjjC = str_replace("EMailingsLabelId", "E" . $_QLO0f["id"], $_ftjjC);
    $_ftjjC = _L81BJ($_ftjjC, "<EMailingName>", "", $_QLO0f["Name"]);
  }
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:EMAILINGS>", "</SHOW:EMAILINGS>", $_ftjjC);
  #### campaigns END

  //Placeholders

  #### normal placeholders
  $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_I1OoI=array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
   $_I1OoI[$_QLO0f["fieldname"]] = $_QLO0f["text"];
  }
  mysql_free_result($_QL8i1);
  # defaults
  foreach ($_Iol8t as $key => $_QltJO)
   $_I1OoI[$key] = $resourcestrings[$INTERFACE_LANGUAGE][$key];

  #### special newsletter unsubscribe placeholders
  $_ICCIo = array_merge($_IolCJ, $_jlJ1o, $_ICiQ1);
  reset($_ICCIo);
  foreach ($_ICCIo as $key => $_QltJO)
    $_I1OoI[$key] = $resourcestrings[$INTERFACE_LANGUAGE][$key];

  $_I1OoI["MembersAge"] = $resourcestrings[$INTERFACE_LANGUAGE]["MembersAge"];

  // functions
  $_QLfol = "SELECT Name FROM $_jQ68I ORDER BY Name";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
   $_I1OoI[$_QLO0f["Name"]] = $_QLO0f["Name"];
  }
  mysql_free_result($_QL8i1);

  $_I0o0O = "";
  $_IC1C6 = _L81DB($_QLJfI, "<LIST:REPLACEMENTS>", "</LIST:REPLACEMENTS>");

  reset($_I1OoI);
  $_IOJoI = "";
  foreach ($_I1OoI as $key => $_QltJO){
    $_IOJoI .= '<option value="'.$key.'">'.$_QltJO.'</option>';
  }

  for($_Qli6J=0; $_Qli6J<20; $_Qli6J++){
     $_Ql0fO = $_IC1C6;
     $_Ql0fO = str_replace('"fieldname"', '"fieldname['.$_Qli6J.']"', $_Ql0fO);
     $_Ql0fO = str_replace('"replacementtext"', '"replacementtext['.$_Qli6J.']"', $_Ql0fO);
     $_Ql0fO = _L81BJ($_Ql0fO, "<fieldnames>", "</fieldnames>", $_IOJoI);
     $_I0o0O .= $_Ql0fO;
  }
  $_QLJfI = _L81BJ($_QLJfI, "<LIST:REPLACEMENTS>", "</LIST:REPLACEMENTS>", $_I0o0O);


  if($_j6jLo != 0) { // Laden
    $_QLfol = "SELECT *, UNIX_TIMESTAMP(CreateDate) AS UCreateDate FROM $_j6JfL WHERE id=$_j6jLo";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_ftjlo = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    $_j6jLo = $_ftjlo["id"];
    $_ftjlo['OneNAListId'] = $_j6jLo;
    $_ftjlo["CREATEDATE"] = date($LongDateFormat, $_ftjlo["UCreateDate"]);

    $_QLfol = "SELECT campaigns_id FROM $_ftjlo[CampaignToNATableName]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_IjO6t = array();
    while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1))
      $_IjO6t[] = $_QLO0f["campaigns_id"];
    if($_QL8i1)
      mysql_free_result($_QL8i1);
    $_ftjlo["EMailings"] = $_IjO6t;

    // remove boolean fields
    for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++)
       if(!$_ftjlo[$_ItI0o[$_Qli6J]])
          unset($_ftjlo[$_ItI0o[$_Qli6J]]);

    $_ftjlo["PlaceHolderReplacements"] = @unserialize($_ftjlo["PlaceHolderReplacements"]);
    if($_ftjlo["PlaceHolderReplacements"] === false)
      $_ftjlo["PlaceHolderReplacements"] = array();
      
    $_ftjlo["InfoBarSupportedTranslationLanguages"] = @unserialize($_ftjlo["InfoBarSupportedTranslationLanguages"]);
    $_ftjlo["InfoBarLinksArray"] = @unserialize($_ftjlo["InfoBarLinksArray"]);
      
    if($_ftjlo["InfoBarSupportedTranslationLanguages"] === false || $_ftjlo["InfoBarLinksArray"] === false )
      _LBJ6B($_ftjlo["InfoBarSupportedTranslationLanguages"], $_ftjlo["InfoBarLinksArray"]);
      

  } else {
    $_ftjlo = $_POST;

    if(!isset($_ftjlo["CREATEDATE"]))
       $_ftjlo["CREATEDATE"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEW"];
    if(!isset($_ftjlo["CampaignToNATableName"]))
       $_ftjlo["CampaignToNATableName"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEW"];
    if(!isset($_ftjlo["OpeningsCount"]))
       $_ftjlo["OpeningsCount"] = 0;
    if(!isset($_ftjlo["MaxLatestEntriesOnStartPage"]))   
      $_ftjlo["MaxLatestEntriesOnStartPage"] = 3;

    // default values
    if(!isset($_ftjlo["NAListId"]) && !isset($_ftjlo["OneNAListId"]) ) {

       $_QLfol = "SHOW COLUMNS FROM $_j6JfL";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_Iil6i = false;
       while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
          if($_QLO0f["Field"] == "CampaignToNATableName") {
             $_Iil6i = true;
             continue;
          }
          if($_Iil6i) {
            if( stripos($_QLO0f["Type"], "tiny") !== false && $_QLO0f["Default"] == 0 ) {
              continue;
            }

            if($_QLO0f["Default"] != "NULL") {
              $_ftjlo[$_QLO0f["Field"]] = $_QLO0f["Default"];
            }

            if(isset($resourcestrings[$INTERFACE_LANGUAGE]["rsNA".$_QLO0f["Field"]]))
              $_ftjlo[$_QLO0f["Field"]] = $resourcestrings[$INTERFACE_LANGUAGE]["rsNA".$_QLO0f["Field"]];


          }
       }
       mysql_free_result($_QL8i1);
       $_ftjlo["ImpressumIsHTML"] = 1;
       $_ftjlo["InfoBarSpacer"] = "<p>&nbsp;</p>";

       _LBJ6B($_ftjlo["InfoBarSupportedTranslationLanguages"], $_ftjlo["InfoBarLinksArray"]);
       
    }else{
     $_QLfol = "SELECT InfoBarSupportedTranslationLanguages, InfoBarLinksArray FROM $_j6JfL WHERE id=$_j6jLo"; 
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_QLO0f = mysql_fetch_assoc($_QL8i1);
     
     $_ftjlo["InfoBarSupportedTranslationLanguages"] = @unserialize($_QLO0f["InfoBarSupportedTranslationLanguages"]);
     $_ftjlo["InfoBarLinksArray"] = @unserialize($_QLO0f["InfoBarLinksArray"]);
      
     if($_ftjlo["InfoBarSupportedTranslationLanguages"] === false || $_ftjlo["InfoBarLinksArray"] === false)
       _LBJ6B($_ftjlo["InfoBarSupportedTranslationLanguages"], $_ftjlo["InfoBarLinksArray"]);
     
     mysql_free_result($_QL8i1);
    }

  }

  if(isset($_ftjlo["PlaceHolderReplacements"]) && count($_ftjlo["PlaceHolderReplacements"]) > 0) {
     //    $_ft6f8[] = array("fieldname" => $_QltJO, "value" => $_QltJO);
     foreach($_ftjlo["PlaceHolderReplacements"] as $key => $_QltJO){
         $_ftjlo['fieldname['.$key.']'] = $_QltJO["fieldname"];
         $_ftjlo['replacementtext['.$key.']'] = $_QltJO["value"];
     }
  }

  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:CREATEDATE>", "</SHOW:CREATEDATE>", $_ftjlo["CREATEDATE"] );
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:INTERNALTABLENAME>", "</SHOW:INTERNALTABLENAME>", $_ftjlo["CampaignToNATableName"] );
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:OPENINGSCOUNT>", "</SHOW:OPENINGSCOUNT>", $_ftjlo["OpeningsCount"]);

  if(isset($_ftjlo["EMailings"])){
    for($_Qli6J=0; $_Qli6J<count($_ftjlo["EMailings"]); $_Qli6J++)
      $_QLJfI = str_replace('name="EMailings[]" value="' .$_ftjlo["EMailings"][$_Qli6J] . '"',  'name="EMailings[]" value="' .$_ftjlo["EMailings"][$_Qli6J] . '" checked="checked"', $_QLJfI);
    unset($_ftjlo["EMailings"]);
  }
  
  // Infobar  
  if(defined("SWM")){
    $_68tlt = "";
    if(!empty($_ftjlo["TemplatesPath"]))
      $_68tlt = InstallPath . _LPC1C($_ftjlo["TemplatesPath"]);
      else
      $_68tlt = InstallPath . _LPC1C("na");
    
    $_61il8 = _LBLDQ($_68tlt);  
    $_Ift08 = _L81DB($_QLJfI, "<InfoBarSchemeColor>");
    $_Ql0fO = "";
    foreach($_61il8 as $key => $_QltJO){
      $_Ql0fO .= $_Ift08;
      $_Ql0fO = str_replace("colorValue", $key, $_Ql0fO);
      $_Ql0fO = str_replace("colorName", $_QltJO, $_Ql0fO);
    }
    $_QLJfI = _L81BJ($_QLJfI, "<InfoBarSchemeColor>", "", $_Ql0fO);
    
    $_Ift08 = _L81DB($_QLJfI, "<InfoBarSrcLanguage>");
    _LBLPR($_j8l18);
    $_Ql0fO = "";
    for($_Qli6J=0; $_Qli6J<count($_j8l18); $_Qli6J++){
      $_Ql0fO .= $_Ift08;
      $_Ql0fO = str_replace("LangCode", $_j8l18[$_Qli6J]["code"], $_Ql0fO);
      $_Ql0fO = str_replace("LangName", $_j8l18[$_Qli6J]["Name"], $_Ql0fO);
    }
    $_QLJfI = _L81BJ($_QLJfI, "<InfoBarSrcLanguage>", "", $_Ql0fO);
      
    $_61LOI = _L81DB($_QLJfI, "<InfoBarLinks>");
    $_Ql0fO = "";
    
    $_jfLj1 = new TAltBrowserLinkInfoBarLinkType(); // < PHP 5.3
    
    $ML = $_ftjlo;
    // $ML["InfoBarLinksArray"] ==> Array Of TAltBrowserLinkInfoBarLink
    foreach($ML["InfoBarLinksArray"] as $_Qli6J => $_QltJO){
      $_Ql0fO .= $_61LOI;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LINKTEXT>", "", $ML["InfoBarLinksArray"][$_Qli6J]->internalCaption);
      
      if($ML["InfoBarLinksArray"][$_Qli6J]->Checked)
        $_Ql0fO = str_replace('name="Link[X]"', 'name="Link[X]"' . " " . 'checked="checked"', $_Ql0fO);

      $_Ql0fO = _L81BJ($_Ql0fO, "<URL[X]>", "", $ML["InfoBarLinksArray"][$_Qli6J]->URL);  
      $_Ql0fO = _L81BJ($_Ql0fO, "<Title[X]>", "", $ML["InfoBarLinksArray"][$_Qli6J]->Title);  
      $_Ql0fO = _L81BJ($_Ql0fO, "<Text[X]>", "", $ML["InfoBarLinksArray"][$_Qli6J]->Text);  
      
      if(
         $ML["InfoBarLinksArray"][$_Qli6J]->LinkType == $_jfLj1->abtTranslate ||
         $ML["InfoBarLinksArray"][$_Qli6J]->LinkType == $_jfLj1->abtEntries ||
         $ML["InfoBarLinksArray"][$_Qli6J]->LinkType == $_jfLj1->abtYears ||
         $ML["InfoBarLinksArray"][$_Qli6J]->LinkType == $_jfLj1->abtHome ||
         $ML["InfoBarLinksArray"][$_Qli6J]->LinkType == $_jfLj1->abtRSS ||
         $ML["InfoBarLinksArray"][$_Qli6J]->LinkType == $_jfLj1->abtAttachments
        )
             $_Ql0fO = str_replace('name="URL[X]"', 'name="URL[X]"' . " " .  'readonly="readonly"', $_Ql0fO);
      
      if(
         $ML["InfoBarLinksArray"][$_Qli6J]->LinkType == $_jfLj1->abtEntries ||
         $ML["InfoBarLinksArray"][$_Qli6J]->LinkType == $_jfLj1->abtYears 
         ){
             $_Ql0fO = str_replace('name="Link[X]"', 'name="Link[X]"' . " " .  'onclick="return false;"', $_Ql0fO);
             $_Ql0fO = str_replace('name="Title[X]"', 'name="Title[X]"' . " " .  'readonly="readonly"', $_Ql0fO);
             $_Ql0fO = str_replace('name="Text[X]"', 'name="Text[X]"' . " " .  'readonly="readonly"', $_Ql0fO);
         }    

      $_Ql0fO = str_replace('="Link[X]"', '="Link[' . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . ']"', $_Ql0fO);
      $_Ql0fO = str_replace('"LinkX"', '"Link' . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . '"', $_Ql0fO);
      $_Ql0fO = str_replace('="URL[X]"', '="URL[' . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . ']"', $_Ql0fO);
      $_Ql0fO = str_replace("'URL[X]'", "'URL[" . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . "]'", $_Ql0fO);
      $_Ql0fO = str_replace('="Title[X]"', '="Title[' . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . ']"', $_Ql0fO);
      $_Ql0fO = str_replace('="Text[X]"', '="Text[' . $ML["InfoBarLinksArray"][$_Qli6J]->LinkType . ']"', $_Ql0fO);
     
    }
    $_QLJfI = _L81BJ($_QLJfI, "<InfoBarLinks>", "", $_Ql0fO);
    
    $_QLJfI = str_replace("browser_altbrowserlinkplaceholders.htm", _LOC8P() . "browser_altbrowserlinkplaceholders.htm", $_QLJfI); 
  }

  $_QLJfI = _L8AOB($errors, $_ftjlo, $_QLJfI);
  $_QLJfI = _JJCCF($_QLJfI);

  $_ICI0L = "";

  $_QLJfI = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_ICI0L, $_QLJfI);
  print $_QLJfI;

  function _LFD6P(&$_j6jLo, $_I6tLJ) {
    global $_j6JfL, $_ItI0o, $_ItIti, $Username, $UserId, $_QLttI;

    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM $_j6JfL";
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
    if($_j6jLo == 0) {
      $_ftJ8J = _L8D00(TablePrefix.$Username."_".trim($_POST['Name'])."_c_na_ref");
      $_POST["CampaignToNATableName"] = $_ftJ8J;
      $_I6tLJ["CampaignToNATableName"] = $_ftJ8J;

      $_QLfol = "INSERT INTO $_j6JfL SET CreateDate=NOW(), `CampaignToNATableName`="._LRAFO($_ftJ8J);
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
      $_QLO0f=mysql_fetch_row($_QL8i1);
      $_j6jLo = $_QLO0f[0];
      mysql_free_result($_QL8i1);

      $key = md5(sprintf("%d %d %s %s", $_j6jLo, $UserId, date("r"), $_j6JfL));
      $_QLfol = "UPDATE $_j6JfL SET UniqueID="._LRAFO($key)." WHERE id=$_j6jLo";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);

      $_IiIlQ = join("", file(_LOCFC()."newsletterarchive.sql"));
      $_IiIlQ = str_replace('`TABLE_CAMPAIGNTONEWSLETTERARCHIVE`', "`$_ftJ8J`", $_IiIlQ);

      $_IijLl = explode(";", $_IiIlQ);

      for($_Qli6J=0; $_Qli6J<count($_IijLl); $_Qli6J++) {
        if(trim($_IijLl[$_Qli6J]) == "") continue;
        $_QL8i1 = mysql_query($_IijLl[$_Qli6J]." CHARSET=" . DefaultMySQLEncoding, $_QLttI);
        if(!$_QL8i1)
          $_QL8i1 = mysql_query($_IijLl[$_Qli6J], $_QLttI);
        if(!$_QL8i1){
          _L8D88($_IijLl[$_Qli6J]);
        }
      }

    }


    $_QLfol = "UPDATE $_j6JfL SET ";
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
           $_Io01j[] = "`$key`="._LRAFO(trim($_I6tLJ[$key]));
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

    $_ft6f8 = array();
    if(!empty($_I6tLJ["PlaceHoldersAction"]) && $_I6tLJ["PlaceHoldersAction"] == 2 && !empty($_I6tLJ["fieldname"])){
       foreach($_I6tLJ["fieldname"] as $key => $_QltJO){
         if(empty($_QltJO) || $_QltJO == "none") continue;
         $_ftf0t = "";
         if(!empty($_I6tLJ["replacementtext"][$key]))
             $_ftf0t = $_I6tLJ["replacementtext"][$key];
         $_ft6f8[] = array("fieldname" => $_QltJO, "value" => $_ftf0t);
       }
    }
    $_QLfol .= ", PlaceHolderReplacements="._LRAFO(serialize($_ft6f8));

    // to show it
    $_POST["PlaceHolderReplacements"] = $_ft6f8;

    $_QLfol .= " WHERE id=$_j6jLo";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }


    if(!isset($_ftJ8J))
      $_ftJ8J = $_I6tLJ["CampaignToNATableName"];

    // *********** EMailings
    $_QLfol = "DELETE FROM $_ftJ8J";
    mysql_query($_QLfol, $_QLttI);
    reset($_I6tLJ["EMailings"]);
    $_QLfol = "INSERT INTO `$_ftJ8J` (`campaigns_id` ) VALUES ";
    $_Io01j = array();
    foreach($_I6tLJ["EMailings"] as $key => $_QltJO) {
       $_QltJO = intval($_QltJO);
       if($_QltJO == 0) continue;
       $_Io01j[] = "($_QltJO)";
    }
    if(count($_Io01j) > 0) {
      $_QLfol .= join(", ", $_Io01j);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (!$_QL8i1) {
          _L8D88($_QLfol);
          exit;
      }
    }




  }
?>
