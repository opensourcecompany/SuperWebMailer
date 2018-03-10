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

   // PHP 5+
   class ResourceStrings implements ArrayAccess {
    private $_686i1;

    public function offsetExists($_ILCIC) {
      global $INTERFACE_LANGUAGE;
      if(!isset($this->_686i1)){
        if(empty($INTERFACE_LANGUAGE))
          _LQLRQ("de");
          else{
            _LQLRQ($INTERFACE_LANGUAGE);
          }
      }
      return isset($this->_686i1);
    }

    public function offsetGet($_ILCIC) {
       if(!isset($this->_686i1) ){
         $this->offsetExists($_ILCIC);
         return $this->_686i1;
       } else
         return $this->_686i1;
    }

    public function offsetSet($_ILCIC, $_Q6ClO) {
       $this->_686i1 = $_Q6ClO;
    }

    public function offsetUnset($_ILCIC) {
      unset($this->_686i1);
    }

   }

   $resourcestrings = new ResourceStrings;


?>
