<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright � 2007 - 2012 Mirko Boeer                         #
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
   /*
    Paths Configuration
   */

  # all Paths WITH slash /
   define('BasePath', ''); # e.g. /sml/
   define('WebsiteURL', ''); # e.g. http://supermailinglist.de/
   define('ScriptBaseURL', ''); # e.g. http://supermailinglist.de/sml/
   define('InstallPath', ''); # e.g. /root/www/vhosts/supermailinglist.de/htdocs/sml/

  # DefaultPath for template files and sql folder if they are in another path with /
   define('DefaultPath', '');

   // Templates path name without / the subdirs must include the languages
   define('TemplatesPath', 'templates');
?>