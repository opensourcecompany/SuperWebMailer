<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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
  include_once("./PEAR/PEAR_.php");
  include_once("./PEAR/URL.php");

  function _OCFOP($_Q6ICj, $_Jf0Ii) {
    if(!$_Jf0Ii["GoogleAnalyticsActive"])
     return $_Q6ICj;
    # fix arg_separator.output we need & and not &amp; in URLs, compatibility problems
    if(ini_get('arg_separator.output') != "&")
      ini_set('arg_separator.output', "&");
    $_Jf1Qi = _OCFFQ($_Jf0Ii);
    $_QOLIl = array();
    _OBAQ8($_Q6ICj, $_QOLIl);
    for($_Q6llo=0; $_Q6llo<count($_QOLIl); $_Q6llo++){
      $link = $_QOLIl[$_Q6llo];
      $_Jf16j = false;
      if(strpos($link, "?") !== false)
         $link .= "&".join("&", $_Jf1Qi); # add it
         else {
            $_Jf1ij = new Net_URL($link, false);

            for($_Qf0Ct=0; $_Qf0Ct<count($_Jf1Qi); $_Qf0Ct++) {
               $_Q6i6i = explode("=", $_Jf1Qi[$_Qf0Ct]);
               $_Jf1ij->addQueryString($_Q6i6i[0], $_Q6i6i[1], true);
            }

            $_JfQJJ = $_Jf1ij->getQueryString();

            $link = $_Jf1ij->protocol . '://'
                       . $_Jf1ij->user . (!empty($_Jf1ij->pass) ? ':' : '')
                       . $_Jf1ij->pass . (!empty($_Jf1ij->user) ? '@' : '')
                       . $_Jf1ij->host . ($_Jf1ij->port == $_Jf1ij->getStandardPort($_Jf1ij->protocol) ? '' : ':' . $_Jf1ij->port)
                       . (strpos($_Jf1ij->path, ".") === false ? _OBLDR( $_Jf1ij->path ) : $_Jf1ij->path )
                       . (!empty($_JfQJJ) ? '?' . $_JfQJJ : '')
                       . (!empty($_Jf1ij->anchor) ? '#' . $_Jf1ij->anchor : '');

            unset($_Jf1ij);
         }
         $_Q6ICj = str_replace('"'.$_QOLIl[$_Q6llo].'"', '"'.$link.'"', $_Q6ICj);
         $_Q6ICj = str_replace('"'._OBLDR($_QOLIl[$_Q6llo]).'"', '"'.$link.'"', $_Q6ICj);
    }
    return $_Q6ICj;
  }

  function _OCFFQ($_Jf0Ii){
     $_II1Ot = array();
     if ( !empty($_Jf0Ii["GoogleAnalytics_utm_source"]) )
         $_II1Ot[] = 'utm_source='.urlencode($_Jf0Ii["GoogleAnalytics_utm_source"]);
     if ( !empty($_Jf0Ii["GoogleAnalytics_utm_medium"]) )
         $_II1Ot[] = 'utm_medium='.urlencode($_Jf0Ii["GoogleAnalytics_utm_medium"]);
     if ( !empty($_Jf0Ii["GoogleAnalytics_utm_term"]) )
         $_II1Ot[] = 'utm_term='.urlencode($_Jf0Ii["GoogleAnalytics_utm_term"]);
     if ( !empty($_Jf0Ii["GoogleAnalytics_utm_content"]) )
         $_II1Ot[] = 'utm_content='.urlencode($_Jf0Ii["GoogleAnalytics_utm_content"]);
     if ( !empty($_Jf0Ii["GoogleAnalytics_utm_campaign"]) )
         $_II1Ot[] = 'utm_campaign='.urlencode($_Jf0Ii["GoogleAnalytics_utm_campaign"]);

     return $_II1Ot;
  }

?>
