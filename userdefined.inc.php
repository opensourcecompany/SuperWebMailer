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

 # User defined settings
 #define("DEMO", 1);
 #define("SimulateMailSending", 1);

 # activate debug, error_reporting is set to E_ALL
 #define('DEBUG', 1);

 # turn security check off
 #define("NoSecurityCheck", 1);

 # force page redirect do not load contents of redirected page on subscribe/unsubscribe
 #define("ForcePageRedirect", 1);

 # force tracking pixel after <body...>
 #define("TrackingPixelTop", 1);

 # show username in titlebar
 #define("ShowUserNameInTitlebar", 1);

 # no prefix [TEST] in test emails
 #define("NoTestPrefix", 1);

 # don't use lock file for CronJob
 #define("NoCronJobLockFile", 1);

 # don't use CSRF protection
 #define("NoCSRFProtection", 1);

 # api_json.php used header value for Access-Control-Allow-Origin, default disabled = *
 #define("JS_Access_Control_Allow_Origin", "*");

 # set List-Unsubscribe-Post header also on no DKIM/DomainKey signature creation
 #define("ListUnsubscribePostOnNoDKIMSignature", 1);

 # Austria only
 # API key for ECGList
 #define("ECG_APIKEY", "");

 # anonymize saved ipv4 or ipv6 address
 # 0 or not defined no anonymization
 # 1 = ipv4: last octett will be filled with zero, ipv6: last segment/chunk will be filled with zero
 # 2 = ipv4: 3th, 4th octett will be filled with zero, ipv6: 7th, 8th segment/chunk will be filled with zero
 # 3 = ipv4: 2th, 3th, 4th octett will be filled with zero, ipv6: 6th, 7th, 8th segment/chunk will be filled with zero
 # 4 = ipv4: 1th, 2th, 3th, 4th octett will be filled with zero, ipv6: 5th, 6th, 7th, 8th segment/chunk will be filled with zero
 #define("ip_address_mask_length", 0);

 #default DefaultMySQLEncoding utf8_unicode_ci, for correct saving emojis it must be utf8mb4
 #define("DefaultMySQLEncoding", 'utf8');
?>
