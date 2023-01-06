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
  include_once(PEAR_PATH . "PEAR_.php");
  include_once(PEAR_PATH . "URL.php");

  function _LBCQ6($_QLoli, $_6j88I) {
    if(!$_6j88I["GoogleAnalyticsActive"])
     return $_QLoli;
    # fix arg_separator.output we need & and not &amp; in URLs, compatibility problems
    if(ini_get('arg_separator.output') != "&")
      ini_set('arg_separator.output', "&");
    $_6tot8 = _LBCJL($_6j88I);
    $_IjQI8 = array();
    _LAQDB($_QLoli, $_IjQI8);
    for($_Qli6J=0; $_Qli6J<count($_IjQI8); $_Qli6J++){
      $link = $_IjQI8[$_Qli6J];
      $_6tCl6 = false;
      if(strpos($link, "?") !== false)
         $link .= "&".join("&", $_6tot8); # add it
         else {
            $_6tiiI = new Net_URL($link, false);

            for($_QliOt=0; $_QliOt<count($_6tot8); $_QliOt++) {
               $_QlOjt = explode("=", $_6tot8[$_QliOt]);
               $_6tiiI->addQueryString($_QlOjt[0], $_QlOjt[1], true);
            }

            $_6tL60 = $_6tiiI->getQueryString();

            $link = $_6tiiI->protocol . '://'
                       . $_6tiiI->user . (!empty($_6tiiI->pass) ? ':' : '')
                       . $_6tiiI->pass . (!empty($_6tiiI->user) ? '@' : '')
                       . $_6tiiI->host . ($_6tiiI->port == $_6tiiI->getStandardPort($_6tiiI->protocol) ? '' : ':' . $_6tiiI->port)
                       . (strpos($_6tiiI->path, ".") === false ? _LPC1C( $_6tiiI->path ) : $_6tiiI->path )
                       . (!empty($_6tL60) ? '?' . $_6tL60 : '')
                       . (!empty($_6tiiI->anchor) ? '#' . $_6tiiI->anchor : '');

            unset($_6tiiI);
         }
         $_QLoli = str_replace('"'.$_IjQI8[$_Qli6J].'"', '"'.$link.'"', $_QLoli);
         $_QLoli = str_replace('"'._LPC1C($_IjQI8[$_Qli6J]).'"', '"'.$link.'"', $_QLoli);
    }
    return $_QLoli;
  }

  function _LBCJL($_6j88I){
     $_IoLOO = array();
     if ( !empty($_6j88I["GoogleAnalytics_utm_source"]) )
         $_IoLOO[] = 'utm_source='.urlencode($_6j88I["GoogleAnalytics_utm_source"]);
     if ( !empty($_6j88I["GoogleAnalytics_utm_medium"]) )
         $_IoLOO[] = 'utm_medium='.urlencode($_6j88I["GoogleAnalytics_utm_medium"]);
     if ( !empty($_6j88I["GoogleAnalytics_utm_term"]) )
         $_IoLOO[] = 'utm_term='.urlencode($_6j88I["GoogleAnalytics_utm_term"]);
     if ( !empty($_6j88I["GoogleAnalytics_utm_content"]) )
         $_IoLOO[] = 'utm_content='.urlencode($_6j88I["GoogleAnalytics_utm_content"]);
     if ( !empty($_6j88I["GoogleAnalytics_utm_campaign"]) )
         $_IoLOO[] = 'utm_campaign='.urlencode($_6j88I["GoogleAnalytics_utm_campaign"]);

     return $_IoLOO;
  }

?>
