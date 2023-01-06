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


 function _LQ6C1(&$_jOQQo, &$_jOQf8){
   $_jOQQo = "";
   $_jOQf8 = "";
   $_jOI6J = "";
   $_jOjI0 = "";
   if(!empty($_SERVER['HTTP_REFERER']))
      $_jOI6J = $_SERVER['HTTP_REFERER'];
   if(!empty($_SERVER['HTTP_USER_AGENT']))
      $_jOjI0 = $_SERVER['HTTP_USER_AGENT'];
   #print $_jOjI0."<br />".$_jOI6J;
   if($_jOI6J != "") {
     if(stripos($_jOI6J, "mail.live.com") !== false)
        $_jOQQo = "name="."Windows Live Mail".";icon=ua/hotmail.gif";
     if(stripos($_jOI6J, "mail.yahoo.com") !== false)
        $_jOQQo = "name="."Yahoo! Mail".";icon=ua/yahoo.gif";
     if(stripos($_jOI6J, "mail.aol.com") !== false)
        $_jOQQo = "name="."AOL Mail".";icon=ua/aol.gif";
     if(stripos($_jOI6J, "mail.google.com") !== false || stripos($_jOI6J, ".gmail.com") !== false)
        $_jOQQo = "name="."Google Mail".";icon=ua/google.gif";
     if(stripos($_jOI6J, ".gmx.net") !== false || stripos($_jOI6J, ".gmx.com") !== false)
        $_jOQQo = "name="."GMX".";icon=ua/gmx.gif";
     if(stripos($_jOI6J, ".web.de") !== false)
        $_jOQQo = "name="."Web.de".";icon=ua/webde.gif";
     if(stripos($_jOI6J, ".arcor.net") !== false || stripos($_jOI6J, ".arcor.de") !== false)
        $_jOQQo = "name="."Arcor".";icon=ua/arcor.gif";
   }

   if($_jOjI0 != "") {
     // IE11 on Windows10
     if(stripos($_jOjI0, "Trident/7.0") !== false && stripos($_jOjI0, "rv:11.0") !== false)
        $_jOQQo = "name="."Internet Explorer 11".";icon=msie.png";

     $_IL6Jt = new UASparser();
     $_IL6Jt->SetCacheDir(/*getcwd()*/"./UASparser/"."/cache/");
     $_Ijj6Q = $_IL6Jt->Parse($_jOjI0);

     if($_jOQQo == "") {
       if(isset($_Ijj6Q["ua_name"]))
         $_jOQQo = "name=".$_Ijj6Q["ua_name"].";icon=";
         else
          if(isset($_Ijj6Q["ua_family"]))
            $_jOQQo = "name=".$_Ijj6Q["ua_family"].";icon=";
       if(isset($_Ijj6Q["ua_icon"]) && $_jOQQo != "")
            $_jOQQo .= $_Ijj6Q["ua_icon"];
            else
            if($_jOQQo != "")
               $_jOQQo .= "ua/unknown.gif";
     }

     if(isset($_Ijj6Q["os_name"])) {
       $_jOQf8 = "name=".$_Ijj6Q["os_name"].";icon=";
       if(isset($_Ijj6Q["os_icon"])) {
         $_jOQf8 .= $_Ijj6Q["os_icon"];
       } else
         $_jOQf8 .= "ua/unknown.gif";
     }

   }
 }


# PHP 5 includes this functions
 if (!function_exists ('stripos') ) {
   function stripos ( $_jOjjt, $_jOjt8, $_jOJ1C=NULL ) {
   if (isset($_jOJ1C) && $_jOJ1C != NULL)
     return strpos( strtolower($_jOjjt), strtolower($_jOjt8), $_jOJ1C);
     else
     return strpos(strtolower($_jOjjt), strtolower($_jOjt8), $_jOjt8);
   }
 }

 if (!function_exists ('str_ireplace') ) {
   function str_ireplace($_jOJtO,$_jOJCl,$_ILi8o){
       $token = chr(1);
       $_jOjjt = strtolower($_ILi8o);
       $_jOjt8 = strtolower($_jOJtO);
       while (($_IOO6C=strpos($_jOjjt,$_jOjt8))!==FALSE){
         $_ILi8o = substr_replace($_ILi8o,$token,$_IOO6C,strlen($_jOJtO));
         $_jOjjt = substr_replace($_jOjjt,$token,$_IOO6C,strlen($_jOJtO));
       }
       $_ILi8o = str_replace($token,$_jOJCl,$_ILi8o);
       return $_ILi8o;
     }
 }
# PHP 5 includes this functions END

?>
