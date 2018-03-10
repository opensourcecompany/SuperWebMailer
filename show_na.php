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
  include_once("templates.inc.php");
  include_once("replacements.inc.php");

  if( empty($_GET["na"]) || empty($_GET["newsletterarchive"]) || empty($_GET["nauser"]) ) {
    print $commonmsgNewsletterArchiveNotFound;
    exit;
  }

  $_GET["nauser"] = intval($_GET["nauser"]);
  $_GET["newsletterarchive"] = intval($_GET["newsletterarchive"]);
  $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE id=".$_GET["nauser"];
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    print $commonmsgNewsletterArchiveNotFound;
    exit;
  }
  $_ICQQo = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  if($_ICQQo["UserType"] != "Admin") {
    print $commonmsgNewsletterArchiveNotFound;
    exit;
  }

  $INTERFACE_LANGUAGE = $_ICQQo["Language"];

  _OP0D0($_ICQQo);
  _OP0AF($_ICQQo["id"]);
  _OP10J($INTERFACE_LANGUAGE);
  _LQLRQ($INTERFACE_LANGUAGE);
  $_6OliC = ScriptBaseURL."show_na.php";
  $_6o0C0 = "na=$_GET[na]&newsletterarchive=$_GET[newsletterarchive]&nauser=$_GET[nauser]";

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  // count it
  $_QJlJ0 = "UPDATE $_IC1lt SET OpeningsCount=OpeningsCount + 1 WHERE id=".$_GET["newsletterarchive"]." AND UniqueID="._OPQLR($_GET["na"]);
  mysql_query($_QJlJ0, $_Q61I1);

  $_QJlJ0 = "SELECT *, UNIX_TIMESTAMP(CreateDate) AS CreateDateUnixTime FROM $_IC1lt WHERE id=".$_GET["newsletterarchive"]." AND UniqueID="._OPQLR($_GET["na"]);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    print $commonmsgNewsletterArchiveNotFound;
    exit;
  }
  $_6o161 = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  $_IJjll = InstallPath."na/de";
  if(!empty($_6o161["TemplatesPath"]))
     $_IJjll = $_6o161["TemplatesPath"];
  $_IJjll = _OBLDR($_IJjll);

  # campaigns_id
  $_QJlJ0 = "SELECT campaigns_id FROM $_6o161[CampaignToNATableName]";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    print $commonmsgNewsletterArchiveNoCampaignsFound;
    exit;
  }
  $_6QLo6 = array();
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    $_QJlJ0 = "SELECT CurrentSendTableName, ArchiveTableName FROM $_Q6jOo WHERE id=$_Q6Q1C[campaigns_id]";
    $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q8Oj8 || mysql_num_rows($_Q8Oj8) == 0) continue;
    $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8);
    mysql_free_result($_Q8Oj8);
    $_6QLo6[$_Q6Q1C["campaigns_id"]] = array("CurrentSendTableName" => $_Q8OiJ["CurrentSendTableName"], "ArchiveTableName" => $_Q8OiJ["ArchiveTableName"]);
  }
  mysql_free_result($_Q60l1);

  if(isset($_GET["showRSS"]) ) {
    _LOP06();
    exit;
  }

  if(isset($_GET["selectedYear"]) && isset($_GET["showEntry"]) && isset($_GET["showContent"]) ) {
    _LOLLO($_GET["selectedYear"], $_GET["showEntry"]);
    exit;
  }

  SetHTMLHeaders($_Q6QQL);

  if(isset($_GET["selectedYear"]) && isset($_GET["showEntry"]) ) {
    _LOOEQ($_GET["selectedYear"], $_GET["showEntry"]);
    exit;
  }

  if(isset($_GET["selectedYear"]) ) {
    _LOOPB($_GET["selectedYear"]);
    exit;
  }

  _LOQFF();

  function _LOQFF() {
    global $_IJjll, $_6o161;

    $_QJCJi = join("", file($_IJjll."na_start.htm"));

    $_QJCJi = str_replace('<!--STARTPAGETITLE//-->', $_6o161["StartPageTitle"], $_QJCJi);
    $_QJCJi = str_replace('<!--STARTPAGEHEADLINE//-->', $_6o161["StartPageHeadline"], $_QJCJi);
    $_QJCJi = str_replace('<!--YEARLABEL//-->', $_6o161["YearsLabel"], $_QJCJi);
    $_QJCJi = str_replace('<!--TEXTBEHINDYEARS//-->', $_6o161["TextBehindYears"], $_QJCJi);

    $_6o1O0 = "";
    if($_6o161["ShowImpressum"]){
      $_6o1O0 = $_6o161["ImpressumText"];
      if(!$_6o161["ImpressumIsHTML"])
         $_6o1O0 = str_replace("\n", "<br />", $_6o1O0);
    }

    $_QJCJi = str_replace('<!--IMPRESSUM//-->', $_6o1O0, $_QJCJi);

    $_QJCJi = _LOJQJ($_QJCJi);

    if($_6o161["LinkToSWM"])
      $_QJCJi = str_ireplace('</body>', '<span style="font-size: 8pt">Powered by <a href="PRODUCTURL" target="_blank">PRODUCTAPPNAME</a></span>'.'</body>', $_QJCJi);

    print _LORBR($_QJCJi);
  }

