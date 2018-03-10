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

  class _LJPOA {

   // @public

   // @private
   var $_fIo1I = "";
   var $_fIoII = "";


   // constructor

   // @public
   function __construct($_JJfL8, $_JJ8jj) {
      $this->_fIo1I = $_JJfL8;
      $this->_fIoII = $_JJ8jj;
   }

   function _LJPOA($_JJfL8, $_JJ8jj) {
     self::__construct($_JJfL8, $_JJ8jj);
   }

   // destructor
   function __destruct() {
   }

   // @public
   function TwitterGetShortURL($_JJLf0, &$_6Olto){
      $_QJCJi = $this->_LJAOL('http://api.bit.ly/v3/shorten', 'uri=' . urlencode($_JJLf0) . '&login=superwebmailer&apiKey=R_0be8cc8d5110127518b26a3059443a79&format=json', false, $_6Olto);
      if(strpos($_QJCJi, '"status_code": 200') !== false || strpos($_QJCJi, '"status_code":200') !== false) {
          if(strpos($_QJCJi, "\n") !== false)
             $_Q66jQ = explode("\n", $_QJCJi);
             else
             $_Q66jQ = explode("\r", $_QJCJi);
          for($_Q6llo=0; $_Q6llo<count($_Q66jQ); $_Q6llo++){
             if(strpos($_Q66jQ[$_Q6llo], '"url":') !== false){
                $_QJCJi = substr(trim($_Q66jQ[$_Q6llo]), strpos($_Q66jQ[$_Q6llo], '"url":') + strlen('"url":') - 1); // 11
                $_QJCJi = substr($_QJCJi, strpos($_QJCJi, '"') + 1); // 11
                $_QJCJi = trim($_QJCJi);
                $_QJCJi = substr($_QJCJi, 0, strpos($_QJCJi, '"'));
                $_QJCJi = str_replace("\\", "", $_QJCJi);
                break;
             }
         }
         return $_QJCJi;
      } else{
        if($_6Olto == "")
           $_6Olto = "Can't connect to bit.ly.";
        return false;
      }
   }

   // old variant with Basic Auth
   // @public
   function TwitterSendStatusMessage($_fICQo, &$_jj0JO){
     $_QJCJi = $this->_LJAL6(TWITTER_API_URL.'statuses/update.xml', "status=".urlencode($_fICQo), true, $_jj0JO);
     if(strpos($_QJCJi, '<created_at>') !== false)
       return true;
       else
       return false;
   }

   // @private
   function _LJAOL($_JJLf0, $_fIC8O, $_fIiJt, &$_6J6iQ){
     $_6J6iQ = "";
     $_j88of = 0;
     $_j8t8L = "";
     $_j8O60 = 80;
     if(strpos($_JJLf0, "http://") !== false) {
        $_j8O8t = substr($_JJLf0, 7);
     } elseif(strpos($_JJLf0, "https://") !== false) {
       $_j8O60 = 443;
       $_j8O8t = substr($_JJLf0, 8);
     }
     $_QCoLj = substr($_j8O8t, strpos($_j8O8t, "/"));
     $_j8O8t = substr($_j8O8t, 0, strpos($_j8O8t, "/"));

     $_QJCJi = _OCQ6E($_j8O8t, "GET", $_QCoLj, $_fIC8O, 0, $_j8O60, $_fIiJt, $this->_fIo1I, $this->_fIoII, $_j88of, $_j8t8L);
     $_6J6iQ = $_j8t8L;

     return $_QJCJi;
  }

   // @private
   function _LJAL6($_JJLf0, $_fIC8O, $_fIiJt, &$_6J6iQ){
      $_j88of = 0;
      $_j8t8L = "";
      $_j8O60 = 80;
      if(strpos($_JJLf0, "http://") !== false) {
         $_j8O8t = substr($_JJLf0, 7);
      } elseif(strpos($_JJLf0, "https://") !== false) {
        $_j8O60 = 443;
        $_j8O8t = substr($_JJLf0, 8);
      }
      $_QCoLj = substr($_j8O8t, strpos($_j8O8t, "/"));
      $_j8O8t = substr($_j8O8t, 0, strpos($_j8O8t, "/"));

      $_QJCJi = _OCQ6E($_j8O8t, "POST", $_QCoLj, $_fIC8O, 0, $_j8O60, $_fIiJt, $this->_fIo1I, $this->_fIoII, $_j88of, $_j8t8L);
      $_6J6iQ = $_j8t8L;

      return $_QJCJi;
   }


 }

?>
