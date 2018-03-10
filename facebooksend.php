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
  include_once("sessioncheck.inc.php");

 if($INTERFACE_LANGUAGE == "de")
   $_jl1Jj = 'http://www.superscripte.de/fb-connect/superwebmailer/index_de.php?FBLink=' . urlencode( $_POST["FBLink"] ). '&FBMessageText=' . urlencode( $_POST["FBMessageText"] );
   else
   $_jl1Jj = 'http://www.superscripte.de/fb-connect/superwebmailer/index_en.php?FBLink=' . urlencode( $_POST["FBLink"] ). '&FBMessageText=' . urlencode( $_POST["FBMessageText"] );

 header("Location: ".$_jl1Jj);
 exit;
?>