function _LOOPB($selectedYear) {
  global $_IJjll, $_6o161, $_6OliC, $_6o0C0, $_Q6QQL;

  $_QJCJi = join("", file($_IJjll."na_year.htm"));


  //
  $_QJCJi = str_replace('<!--HEADLINEFORSELECTEDYEAR//-->', _LO6OF($selectedYear), $_QJCJi);
  $_QJCJi = str_replace('<!--YEARLABEL//-->', $_6o161["YearsLabel"], $_QJCJi);
  $_QJCJi = str_replace('<!--LINKLABELTOMAINARCHIVEPAGE//-->', $_6o161["LinkLabelToMainArchive"], $_QJCJi);
  $_6o1O0 = "";
  if($_6o161["ShowImpressum"]){
    $_6o1O0 = $_6o161["ImpressumText"];
    if(!$_6o161["ImpressumIsHTML"])
       $_6o1O0 = str_replace("\n", "<br />", $_6o1O0);
  }
  $_QJCJi = str_replace('<!--IMPRESSUM//-->', $_6o1O0, $_QJCJi);

  $_QJCJi = _LOJQJ($_QJCJi);

  $_QJCJi = str_replace('"na_start"', '"'.$_6OliC."?".$_6o0C0.'"', $_QJCJi);

  //
  $_6oQ8Q = array();
  _LO6QF($selectedYear, $_6oQ8Q);

  $_Qf1t6 = _OP81D($_QJCJi, "<!--NLENTRY_BEGIN//-->", "<!--NLENTRY_END//-->");

  $_Q8otJ = _LO81C();

  $_6o161["PlaceHolderReplacements"] = @unserialize($_6o161["PlaceHolderReplacements"]);
  if($_6o161["PlaceHolderReplacements"] === false)
    $_6o161["PlaceHolderReplacements"] = array();

  if(count($_6o161["PlaceHolderReplacements"]) > 0) {

     foreach($_6o161["PlaceHolderReplacements"] as $key => $_Q6ClO){
         $_Q8otJ[$_Q6ClO["fieldname"]] = $_Q6ClO["value"];
     }

  }

  $_Q6ICj = "";
  $_Q6llo = 0;
  # sort creates new keys
  foreach($_6oQ8Q as $key => $_Q6ClO) {
   $_6oQif = $_Q6ClO;
   $_Q66jQ = $_Qf1t6;
   $_Q66jQ = str_replace('<!--NEWSLETTERENTRYTEXT//-->', _LO6LP($_6oQif["StartSendDateTimeFormated"], $_6oQif["AYear"], $_6oQif["AMonth"], $_6oQif["ADay"]), $_Q66jQ);
   $_I6016 = _L1ERL($_Q8otJ, 0, $_6oQif["MailSubject"], $_Q6QQL, false, array());
   $_Q66jQ = str_replace('<!--NEWSLETTERENTRYTITLE//-->', $_I6016, $_Q66jQ);

   //
   $_Q66jQ = str_replace('"na_day"', '"'.$_6OliC.'?showEntry='.$_Q6llo.'&selectedYear='.$selectedYear."&".$_6o0C0.'"', $_Q66jQ);

   $_Q6ICj .= $_Q66jQ;
   $_Q6llo++;
  }

  $_QJCJi = _OPR6L($_QJCJi, "<!--NLENTRY_BEGIN//-->", "<!--NLENTRY_END//-->", $_Q6ICj);

  if($_6o161["LinkToSWM"])
    $_QJCJi = str_ireplace('</body>', '<span style="font-size: 8pt">Powered by <a href="PRODUCTURL" target="_blank">PRODUCTAPPNAME</a></span>'.'</body>', $_QJCJi);

  print _LORBR($_QJCJi);
}

