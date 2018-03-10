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

 require './UASparser/UASparser.php';


 function _OJJJE(&$_IL8io, &$_ILtIQ){
   $_IL8io = "";
   $_ILtIQ = "";
   $_ILt8f = "";
   $_ILOjO = "";
   if(!empty($_SERVER['HTTP_REFERER']))
      $_ILt8f = $_SERVER['HTTP_REFERER'];
   if(!empty($_SERVER['HTTP_USER_AGENT']))
      $_ILOjO = $_SERVER['HTTP_USER_AGENT'];
   #print $_ILOjO."<br />".$_ILt8f;
   if($_ILt8f != "") {
     if(stripos($_ILt8f, "mail.live.com") !== false)
        $_IL8io = "name="."Windows Live Mail".";icon=ua/hotmail.gif";
     if(stripos($_ILt8f, "mail.yahoo.com") !== false)
        $_IL8io = "name="."Yahoo! Mail".";icon=ua/yahoo.gif";
     if(stripos($_ILt8f, "mail.aol.com") !== false)
        $_IL8io = "name="."AOL Mail".";icon=ua/aol.gif";
     if(stripos($_ILt8f, "mail.google.com") !== false || stripos($_ILt8f, ".gmail.com") !== false)
        $_IL8io = "name="."Google Mail".";icon=ua/google.gif";
     if(stripos($_ILt8f, ".gmx.net") !== false || stripos($_ILt8f, ".gmx.com") !== false)
        $_IL8io = "name="."GMX".";icon=ua/gmx.gif";
     if(stripos($_ILt8f, ".web.de") !== false)
        $_IL8io = "name="."Web.de".";icon=ua/webde.gif";
     if(stripos($_ILt8f, ".arcor.net") !== false || stripos($_ILt8f, ".arcor.de") !== false)
        $_IL8io = "name="."Arcor".";icon=ua/arcor.gif";
   }

   if($_ILOjO != "") {
     // IE11 on Windows10
     if(stripos($_ILOjO, "Trident/7.0") !== false && stripos($_ILOjO, "rv:11.0") !== false)
        $_IL8io = "name="."Internet Explorer 11".";icon=msie.png";

     $_IJ8oI = new UASparser();
     $_IJ8oI->SetCacheDir(/*getcwd()*/"./UASparser/"."/cache/");
     $_QoQOL = $_IJ8oI->Parse($_ILOjO);

     if($_IL8io == "") {
       if(isset($_QoQOL["ua_name"]))
         $_IL8io = "name=".$_QoQOL["ua_name"].";icon=";
         else
          if(isset($_QoQOL["ua_family"]))
            $_IL8io = "name=".$_QoQOL["ua_family"].";icon=";
       if(isset($_QoQOL["ua_icon"]) && $_IL8io != "")
            $_IL8io .= $_QoQOL["ua_icon"];
            else
            if($_IL8io != "")
               $_IL8io .= "ua/unknown.gif";
     }

     if(isset($_QoQOL["os_name"])) {
       $_ILtIQ = "name=".$_QoQOL["os_name"].";icon=";
       if(isset($_QoQOL["os_icon"])) {
         $_ILtIQ .= $_QoQOL["os_icon"];
       } else
         $_ILtIQ .= "ua/unknown.gif";
     }

   }
 }


# PHP 5 includes this functions
 if (!function_exists ('stripos') ) {
   function stripos ( $_ILo0C, $_ILooj, $_ILCIC=NULL ) {
   if (isset($_ILCIC) && $_ILCIC != NULL)
     return strpos( strtolower($_ILo0C), strtolower($_ILooj), $_ILCIC);
     else
     return strpos(strtolower($_ILo0C), strtolower($_ILooj), $_ILooj);
   }
 }

 if (!function_exists ('str_ireplace') ) {
   function str_ireplace($_ILCOl,$_ILi66,$_I6016){
       $_ILL10 = chr(1);
       $_ILo0C = strtolower($_I6016);
       $_ILooj = strtolower($_ILCOl);
       while (($_I1t0l=strpos($_ILo0C,$_ILooj))!==FALSE){
         $_I6016 = substr_replace($_I6016,$_ILL10,$_I1t0l,strlen($_ILCOl));
         $_ILo0C = substr_replace($_ILo0C,$_ILL10,$_I1t0l,strlen($_ILCOl));
       }
       $_I6016 = str_replace($_ILL10,$_ILi66,$_I6016);
       return $_I6016;
     }
 }
# PHP 5 includes this functions END

?>
