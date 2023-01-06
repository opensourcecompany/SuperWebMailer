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

 $_801j1 = "";
 $_JIQCl = version_compare(PHP_VERSION, "5.0.0") >= 0;

 if($_JIQCl) {
   include_once("ressources_array_access.inc.php");
 }

 function _JQRLR($INTERFACE_LANGUAGE){
    global $resourcestrings, $_801j1, $_JIQCl, $ShortDateFormat, $LongDateFormat;

    $INTERFACE_LANGUAGE = preg_replace( '/[^a-z]+/', '', strtolower( $INTERFACE_LANGUAGE ) );
    if(strlen($INTERFACE_LANGUAGE) > 3)
      $INTERFACE_LANGUAGE = substr($INTERFACE_LANGUAGE, 0, 3);

    if(empty($INTERFACE_LANGUAGE)) $INTERFACE_LANGUAGE = "de";

    if($_801j1 == $INTERFACE_LANGUAGE) return;

    $_I0lji = "./language/$INTERFACE_LANGUAGE.php";
    if(file_exists($_I0lji))
      include($_I0lji);
      else{
       $_I0lji = InstallPath."language/$INTERFACE_LANGUAGE.php";
       if(file_exists($_I0lji))
          include($_I0lji);
      }

    if($_JIQCl){
        if($_801j1 != "")
           unset($resourcestrings[$_801j1]);
      }
      else
      $resourcestrings = array();

    eval("\$lang = \$language_strings_".$INTERFACE_LANGUAGE.";");

    if(!$lang){
      print "Can't load correct language file in /language directory";
      exit;
    }

    // utf-8 encoding if necessary
    reset($lang);
    foreach ($lang as $key => $_QltJO) {
       $_QltJO = utf8_encode($_QltJO);
       $lang[$key] = $_QltJO;
    }

    $_801j1 = $INTERFACE_LANGUAGE;

    if($_JIQCl)
      $resourcestrings[$INTERFACE_LANGUAGE] = $lang;
      else
      $resourcestrings = array($INTERFACE_LANGUAGE => $lang);
      
    if($INTERFACE_LANGUAGE == "de") {
       $ShortDateFormat = 'd.m.Y';
       $LongDateFormat = 'd.m.Y H:i:s';
    }else{
       $ShortDateFormat = 'Y/m/d';
       $LongDateFormat = 'Y/m/d H:i:s';
    }

 }

  # Default de/en error texts
  $commonmsgAnErrorOccured = "Es ist ein Fehler aufgetreten / An error occured";
  $commonmsgNoGETPOSTParameters = "Es wurden keine HTTP GET/POST Parameter &uuml;bergeben, pr&uuml;fen Sie ob die URL des HTTP-Aufrufs korrekt ist und keine Umleitung des Aufrufs erfolgte.<br /><br />There are not HTTP GET/POST parameters check URL of HTTP request and HTTP redirects of script URL.";
  $commonmsgNoParameters = "Es wurden keine Parameter &uuml;bergeben oder diese sind unvollst&auml;ndig.<br /><br />No script parameters specified or there are incompletely.<br /><br />";
  $commonmsgMailListNotFound = "Empf&auml;ngerliste nicht gefunden.<br /><br />Recipients list not found.<br /><br />";
  $commonmsgRGNotFound = "Empfaengergruppe nicht gefunden.<br /><br />Recipients group not found.<br /><br />";
  $commonmsgHTMLFormNotFound = "HTML-Formular nicht gefunden.<br /><br />HTML form not found.<br /><br />";
  $commonmsgParamKeyNotFound = "Parameter key nicht gefunden.<br /><br />Parameter key not specified.<br /><br />";
  $commonmsgParamKeyInvalid = "Parameter key nicht korrekt.<br /><br />Parameter key invalid.<br /><br />";
  $commonmsgParamEMailNotFound = "Parameter EMail nicht gefunden.<br /><br />Parameter EMail not specified.<br /><br />";
  $commonmsgHTMLMTANotFound = "Versandvariante (MTA) nicht gefunden.<br /><br />Send variant (MTA) not found.";
  $commonmsgNewsletterArchiveNotFound = "Newsletter-Archiv nicht gefunden. / Newsletter archive not found.";
  $commonmsgNewsletterArchiveNoCampaignsFound = "Es wurden dem Newsletter-Archiv keine E-Mailings zugewiesen. / There are no emailings for this newsletter archive.";
  $commonmsgNewsletterArchiveNoText = "Dieser Newsletter enth&auml;lt keinen Text. / Newsletter doesn't contains text.";
  $commonmsgNewsletterArchiveEntryNotFound = "Newsletter nicht gefunden. / Can't find newsletter.";
  $commonmsgNewsletterArchiveNoFramesError = "Ihr Browser unterst&uuml;tzt den &lt;iframe&gt;-Tag nicht, Sie m&uuml;ssen einen aktuellen Browser f&uuml;r die Anzeige dieser Seite verwenden. / Your browser doesn't support &lt;iframe&gt;-tag, you must use a newer browser to view this page.";
  $commonmsgUserDisabled = "Der Nutzer ist zur Zeit nicht aktiviert. / User isn't activated at this time.";
  $commonmsgDistribListFailure = "Verteilerlisten / Distribution lists";
  $commonmsgDistribListFailureUnspecific = "Undefinierter Fehler / Undefined error";
  $commonmsgSubUnsubScriptRequestOverLimit = "Zu viele Aufrufe &uuml;ber IP-Adresse %s, probieren Sie es sp&auml;ter nochmals.<br /><br />Too many requests from IP address %s, try again later.";
  $commonmsgSubUnsubNotAllowed = "An-/Abmeldungen sind f&uuml;r diese Empf&auml;ngerliste nicht erlaubt.<br /><br />Subscriptions/Cancelling subscriptions for this mailing list not allowed.";
  $commonmsgSubscriptionsNotAllowed = "Anmeldungen/&Auml;nderungen sind f&uuml;r diese Empf&auml;ngerliste nicht erlaubt.<br /><br />Subscriptions/Changes for this mailing list not allowed.";
  $commonmsgUnsubscriptionsNotAllowed = "Abmeldungen sind f&uuml;r diese Empf&auml;ngerliste nicht erlaubt.<br /><br />Cancelling subscriptions for this mailing list not allowed.";
  $_Ji60j = "Der Eintrag wurde nicht gefunden oder wurde bereits aktiviert.<br /><br />Entry not found or was always activated.";

  # default mime types
  $Mimetypes = 'txt=text/plain,c=text/plain,cc=text/plain,g=text/plain,h=text/plain,hh=text/plain,m=text/plain,f90=text/plain,rtx=text/richtext,css=text/css,xml=text/xml,htm=text/html,html=text/html,shtm=text/html,shtml=text/html,js=text/javascript,';
  $Mimetypes .= 'tsv=text/tab-separated-values,etx=text/x-setext,sgm=text/x-sgml,sgml=text/x-sgml,gif=image/gif,jpeg=image/jpeg,jpg=image/jpeg,jpe=image/jpeg,tiff=image/tiff,tif=image/tiff,ras=image/cmu-raster,fh4=image/x-freehand,';
  $Mimetypes .= 'fh5=image/x-freehand,fhc=image/x-freehand,ief=image/ief,pnm=image/x-portable-anymap,pbm=image/x-portable-anymap,pgm=image/x-portable-graymap,ppm=image/x-portable-pixmap,rgb=image/x-rgb,xwd=image/x-windowdump,';
  $Mimetypes .= 'exe=application/octet-stream,com=application/octet-stream,dll=application/octet-stream,bin=application/octet-stream,class=application/octet-stream,zip=application/zip,pdf=application/pdf,ps=application/postscript,';
  $Mimetypes .= 'ai=application/postscript,eps=application/postscript,pac=application/x-ns-proxy-autoconfig,dwg=application/acad,dxf=application/dxf,mif=application/mif,doc=application/msword,dot=application/msword,png=image/png,';
  $Mimetypes .= 'ppt=application/mspowerpoint,ppz=application/mspowerpoint,pps=application/mspowerpoint,pot=application/mspowerpoint,xls=application/msexcel,xla=application/msexcel,hlp=application/mshelp,chm=application/mshelp,';
  $Mimetypes .= 'rtf=application/rtf,sh=application/x-sh,csh=application/x-csh,latex=application/x-latex,tar=application/x-tar,bcpio=application/x-bcpio,cpio=application/x-cpio,sv4cpio=application/x-sv4cpio,';
  $Mimetypes .= 'sv4crc=application/x-sv4crc,hdf=application/x-hdf,ustar=application/x-ustar,shar=application/x-shar,tcl=application/x-tcl,dvi=application/x-dvi,texinfo=application/x-texinfo,texi=application/x-texinfo,';
  $Mimetypes .= 't=application/x-troff,tr=application/x-troff,roff=application/x-troff,man=application/x-troff-man,me=application/x-troff-me,ms=application/x-troff-ms,nc=application/x-netcdf,cdf=application/x-netcdf,';
  $Mimetypes .= 'src=application/x-wais-source,au=audio/basic,snd=audio/basic,aif=audio/x-aiff,aiff=audio/x-aiff,aifc=audio/x-aiff,aif=audio/x-aiff,aiff=audio/x-aiff,aifc=audio/x-aiff,dus=audio/x-dspeeh,cht=audio/x-dspeeh,';
  $Mimetypes .= 'midi=audio/x-midi,mid=audio/x-midi,ram=audio/x-pn-realaudio,ra=audio/x-pn-realaudio,rpm=audio/x-pn-realaudio-plugin,mpeg=video/mpeg,mpg=video/mpeg,mpe=video/mpeg,qt=video/quicktime,mov=video/quicktime,';
  $Mimetypes .= 'avi=video/x-msvideo,movie=video/x-sgi-movie,wrl=x-world/x-vrml,';

  // don't localize
  $DayNumToDayName = array(

                          0 => 'Monday',
                          1 => 'Tuesday',
                          2 => 'Wednesday',
                          3 => 'Thursday',
                          4 => 'Friday',
                          5 => 'Saturday',
                          6 => 'Sunday'
                          );


  $MonthNumToMonthName = array (
                                 1  => "January",
                                 2  => "February",
                                 3  => "March",
                                 4  => "April",
                                 5  => "May",
                                 6  => "June",
                                 7  => "July",
                                 8  => "August",
                                 9  => "September",
                                 10 => "October",
                                 11 => "November",
                                 12 => "December"
                               );

?>