function _LOOEQ($selectedYear, $showEntry) {
  global $_IJjll, $_6o161, $_6OliC, $_6o0C0, $commonmsgNewsletterArchiveEntryNotFound;
  global $commonmsgNewsletterArchiveNoFramesError, $commonmsgNewsletterArchiveNoText, $_Q6QQL;

  $_QJCJi = join("", file($_IJjll."na_day.htm"));

  $_6oQ8Q = array();
  _LO6QF($selectedYear, $_6oQ8Q);

  $_6oIoL = "";
  if ( ($showEntry < 0) || ($showEntry > count($_6oQ8Q) - 1) )  {
    $_6oIoL = $commonmsgNewsletterArchiveNoText;
  }
  $_Q6llo = 0;
  # sort creates new keys
  foreach($_6oQ8Q as $key => $_Q6ClO) {
    if($_Q6llo == $showEntry){
      $_6oQif = $_Q6ClO;
      break;
    }
    $_Q6llo++;
  }
  if(!isset($_6oQif)){
    print $commonmsgNewsletterArchiveEntryNotFound;
    exit;
  }

  $_QJCJi = str_replace('<!--NEWSLETTERENTRYTEXT//-->', _LO6LP($_6oQif["StartSendDateTimeFormated"], $_6oQif["AYear"], $_6oQif["AMonth"], $_6oQif["ADay"]), $_QJCJi);


  $_Q8otJ = _LO81C();
  $_6o161["PlaceHolderReplacements"] = @unserialize($_6o161["PlaceHolderReplacements"]);
  if($_6o161["PlaceHolderReplacements"] === false)
    $_6o161["PlaceHolderReplacements"] = array();

  if(count($_6o161["PlaceHolderReplacements"]) > 0) {

     foreach($_6o161["PlaceHolderReplacements"] as $key => $_Q6ClO){
         $_Q8otJ[$_Q6ClO["fieldname"]] = $_Q6ClO["value"];
     }

  }

  $_I6016 = _L1ERL($_Q8otJ, 0, $_6oQif["MailSubject"], $_Q6QQL, false, array());

  $_QJCJi = str_replace('<!--NEWSLETTERENTRYTITLE//-->', $_I6016, $_QJCJi);

  $_QJCJi = str_replace('<!--YEARLABEL//-->', $_6o161["YearsLabel"], $_QJCJi);
  $_QJCJi = str_replace('<!--LINKLABELTOMAINARCHIVEPAGE//-->', $_6o161["LinkLabelToMainArchive"], $_QJCJi);

  $_6o1O0 = "";
  if($_6o161["ShowImpressum"]){
    $_6o1O0 = $_6o161["ImpressumText"];
    if(!$_6o161["ImpressumIsHTML"])
       $_6o1O0 = str_replace("\n", "<br />", $_6o1O0);
  }

  $_QJCJi = str_replace('<!--IMPRESSUM//-->', $_6o1O0, $_QJCJi);
  $_QJCJi = str_replace('<!--LINKLABELPREV//-->', $_6o161["LinkLabelPrev"], $_QJCJi);
  $_QJCJi = str_replace('<!--LINKLABELNEXT//-->', $_6o161["LinkLabelNext"], $_QJCJi);
  $_QJCJi = str_replace('<!--HEADLINEFORPRINTING//-->', $_6o161["PrintingLabel"], $_QJCJi);
  $_QJCJi = str_replace('<!--HEADLINEFORSELECTEDYEAR//-->', _LO6OF($selectedYear), $_QJCJi);

  if(!$_6o161["SortOrderNewToOld"]) {
    $_Q6llo = $showEntry - 1;
    if($_Q6llo < 0) $_Q6llo = 0;
    $_QJCJi = str_replace('"na_day_prev"', '"'.$_6OliC.'?showEntry='.$_Q6llo.'&selectedYear='.$selectedYear."&$_6o0C0".'"' , $_QJCJi);

    if($showEntry <= 0) {
      $_QJCJi = _OP6PQ($_QJCJi, "<!--LINKLABELPREV_IF_NOT_FIRST_BEGIN//-->", "<!--LINKLABELPREV_IF_NOT_FIRST_END//-->");
    } else {
      $_QJCJi = _OP6PQ($_QJCJi, "<!--LINKLABELPREV_IF_FIRST_BEGIN//-->", "<!--LINKLABELPREV_IF_FIRST_END//-->");
    }

    $_Q6llo = $showEntry + 1;
    if($_Q6llo > count($_6oQ8Q) - 1) $_Q6llo = count($_6oQ8Q) - 1;
    $_QJCJi = str_replace('"na_day_next"', '"'.$_6OliC.'?showEntry='.$_Q6llo.'&selectedYear='.$selectedYear."&$_6o0C0".'"' , $_QJCJi);

    if($showEntry >= count($_6oQ8Q) - 1) {
      $_QJCJi = _OP6PQ($_QJCJi, "<!--LINKLABELNEXT_IF_NOT_LAST_BEGIN//-->", "<!--LINKLABELNEXT_IF_NOT_LAST_END//-->");
    } else {
      $_QJCJi = _OP6PQ($_QJCJi, "<!--LINKLABELNEXT_IF_LAST_BEGIN//-->", "<!--LINKLABELNEXT_IF_LAST_END//-->");
    }
  } else {

    $_Q6llo = $showEntry - 1;
    if($_Q6llo < 0) $_Q6llo = 0;
    $_QJCJi = str_replace('"na_day_next"', '"'.$_6OliC.'?showEntry='.$_Q6llo.'&selectedYear='.$selectedYear."&$_6o0C0".'"' , $_QJCJi);

    if($showEntry <= 0) {
      $_QJCJi = _OP6PQ($_QJCJi, "<!--LINKLABELNEXT_IF_NOT_LAST_BEGIN//-->", "<!--LINKLABELNEXT_IF_NOT_LAST_END//-->");
    } else {
      $_QJCJi = _OP6PQ($_QJCJi, "<!--LINKLABELNEXT_IF_LAST_BEGIN//-->", "<!--LINKLABELNEXT_IF_LAST_END//-->");
    }

    $_Q6llo = $showEntry + 1;
    if($_Q6llo > count($_6oQ8Q) - 1) $_Q6llo = count($_6oQ8Q) - 1;
    $_QJCJi = str_replace('"na_day_prev"', '"'.$_6OliC.'?showEntry='.$_Q6llo.'&selectedYear='.$selectedYear."&$_6o0C0".'"' , $_QJCJi);

    if($showEntry >= count($_6oQ8Q) - 1) {
      $_QJCJi = _OP6PQ($_QJCJi, "<!--LINKLABELPREV_IF_NOT_FIRST_BEGIN//-->", "<!--LINKLABELPREV_IF_NOT_FIRST_END//-->");
    } else {
      $_QJCJi = _OP6PQ($_QJCJi, "<!--LINKLABELPREV_IF_FIRST_BEGIN//-->", "<!--LINKLABELPREV_IF_FIRST_END//-->");
    }

  }

  $_QJCJi = str_replace('"na_start"', '"'.$_6OliC."?$_6o0C0".'"', $_QJCJi);

  $_QJCJi = str_replace('"na_year_selected"', '"'.$_6OliC."?selectedYear=".$selectedYear."&$_6o0C0".'"', $_QJCJi);

  $_QJCJi = str_replace('<!--NEWSLETTERTEXT//-->', $commonmsgNewsletterArchiveNoFramesError, $_QJCJi);
  $_QJCJi = str_replace('<!--LABELSHOWNEWSLETTERWITHOUTFRAMESTEXT//-->', $_6o161["ShowNewsletterWithoutFramesText"], $_QJCJi);


  if($_6oIoL != $commonmsgNewsletterArchiveNoText ) {
       $_QJCJi = str_replace('[NEWSLETTERCONTENTLINK]', $_6OliC.'?showEntry='.$showEntry.'&selectedYear='.$selectedYear."&showContent=".rand(1, 1024)."&".$_6o0C0, $_QJCJi);
     }
     else
     $_QJCJi = str_replace('[NEWSLETTERCONTENTLINK]', "", $_QJCJi);

//

  if($_6o161["LinkToSWM"])
    $_QJCJi = str_ireplace('</body>', '<span style="font-size: 8pt">Powered by <a href="PRODUCTURL" target="_blank">PRODUCTAPPNAME</a></span>'.'</body>', $_QJCJi);

  print _LORBR($_QJCJi);

}

