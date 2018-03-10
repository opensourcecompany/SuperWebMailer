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

  function utf8_to_windows1255($_fj8t1) {
      $_fjtQC = "";
      $_fjtOQ = preg_split("//",$_fj8t1);
      for ($_Q6llo=1; $_Q6llo<count($_fjtOQ)-1; $_Q6llo++) {
          $_I8i66 = ord($_fjtOQ[$_Q6llo]);
          $_fjO81 = ord($_fjtOQ[$_Q6llo+1]);
          //print ("<p>$_I8i66 $_fjO81");
          if ($_I8i66==215) {
              $_fjtQC .= chr($_fjO81+80);
              $_Q6llo++;
          }
          elseif ($_I8i66==214) {
              $_fjtQC .= chr($_fjO81+16);
              $_Q6llo++;
          }
          else {
              $_fjtQC .= $_fjtOQ[$_Q6llo];
          }
      }
      return $_fjtQC;
  }

?>
