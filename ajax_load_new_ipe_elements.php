<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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
  @header( 'Content-Type: text/html; charset='.$_Q6QQL ) ;

  if(!isset($_GET["filename"])) {

    $_Q6LIL = array();
    if ($_Q6l1t = opendir(InstallPath."ipe/elements")) {
        while (($_Q6lfJ = readdir($_Q6l1t)) !== false) {
          if($_Q6lfJ != "." && !is_dir(InstallPath."ipe/elements/".$_Q6lfJ))
            $_Q6LIL[] = $_Q6lfJ;
        }
        closedir($_Q6l1t);
    }

    sort($_Q6LIL);

    $_Q6lOO = utf8_encode( join("", file(_O68QF()."ipe_elements.txt")) );
    $_Q6llo=0;
    for($_Qf0Ct=0; $_Qf0Ct<count($_Q6LIL); $_Qf0Ct++) {
          if($_Q6llo++ == 0)
            $_Qf1Jf = "ui-selected";
            else
            $_Qf1Jf = "";
          $_Q6lfJ = $_Q6LIL[$_Qf0Ct];
          $_Qf186 = substr($_Q6lfJ, 0, strpos($_Q6lfJ, "."));
          $_Q6i6i = strpos($_Q6lOO, "[".$_Qf186."]"."=");
          if($_Q6i6i !== false){
            $_Qf186 = substr($_Q6lOO, $_Q6i6i + strlen($_Qf186) + 3);

            if(strpos($_Qf186, "\r") !== false)
              $_Qf186 = trim(substr($_Qf186, 0, strpos($_Qf186, "\r") ));
          }

          print '<li class="'.$_Qf1Jf.'"><img src="ipe/elements/images/'.substr($_Q6lfJ, 0, strpos($_Q6lfJ, ".")).'.png" width="130" height="100" border="0" rel="'.$_Q6lfJ.'" alt="'.$_Qf186.'" title="'.$_Qf186.'" /></li>';
   } // for j

  } else {
    if ($_Q6l1t = opendir(InstallPath."ipe/elements")) {
        while (($_Q6lfJ = readdir($_Q6l1t)) !== false) {
            if($_Q6lfJ == $_GET["filename"]){

              if(strpos($_GET["filename"], "socialmediashare") === false)
                print join("", file(InstallPath."ipe/elements/".$_GET["filename"]));
                else {
                 $_Q6ICj = join("", file(InstallPath."ipe/elements/".$_GET["filename"]));
                 $_Qf1t6 = trim(_OP81D($_Q6ICj, "<socialmedia_link>", "</socialmedia_link>"));
                 $_Qf1i1 = "";
                 $_QfQ08 = file(InstallPath."js/socialmediashare.ini");
                 for($_Q6llo=0; $_Q6llo<count($_QfQ08); $_Q6llo++){
                    if(strpos($_QfQ08[$_Q6llo], "'") !== false)
                       $_QfQ08[$_Q6llo] = substr($_QfQ08[$_Q6llo], 0, strpos($_QfQ08[$_Q6llo], "'"));
                    if(trim($_QfQ08[$_Q6llo]) == "") continue;
                    $_QfQ08[$_Q6llo] = trim($_QfQ08[$_Q6llo]);
                    $_QfQff = explode("=", $_QfQ08[$_Q6llo], 2);
                    if(count($_QfQff) < 2) continue;
                    $_Qf1i1 .= $_Qf1t6;
                    $_Qf1i1 = str_replace("[~SHARE_URL~]", $_QfQff[1], $_Qf1i1);
                    $_Qf1i1 = str_replace("[~SHARE_NAME~]", $_QfQff[0], $_Qf1i1);
                    $_Qf1i1 = str_replace("[~SHARE_NAME_NO_BLANK~]", str_replace(" ", "_", $_QfQff[0]), $_Qf1i1);
                    $_Qf1i1 = str_replace("[~SHARE_IMAGE_URL~]", "images/socialmedia/".str_replace(" ", "", strtolower($_QfQff[0]).".png"), $_Qf1i1);
                    if(empty($_GET["smechkboxes"]) || !$_GET["smechkboxes"])
                      $_Qf1i1 = _OP6PQ($_Qf1i1, "<smcheckboxes>", "</smcheckboxes>");
                      else {
                        $_Qf1i1 = str_replace("<smcheckboxes>", "", $_Qf1i1);
                        $_Qf1i1 = str_replace("</smcheckboxes>", "", $_Qf1i1);
                      }
                 }

                 $_Q6ICj = _OPR6L($_Q6ICj, "<socialmedia_link>", "</socialmedia_link>", $_Qf1i1);

                 # ckeditor iframe
                 if(!empty($_GET["ckeditor"])) {

                   $_QfQCQ = join("", file(_O68QF()."loadsmebar_iframe.htm"));
                   $_QfQCQ = _OPR6L($_QfQCQ, "<SMEBAR>", "</SMEBAR>", $_Q6ICj);

                   $_Q6ICj = $_QfQCQ;

                 }
                 print $_Q6ICj;
                }
            }
        }
        closedir($_Q6l1t);
    }
  }

?>