function _LOLLO($selectedYear, $showEntry) {
  global $_6o161, $_6OliC, $_6o0C0, $commonmsgNewsletterArchiveEntryNotFound;
  global $commonmsgNewsletterArchiveNoText, $_Q6QQL;


  $_6oQ8Q = array();
  _LO6QF($selectedYear, $_6oQ8Q);

  $_6oIoL = "";
  if ( ($showEntry < 0) || ($showEntry > count($_6oQ8Q) - 1) )  {
    $_6oIoL = $commonmsgNewsletterArchiveNoText;
  }
  $_Q6llo = 0;
  # sort creates new keys
  foreach($_6oQ8Q as $key => $_Q6ClO) {
    if($_Q6llo == $showEntry){
      $_6oQif = $_Q6ClO;
      break;
    }
    $_Q6llo++;
  }
  if(!isset($_6oQif)){
    print $commonmsgNewsletterArchiveEntryNotFound;
    exit;
  }

  $_Q8otJ = _LO81C();

  $_6o161["PlaceHolderReplacements"] = @unserialize($_6o161["PlaceHolderReplacements"]);
  if($_6o161["PlaceHolderReplacements"] === false)
    $_6o161["PlaceHolderReplacements"] = array();

  if(count($_6o161["PlaceHolderReplacements"]) > 0) {

     foreach($_6o161["PlaceHolderReplacements"] as $key => $_Q6ClO){
         $_Q8otJ[$_Q6ClO["fieldname"]] = $_Q6ClO["value"];
     }

  }

  $_I6016 = $_6oQif["MailSubject"];
  $_I6016 = _L1ERL($_Q8otJ, 0, $_I6016, $_Q6QQL, false, array());

  // Social media links
  $_Q8otJ['AltBrowserLink_SME'] = $_6OliC."?showEntry=$showEntry&selectedYear=$selectedYear"."&$_6o0C0";
  $_Q8otJ['AltBrowserLink_SME_URLEncoded'] = urlencode($_Q8otJ['AltBrowserLink_SME']);
  $_Q8otJ['Mail_Subject_ISO88591'] = ConvertString($_Q6QQL, "ISO-8859-1", $_I6016, false);
  $_Q8otJ['Mail_Subject_UTF8'] = $_I6016;
  $_Q8otJ['Mail_Subject_ISO88591_URLEncoded'] = urlencode($_Q8otJ['Mail_Subject_ISO88591']);
  $_Q8otJ['Mail_Subject_UTF8_URLEncoded'] = urlencode($_Q8otJ['Mail_Subject_UTF8']);
  // Social media links /


  if($_6oQif["MailFormat"] == 'PlainText')
    $_6oIoL = $_6oQif["MailPlainText"];
  if($_6oQif["MailFormat"] != 'PlainText') {
    $_6oIoL = $_6oQif["MailHTMLText"];
    $_6oIoL = _OB8O0("<title>", "</title>", $_6oIoL, $_I6016);
  }
  $_6oIoL = _L1ERL($_Q8otJ, 0, $_6oIoL, $_Q6QQL, $_6oQif["MailFormat"] != 'PlainText', array());

  $_6oIoL = str_replace('"'.$_6oQif["MailEncoding"].'"', '"'.$_Q6QQL.'"', $_6oIoL);

  if( $_6oQif["MailFormat"] == 'PlainText' ) {
    $_Q8otJ = @file(_O68QF()."blank.htm");
    if($_Q8otJ)
      $_QJCJi = join("", $_Q8otJ);
      else
      $_QJCJi = join("", file(InstallPath._O68QF()."blank.htm"));


     if(strpos($_6oIoL, "\n") !== false)
        $_II1Ot = explode("\n", $_6oIoL);
        else
        $_II1Ot = explode("\r", $_6oIoL);

     $_6oIoL = str_replace("<body>", "<body>".join("<br />", $_II1Ot), $_QJCJi);
     $_6oIoL = _OB8O0("<title>", "</title>", $_6oIoL, $_6oQif["MailSubject"]);
     $_6oIoL = str_replace("noindex,nofollow", "index,follow", $_6oIoL);
     $_6oIoL = str_replace("no-cache", "cache", $_6oIoL);
  }

  SetHTMLHeaders($_Q6QQL);
  print _LORBR($_6oIoL, false, $_6o161["RemoveMailToLinks"]);
}

