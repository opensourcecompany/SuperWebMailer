<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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
  include_once("templates.inc.php");

  /* iframe request
  if(!_LJBLD()){
    $s = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $s = _L81BJ($s, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]." - Csrf");
    print $s;
    exit;
  } */

  // Prevent the browser from caching the result.
  // Date in the past
  @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
  // always modified
  @header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
  // HTTP/1.1
  @header('Cache-Control: no-store, no-cache, must-revalidate') ;
  @header('Cache-Control: post-check=0, pre-check=0', false) ;
  // HTTP/1.0
  @header('Pragma: no-cache') ;

  // Set the response format.
  @header( 'Content-Type: text/html; charset='.$_QLo06 ) ;

  if(!isset($_GET["filename"])) {

    $_QlooO = array();
    if ($_QlCt0 = opendir(InstallPath."ipe/elements")) {
        while (($_QlCtl = readdir($_QlCt0)) !== false) {
          if($_QlCtl != "." && !is_dir(InstallPath."ipe/elements/".$_QlCtl))
            $_QlooO[] = $_QlCtl;
        }
        closedir($_QlCt0);
    }

    sort($_QlooO);

    $_QlCLf = utf8_encode( _JJAQE("ipe_elements.txt", false) );
    $_Qli6J=0;
    for($_QliOt=0; $_QliOt<count($_QlooO); $_QliOt++) {
          if($_Qli6J++ == 0)
            $_QliLj = "ui-selected";
            else
            $_QliLj = "";
          $_QlCtl = $_QlooO[$_QliOt];
          $_QlLoL = substr($_QlCtl, 0, strpos($_QlCtl, "."));
          $_QlOjt = strpos($_QlCLf, "[".$_QlLoL."]"."=");
          if($_QlOjt !== false){
            $_QlLoL = substr($_QlCLf, $_QlOjt + strlen($_QlLoL) + 3);

            if(strpos($_QlLoL, "\r") !== false)
              $_QlLoL = trim(substr($_QlLoL, 0, strpos($_QlLoL, "\r") ));
          }

          print '<li class="'.$_QliLj.'"><img src="' . BasePath . 'ipe/elements/images/'.substr($_QlCtl, 0, strpos($_QlCtl, ".")).'.png" width="130" height="100" border="0" rel="'.$_QlCtl.'" alt="'.$_QlLoL.'" title="'.$_QlLoL.'" /></li>';
   } // for j

   // userdefined templates
   $_QlooO = array();
   if ($_QlCt0 = @opendir($_QlLlJ."ipe/elements")) {
        while (($_QlCtl = readdir($_QlCt0)) !== false) {
          if($_QlCtl != "." && !is_dir($_QlLlJ."ipe/elements/".$_QlCtl))
            $_QlooO[] = $_QlLlJ."ipe/elements/".$_QlCtl;
        }
        closedir($_QlCt0);
   }

   sort($_QlooO);

   $_QliLj = "";
   if($OwnerUserId != 0)
     $_Qll8O = $OwnerUserId;
     else
     $_Qll8O = $UserId;
   $_Qlllf = BasePath . "userfiles/". $_Qll8O . "/templates/";

   for($_QliOt=0; $_QliOt<count($_QlooO); $_QliOt++) {
          $_QlCtl = $_QlooO[$_QliOt];
          $_QlLoL = substr(basename($_QlCtl), 0, strpos(basename($_QlCtl), "."));

          $_I00iJ = $_Qlllf . 'ipe/elements/images/'.substr( basename($_QlCtl), 0, strpos(basename($_QlCtl), ".")) . '.png';

          if(!file_exists(_LA6ED($_I00iJ)))
            $_I00iJ = "images/swm_logo32x32.png";

          print '<li class="'.$_QliLj.'"><img src="' . $_I00iJ . '" width="130" height="100" border="0" rel="'.$_QlCtl.'" alt="'.$_QlLoL.'" title="'.$_QlLoL.'" /></li>';
   } // for j

   print _LJA6C('');

  } else {

    $_I016j = strpos($_GET["filename"], $_QlLlJ);
    if(strpos($_GET["filename"], "/") !== false && basename($_GET["filename"]) != $_GET["filename"] && $_I016j !== false && $_I016j == 0 ){
      print _LA8F6(join("", file($_GET["filename"])));
      return;
    }

    if ($_QlCt0 = opendir(InstallPath."ipe/elements")) {
        $_GET["filename"] = str_replace('/', '', $_GET["filename"]);
        $_GET["filename"] = str_replace("\\", '', $_GET["filename"]);
        while (($_QlCtl = readdir($_QlCt0)) !== false) {
            if($_QlCtl == $_GET["filename"]){

              if(strpos($_GET["filename"], "socialmediashare") === false)
                print join("", file(InstallPath."ipe/elements/".$_GET["filename"]));
                else {
                 $_QLoli = join("", file(InstallPath."ipe/elements/".$_GET["filename"]));
                 $_I01Lf = trim(_L81DB($_QLoli, "<socialmedia_link>", "</socialmedia_link>"));
                 $_I0QjQ = "";
                 $_I0Q8C = file(InstallPath."js/socialmediashare.ini");
                 for($_Qli6J=0; $_Qli6J<count($_I0Q8C); $_Qli6J++){
                    if(strpos($_I0Q8C[$_Qli6J], "'") !== false)
                       $_I0Q8C[$_Qli6J] = substr($_I0Q8C[$_Qli6J], 0, strpos($_I0Q8C[$_Qli6J], "'"));
                    if(trim($_I0Q8C[$_Qli6J]) == "") continue;
                    $_I0Q8C[$_Qli6J] = trim($_I0Q8C[$_Qli6J]);
                    $_I0QLo = explode("=", $_I0Q8C[$_Qli6J], 2);
                    if(count($_I0QLo) < 2) continue;
                    $_I0QjQ .= $_I01Lf;
                    $_I0QjQ = str_replace("[~SHARE_URL~]", $_I0QLo[1], $_I0QjQ);
                    $_I0QjQ = str_replace("[~SHARE_NAME~]", $_I0QLo[0], $_I0QjQ);
                    $_I0QjQ = str_replace("[~SHARE_NAME_NO_BLANK~]", str_replace(" ", "_", $_I0QLo[0]), $_I0QjQ);
                    $_I0QjQ = str_replace("[~SHARE_IMAGE_URL~]", BasePath . "images/socialmedia/".str_replace(" ", "", strtolower($_I0QLo[0]).".png"), $_I0QjQ);
                    if(empty($_GET["smechkboxes"]) || !$_GET["smechkboxes"])
                      $_I0QjQ = _L80DF($_I0QjQ, "<smcheckboxes>", "</smcheckboxes>");
                      else {
                        $_I0QjQ = str_replace("<smcheckboxes>", "", $_I0QjQ);
                        $_I0QjQ = str_replace("</smcheckboxes>", "", $_I0QjQ);
                      }
                 }

                 $_QLoli = _L81BJ($_QLoli, "<socialmedia_link>", "</socialmedia_link>", $_I0QjQ);

                 # ckeditor iframe
                 if(!empty($_GET["ckeditor"])) {

                   $_I0I1f = _JJAQE("loadsmebar_iframe.htm", false);
                   $_I0I1f = _L81BJ($_I0I1f, "<SMEBAR>", "</SMEBAR>", $_QLoli);

                   $_QLoli = $_I0I1f;

                 }
                 print _LJA6C($_QLoli);
                }
            }
        }
        closedir($_QlCt0);
    }
  }

?>
