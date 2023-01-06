<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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

 function _JQ8RF(&$HTMLPart, &$Subject, $_80660, $_806io, $_80ftL){
   $_80866 = array("TITLE", "LINK", "DESCRIPTION", "CONTENT:ENCODED", "PUBDATE", "AUTHOR", "CATEGORY", "COMMENTS", "GUID", "SOURCE", "ENCLOSURE");
   $_808Cf = array("URL", "LENGTH", "TYPE");

   $HTMLPart = _JQPJJ($HTMLPart, $_806io);
   $Subject = _JQPJJ($Subject, $_806io);

   // [rss_channel_items] Block
   $_808l0 = _L81DB($HTMLPart, '[rss_channel_items]', '[/rss_channel_items]');
   if($_808l0 == "") return;
   $_QLJfI = "";
   if(isset($_806io["ITEMS"])) {
     reset($_806io["ITEMS"]);
     foreach($_806io["ITEMS"] as $key => $_QltJO){
         // new entries only=
         if(!$_80ftL && !in_array($_806io["ITEMS"][$key]["GUID"], $_80660)) continue;
         $_QLJfI .= $_808l0;

         reset($_80866);
         foreach($_80866 as $_80t10){
           $_QltJO = "";

           if($_80t10 == "ENCLOSURE") {
             if(!isset($_806io["ITEMS"][$key][$_80t10]))
               $_QLJfI = _L80DF($_QLJfI, '[rss_channel_item_enclosure_included]', '[/rss_channel_item_enclosure_included]');
               else {
                reset($_808Cf);
                foreach($_808Cf as $_80t68){
                   $_QltJO = "";
                   if(isset($_806io["ITEMS"][$key][$_80t10][$_80t68])) {
                     $_QltJO = $_806io["ITEMS"][$key][$_80t10][$_80t68];
                   }
                   $_QLJfI = str_ireplace("[rss_channel_item_enclosure_$_80t68]", $_QltJO, $_QLJfI);
                }
                $_QLJfI = str_ireplace("[rss_channel_item_enclosure_included]", "", $_QLJfI);
                $_QLJfI = str_ireplace("[/rss_channel_item_enclosure_included]", "", $_QLJfI);
               }
             continue;
           }

           if(isset($_806io["ITEMS"][$key][$_80t10]))
              $_QltJO = $_806io["ITEMS"][$key][$_80t10];
           if($_80t10 == "PUBDATE" || $_80t10 == "LASTBUILDDATE")
              $_QltJO = _JQPF0($_QltJO);
           $_QLJfI = str_ireplace("[rss_channel_item_$_80t10]", $_QltJO, $_QLJfI);
         }

     }
   }

   // Block replacement
   $HTMLPart = _L81BJ($HTMLPart, '[rss_channel_items]', '[/rss_channel_items]', $_QLJfI, true);
   // recursive again
   _JQ8RF($HTMLPart, $Subject, $_80660, $_806io, $_80ftL);
 }


 function _JQPJJ($_QLoli, $_806io){
   $_80tCf = array("TITLE", "LINK", "DESCRIPTION", "COPYRIGHT", "PUBDATE", "LASTBUILDDATE", "LANGUAGE", "CATEGORY", "TTL", "MANAGINGEDITOR", "WEBMASTER", "GENERATOR");
   $_80OIo = array("URL", "LINK", "TITLE", "DESCRIPTION");

   reset($_80tCf);
   foreach($_80tCf as $key){
     $_QltJO = "";
     if(isset($_806io[$key]))
        $_QltJO = $_806io[$key];
     if($key == "PUBDATE" || $key == "LASTBUILDDATE")
        $_QltJO = _JQPF0($_QltJO);
     $_QLoli = str_ireplace("[rss_channel_$key]", $_QltJO, $_QLoli);
   }

   reset($_80OIo);
   foreach($_80OIo as $key){
     $_QltJO = "";
     if(isset($_806io["IMAGE"]) && isset($_806io["IMAGE"][$key]))
        $_QltJO = $_806io["IMAGE"][$key];
     $_QLoli = str_ireplace("[rss_channel_image_$key]", $_QltJO, $_QLoli);
   }

   $_QltJO = 0;
   if(isset($_806io["ITEMS"]))
     $_QltJO = count($_806io["ITEMS"]);
   $_QLoli = str_ireplace("[rss_items_count]", $_QltJO, $_QLoli);

   return $_QLoli;
 }

 function _JQPF0($_QLJfI){
   global $INTERFACE_LANGUAGE;
   $_IOCjL = strtotime($_QLJfI);
   if($_IOCjL === false || $_IOCjL == -1)
      $_IOCjL = time();
   if($INTERFACE_LANGUAGE != "de")
     return date("Y/m/d H:i:s", $_IOCjL);
     else
     return date("d.m.Y H:i:s", $_IOCjL);
 }

 function _JQAQR($_QLJfI){
   $_QLJfI = _L80DF($_QLJfI, '<head>', '</head>');

   if ( stripos($_QLJfI, '</html') !== false )
      $_QLJfI = substr($_QLJfI, 0, stripos($_QLJfI, '</html'));
   if ( stripos($_QLJfI, '<html') !== false ) {
    $_QLJfI = substr($_QLJfI, stripos($_QLJfI, '<html'));
    $_QLJfI = substr($_QLJfI, stripos($_QLJfI, '>') + 1);
   }

   if ( stripos($_QLJfI, '</body') !== false )
      $_QLJfI = substr($_QLJfI, 0, stripos($_QLJfI, '</body'));
   if ( stripos($_QLJfI, '<body') !== false ) {
    $_QLJfI = substr($_QLJfI, stripos($_QLJfI, '<body'));
    $_QLJfI = substr($_QLJfI, stripos($_QLJfI, '>') + 1);
   }

   return $_QLJfI;
 }

?>