function _LOJQJ($_QJCJi, $_JjOC0 = "") {
  global $_6QLo6, $_6o161, $_6OliC, $_6o0C0;

  $_6ojOi = array();
  _LO61O($_6ojOi);

  $_Qf1t6 = _OP81D($_QJCJi, "<!--YEARS_BEGIN//-->", "<!--YEARS_END//-->");

  if ($_Qf1t6 == "")
    return $_QJCJi;

  $_Q6ICj = "";
  for($_Q6llo=0; $_Q6llo<count($_6ojOi); $_Q6llo++) {
   if($_JjOC0 == $_6ojOi[$_Q6llo]) continue;

   $_Q66jQ = str_replace('<!--YEARNUMBER//-->', $_6ojOi[$_Q6llo], $_Qf1t6);
   $_Q66jQ = str_replace('"na_year"', '"'.$_6OliC."?selectedYear=".$_6ojOi[$_Q6llo]."&$_6o0C0".'"', $_Q66jQ);
   $_Q66jQ = str_replace('"na_start"', '"'.$_6OliC."?$_6o0C0".'"', $_Q66jQ);
   $_Q6ICj .= $_Q66jQ;
  }

  $_QJCJi = _OPR6L($_QJCJi, "<!--YEARS_BEGIN//-->", "<!--YEARS_END//-->", $_Q6ICj);

  return $_QJCJi;

}


function _LO61O(&$_6ojOi) {
  global $_6QLo6, $_Q61I1;

  reset($_6QLo6);
  foreach($_6QLo6 as $key => $_Q6ClO) {
    $_QJlJ0 = "SELECT DISTINCT YEAR(StartSendDateTime) As AYear FROM $_Q6ClO[CurrentSendTableName] ORDER BY AYear DESC";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C = mysql_fetch_row($_Q60l1)) {
       if(!in_array($_Q6Q1C[0], $_6ojOi))
          $_6ojOi[] = $_Q6Q1C[0];
    }
    mysql_free_result($_Q60l1);
  }
  rsort($_6ojOi, SORT_NUMERIC);
}

function _LO6QF($_6ojio, &$_6oQ8Q) {
  global $_6QLo6, $_If0Ql, $_Q6QiO, $_6o161, $_Q61I1;

  $_6ojio = intval($_6ojio);
  reset($_6QLo6);
  foreach($_6QLo6 as $key => $_Q6ClO) {
    $_QJlJ0 = "SELECT *, DATE_FORMAT(StartSendDateTime, $_If0Ql) AS StartSendDateTimeFormated, UNIX_TIMESTAMP(StartSendDateTime) AS StartSendDateTimeUNIXTIME, YEAR(StartSendDateTime) As AYear, MONTH(StartSendDateTime) As AMonth, DAYOFMONTH(StartSendDateTime) As ADay FROM $_Q6ClO[CurrentSendTableName] LEFT JOIN $_Q6ClO[ArchiveTableName] ON $_Q6ClO[ArchiveTableName].SendStat_id=$_Q6ClO[CurrentSendTableName].id WHERE YEAR(StartSendDateTime)=$_6ojio";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_Q6Q1C["Campaigns_id"] = $key;
      $_6oQ8Q[$_Q6Q1C["StartSendDateTime"]] = $_Q6Q1C;
    }
    mysql_free_result($_Q60l1);
  }
  if($_6o161["SortOrderNewToOld"])
    krsort($_6oQ8Q);
    else
    ksort($_6oQ8Q);
}

function _LO6OF($selectedYear) {
  global $_IJjll, $_6o161;
  $_QJCJi = $_6o161["YearsHeaderlineSelect"];
  $_QJCJi = str_ireplace('[NewsletterYear]', $selectedYear, $_QJCJi);
  return $_QJCJi;
}

//
function _LO6LP($Date, $_6ojio, $_6oJCO, $_6o6tO) {
  global $_IJjll, $_6o161;
  $_QJCJi = $_6o161["NewsletterEntryText"];
  $_QJCJi = str_ireplace('[NewsletterYear]', $_6ojio, $_QJCJi);
  $_QJCJi = str_ireplace('[NewsletterMonth]', $_6oJCO, $_QJCJi);
  $_QJCJi = str_ireplace('[NewsletterDay]', $_6o6tO, $_QJCJi);
  $_QJCJi = str_ireplace('[NewsletterDate]', $Date, $_QJCJi);
  $_QJCJi = str_ireplace('[NewsletterWeekNumber]', date("W", mktime(0, 0, 0, $_6oJCO, $_6o6tO, $_6ojio) ), $_QJCJi);
  $_QJCJi = str_ireplace('[NewsletterDayName]', date("l", mktime(0, 0, 0, $_6oJCO, $_6o6tO, $_6ojio) ), $_QJCJi);
  return $_QJCJi;
}

