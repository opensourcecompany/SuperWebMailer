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

 function _LQRFC(&$HTMLPart, &$Subject, $_68ftC, $_68flo, $_688oJ){
   $_688lo = array("TITLE", "LINK", "DESCRIPTION", "CONTENT:ENCODED", "PUBDATE", "AUTHOR", "CATEGORY", "COMMENTS", "GUID", "SOURCE", "ENCLOSURE");
   $_68tji = array("URL", "LENGTH", "TYPE");

   $HTMLPart = _LQ8JB($HTMLPart, $_68flo);
   $Subject = _LQ8JB($Subject, $_68flo);

   // [rss_channel_items] Block
   $_68tJl = _OP81D($HTMLPart, '[rss_channel_items]', '[/rss_channel_items]');
   if($_68tJl == "") return;
   $_QJCJi = "";
   if(isset($_68flo["ITEMS"])) {
     reset($_68flo["ITEMS"]);
     foreach($_68flo["ITEMS"] as $key => $_Q6ClO){
         // new entries only=
         if(!$_688oJ && !in_array($_68flo["ITEMS"][$key]["GUID"], $_68ftC)) continue;
         $_QJCJi .= $_68tJl;

         reset($_688lo);
         foreach($_688lo as $_68tto){
           $_Q6ClO = "";

           if($_68tto == "ENCLOSURE") {
             if(!isset($_68flo["ITEMS"][$key][$_68tto]))
               $_QJCJi = _OP6PQ($_QJCJi, '[rss_channel_item_enclosure_included]', '[/rss_channel_item_enclosure_included]');
               else {
                reset($_68tji);
                foreach($_68tji as $_68tLl){
                   $_Q6ClO = "";
                   if(isset($_68flo["ITEMS"][$key][$_68tto][$_68tLl])) {
                     $_Q6ClO = $_68flo["ITEMS"][$key][$_68tto][$_68tLl];
                   }
                   $_QJCJi = str_ireplace("[rss_channel_item_enclosure_$_68tLl]", $_Q6ClO, $_QJCJi);
                }
                $_QJCJi = str_ireplace("[rss_channel_item_enclosure_included]", "", $_QJCJi);
                $_QJCJi = str_ireplace("[/rss_channel_item_enclosure_included]", "", $_QJCJi);
               }
             continue;
           }

           if(isset($_68flo["ITEMS"][$key][$_68tto]))
              $_Q6ClO = $_68flo["ITEMS"][$key][$_68tto];
           if($_68tto == "PUBDATE" || $_68tto == "LASTBUILDDATE")
              $_Q6ClO = _LQP0A($_Q6ClO);
           $_QJCJi = str_ireplace("[rss_channel_item_$_68tto]", $_Q6ClO, $_QJCJi);
         }

     }
   }

   // Block replacement
   $HTMLPart = _OPR6L($HTMLPart, '[rss_channel_items]', '[/rss_channel_items]', $_QJCJi);
 }


 function _LQ8JB($_Q6ICj, $_68flo){
   $_68Ojl = array("TITLE", "LINK", "DESCRIPTION", "COPYRIGHT", "PUBDATE", "LASTBUILDDATE", "LANGUAGE", "CATEGORY", "TTL", "MANAGINGEDITOR", "WEBMASTER", "GENERATOR");
   $_68OLj = array("URL", "LINK", "TITLE", "DESCRIPTION");

   reset($_68Ojl);
   foreach($_68Ojl as $key){
     $_Q6ClO = "";
     if(isset($_68flo[$key]))
        $_Q6ClO = $_68flo[$key];
     if($key == "PUBDATE" || $key == "LASTBUILDDATE")
        $_Q6ClO = _LQP0A($_Q6ClO);
     $_Q6ICj = str_ireplace("[rss_channel_$key]", $_Q6ClO, $_Q6ICj);
   }

   reset($_68OLj);
   foreach($_68OLj as $key){
     $_Q6ClO = "";
     if(isset($_68flo["IMAGE"]) && isset($_68flo["IMAGE"][$key]))
        $_Q6ClO = $_68flo["IMAGE"][$key];
     $_Q6ICj = str_ireplace("[rss_channel_image_$key]", $_Q6ClO, $_Q6ICj);
   }

   $_Q6ClO = 0;
   if(isset($_68flo["ITEMS"]))
     $_Q6ClO = count($_68flo["ITEMS"]);
   $_Q6ICj = str_ireplace("[rss_items_count]", $_Q6ClO, $_Q6ICj);

   return $_Q6ICj;
 }

 function _LQP0A($_QJCJi){
   global $INTERFACE_LANGUAGE;
   $_I1L81 = strtotime($_QJCJi);
   if($_I1L81 === false || $_I1L81 == -1)
      $_I1L81 = time();
   if($INTERFACE_LANGUAGE != "de")
     return date("Y/m/d H:i:s", $_I1L81);
     else
     return date("d.m.Y H:i:s", $_I1L81);
 }

 function _LQP10($_QJCJi){
   $_QJCJi = _OP6PQ($_QJCJi, '<head>', '</head>');

   if ( stripos($_QJCJi, '</html') !== false )
      $_QJCJi = substr($_QJCJi, 0, stripos($_QJCJi, '</html'));
   if ( stripos($_QJCJi, '<html') !== false ) {
    $_QJCJi = substr($_QJCJi, stripos($_QJCJi, '<html'));
    $_QJCJi = substr($_QJCJi, stripos($_QJCJi, '>') + 1);
   }

   if ( stripos($_QJCJi, '</body') !== false )
      $_QJCJi = substr($_QJCJi, 0, stripos($_QJCJi, '</body'));
   if ( stripos($_QJCJi, '<body') !== false ) {
    $_QJCJi = substr($_QJCJi, stripos($_QJCJi, '<body'));
    $_QJCJi = substr($_QJCJi, stripos($_QJCJi, '>') + 1);
   }

   return $_QJCJi;
 }

?>
