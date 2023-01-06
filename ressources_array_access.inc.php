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

   // PHP 5+
   class ResourceStrings implements ArrayAccess {
    private $_80Jii;

    #[\ReturnTypeWillChange]
    public function offsetExists($_jOJ1C) {
      global $INTERFACE_LANGUAGE;
      if(!isset($this->_80Jii)){
        if(empty($INTERFACE_LANGUAGE)){
          $INTERFACE_LANGUAGE = "de";
          _JQRLR($INTERFACE_LANGUAGE);
         }
          else{
            _JQRLR($INTERFACE_LANGUAGE);
          }
      }
      return isset($this->_80Jii);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($_jOJ1C) {
       if(!isset($this->_80Jii) ){
         $this->offsetExists($_jOJ1C);
         return $this->_80Jii;
       } else
         return $this->_80Jii;
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($_jOJ1C, $_QltJO) {
       $this->_80Jii = $_QltJO;
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($_jOJ1C) {
      unset($this->_80Jii);
    }

   }

   $resourcestrings = new ResourceStrings;


?>