function _LO6D0($_ILCOl, $_ILi66, $_I6016) {
    $_6o6o8 = strpos($_I6016, $_ILCOl);
    if($_6o6o8 !== false) {
        $_6of68 = substr($_I6016,0,$_6o6o8);
        $_6ofoQ = substr($_I6016, $_6o6o8 + strlen($_ILCOl));
        return $_6of68.$_ILi66.$_6ofoQ;
    } else {
        return $_I6016;
    }
}

function _LORBR($_Q6ICj, $_6o8j8 = true, $_6ot1Q = false) {
 global $_6o161, $_Q6JJJ;

 $_Q6ICj = _OP6PQ($_Q6ICj, "[AltBrowserLink_begin]", "[AltBrowserLink_end]");
 $_Q6ICj = _OP6PQ($_Q6ICj, "<!--AltBrowserLink_begin//-->", "<!--AltBrowserLink_end//-->");

 if(!empty($_6o161["HeadDescription"]))
   $_Q6ICj = _LO6D0("</head>", '<meta name="description" content="'.$_6o161["HeadDescription"].'">'.$_Q6JJJ.'</head>', $_Q6ICj);

 if(!empty($_6o161["HeadKeywords"]))
   $_Q6ICj = _LO6D0("</head>", '<meta name="keywords" content="'.$_6o161["HeadKeywords"].'">'.$_Q6JJJ.'</head>', $_Q6ICj);

 if(!empty($_6o161["HeadAutor"])){
   $_Q6ICj = _LO6D0("</head>", '<meta name="author" content="'.$_6o161["HeadAutor"].'">'.$_Q6JJJ.'</head>', $_Q6ICj);
   $_Q6ICj = _LO6D0("</head>", '<meta name="copyright" content="'.$_6o161["HeadAutor"].'">'.$_Q6JJJ.'</head>', $_Q6ICj);
 }

 _LJ81E($_Q6ICj);

 //

 if($_6o8j8) {

   if(!$_6o161["UserDefinedColorsAndFonts"])
     $_Q6ICj = _LO6D0("</head>", '<link rel="stylesheet" type="text/css" href="css/na.css" />'.$_Q6JJJ.'</head>', $_Q6ICj);
     else{

       $_j8Li0 = "";

       $_II1Ot =  explode(",", $_6o161["FontName"]);
       for($_Q6llo=0; $_Q6llo<count($_II1Ot); $_Q6llo++) {
           $_II1Ot[$_Q6llo] = trim($_II1Ot[$_Q6llo]);
           if(strpos($_II1Ot[$_Q6llo], " ") !== false)
              $_II1Ot[$_Q6llo] = "'" . $_II1Ot[$_Q6llo] . "'";
         }

       $_j8Li0 .= 'body         { ';
       if ($_6o161["FontName"] != '')
          $_j8Li0 .= 'font-family: '. join(', ', $_II1Ot);

       $_j8Li0 .= '; font-size: ' . $_6o161["FontSize"] . ';' . $_Q6JJJ;
       $_j8Li0 .= '    color: ' . $_6o161["FontColor"] . '; font-weight: ';

       if ($_6o161["FontStyle"] == "normal")
          $_j8Li0 .= 'normal';
          else
          if ($_6o161["FontStyle"] == "bold")
            $_j8Li0 .= 'bold';
          else
          if ($_6o161["FontStyle"] == "italic")
            $_j8Li0 .= 'italic';


       $_j8Li0 .= '; background-color: ' . $_6o161["BackgroundColor"] . ' } ' . $_Q6JJJ;

       $_j8Li0 .= sprintf ('a {color:%s;}' . $_Q6JJJ . 'a:link {color:%s;}' . $_Q6JJJ . 'a:active {color:%s;}' . $_Q6JJJ . 'a:hover {color:%s;}' . $_Q6JJJ . 'a:visited {color:%s;}' . $_Q6JJJ,
                $_6o161["link"],
                $_6o161["link"],
                $_6o161["alink"],
                $_6o161["alink"],
                $_6o161["vlink"]

              );


       $_j8Li0 .= 'h1           { font-size: ' . $_6o161["H1FontSize"] . '; }' . $_Q6JJJ;


       $_j8Li0 .= '.years       { ';

       $_II1Ot =  explode(",", $_6o161["NavFontName"]);
       for($_Q6llo=0; $_Q6llo<count($_II1Ot); $_Q6llo++) {
           $_II1Ot[$_Q6llo] = trim($_II1Ot[$_Q6llo]);
           if(strpos($_II1Ot[$_Q6llo], " ") !== false)
              $_II1Ot[$_Q6llo] = "'" . $_II1Ot[$_Q6llo] . "'";
         }


       if ($_6o161["NavFontName"] != '')
          $_j8Li0 .= 'font-family: '. join(', ', $_II1Ot);

       $_j8Li0 .= '; font-size: ' . $_6o161["NavFontSize"] . ';' . $_Q6JJJ;
       $_j8Li0 .= '    color: ' . $_6o161["NavFontColor"] . '; font-weight: ';

       if ($_6o161["NavFontStyle"] == "normal")
          $_j8Li0 .= 'normal';
          else
          if ($_6o161["NavFontStyle"] == "bold")
            $_j8Li0 .= 'bold';
          else
          if ($_6o161["NavFontStyle"] == "italic")
            $_j8Li0 .= 'italic';

       $_j8Li0 .= '; } ' . $_Q6JJJ;

       $_j8Li0 .= '.nlentry     { font-size: ' . $_6o161["EntryFontSize"] . '; }' . $_Q6JJJ;
       $_j8Li0 .= 'table.bg     { background-color: ' .$_6o161["NavBackgroundColor"] . ' }' . $_Q6JJJ;

       $_j8Li0 = '<style type="text/css">'.$_Q6JJJ."<!--".$_Q6JJJ.$_j8Li0.'//-->'.$_Q6JJJ.'</style>';
       $_Q6ICj = _LO6D0("</head>", $_j8Li0.$_Q6JJJ.'</head>', $_Q6ICj);
     }
   }

 if($_6ot1Q) {
   $_QOLIl = array();
   _OBAF1($_Q6ICj, $_QOLIl);
   for($_Q6llo=0; $_Q6llo<count($_QOLIl); $_Q6llo++)
      $_Q6ICj = str_replace($_QOLIl[$_Q6llo], "mailto:noemail", $_Q6ICj);
 }


 return $_Q6ICj;
}

