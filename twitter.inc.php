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

  include_once("functions.inc.php");

  define('TWITTER_API_URL', 'http://twitter.com/');
  define('TWITTER_API_PORT', 80);

  class _JJDPE {

   // @public

   // @private
   var $_8itOC = "";
   var $_8iO8o = "";


   // constructor

   // @public
   function __construct($_6fCJQ, $_6fi18) {
      $this->_8itOC = $_6fCJQ;
      $this->_8iO8o = $_6fi18;
   }

   function _JJDPE($_6fCJQ, $_6fi18) {
     self::__construct($_6fCJQ, $_6fi18);
   }

   // destructor
   function __destruct() {
   }

   // @public
   function TwitterGetShortURL($URL, &$Error){
      $_QLJfI = $this->_JJF0F('http://api.bit.ly/v3/shorten', 'uri=' . urlencode($URL) . '&login=superwebmailer&apiKey=R_0be8cc8d5110127518b26a3059443a79&format=json', false, $Error);
      if(strpos($_QLJfI, '"status_code": 200') !== false || strpos($_QLJfI, '"status_code":200') !== false) {
          if(strpos($_QLJfI, "\n") !== false)
             $_Ql0fO = explode("\n", $_QLJfI);
             else
             $_Ql0fO = explode("\r", $_QLJfI);
          for($_Qli6J=0; $_Qli6J<count($_Ql0fO); $_Qli6J++){
             if(strpos($_Ql0fO[$_Qli6J], '"url":') !== false){
                $_QLJfI = substr(trim($_Ql0fO[$_Qli6J]), strpos($_Ql0fO[$_Qli6J], '"url":') + strlen('"url":') - 1); // 11
                $_QLJfI = substr($_QLJfI, strpos($_QLJfI, '"') + 1); // 11
                $_QLJfI = trim($_QLJfI);
                $_QLJfI = substr($_QLJfI, 0, strpos($_QLJfI, '"'));
                $_QLJfI = str_replace("\\", "", $_QLJfI);
                break;
             }
         }
         return $_QLJfI;
      } else{
        if($Error == "")
           $Error = "Can't connect to bit.ly.";
        return false;
      }
   }

   // old variant with Basic Auth
   // @public
   function TwitterSendStatusMessage($_8iOLJ, &$_J0COJ){
     $_QLJfI = $this->_JJF66(TWITTER_API_URL.'statuses/update.xml', "status=".urlencode($_8iOLJ), true, $_J0COJ);
     if(strpos($_QLJfI, '<created_at>') !== false)
       return true;
       else
       return false;
   }

   // @private
   function _JJF0F($URL, $_8ioIf, $_8iotJ, &$_JO0lI){
     $_JO0lI = "";
     $_JJl1I = 0;
     $_J600J = "";
     $_J608j = 80;
     if(strpos($URL, "http://") !== false) {
        $_J60tC = substr($URL, 7);
     } elseif(strpos($URL, "https://") !== false) {
       $_J608j = 443;
       $_J60tC = substr($URL, 8);
     }
     $_IJL6o = substr($_J60tC, strpos($_J60tC, "/"));
     $_J60tC = substr($_J60tC, 0, strpos($_J60tC, "/"));

     $_QLJfI = DoHTTPRequest($_J60tC, "GET", $_IJL6o, $_8ioIf, 0, $_J608j, $_8iotJ, $this->_8itOC, $this->_8iO8o, $_JJl1I, $_J600J);
     $_JO0lI = $_J600J;

     return $_QLJfI;
  }

   // @private
   function _JJF66($URL, $_8ioIf, $_8iotJ, &$_JO0lI){
      $_JJl1I = 0;
      $_J600J = "";
      $_J608j = 80;
      if(strpos($URL, "http://") !== false) {
         $_J60tC = substr($URL, 7);
      } elseif(strpos($URL, "https://") !== false) {
        $_J608j = 443;
        $_J60tC = substr($URL, 8);
      }
      $_IJL6o = substr($_J60tC, strpos($_J60tC, "/"));
      $_J60tC = substr($_J60tC, 0, strpos($_J60tC, "/"));

      $_QLJfI = DoHTTPRequest($_J60tC, "POST", $_IJL6o, $_8ioIf, 0, $_J608j, $_8iotJ, $this->_8itOC, $this->_8iO8o, $_JJl1I, $_J600J);
      if($_JJl1I != 200)
        $_JO0lI = $_J600J;

      return $_QLJfI;
   }


 }

?>