function _LO81C() {
  global $_Qofjo, $INTERFACE_LANGUAGE, $_IIQI8, $_III0L, $_jQt18, $_Ij18l, $_I88i8, $_I8tjl, $_QOifL, $_Q61I1;
  #### normal placeholders
  $_QJlJ0 = "SELECT fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q8otJ=array();
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
   $_Q8otJ[$_Q6Q1C["fieldname"]] = "";
  }
  mysql_free_result($_Q60l1);
  # defaults
  foreach ($_IIQI8 as $key => $_Q6ClO)
   $_Q8otJ[$key] = "";

  // functions
  $_QJlJ0 = "SELECT Name FROM $_I88i8 ORDER BY Name";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
   $_Q8otJ[$_Q6Q1C["Name"]] = "";
  }
  mysql_free_result($_Q60l1);

  // textblocks
  $_QJlJ0 = "SELECT Name FROM $_I8tjl ORDER BY Name";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
   $_Q8otJ[$_Q6Q1C["Name"]] = "";
  }
  mysql_free_result($_Q60l1);

  #### special newsletter unsubscribe placeholders
  $_Ij0oj = array_merge($_III0L, $_jQt18, $_Ij18l, $_QOifL);
  reset($_Ij0oj);
  foreach ($_Ij0oj as $key => $_Q6ClO)
    $_Q8otJ[$key] = "";

  $_Q8otJ["MembersAge"] = "";

  return $_Q8otJ;
}

function _LOP06(){
 global $_6o161, $_Q6JJJ, $INTERFACE_LANGUAGE, $_6OliC, $_6o0C0, $_Q6QQL;

 if(!$_6o161["rssshowAll"]){
   $_6oQ8Q = array();
   _LO6QF(date("Y"), $_6oQ8Q);
 } else{
   $_6ojOi = array();
   _LO61O($_6ojOi);
   $_6oQ8Q = array();
   for($_Q6llo=0; $_Q6llo<count($_6ojOi); $_Q6llo++) {
     $_6oQif = array();
     _LO6QF($_6ojOi[$_Q6llo], $_6oQif);
     $_QL8Q8 = 0;
     foreach($_6oQif as $key => $_Q6ClO) {
       $_Q6ClO["internalSortId"] = $_QL8Q8;
       $_QL8Q8++;
       $_6oQif[$key] = $_Q6ClO;
     }

     $_6oQ8Q = array_merge($_6oQ8Q, $_6oQif);
   }
 }

 $_Q8otJ = @file(_O68QF()."rsstemplate.xml");
 if($_Q8otJ)
   $_QJCJi = join("", $_Q8otJ);
   else
   $_QJCJi = join("", file(InstallPath._O68QF()."rsstemplate.xml"));

 $_6otOI = "<item>"._OP81D($_QJCJi, "<item>", "</item>")."</item>";
 $_QJCJi = _OPR6L($_QJCJi, "<item>", "</item>", "<!--RSSFEED-->");

 $_QJCJi = _OP8CP($_QJCJi, "<title>", "</title>", "<![CDATA[".$_6o161["rssTitle"]."]]>");
 $_QJCJi = _OP8CP($_QJCJi, "<link>", "</link>", "<![CDATA[". $_6o161["rssLinkURL"]."]]>" );
 $_QJCJi = _OP8CP($_QJCJi, "<description>", "</description>", "<![CDATA[".$_6o161["rssDescription"]."]]>");
 $_QJCJi = _OP8CP($_QJCJi, "<language>", "</language>", "$INTERFACE_LANGUAGE");
 $_QJCJi = _OP8CP($_QJCJi, "<copyright>", "</copyright>", "<![CDATA[".$_6o161["rssCopyright"]."]]>");
 $_QJCJi = _OP8CP($_QJCJi, "<pubDate>", "</pubDate>", date("r", $_6o161["CreateDateUnixTime"]));

 $_Q8otJ = _LO81C();

 $_6o161["PlaceHolderReplacements"] = @unserialize($_6o161["PlaceHolderReplacements"]);
 if($_6o161["PlaceHolderReplacements"] === false)
   $_6o161["PlaceHolderReplacements"] = array();

 if(count($_6o161["PlaceHolderReplacements"]) > 0) {

    foreach($_6o161["PlaceHolderReplacements"] as $key => $_Q6ClO){
        $_Q8otJ[$_Q6ClO["fieldname"]] = $_Q6ClO["value"];
    }

 }


 $_6oO6j = "";
 # sort creates new keys
 $_QL8Q8 = 0;
 foreach($_6oQ8Q as $key => $_Q6ClO) {
   $_6oQif = $_Q6ClO;
   if(isset($_Q6ClO["internalSortId"]))
     $_QL8Q8 = $_Q6ClO["internalSortId"];
   $_Q66jQ = $_6otOI;

   $_I6016 = $_6oQif["MailSubject"];

   if($_6oQif["MailFormat"] == 'PlainText')
     $_6oIoL = $_6oQif["MailPlainText"];
   if($_6oQif["MailFormat"] != 'PlainText') {
     $_6oIoL = $_6oQif["MailHTMLText"];
     $_6oIoL = _OP6PQ($_6oIoL, "<style", "</style>"); // CSS in RSS not allowed
     $_6oIoL = _OB8O0("<title>", "</title>", $_6oIoL, $_I6016);

     $_jitLI = array();
     GetInlineFiles($_6oIoL, $_jitLI, true);
     for($_Q6llo=0; $_Q6llo< count($_jitLI); $_Q6llo++) {
       if(!@file_exists($_jitLI[$_Q6llo])) {
         $_QCoLj = _OBLCO(WebsiteURL).$_jitLI[$_Q6llo];
         $_6oIoL = str_replace($_jitLI[$_Q6llo], $_QCoLj, $_6oIoL);
       }
     }

   }
   $_I6016 = $_6oQif["MailSubject"];
   $_I6016 = _L1ERL($_Q8otJ, 0, $_I6016, $_Q6QQL, false, array());

   // Social media links
   $_Q8otJ['AltBrowserLink_SME'] = $_6OliC.""."?$_6o0C0&showRSS=1";
   $_Q8otJ['AltBrowserLink_SME_URLEncoded'] = urlencode($_Q8otJ['AltBrowserLink_SME']);
   $_Q8otJ['Mail_Subject_ISO88591'] = ConvertString($_Q6QQL, "ISO-8859-1", $_I6016, false);
   $_Q8otJ['Mail_Subject_UTF8'] = $_I6016;
   $_Q8otJ['Mail_Subject_ISO88591_URLEncoded'] = urlencode($_Q8otJ['Mail_Subject_ISO88591']);
   $_Q8otJ['Mail_Subject_UTF8_URLEncoded'] = urlencode($_Q8otJ['Mail_Subject_UTF8']);
   // Social media links /

   $_6oIoL = _L1ERL($_Q8otJ, 0, $_6oIoL, $_Q6QQL, $_6oQif["MailFormat"] != 'PlainText', array());

   $_6oIoL = str_replace('"'.$_6oQif["MailEncoding"].'"', '"'.$_Q6QQL.'"', $_6oIoL);

   $_Q66jQ = _OP8CP($_Q66jQ, "<title>", "</title>", "<![CDATA[".$_I6016."]]>");
   $_Q66jQ = _OP8CP($_Q66jQ, "<link>", "</link>", "<![CDATA[".$_6OliC.'?showEntry='.$_QL8Q8.'&selectedYear='.$_Q6ClO["AYear"]."&showContent=".rand(1, 1024)."&".$_6o0C0."]]>" );
   $_Q66jQ = _OP8CP($_Q66jQ, '<guid isPermaLink="false">', "</guid>", md5($_6oQif["StartSendDateTimeUNIXTIME"]));
   $_Q66jQ = _OP8CP($_Q66jQ, "<pubDate>", "</pubDate>", date("r", $_6oQif["StartSendDateTimeUNIXTIME"]));
   $_Q66jQ = _OP8CP($_Q66jQ, "<description>", "</description>", "<![CDATA[".$_6oIoL."]]>" );
   $_6oO6j .= $_Q6JJJ.$_Q66jQ;
   $_QL8Q8++;
 }
 $_QJCJi = str_replace("<!--RSSFEED-->", $_6oO6j, $_QJCJi);

 $_QJCJi = _OP6PQ($_QJCJi, "[AltBrowserLink_begin]", "[AltBrowserLink_end]");
 $_QJCJi = _OP6PQ($_QJCJi, "<!--AltBrowserLink_begin//-->", "<!--AltBrowserLink_end//-->");

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
 @header( 'Content-Type: text/xml; charset='.$_Q6QQL ) ;
 print $_QJCJi;
}

?>
